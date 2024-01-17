<x-mail::message>
# Email de verificación

Se ha registrado en nuestro portal, para poder utilizar todos nuestros servicios le solicitamos que confirme su email

<x-mail::button :url="$url">
Verificar Email
</x-mail::button>
    

Gracias, {{ config('app.name') }}
</x-mail::message>