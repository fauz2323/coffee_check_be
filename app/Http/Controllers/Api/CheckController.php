<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HexColor;
use App\Models\HistoryCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class CheckController extends Controller
{
    function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|max:2048',
            'type' => 'required',
        ]);

        $rgbType = HexColor::where('type', $request->type)->first();
        $red = '';
        $green = '';
        $blue = '';

        if (!$rgbType) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('storage/images/');

            // Store the original image
            $image->move($uploadPath, $imageName);

            // Resize the image
            $resizedImage = Image::make($uploadPath . $imageName)->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Save the resized image
            $resizedImage->save($uploadPath . 'resized_' . $imageName);


            $rgbData = [];
            $resizedImage = Image::make($uploadPath . 'resized_' . $imageName);
            $width = $resizedImage->width();
            $height = $resizedImage->height();
            $yes = 0;
            $no = 0;

            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    // Get the RGB values at each pixel
                    $rgb = $resizedImage->pickColor($x, $y, 'array');
                    $rgbData[$y][$x] = $rgb;
                    if ($rgbType->red >= $rgb[0] && $rgbType->green >= $rgb[1] && $rgbType->blue >= $rgb[2]) {
                        $yes++;
                        $red = $rgb[0];
                        $green = $rgb[1];
                        $blue = $rgb[2];
                    } else {
                        $no++;
                    }
                }
            }

            if ($yes > $no) {
                $iamgeRGBCount = 'image is ' . $request->type;
            } else {
                $iamgeRGBCount = 'image is not ' . $request->type;
            }

            $history = new HistoryCheck();
            $history->user_id = Auth::user()->id;
            $history->uuid = Uuid::uuid4();
            $history->name = $imageName;
            $history->type = $iamgeRGBCount;
            $history->red = $red;
            $history->green = $green;
            $history->blue = $blue;
            $history->save();

            return response()->json([
                'message' => 'success upload image',
                'image' => $imageName,
                'dataType' => $iamgeRGBCount,
                'red' => $red,
                'green' => $green,
                'blue' => $blue
            ], 200);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 222);
        }
    }
}
