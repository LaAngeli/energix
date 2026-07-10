@props(['code', 'name', 'index' => null, 'status' => 'Sub tensiune', 'live' => true])

{{--
| Fișa de lucrare: fiecare pagină se deschide ca un document tehnic, nu ca un
| banner de marketing. Metadata e reală, iar LED-ul chiar înseamnă ceva:
| pe 404 e stins, cu statusul „Circuit întrerupt”.
--}}
<div {{ $attributes->class('job-sheet') }}>
    <span>Fișă de lucrare <strong>{{ $code }}</strong></span>
    <span>Obiect: <strong>{{ $name }}</strong></span>
    @if ($index)
        <span>Pagina <strong>{{ $index }}</strong></span>
    @endif
    <span class="inline-flex items-center gap-2">
        <span @class(['led', 'is-on led-ignite' => $live]) aria-hidden="true"></span>
        <span @class(['is-live' => $live])>{{ $status }}</span>
    </span>
</div>
