<?php

namespace App\Services;

class WhatsAppService
{
    public static function generarEnlace($telefono, $nombre, $mes, $monto, $fechaLimite)
    {
        // 1. Construimos el texto del mensaje
        $mensaje = "Estimado Sr(a). {$nombre}:\n"
                 . "Le recordamos que la expensa correspondiente al mes de {$mes} se encuentra pendiente.\n"
                 . "Monto: Bs. {$monto}\n"
                 . "Fecha límite: {$fechaLimite}\n\n"
                 . "Administración Edificio “SAN PEDRO”";

        // 2. Formateamos el número asegurando el código de país (Bolivia: 591)
        $numeroFormateado = "591" . ltrim($telefono, '+591');

        // 3. Codificamos el texto para que los espacios y saltos de línea funcionen en la URL
        $mensajeCodificado = urlencode($mensaje);

        // 4. Retornamos el enlace oficial de WhatsApp
        return "https://api.whatsapp.com/send?phone={$numeroFormateado}&text={$mensajeCodificado}";
    }
}