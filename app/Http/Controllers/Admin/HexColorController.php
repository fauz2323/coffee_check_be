<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HexColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class HexColorController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index(Request $request)
    {

        if ($request->ajax()) {
            $user = HexColor::all();
            return DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('user.detail', Crypt::encrypt($row->id)) . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editCustomer">Detail</a> ';
                    $btn .= '<a href="' . route('hexcolor.delete', Crypt::encrypt($row->id)) . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-danger btn-sm editCustomer">Delete</a> ';
                    return $btn;
                })
                ->addColumn('date', function ($row) {
                    $date = $row->created_at->format('d-m-Y');
                    return $date;
                })
                ->rawColumns(['action', 'date'])
                ->make(true);
        }

        return view('admin.rgb.index');
    }

    function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'red' => 'required',
            'green' => 'required',
            'blue' => 'required',
        ]);

        $hexColor = new HexColor();
        $hexColor->type = $request->type;
        $hexColor->red = $request->red;
        $hexColor->green = $request->green;
        $hexColor->blue = $request->blue;
        $hexColor->save();

        return redirect()->back()->with('success', 'Hex Color Added Successfully');
    }

    function delete($id)
    {
        $color = HexColor::find(Crypt::decrypt($id));
        $color->delete();

        return redirect()->back()->with('success', 'Hex Color Deleted Successfully');
    }
}
