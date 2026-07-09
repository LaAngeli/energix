<x-mail::message>
# Cerere nouă de pe energix.md

**{{ $data['prenume'] }} {{ $data['name'] }}** a completat formularul de contact.

- **Telefon:** [{{ $data['phone'] }}](tel:{{ preg_replace('/[^0-9+]/', '', $data['phone']) }})
- **Email:** [{{ $data['email'] }}](mailto:{{ $data['email'] }})

## Mesaj

{{ $data['message'] }}

<x-mail::button :url="'tel:'.preg_replace('/[^0-9+]/', '', $data['phone'])">
Sună clientul
</x-mail::button>

Poți răspunde direct la acest email — ajunge la client.
</x-mail::message>
