@csrf

<div class="form-group">
    <label for="title">Título</label>
    <input type="text" name="title" id="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $payable->title) }}" required autofocus>
    @error('title')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="description">Descrição</label>
    <textarea name="description" id="description" rows="3"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Detalhes adicionais">{{ old('description', $payable->description) }}</textarea>
    @error('description')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="amount">Valor</label>
        <input type="number" name="amount" id="amount" step="0.01" min="0.01"
            class="form-control @error('amount') is-invalid @enderror"
            value="{{ old('amount', $payable->amount) }}" required>
        @error('amount')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="due_date">Data de vencimento</label>
        <input type="date" name="due_date" id="due_date"
            class="form-control @error('due_date') is-invalid @enderror"
            value="{{ old('due_date', optional($payable->due_date)->format('Y-m-d')) }}" required>
        @error('due_date')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="reminder_date">Lembrar em</label>
        <input type="date" name="reminder_date" id="reminder_date"
            class="form-control @error('reminder_date') is-invalid @enderror"
            value="{{ old('reminder_date', optional($payable->reminder_date)->format('Y-m-d')) }}">
        @error('reminder_date')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $payable->status ?? \App\Models\Payable::STATUS_PENDING) === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="paid_at">Data de pagamento</label>
        <input type="datetime-local" name="paid_at" id="paid_at"
            class="form-control @error('paid_at') is-invalid @enderror"
            value="{{ old('paid_at', optional($payable->paid_at)->format('Y-m-d\TH:i')) }}">
        @error('paid_at')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <div class="custom-control custom-switch mt-4">
            <input type="checkbox" name="is_recurring" class="custom-control-input" id="is_recurring" value="1"
                {{ old('is_recurring', $payable->is_recurring) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_recurring">Conta recorrente</label>
        </div>
        @error('is_recurring')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group recurrence-wrapper {{ old('is_recurring', $payable->is_recurring) ? '' : 'd-none' }}">
    <label for="recurrence_interval">Recorrência</label>
    <select name="recurrence_interval" id="recurrence_interval"
        class="form-control @error('recurrence_interval') is-invalid @enderror">
        <option value="">Selecione uma recorrência</option>
        @foreach ($recurrenceOptions as $option)
            <option value="{{ $option }}"
                @selected(old('recurrence_interval', $payable->recurrence_interval) === $option)>
                {{ ucfirst($option) }}
            </option>
        @endforeach
    </select>
    @error('recurrence_interval')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('payables.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Voltar
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save mr-1"></i> Salvar
    </button>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var checkbox = document.getElementById('is_recurring');
                var wrapper = document.querySelector('.recurrence-wrapper');
                if (!checkbox || !wrapper) {
                    return;
                }

                function toggleRecurrence() {
                    if (checkbox.checked) {
                        wrapper.classList.remove('d-none');
                    } else {
                        wrapper.classList.add('d-none');
                        document.getElementById('recurrence_interval').value = '';
                    }
                }

                checkbox.addEventListener('change', toggleRecurrence);
                toggleRecurrence();
            });
        </script>
    @endpush
@endonce
