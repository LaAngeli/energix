@php($sender = trim($data['prenume'].' '.$data['name']))
{{ mb_strtoupper(__('site.email.notify.eyebrow')) }}

{{ $sender }} {{ __('site.email.notify.intro') }}

{{ __('site.email.notify.label_phone') }}: {{ $data['phone'] }}
{{ __('site.email.notify.label_email') }}: {{ $data['email'] }}

{{ mb_strtoupper(__('site.email.notify.label_message')) }}
{{ str_repeat('-', mb_strlen(__('site.email.notify.label_message'))) }}
{{ $data['message'] }}

{{ __('site.email.notify.reply_hint', ['name' => $sender]) }}
@include('emails.partials.signature-text')
