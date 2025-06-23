@component('mail::message')
# Nouveau message de contact

**De**: {{ $name }}
**Email**: {{ $email }}

**Message**:
{{ $messageContent }}

@endcomponent
