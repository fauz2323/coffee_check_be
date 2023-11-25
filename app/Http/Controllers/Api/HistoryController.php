<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryCheck;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    function getHistory()
    {
        $history = HistoryCheck::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'history' => $history
        ]);
    }

    function detailHistory(Request $request)
    {
        $request->validate([
            'uuid' => 'required'
        ]);

        $history = HistoryCheck::where('uuid', $request->uuid)->with('image')->first();

        return response()->json([
            'history' => $history
        ]);
    }
}
