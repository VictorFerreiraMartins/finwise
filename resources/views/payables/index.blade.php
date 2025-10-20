@extends('layouts.admin')

@section('title', 'Contas a pagar')

@php
    use Illuminate\Support\Str;
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Contas a pagar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Listagem</h3>
                    <div class="card-tools">
                        <a href="{{ route('payables.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Nova conta
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-row mb-3" method="GET" action="{{ route('payables.index') }}">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <input type="search" name="search" class="form-control" placeholder="Buscar por título ou descrição"
                                value="{{ $search }}">
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <select name="status" class="form-control">
                                <option value="all" @selected($statusFilter === 'all' || $statusFilter === '')>Todos os status</option>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-filter mr-1"></i> Filtrar
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th class="text-right">Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Recorrente</th>
                                    <th>Atualizado em</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payables as $payable)
                                    <tr>
                                        <td>
                                            <strong>{{ $payable->title }}</strong>
                                            @if ($payable->description)
                                                <p class="text-muted small mb-0">{{ Str::limit($payable->description, 80) }}</p>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            R$ {{ number_format($payable->amount, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            {{ optional($payable->due_date)->format('d/m/Y') }}
                                            @if ($payable->reminder_date)
                                                <span class="d-block text-muted small">
                                                    Lembrar em {{ $payable->reminder_date->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match ($payable->status) {
                                                    \App\Models\Payable::STATUS_PAID => 'badge-success',
                                                    \App\Models\Payable::STATUS_OVERDUE => 'badge-danger',
                                                    default => 'badge-warning',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $payable->status_label }}</span>
                                            @if ($payable->status === \App\Models\Payable::STATUS_PAID && $payable->paid_at)
                                                <span class="d-block text-muted small">
                                                    Pago em {{ $payable->paid_at->format('d/m/Y H:i') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($payable->is_recurring)
                                                <span class="badge badge-info text-uppercase">
                                                    {{ $payable->recurrence_interval }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $payable->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('payables.edit', $payable) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('payables.destroy', $payable) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Deseja realmente excluir esta conta?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Nenhuma conta cadastrada até o momento.
                                            <a href="{{ route('payables.create') }}">Crie a primeira conta</a>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($payables->hasPages())
                    <div class="card-footer">
                        {{ $payables->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
