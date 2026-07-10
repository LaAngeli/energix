@php($contact = config('energix.contact'))

{{--
| Footer = placuta de identificare a instalatiei: datele firmei ca specificatii
| stantate pe o placa nituita, nu un footer generic de marketing.
--}}
<footer class="border-t border-line bg-ink-deep">
    <div class="mx-auto max-w-6xl px-5 py-14 sm:px-8">
        <div class="nameplate p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-line pb-5">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo/mark-64.png') }}" alt="" width="32" height="32" class="h-8 w-8" aria-hidden="true">
                    <span class="font-display text-lg tracking-tight text-paper">Energix</span>
                </div>
                <p class="flex items-center gap-2.5 font-mono text-[0.65rem] tracking-[0.16em] text-gold uppercase">
                    <x-signature.wye :size="12" :live="true" />
                    {{ config('energix.experience_years') }} {{ __('site.common.experience_label') }}
                </p>
            </div>

            <div class="grid gap-x-10 gap-y-8 pt-7 md:grid-cols-3">
                <div>
                    <h2 class="eyebrow">{{ __('site.common.object') }}</h2>
                    <p class="mt-3 text-sm text-paper-dim">{{ __('site.about_page.lead') }}</p>
                    <x-social-links class="mt-5" />
                </div>

                <div>
                    <h2 class="eyebrow">{{ __('site.nav.contact') }}</h2>
                    <ul class="mt-3 space-y-2.5">
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
                </div>

                <div>
                    <h2 class="eyebrow">{{ __('site.common.schedule') }}</h2>
                    <ul class="mt-3 space-y-1.5 text-sm">
                        @foreach (__('site.hours') as $slot)
                            <li class="flex justify-between gap-4">
                                <span class="text-paper-dim">{{ $slot['days'] }}</span>
                                <span class="readout text-paper">{{ $slot['time'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-3 text-xs text-paper-dim">{{ __('site.common.response_time') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-3 text-xs text-paper-dim sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Energix. {{ __('site.common.rights') }}</p>
            <nav aria-label="{{ __('site.common.legal') }}">
                <ul class="flex flex-wrap gap-x-5 gap-y-2">
                    <li><a href="{{ URL::localized('legal.terms') }}" class="transition hover:text-paper">{{ __('site.common.legal_terms') }}</a></li>
                    <li><a href="{{ URL::localized('legal.privacy') }}" class="transition hover:text-paper">{{ __('site.common.legal_privacy') }}</a></li>
                    <li><a href="{{ URL::localized('legal.cookies') }}" class="transition hover:text-paper">{{ __('site.common.legal_cookies') }}</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>
