@php($page = 'legal.'.$doc)
@extends('layouts.app')

@php($t = __("site.legal.{$doc}"))

@section('content')

    <section class="mx-auto max-w-3xl px-5 py-16 sm:px-8 sm:py-24" aria-labelledby="legal-title">
        <p class="eyebrow flex items-center gap-2.5">
            <x-signature.wye :size="13" />
            {{ __('site.common.legal') }}
        </p>

        <h1 id="legal-title" class="mt-6 text-h1 text-paper">{{ $t['title'] }}</h1>

        <p class="mt-4 font-mono text-xs tracking-wide text-paper-dim uppercase">{{ __('site.legal.updated') }}</p>

        <p class="mt-8 text-lead text-paper-dim">{{ $t['intro'] }}</p>

        @foreach ($t['sections'] as $section)
            <h2 class="mt-12 text-h3 text-paper">{{ $section['h'] }}</h2>

            @foreach ($section['p'] as $paragraph)
                <p class="mt-4 text-paper-dim">{{ $paragraph }}</p>
            @endforeach

            @isset($section['ul'])
                <ul class="mt-4 list-disc space-y-2 pl-5 text-paper-dim">
                    @foreach ($section['ul'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endisset
        @endforeach
    </section>

@endsection
