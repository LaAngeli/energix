{{--
| Notificarea trimisa firmei cand cineva completeaza formularul.
|
| Blade, nu Markdown: continutul scris de vizitator NU trebuie interpretat ca sintaxa.
| `{{ }}` escapeaza, deci un `<script>` sau un `[link](...)` raman text simplu.
--}}
@php
    $phoneHref = preg_replace('/[^0-9+]/', '', $data['phone']);
    $sender = trim($data['prenume'].' '.$data['name']);
@endphp
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Cerere nouă de pe energix.md</title>
</head>
<body style="margin:0;padding:24px;background:#f4f6f9;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#0e1b2e">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #dde3ea;border-radius:4px">
        <tr>
            <td style="padding:24px;border-bottom:3px solid #f2d147">
                <h1 style="margin:0;font-size:18px;color:#091a31">Cerere nouă de pe energix.md</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:24px">
                <p style="margin:0 0 16px">
                    <strong>{{ $sender }}</strong> a completat formularul de contact.
                </p>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px">
                    <tr>
                        <td style="padding:8px 0;color:#47566c;width:90px">Telefon</td>
                        <td style="padding:8px 0"><a href="tel:{{ $phoneHref }}" style="color:#091a31">{{ $data['phone'] }}</a></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:#47566c">Email</td>
                        <td style="padding:8px 0"><a href="mailto:{{ $data['email'] }}" style="color:#091a31">{{ $data['email'] }}</a></td>
                    </tr>
                </table>

                <p style="margin:24px 0 8px;color:#47566c;font-size:12px;text-transform:uppercase;letter-spacing:1px">Mesaj</p>
                <div style="padding:16px;background:#f4f6f9;border-left:3px solid #dde3ea;white-space:pre-wrap;font-size:14px">{{ $data['message'] }}</div>

                <p style="margin:24px 0 0;font-size:13px;color:#47566c">
                    Poți răspunde direct la acest email — ajunge la client.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
