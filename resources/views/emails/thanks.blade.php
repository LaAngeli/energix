{{--
| Confirmarea trimisa inapoi clientului dupa o trimitere reusita.
|
| Ecoul mesajului („Ce ne-ai scris”) foloseste `{{ }}` din acelasi motiv ca
| notificarea: continut de utilizator, nu sintaxa.
--}}
@php
    $prenume = trim($data['prenume']);
    $contact = config('energix.contact');

    $title = __('site.email.thanks.subject');
    $eyebrow = __('site.email.thanks.eyebrow');
    $preheader = __('site.email.thanks.preheader');
@endphp

@extends('emails.layout')

@section('content')
    <p style="margin:0 0 18px;font-size:22px;font-weight:700;color:#091a31;letter-spacing:-0.01em">{{ __('site.email.thanks.heading', ['name' => $prenume]) }}</p>

    <p style="margin:0 0 14px;font-size:16px;line-height:1.6;color:#0e1b2e">{{ __('site.email.thanks.lead') }}</p>
    <p style="margin:0 0 26px;font-size:15px;line-height:1.6;color:#47566c">{{ __('site.email.thanks.body') }}</p>

    <p style="margin:0 0 8px;font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#8a97a8">{{ __('site.email.thanks.your_message') }}</p>
    <div style="padding:18px;background:#f7f9fb;border-left:3px solid #f2d147;border-radius:0 4px 4px 0;white-space:pre-wrap;font-size:15px;line-height:1.6;color:#0e1b2e">{{ $data['message'] }}</div>

    {{-- Cutia „Ai nevoie mai repede?” — telefonul e conversia principala. --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:26px 0 8px;background:#091a31;border-radius:5px">
        <tr>
            <td style="padding:20px 22px">
                <p style="margin:0 0 4px;font-size:13px;color:#93a6c4">{{ __('site.email.thanks.urgent_label') }}</p>
                <a href="tel:{{ $contact['phone_href'] }}" style="font-size:20px;font-weight:700;color:#f2d147;text-decoration:none;letter-spacing:-0.01em">{{ $contact['phone'] }}</a>
            </td>
        </tr>
    </table>

    <p style="margin:26px 0 2px;font-size:15px;color:#47566c">{{ __('site.email.thanks.signoff') }}</p>
    <p style="margin:0;font-size:15px;font-weight:600;color:#091a31">{{ __('site.email.thanks.team') }}</p>
@endsection
