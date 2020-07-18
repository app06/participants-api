@component('mail::message')

Спасибо за участие {{ $participant->first_name }}<br><br>

Мероприятие: {{ $participant->event->name }}<br>
Дата: {{ $participant->event->date->format('d.m.Y H:i') }}<br>
Город: {{ $participant->event->city }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
