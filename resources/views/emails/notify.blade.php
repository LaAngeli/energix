{{--
| Notificarea catre firma. Blade, NU markdown: continutul scris de vizitator nu
| trebuie interpretat ca sintaxa — `{{ }}` escapeaza, deci un `<script>` sau un
| `[link](...)` din mesaj raman text simplu. Vezi comentariul din ContactMessage.
--}}
@php
    $sender = trim($data['prenume'].' '.$data['name']);
    $phoneHref = preg_replace('/[^0-9+]/', '', $data['phone']);
    $now = now()->locale(app()->getLocale());

    $title = __('site.email.notify.subject', ['name' => $sender]);
    $eyebrow = __('site.email.notify.eyebrow');
    $preheader = __('site.email.notify.preheader', ['name' => $sender]);
@endphp

@extends('emails.layout')

@section('content')
    <p style="margin:0 0 4px;font-size:22px;font-weight:700;color:#091a31;letter-spacing:-0.01em">{{ $sender }}</p>
    <p style="margin:0 0 24px;font-size:15px;color:#47566c">{{ __('site.email.notify.intro') }}</p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;border:1px solid #e2e7ee;border-radius:5px;overflow:hidden">
        <tr>
            <td style="padding:14px 16px;color:#8a97a8;width:96px;background:#f7f9fb;border-bottom:1px solid #eef1f5">{{ __('site.email.notify.label_phone') }}</td>
            <td style="padding:14px 16px;border-bottom:1px solid #eef1f5"><a href="tel:{{ $phoneHref }}" style="color:#091a31;text-decoration:none;font-weight:600">{{ $data['phone'] }}</a></td>
        </tr>
        <tr>
            <td style="padding:14px 16px;color:#8a97a8;background:#f7f9fb">{{ __('site.email.notify.label_email') }}</td>
            <td style="padding:14px 16px"><a href="mailto:{{ $data['email'] }}" style="color:#091a31;text-decoration:none">{{ $data['email'] }}</a></td>
        </tr>
    </table>

    <p style="margin:26px 0 8px;font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#8a97a8">{{ __('site.email.notify.label_message') }}</p>
    <div style="padding:18px;background:#f7f9fb;border-left:3px solid #f2d147;border-radius:0 4px 4px 0;white-space:pre-wrap;font-size:15px;line-height:1.6;color:#0e1b2e">{{ $data['message'] }}</div>

    <p style="margin:24px 0 4px;font-size:13px;color:#47566c">{{ __('site.email.notify.reply_hint', ['name' => $sender]) }}</p>
    <p style="margin:0;font-size:12px;color:#a7b0bd">{{ __('site.email.notify.received', ['date' => $now->translatedFormat('j F Y'), 'time' => $now->format('H:i')]) }}</p>
@endsection
