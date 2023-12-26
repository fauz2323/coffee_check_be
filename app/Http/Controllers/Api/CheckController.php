<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HexColor;
use App\Models\HistoryCheck;
use App\Models\ImageHistory;
use App\Services\ImageService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Ramsey\Uuid\Uuid;

class CheckController extends Controller
{
    function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|max:2048',
            'type' => 'required',
        ]);

        $red = 0;
        $green = 0;
        $blue = 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Uuid::uuid1() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('storage/images/');
            // Store the original image
            $image->move($uploadPath, $imageName);
            // Resize the image
            if ($image->getClientOriginalExtension() == 'png') {
                $images = imagecreatefrompng($uploadPath . $imageName);
            } else {
                $images = imagecreatefromjpeg($uploadPath . $imageName);
            }
            $redCount = 0;
            $greenCount = 0;
            $yellowCount = 0;

            $width = imagesx($images);
            $height = imagesy($images);

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color = imagecolorat($images, $x, $y);
                    $rgb = imagecolorsforindex($images, $color);

                    if ($request->type == 'mentah') {
                        // Check for predominant colors (this is a very basic example)
                        if ($rgb['green'] > $rgb['red'] && $rgb['green'] > $rgb['blue']) {
                            $greenCount++;
                            $red = $rgb['red'];
                            $green = $rgb['green'];
                            $blue = $rgb['blue'];
                        }
                    }

                    if ($request->type == 'matang') {
                        $redThreshold = 150;
                        $greenThreshold = 50;
                        $blueThreshold = 50;

                        // Periksa apakah nilai-nilai RGB berada dalam rentang yang mendekati merah
                        if ($rgb['red'] >= $redThreshold && $rgb['green'] <= $greenThreshold && $rgb['blue'] <= $blueThreshold) {
                            $redCount++;
                            $red = $rgb['red'];
                            $green = $rgb['green'];
                            $blue = $rgb['blue'];
                        }
                    }

                    if ($request->type == 'setengah matang') {
                        $redThreshold = 150;
                        $greenThreshold = 150;
                        $blueThreshold = 50;
                        // Periksa apakah nilai-nilai RGB berada dalam rentang yang mendekati kuning
                        if ($rgb['red'] >= $redThreshold && $rgb['green'] >= $greenThreshold && $rgb['blue'] <= $blueThreshold) {
                            $yellowCount++;
                            $red = $rgb['red'];
                            $green = $rgb['green'];
                            $blue = $rgb['blue'];
                        }
                    }
                }
            }

            if ($request->type == 'mentah' && $greenCount >= 100) {
                $typeData = 'mentah';
            } elseif ($request->type == 'matang' && $redCount >= 100) {
                $typeData = 'matang';
            } elseif ($request->type == 'setengah matang' && $yellowCount >= 100) {
                $typeData = 'setengah matang';
            } else {
                $typeData = 'tidak terdeteksi';
            }


            $history = new HistoryCheck();
            $history->user_id = Auth::user()->id;
            $history->uuid = Uuid::uuid4();
            $history->name = $image->getClientOriginalName();
            $history->type = $typeData;
            $history->red = $red;
            $history->green = $green;
            $history->blue = $blue;
            $history->save();

            $imageHistoty = new ImageHistory;
            $imageHistoty->history_id = $history->id;
            $imageHistoty->image = $imageName;
            $imageHistoty->uuid = Uuid::uuid4();
            $imageHistoty->save();

            return response()->json([
                'message' => 'success upload image',
                'image' => $imageName,
                'dataType' => $typeData,
                'red' => $red,
                'green' => $green,
                'blue' => $blue,
                'count' => [
                    $yellowCount, $redCount, $greenCount
                ]
            ], 200);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 222);
        }
    }

    function upload2(Request $request)
    {
        $request->validate([
            'image' => 'required|max:2048',
            'type' => 'required',
        ]);

        $image = $request->file('image');
        $imageName = Uuid::uuid1() . '.' . $image->getClientOriginalExtension();
        $uploadPath = public_path('storage/images/');
        $image->move($uploadPath, $imageName);

        $imagePath = public_path('storage/images/' . $imageName);

        // Create a palette from the image
        $palette = Palette::fromFilename($imagePath);

        // Create a color extractor
        $extractor = new ColorExtractor($palette);

        // Get the dominant colors from the image
        $colors = $extractor->extract();

        // Check if red, green, or yellow is dominant
        $data = [];

        // an extractor is built from a palette
        $extractor = new ColorExtractor($palette);

        // it defines an extract method which return the most “representative” colors
        $colors = $extractor->extract(300);
        $r = 0;
        $g = 0;
        $b = 0;

        foreach ($colors as $key) {
            $dataColor = Color::fromIntToRgb($key);
            $data[] = $dataColor;

            if ($dataColor['r'] > $dataColor['g'] && $dataColor['r'] > $dataColor['b']) {
                $r++;
            }
            // Deteksi warna hijau
            elseif (
                $dataColor['g'] > $dataColor['r']  && $dataColor['g'] >  $dataColor['b']
            ) {
                $g++;
            }
            // Deteksi warna kuning
            elseif (
                $dataColor['r'] > $dataColor['g'] && ($dataColor['r'] - $dataColor['g']) < 20 && $dataColor['b'] < $dataColor['g']
            ) {
                $b++;
            }
        }


        return response()->json([
            'data' => $data,
            'top' => $colors,
            'r' => $r,
            'g' => $g,
            'b' => $b,
        ]);
    }

    function upload3(Request $request)
    {

        $image = $request->file('image');
        $imageName = Uuid::uuid1() . '.' . $image->getClientOriginalExtension();
        $uploadPath = public_path('storage/images/');
        // Store the original image
        $image->move($uploadPath, $imageName);
        // Resize the image
        if ($image->getClientOriginalExtension() == 'png') {
            $image = imagecreatefrompng($uploadPath . $imageName);
        } else {
            $image = imagecreatefromjpeg($uploadPath . $imageName);
        }
        $redCount = 0;
        $greenCount = 0;
        $yellowCount = 0;

        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($image, $x, $y);
                $rgb = imagecolorsforindex($image, $color);

                if ($request->type == 'mentah') {
                    $redThreshold = 100;
                    $greenThreshold = 150;
                    $blueThreshold = 100;
                    // Check for predominant colors (this is a very basic example)
                    if ($rgb['green'] > $rgb['red'] && $rgb['green'] > $rgb['blue']) {
                        $greenCount++;
                    }
                }

                if ($request->type == 'matang') {
                    $redThreshold = 150;
                    $greenThreshold = 50;
                    $blueThreshold = 50;

                    // Periksa apakah nilai-nilai RGB berada dalam rentang yang mendekati merah
                    if ($rgb['red'] >= $redThreshold && $rgb['green'] <= $greenThreshold && $rgb['blue'] <= $blueThreshold) {
                        $redCount++;
                    }
                }

                if ($request->type == 'setengah matang') {
                    $redThreshold = 150;
                    $greenThreshold = 150;
                    $blueThreshold = 50;

                    // Periksa apakah nilai-nilai RGB berada dalam rentang yang mendekati kuning
                    if ($rgb['red'] >= $redThreshold && $rgb['green'] >= $greenThreshold && $rgb['blue'] <= $blueThreshold) {
                        $yellowCount++;
                    }
                }
            }
        }

        return response()->json([
            'r' => $redCount,
            'g' => $greenCount,
            'y' => $yellowCount
        ]);
    }
}
