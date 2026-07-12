@php($contact = config('energix.contact'))
{{ __('site.email.thanks.heading', ['name' => trim($data['prenume'])]) }}

{{ __('site.email.thanks.lead') }}

{{ __('site.email.thanks.body') }}

{{ mb_strtoupper(__('site.email.thanks.your_message')) }}
{{ str_repeat('-', mb_strlen(__('site.email.thanks.your_message'))) }}
{{ $data['message'] }}

{{ __('site.email.thanks.urgent_label') }} {{ __('site.email.thanks.urgent_cta', ['phone' => $contact['phone']]) }}

{{ __('site.email.thanks.signoff') }}
{{ __('site.email.thanks.team') }}
@include('emails.partials.signature-text')
