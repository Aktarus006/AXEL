@component('mail::message')
# Nouvelle demande d'information

**Bijou**: {{ $jewel->name }}
**Prix**: {{ number_format($jewel->price, 0, '.', ' ') }} €

**De**: {{ $userName }}
**Email**: {{ $userEmail }}

**Message**:
{{ $userMessage }}

@component('mail::button', ['url' => url("/jewels/{$jewel->id}")])
Voir le bijou
@endcomponent

@endcomponent
