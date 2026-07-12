{{--
| Chrome-ul comun al emailurilor Energix: antet brandat + slot de continut +
| semnatura. Tabele si stiluri inline — singura structura pe care clientii de
| mail o randeaza fiabil. Paleta din brand: bleumarin #091a31, auriu #f2d147.
|
| `$preheader` — textul de previzualizare din inbox (ascuns in corp).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $title ?? 'Energix' }}</title>
</head>
<body style="margin:0;padding:0;background:#eef1f5;-webkit-text-size-adjust:100%;font-family:-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif">
    @isset($preheader)
        <div style="display:none;max-height:0;overflow:hidden;opacity:0">{{ $preheader }}</div>
    @endisset

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f5">
        <tr>
            <td style="padding:32px 16px">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:6px;overflow:hidden;border:1px solid #e2e7ee">
                    {{-- Antet: banda bleumarin cu wordmark si eyebrow, subliniata auriu. --}}
                    <tr>
                        <td style="background:#091a31;padding:26px 32px;border-bottom:3px solid #f2d147">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:middle">
                                        <span style="font-size:22px;font-weight:700;color:#ffffff;letter-spacing:-0.02em">Energix</span>
                                    </td>
                                    <td style="vertical-align:middle;text-align:right">
                                        <span style="font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#f2d147">{{ $eyebrow ?? '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Continutul specific fiecarui email. --}}
                    <tr>
                        <td style="padding:32px 32px 8px;color:#0e1b2e">
                            @yield('content')
                        </td>
                    </tr>

                    @include('emails.partials.signature')
                </table>

                <p style="max-width:600px;margin:16px auto 0;text-align:center;font-size:11px;color:#a7b0bd">
                    &copy; {{ date('Y') }} Energix &middot; Chișinău, {{ __('site.common.country') }}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
