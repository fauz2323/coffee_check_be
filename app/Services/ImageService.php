<?php

namespace App\Services;

use Intervention\Image\Image;

final class ImageService
{
    function checkDominantColor(Image $image)
    {
        // Inisialisasi hitung piksel untuk warna merah, hijau, dan kuning
        $count_red = 0;
        $count_green = 0;
        $count_yellow = 0;

        $r1 = '';
        $g1 = '';
        $b1 = '';

        $r2 = '';
        $g2 = '';
        $b2 = '';

        $r3 = '';
        $g3 = '';
        $b3 = '';

        $width = $image->width();
        $height = $image->height();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                // Get the RGB values at each pixel
                $rgb = $image->pickColor($x, $y, 'array');
                $rgbData[$y][$x] = $rgb;
                $red = $rgb[0];
                $green = $rgb[1];
                $blue = $rgb[2];
                $threshold = 50; // Sesuaikan nilai sesuai preferensi
                // Deteksi warna merah
                if ($red > $green + $threshold && $red > $blue + $threshold) {
                    $r1 = $red;
                    $g1 = $green;
                    $b1 = $blue;
                    $count_red++;
                }
                // Deteksi warna hijau
                elseif (
                    $green > $red + $threshold && $green > $blue + $threshold
                ) {
                    $r2 = $red;
                    $g2 = $green;
                    $b2 = $blue;
                    $count_green++;
                }
                // Deteksi warna kuning
                elseif (
                    $red > $green && ($red - $green) < $threshold && $blue < $green
                ) {
                    $r3 = $red;
                    $g3 = $green;
                    $b3 = $blue;
                    $count_yellow++;
                }
            }
        }

        // Analisis hasil perhitungan
        if ($count_red > $count_green && $count_red > $count_yellow) {
            $dataReturn = [
                'r' => $r1,
                'g' => $g1,
                'b' => $b1,
                'color' => 'matang',
            ];
            return $dataReturn;
        } elseif ($count_green > $count_red && $count_green > $count_yellow) {
            $dataReturn = [
                'r' => $r2,
                'g' => $g2,
                'b' => $b2,
                'color' => 'mentah',
            ];
            return $dataReturn;
        } elseif ($count_yellow > $count_red && $count_yellow > $count_green) {
            $dataReturn = [
                'r' => $r3,
                'g' => $g3,
                'b' => $b3,
                'color' => 'setengah matang',
            ];
            return $dataReturn;
        } else {
            $dataReturn = [
                'r' => 0,
                'g' => 0,
                'b' => 0,
                'color' => 'tidak terdeteksi',
            ];
            return $dataReturn;
        }
    }
}
