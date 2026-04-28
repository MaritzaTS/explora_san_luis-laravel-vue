<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu cuenta</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 40px 20px;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #2d6a4f; padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold;">
                                Explora San Luis
                            </h1>
                            <p style="margin: 8px 0 0 0; color: #d8f3dc; font-size: 14px;">
                                Antioquia, Colombia
                            </p>
                        </td>
                    </tr>

                    {{-- Cuerpo --}}
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px 0; color: #1b4332; font-size: 22px;">
                                ¡Hola, {{ $nombre }}!
                            </h2>

                            <p style="margin: 0 0 16px 0; color: #333333; font-size: 16px; line-height: 1.6;">
                                Gracias por registrarte en <strong>Explora San Luis</strong>.
                                Para completar tu registro y empezar a descubrir el municipio, ingresa el siguiente código de verificación:
                            </p>

                            {{-- Caja del código --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 32px 0;">
                                <tr>
                                    <td align="center"
                                        style="background-color: #f1faee; border: 2px dashed #2d6a4f; border-radius: 8px; padding: 24px;">
                                        <p style="margin: 0 0 8px 0; color: #555555; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                            Tu código de verificación
                                        </p>
                                        <p style="margin: 0; color: #2d6a4f; font-size: 36px; font-weight: bold; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                                            {{ $codigo }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 16px 0; color: #555555; font-size: 14px; line-height: 1.6;">
                                Este código expirará en <strong>30 minutos</strong>. Si no solicitaste este registro,
                                puedes ignorar este correo de forma segura.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 24px 40px; text-align: center; border-top: 1px solid #e9ecef;">
                            <p style="margin: 0 0 8px 0; color: #6c757d; font-size: 12px;">
                                Este es un correo automático, por favor no respondas a este mensaje.
                            </p>
                            <p style="margin: 0; color: #6c757d; font-size: 12px;">
                                © {{ date('Y') }} Explora San Luis. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>