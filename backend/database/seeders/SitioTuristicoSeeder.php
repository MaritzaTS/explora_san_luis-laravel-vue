<?php

namespace Database\Seeders;

use App\Models\Lugar;
use App\Models\SitioTuristico;
use Illuminate\Database\Seeder;

class SitioTuristicoSeeder extends Seeder
{
    public function run(): void
    {
        $lugar = Lugar::where('nombre', 'San Luis')->first();

        if (!$lugar) {
            $this->command->warn('Lugar "San Luis" no encontrado. Ejecuta LugarSeeder primero.');
            return;
        }

        $sitios = [
            [
                'nombre'      => 'Cascada La Chorrera',
                'descripcion' => 'Una impresionante cascada rodeada de vegetación exuberante, ideal para senderismo y fotografía de naturaleza. Sus aguas cristalinas caen desde 40 metros de altura formando una piscina natural.',
                'url_imagen_1' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=800',
                'url_imagen_2' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
                'url_imagen_3' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800',
                'estado'       => true,
            ],
            [
                'nombre'      => 'Mirador El Cerro Grande',
                'descripcion' => 'Desde este mirador natural se puede apreciar una vista panorámica de todo el municipio de San Luis y sus alrededores. El ascenso toma aproximadamente 45 minutos por senderos bien marcados.',
                'url_imagen_1' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                'url_imagen_2' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800',
                'url_imagen_3' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=800',
                'estado'       => true,
            ],
            [
                'nombre'      => 'Río San Luis',
                'descripcion' => 'El río principal del municipio ofrece un espacio perfecto para el turismo de aventura: kayak, tubing y pesca deportiva. Sus orillas están bordeadas de guadua y árboles nativos.',
                'url_imagen_1' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                'url_imagen_2' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=800',
                'url_imagen_3' => 'https://images.unsplash.com/photo-1504701954957-2010ec3bcec1?w=800',
                'estado'       => true,
            ],
            [
                'nombre'      => 'Parque Principal San Luis',
                'descripcion' => 'Corazón del municipio, rodeado de la iglesia colonial y casas de arquitectura tradicional antioqueña. El lugar de encuentro de locales y visitantes, con kioscos de comida típica los fines de semana.',
                'url_imagen_1' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800',
                'url_imagen_2' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800',
                'url_imagen_3' => 'https://images.unsplash.com/photo-1526495124232-a04e1849168c?w=800',
                'estado'       => true,
            ],
            [
                'nombre'      => 'Finca Los Arrayanes',
                'descripcion' => 'Reserva privada de bosque nativo con senderos interpretativos donde se pueden avistar más de 80 especies de aves. Ofrece cabalgatas guiadas y talleres de avistamiento para grupos.',
                'url_imagen_1' => 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800',
                'url_imagen_2' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?w=800',
                'url_imagen_3' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?w=800',
                'estado'       => true,
            ],
        ];

        foreach ($sitios as $data) {
            SitioTuristico::updateOrCreate(
                ['nombre' => $data['nombre'], 'lugar_id' => $lugar->id],
                array_merge($data, ['lugar_id' => $lugar->id])
            );
        }

        $this->command->info('✓ ' . count($sitios) . ' sitios turísticos creados para San Luis.');
    }
}
