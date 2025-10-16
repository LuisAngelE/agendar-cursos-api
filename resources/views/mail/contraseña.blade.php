<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <title>Correo LDR Solutions</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif; color:#333;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="background-color:#f4f4f4; padding:20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
                    style="background:#ffffff; border-radius:8px; overflow:hidden;">

                    <tr>
                        <td align="center" style="padding:20px; background:#fff;">
                            <img src="{{ asset('images/FOTON.png') }}" alt="LDR Solutions"
                                style="max-width:150px; height:auto; display:block; margin:0 auto;">
                            <div style="height:1px;background:#ddd;margin:20px 0;"></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px; font-size:15px; line-height:1.6;">

                            <h2
                                style="font-size:20px; margin:0 0 15px 0; color:#333; font-weight:normal; line-height:1.4;">
                                Buen día estimado(a): <br>
                                <span style="color:#F05E29; font-weight:bold;">
                                    {{ $user->razon_social ?? $user->name . ' ' . $user->last_name }}
                                </span>
                            </h2>

                            <p style="margin:0 0 15px 0;">
                                Hemos generado con éxito tu nueva contraseña para acceder a tu cuenta en nuestra
                                plataforma de
                                <span style="color:#F05E29; font-weight:bold;">Cursos</span>.
                                Esta contraseña es única y temporal, te recomendamos cambiarla tan pronto como inicies
                                sesión.
                            </p>

                            <p style="margin:0 0 15px 0;">
                                Puedes acceder a tu cuenta utilizando tu correo electrónico y la nueva contraseña
                                proporcionada a continuación:
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="8" border="0" width="100%"
                                style="font-size:14px; margin-bottom:20px; background:#f9f9f9; border-radius:6px;">
                                <tr>
                                    <td style="font-weight:bold;">Correo Electrónico:</td>
                                    <td style="color:#F05E29;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Contraseña:</td>
                                    <td style="color:#F05E29;">{{ $randomPassword }}</td>
                                </tr>
                            </table>

                            <div style="text-align:center; margin:20px 0;">
                                <a href="{{ $url }}"
                                    style="display:inline-block; background:#F05E29; color:#ffffff;
                                          font-size:16px; font-weight:bold;
                                          text-decoration:none; padding:14px 28px;
                                          border-radius:6px;">
                                    Iniciar Sesión
                                </a>
                            </div>

                            <p style="margin:0 0 15px 0;">
                                Recuerda que tu seguridad es nuestra prioridad. No compartas tu contraseña con nadie.
                                Si experimentas algún problema al iniciar sesión, estamos aquí para ayudarte.
                                Ponte en contacto con nuestro equipo de soporte.
                            </p>

                            <p style="margin:0 0 15px 0;">
                                Si no realizaste esta solicitud o tienes alguna pregunta, por favor, contáctanos de
                                inmediato.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px; text-align:center; font-size:13px; color:#666; background:#fafafa;">
                            <div style="height:1px;background:#ddd;margin:20px 0;"></div>
                            Agradecemos tu confianza en <span style="color:#F05E29; font-weight:bold;">LDR
                                Solutions</span>.<br>
                            ¡Que tengas una excelente experiencia utilizando nuestros servicios!
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
