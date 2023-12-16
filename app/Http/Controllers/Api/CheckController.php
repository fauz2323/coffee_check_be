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
use Ramsey\Uuid\Uuid;

class CheckController extends Controller
{
    function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|max:2048',
        ]);

        $imageService = new ImageService;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Uuid::uuid1() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('storage/images/');

            // Store the original image
            $image->move($uploadPath, $imageName);

            // Resize the image
            $resizedImage = Image::make($uploadPath . $imageName)->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Save the resized image
            $resizedImage->save($uploadPath . 'resized_' . $imageName);

            $resizedImage = Image::make($uploadPath . 'resized_' . $imageName);
            $checkImage = $imageService->checkDominantColor($resizedImage);

            $history = new HistoryCheck();
            $history->user_id = Auth::user()->id;
            $history->uuid = Uuid::uuid4();
            $history->name = $image->getClientOriginalName();
            $history->type = $checkImage['color'];
            $history->red = $checkImage['r'];
            $history->green = $checkImage['g'];
            $history->blue = $checkImage['b'];
            $history->save();

            $imageHistoty = new ImageHistory;
            $imageHistoty->history_id = $history->id;
            $imageHistoty->image = $imageName;
            $imageHistoty->uuid = Uuid::uuid4();
            $imageHistoty->save();

            return response()->json([
                'message' => 'success upload image',
                'image' => $imageName,
                'dataType' => $checkImage['color'],
                'red' => $checkImage['r'],
                'green' => $checkImage['g'],
                'blue' => $checkImage['b'],
            ], 200);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 222);
        }
    }
}
