@php($contact = config('energix.contact'))

{{--
| Footer = placuta de identificare a instalatiei: datele firmei ca specificatii
| stantate pe o placa nituita, nu un footer generic de marketing.
--}}
<footer class="border-t border-line bg-ink-deep">
    <div class="mx-auto max-w-6xl px-5 py-14 sm:px-8">
        <div class="nameplate p-6 sm:p-8">
            {{--
            | Aici sigla ESTE elementul cel mai inalt al randului, deci cresterea ei
            | ar impinge footer-ul in jos. Cei 12px castigati (32 -> 44) se iau inapoi
            | din `pt-7` -> `pt-4` al grilei de mai jos: inaltimea totala ramane 468px.
            --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-line pb-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo/mark-96.png') }}" alt="" width="44" height="44" class="h-11 w-11" aria-hidden="true">
                    <span class="font-display text-2xl leading-none tracking-tight text-paper">Energix</span>
                </div>
                <p class="flex items-center gap-2.5 font-mono text-[0.65rem] tracking-[0.16em] text-gold uppercase">
                    <x-signature.wye :size="12" :live="true" />
                    {{ config('energix.experience_years') }} {{ __('site.common.experience_label') }}
                </p>
            </div>

            {{--
            | Trei campuri de spec-plate, echilibrate. Paragraful „Obiect” a iesit:
            | era lead-ul de pe /despre, repetat pe fiecare pagina (continut duplicat
            | site-wide) si dezechilibra coloanele. In loc — navigare functionala.
            | Iconitele sociale stau langa contact, fiindca SUNT canale de contact.
            --}}
            <div class="grid gap-x-10 gap-y-9 pt-4 sm:grid-cols-2 lg:grid-cols-3">
                <nav aria-label="{{ __('site.common.sitemap') }}">
                    <h2 class="eyebrow">{{ __('site.common.sitemap') }}</h2>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        @foreach (['home', 'services', 'gallery', 'about', 'contact'] as $route)
                            <li>
                                <a href="{{ URL::localized($route) }}" class="text-paper-dim transition-colors hover:text-gold">
                                    {{ __('site.nav.'.$route) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div>
                    <h2 class="eyebrow">{{ __('site.nav.contact') }}</h2>
                    <ul class="mt-4 space-y-2.5">
                        <li>
                            <a href="tel:{{ $contact['phone_href'] }}" class="readout text-lg text-gold transition hover:brightness-110">
                                {{ $contact['phone'] }}
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ $contact['email'] }}" class="text-sm text-paper-dim transition hover:text-paper">
                                {{ $contact['email'] }}
                            </a>
                        </li>
                        <li class="text-sm text-paper-dim">{{ __('site.common.area_served') }}</li>
                    </ul>

                    <p class="mt-6 mb-3 eyebrow">{{ __('site.common.find_us') }}</p>
                    <x-social-links />
                </div>

                <div class="sm:col-span-2 lg:col-span-1">
                    <h2 class="eyebrow">{{ __('site.common.schedule') }}</h2>
                    <ul class="mt-4 space-y-1.5 text-sm">
                        @foreach (__('site.hours') as $slot)
                            <li class="flex justify-between gap-6">
                                <span class="text-paper-dim">{{ $slot['days'] }}</span>
                                <span class="readout text-paper">{{ $slot['time'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-4 border-t border-line pt-3 text-xs text-paper-dim">{{ __('site.common.response_time') }}</p>
                </div>
            </div>
        </div>

        {{--
        | Sub-bara: doua grupuri, nu trei blocuri risipite. Separatorul e middot-ul
        | hairline (`text-line`), acelasi idiom ca firimiturile si fisa de lucrare.
        | Centrat si simetric pe mobil; justificat pe laturi de la sm in sus.
        --}}
        <div class="mt-8 flex flex-col items-center gap-5 border-t border-line/50 pt-6 text-xs text-paper-dim sm:flex-row sm:justify-between sm:gap-6">
            <p class="flex flex-wrap items-center justify-center gap-x-2.5 gap-y-1 sm:justify-start">
                <span>&copy; {{ date('Y') }} Energix. {{ __('site.common.rights') }}</span>
                <span class="text-line" aria-hidden="true">&middot;</span>
                {{--
                | Creditul agentiei. NU se traduce: e semnatura ei, la fel ca numele
                | „AdVista” — deci nu trece prin `lang/`. Cerinta explicita a clientului.
                --}}
                <span>Created by <a href="https://advista.marketing" target="_blank" rel="noopener" class="text-paper-dim transition-colors hover:text-gold">AdVista</a></span>
            </p>

            <nav class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1" aria-label="{{ __('site.common.legal') }}">
                <a href="{{ URL::localized('legal.terms') }}" class="transition-colors hover:text-paper">{{ __('site.common.legal_terms') }}</a>
                <span class="text-line" aria-hidden="true">&middot;</span>
                <a href="{{ URL::localized('legal.privacy') }}" class="transition-colors hover:text-paper">{{ __('site.common.legal_privacy') }}</a>
                <span class="text-line" aria-hidden="true">&middot;</span>
                <a href="{{ URL::localized('legal.cookies') }}" class="transition-colors hover:text-paper">{{ __('site.common.legal_cookies') }}</a>
            </nav>
        </div>
    </div>
</footer>
