<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo Yo Comparto</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,700&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-style: italic;
            font-weight: bold;
            color: #F39F5A;
            margin: 10px 0;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .content h2 {
            margin-bottom: 10px;
        }

        #button {
            display: block;
            width: fit-content;
            padding: 12px 24px;
            font-size: 16px;
            color: white;
            background-color: #F39F5A;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto;
        }

        .footer {
            text-align: center;
            font-style: italic;
            font-weight: bold;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Yo Comparto</h1>
            <hr>
        </div>
        <div class="content">
            <h2>Buen día estimado(a): <span style="color: #F39F5A;">{{ $user->name }} {{ $user->last_name }}</span>
            </h2>
            <h2>
                Hemos generado con éxito tu nueva contraseña para acceder a tu cuenta
                en <span style="color: #F39F5A;">Yo Comparto.</span>
                Esta contraseña es única y temporal, te recomendamos cambiarla tan pronto como inicies sesión.
                Puedes acceder a tu cuenta utilizando tu correo electrónico y la nueva contraseña proporcionada
                a continuación.
            </h2>
            <h2>
                Correo Electronico:
                <span style="color: #F39F5A;">{{ $user->email }}</span>
            </h2>
            <h2>
                Contraseña:
                <span style="color: #F39F5A;">{{ $randomPassword }}</span>
            </h2>

            <a href="{{ $url }}" id="button">Acceder a Yo Comparto</a>

            <h2>
                Recuerda que tu seguridad es nuestra prioridad. No compartas tu
                contraseña con nadie. Si experimentas algún problema al iniciar sesión, estamos aquí para
                ayudarte. Ponte en contacto con nuestro equipo de soporte.
            </h2>

            <h2>Si no realizaste esta solicitud o tienes alguna pregunta, por favor,
                contáctanos de inmediato.
            </h2>

        </div>
        <div class="footer">
            Agradecemos tu confianza en <span style="color: #F39F5A;">Yo Comparto.</span> ¡Que tengas una excelente
            experiencia utilizando nuestros servicios!
            <hr>
        </div>
    </div>
</body>

</html>
