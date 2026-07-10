@props(['code', 'name', 'index' => null, 'status' => null, 'live' => true])

@php($status ??= __('site.common.under_power'))

{{--
| Fișa de lucrare: fiecare pagină se deschide ca un document tehnic, nu ca un
| banner de marketing. LED-ul chiar înseamnă ceva: pe 404 e stins.
--}}
<div {{ $attributes->class('job-sheet') }}>
    <span>{{ __('site.common.job_sheet') }} <strong>{{ $code }}</strong></span>
    <span>{{ __('site.common.object') }}: <strong>{{ $name }}</strong></span>
    @if ($index)
        <span>{{ __('site.common.page') }} <strong>{{ $index }}</strong></span>
    @endif
    <span class="inline-flex items-center gap-2">
        <span @class(['led', 'is-on led-ignite' => $live]) aria-hidden="true"></span>
        <span @class(['is-live' => $live])>{{ $status }}</span>
    </span>
</div>
