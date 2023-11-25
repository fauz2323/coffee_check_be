<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryCheck;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HistoryAdminController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index(Request $request)
    {

        if ($request->ajax()) {
            $user = HistoryCheck::all();
            return DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    $date = $row->created_at->format('d-m-Y');
                    return $date;
                })
                ->rawColumns(['date'])
                ->make(true);
        }
        return view('admin.history.index');
    }
}
