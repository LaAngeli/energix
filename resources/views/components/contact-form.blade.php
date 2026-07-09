@php
    $fields = [
        ['name' => 'name', 'label' => 'Nume', 'type' => 'text', 'autocomplete' => 'family-name'],
        ['name' => 'prenume', 'label' => 'Prenume', 'type' => 'text', 'autocomplete' => 'given-name'],
    ];
@endphp

<div {{ $attributes }}>
    @if (session('contact.success'))
        <div role="status" class="mb-6 flex gap-3 rounded-sm border border-gold/40 bg-gold/10 p-4">
            <x-signature.wye :size="18" :live="true" class="mt-1 shrink-0" />
            <p class="text-sm text-paper">{{ session('contact.success') }}</p>
        </div>
    @endif

    @if (session('contact.error'))
        <div role="alert" class="mb-6 rounded-sm border border-line bg-ink-raised p-4">
            <p class="text-sm text-paper">{{ session('contact.error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
        @csrf

        {{-- Momentul randarii, criptat: un bot nu il poate fabrica. --}}
        <input type="hidden" name="rendered_at" value="{{ encrypt(time()) }}">

        {{-- Capcana. Ascunsa vizual SI pentru cititoarele de ecran. --}}
        <div class="absolute left-[-9999px]" aria-hidden="true">
            <label for="website">Nu completa acest câmp</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($fields as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="eyebrow block">{{ $field['label'] }} *</label>
                    <input
                        type="{{ $field['type'] }}"
                        id="{{ $field['name'] }}"
                        name="{{ $field['name'] }}"
                        value="{{ old($field['name']) }}"
                        autocomplete="{{ $field['autocomplete'] }}"
                        required
                        maxlength="50"
                        @error($field['name']) aria-invalid="true" aria-describedby="{{ $field['name'] }}-error" @enderror
                        class="mt-2 w-full rounded-sm border border-line bg-ink-raised px-4 py-3 text-paper transition placeholder:text-paper-dim/60 focus:border-gold focus:outline-none"
                    >
                    @error($field['name'])
                        <p id="{{ $field['name'] }}-error" class="mt-2 text-sm text-gold">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <div>
            <label for="phone" class="eyebrow block">Telefon *</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                autocomplete="tel"
                inputmode="tel"
                placeholder="+373 XX XXX XXX"
                required
                maxlength="20"
                @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror
                class="mt-2 w-full rounded-sm border border-line bg-ink-raised px-4 py-3 text-paper tabular-nums transition placeholder:text-paper-dim/60 focus:border-gold focus:outline-none"
            >
            @error('phone')
                <p id="phone-error" class="mt-2 text-sm text-gold">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="eyebrow block">Email *</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                maxlength="100"
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                class="mt-2 w-full rounded-sm border border-line bg-ink-raised px-4 py-3 text-paper transition placeholder:text-paper-dim/60 focus:border-gold focus:outline-none"
            >
            @error('email')
                <p id="email-error" class="mt-2 text-sm text-gold">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="message" class="eyebrow block">Ce ai nevoie *</label>
            <textarea
                id="message"
                name="message"
                rows="6"
                required
                maxlength="2000"
                placeholder="Descrie pe scurt lucrarea sau problema."
                @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                class="mt-2 w-full resize-y rounded-sm border border-line bg-ink-raised px-4 py-3 text-paper transition placeholder:text-paper-dim/60 focus:border-gold focus:outline-none"
            >{{ old('message') }}</textarea>
            @error('message')
                <p id="message-error" class="mt-2 text-sm text-gold">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full rounded-sm bg-gold px-6 py-4 font-mono text-sm font-medium tracking-wider text-ink uppercase transition hover:brightness-110 hover:shadow-[0_0_28px_-6px_var(--color-gold)] sm:w-auto"
        >
            Trimite mesajul
        </button>

        <p class="text-sm text-paper-dim">{{ config('energix.response_time') }}</p>
    </form>
</div>
