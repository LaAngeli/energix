@php
    $contact = config('energix.contact');
    $social = config('energix.social');
@endphp

<footer class="border-t border-line bg-ink">
    <div class="mx-auto max-w-6xl px-5 py-14 sm:px-8">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">

            <div class="lg:col-span-2">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo/mark-64.png') }}" alt="" width="32" height="32" class="h-8 w-8" aria-hidden="true">
                    <span class="font-display text-lg tracking-tight text-paper">Energix</span>
                </div>
                <p class="mt-4 max-w-sm text-paper-dim">
                    Instalații electrice complete pentru apartamente, case și spații industriale.
                    {{ $contact['city'] }} și toată {{ $contact['country'] }}.
                </p>

                <ul class="mt-6 flex flex-wrap gap-3">
                    @foreach ($social as $item)
                        {{-- `viber://` nu se deschide pe desktop fara clientul instalat. --}}
                        <li @class(['hidden sm:list-item' => str_starts_with($item['url'], 'viber:')])>
                            <a
                                href="{{ $item['url'] }}"
                                @if (str_starts_with($item['url'], 'https://')) target="_blank" rel="noopener noreferrer" @endif
                                class="inline-flex items-center rounded-sm border border-line px-3 py-2 font-mono text-xs tracking-wider text-paper-dim uppercase transition hover:border-gold hover:text-gold"
                            >{{ $item['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="eyebrow">Contact</h2>
                <ul class="mt-4 space-y-3">
                    <li>
                        <a href="tel:{{ $contact['phone_href'] }}" class="readout text-lg text-gold transition hover:brightness-110">
                            {{ $contact['phone'] }}
                        </a>
                    </li>
                    <li>
                        <a href="mailto:{{ $contact['email'] }}" class="text-paper-dim transition hover:text-paper">
                            {{ $contact['email'] }}
                        </a>
                    </li>
                    <li class="text-paper-dim">{{ $contact['city'] }}, {{ $contact['country'] }}</li>
                </ul>
            </div>

            <div>
                <h2 class="eyebrow">Program</h2>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach (config('energix.hours') as $slot)
                        <li class="flex justify-between gap-4">
                            <span class="text-paper-dim">{{ $slot['days'] }}</span>
                            <span class="readout text-paper">{{ $slot['time'] }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-4 inline-flex items-center gap-2 font-mono text-xs tracking-wider text-gold uppercase">
                    <x-signature.wye :size="12" :live="true" />
                    {{ config('energix.experience.years') }} {{ config('energix.experience.label') }}
                </p>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-4 border-t border-line pt-6 text-sm text-paper-dim sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Energix. Toate drepturile rezervate.</p>
            <nav aria-label="Pagini legale">
                <ul class="flex flex-wrap gap-x-5 gap-y-2">
                    <li><a href="{{ route('legal.terms') }}" class="transition hover:text-paper">Termeni și condiții</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="transition hover:text-paper">Confidențialitate</a></li>
                    <li><a href="{{ route('legal.cookies') }}" class="transition hover:text-paper">Cookie</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>
