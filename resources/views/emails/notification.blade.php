<x-mail::message>
# Notification from {{ $userName }}

{{ $message }}

<x-mail::button :url="route('admin.notifications.show', $notificationId)">
View Notification
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
