<x-mail::message>
# Cambio de Contraseña

Hemos recibido una solicitud de cambio de contraseña, te enviamos este link para que puedas acceder a cambiar la misma

<x-mail::button :url="$token">
Click aquí
</x-mail::button>
    

Gracias, {{ config('app.name') }}
</x-mail::message>
