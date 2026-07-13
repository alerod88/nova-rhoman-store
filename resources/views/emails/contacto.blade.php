<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
        <h2 style="color: #1b3d81; border-bottom: 2px solid #2c5ebd; padding-bottom: 10px;">Nuevo mensaje desde la Web</h2>
        <p><strong>Nombre:</strong> {{ $data['nombre'] }}</p>
        <p><strong>Email:</strong> {{ $data['email'] }}</p>
        <p><strong>Asunto:</strong> {{ $data['asunto'] }}</p>
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #2c5ebd; margin-top: 15px;">
            <strong>Mensaje:</strong><br>
            {!! nl2br(e($data['mensaje'])) !!}
        </div>
    </div>
</body>
</html>