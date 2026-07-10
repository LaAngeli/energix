@props(['code', 'name', 'index' => null])

{{--
| Fișa de lucrare: fiecare pagină se deschide ca un document tehnic, nu ca un
| banner de marketing. Metadata e reală (codul circuitului = ruta curentă).
--}}
<div {{ $attributes->class('job-sheet') }}>
    <span>Fișă de lucrare <strong>{{ $code }}</strong></span>
    <span>Obiect: <strong>{{ $name }}</strong></span>
    @if ($index)
        <span>Pagina <strong>{{ $index }}</strong></span>
    @endif
    <span class="inline-flex items-center gap-2">
        <span class="led is-on" aria-hidden="true"></span>
        <span class="is-live">Sub tensiune</span>
    </span>
</div>
