<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->string('status')->toString();
        $search = $request->string('search')->toString();

        $query = Payable::query()
            ->where('user_id', $request->user()->id);

        if ($search !== '') {
            $query->where(function ($inner) use ($search): void {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($statusFilter !== '' && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $payables = $query
            ->orderBy('due_date')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('payables.index', [
            'payables' => $payables,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'statuses' => Payable::statusOptions(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payables.create', [
            'payable' => new Payable(),
            'statuses' => Payable::statusOptions(),
            'recurrenceOptions' => $this->recurrenceOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $request->user()->payables()->create($data);

        return redirect()
            ->route('payables.index')
            ->with('status', 'Conta a pagar criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Payable $payable): View
    {
        $this->authorizePayable($request, $payable);

        return view('payables.edit', [
            'payable' => $payable,
            'statuses' => Payable::statusOptions(),
            'recurrenceOptions' => $this->recurrenceOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payable $payable): RedirectResponse
    {
        $this->authorizePayable($request, $payable);

        $data = $this->validatedData($request);

        $payable->update($data);

        return redirect()
            ->route('payables.index')
            ->with('status', 'Conta a pagar atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Payable $payable): RedirectResponse
    {
        $this->authorizePayable($request, $payable);

        $payable->delete();

        return redirect()
            ->route('payables.index')
            ->with('status', 'Conta a pagar removida.');
    }

    /**
     * Authorize access to the payable.
     */
    protected function authorizePayable(Request $request, Payable $payable): void
    {
        abort_unless($payable->user_id === $request->user()->id, 403);
    }

    /**
     * Validate and normalize incoming data.
     */
    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'reminder_date' => ['nullable', 'date', 'before_or_equal:due_date'],
            'status' => ['required', Rule::in(array_keys(Payable::statusOptions()))],
            'paid_at' => ['nullable', 'date'],
            'is_recurring' => ['nullable', 'boolean'],
            'recurrence_interval' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['is_recurring'] = (bool) ($validated['is_recurring'] ?? false);

        if (! $validated['is_recurring']) {
            $validated['recurrence_interval'] = null;
        } else {
            $validated['recurrence_interval'] = $validated['recurrence_interval'] ?: Arr::first($this->recurrenceOptions());
        }

        if ($validated['status'] === Payable::STATUS_PAID) {
            $validated['paid_at'] = $validated['paid_at'] ?? now();
        } else {
            $validated['paid_at'] = null;
        }

        return $validated;
    }

    /**
     * Available recurrence options.
     *
     * @return array<int, string>
     */
    protected function recurrenceOptions(): array
    {
        return [
            'weekly',
            'biweekly',
            'monthly',
            'quarterly',
            'yearly',
        ];
    }
}
