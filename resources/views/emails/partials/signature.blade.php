{{--
| Semnatura comuna a emailurilor Energix. O singura sursa — o folosesc si
| notificarea catre firma, si confirmarea catre client.
|
| `asset()` da URL absolut din APP_URL: in productie (https://energix.md) logo-ul
| se incarca in clientul de mail. Wordmark-ul e insa TEXT, nu imagine — multe
| clienturi blocheaza imaginile, iar brandul trebuie sa apara oricum.
--}}
@php($contact = config('energix.contact'))
<tr>
    <td style="padding:0 32px 32px">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #dde3ea">
            <tr>
                <td style="padding-top:24px">
                    {{-- Placuta de identitate: wordmark + nod auriu, ca pe site. --}}
                    <table role="presentation" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding-right:10px;vertical-align:middle">
                                <span style="display:inline-block;width:10px;height:10px;background:#f2d147;border-radius:2px"></span>
                            </td>
                            <td style="vertical-align:middle">
                                <span style="font-size:18px;font-weight:700;color:#091a31;letter-spacing:-0.02em">Energix</span>
                            </td>
                        </tr>
                    </table>

                    <p style="margin:12px 0 18px;font-size:13px;line-height:1.5;color:#47566c">
                        {{ __('site.email.sig.tagline') }}
                    </p>

                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:13px;line-height:1.7;color:#0e1b2e">
                        <tr>
                            <td style="color:#8a97a8;padding-right:14px;white-space:nowrap">{{ __('site.email.notify.label_phone') }}</td>
                            <td><a href="tel:{{ $contact['phone_href'] }}" style="color:#091a31;text-decoration:none;font-weight:600">{{ $contact['phone'] }}</a></td>
                        </tr>
                        <tr>
                            <td style="color:#8a97a8;padding-right:14px">{{ __('site.email.notify.label_email') }}</td>
                            <td><a href="mailto:{{ $contact['email'] }}" style="color:#091a31;text-decoration:none">{{ $contact['email'] }}</a></td>
                        </tr>
                        <tr>
                            <td style="color:#8a97a8;padding-right:14px">{{ __('site.email.sig.web_label') }}</td>
                            <td><a href="https://energix.md" style="color:#091a31;text-decoration:none">energix.md</a></td>
                        </tr>
                        <tr>
                            <td style="color:#8a97a8;padding-right:14px;vertical-align:top">{{ __('site.email.sig.hours_label') }}</td>
                            <td style="color:#47566c">
                                @foreach (__('site.hours') as $slot)
                                    {{ $slot['days'] }}: {{ $slot['time'] }}@if (! $loop->last)<br>@endif
                                @endforeach
                            </td>
                        </tr>
                    </table>

                    <p style="margin:18px 0 6px;font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#8a97a8">{{ __('site.email.sig.follow') }}</p>
                    <p style="margin:0;font-size:13px">
                        @foreach (config('energix.social') as $social)
                            @if (str_starts_with($social['url'], 'https://'))
                                <a href="{{ $social['url'] }}" style="color:#0e1b2e;text-decoration:none">{{ $social['name'] }}</a>@if (! $loop->last)<span style="color:#c7cfda"> &middot; </span>@endif
                            @endif
                        @endforeach
                    </p>

                    <p style="margin:22px 0 0;font-size:11px;line-height:1.5;color:#a7b0bd">
                        {{ __('site.email.sig.automated') }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
