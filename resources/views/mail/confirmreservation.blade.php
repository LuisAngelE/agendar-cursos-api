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
                        <td style="padding:20px;">
                            <h2
                                style="font-size:20px; margin:0 0 15px 0; color:#333; font-weight:normal; line-height:1.4;">
                                Buen día <span
                                    style="color:#F05E29; font-weight:bold;">{{ $reservation->student->name, $reservation->student->last_name }}</span>,
                            </h2>

                            <p style="font-size:15px; line-height:1.6; margin:0 0 15px 0;">
                                Tu reserva ha sido <strong>confirmada</strong> y el curso ya tiene un instructor
                                asignado.
                                Pronto se realizará según lo programado.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="font-size:14px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:5px 0;"><strong>Curso:</strong></td>
                                    <td>{{ $schedule->course->title }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Fecha y hora solicitada:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_date)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Estado:</strong></td>
                                    <td>{{ $schedule->state->name ?? 'Estado no definido' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Municipio:</strong></td>
                                    <td>{{ $schedule->municipality->name ?? 'Municipio no definido' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Locación:</strong></td>
                                    <td>{{ $schedule->location }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Solicitante:</strong></td>
                                    <td>{{ $reservation->student->name, $reservation->student->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Instructor:</strong></td>
                                    <td>{{ $schedule->instructor->name, $schedule->instructor->last_name ?? 'Por asignar' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statuses = [
                                                1 => 'Pendiente',
                                                2 => 'Confirmada',
                                                3 => 'Cancelada',
                                                4 => 'Atendida',
                                            ];
                                        @endphp
                                        {{ $statuses[$reservation->status] ?? 'Desconocido' }}
                                    </td>
                                </tr>
                            </table>

                            <div style="text-align:center;">
                                <a href="{{ $url }}"
                                    style="display:inline-block; background:#F05E29; color:#ffffff;
                                      font-size:16px; font-weight:bold; text-decoration:none; padding:14px 28px;
                                      border-radius:6px;">
                                    Mis cursos reservados
                                </a>
                            </div>
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
