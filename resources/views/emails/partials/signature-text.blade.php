@php($contact = config('energix.contact'))
--
Energix — {{ __('site.email.sig.tagline') }}

{{ __('site.email.notify.label_phone') }}: {{ $contact['phone'] }}
{{ __('site.email.notify.label_email') }}: {{ $contact['email'] }}
{{ __('site.email.sig.web_label') }}: energix.md
{{ __('site.email.sig.hours_label') }}:
@foreach (__('site.hours') as $slot)
  {{ $slot['days'] }}: {{ $slot['time'] }}
@endforeach

{{ __('site.email.sig.automated') }}
