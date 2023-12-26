<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryCheck;
use App\Models\UserApps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index(Request $request)
    {
        if ($request->ajax()) {
            $user = UserApps::all();
            return DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('user.detail', Crypt::encrypt($row->id)) . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editCustomer">Detail</a> ';
                    $btn .= '<a href="' . route('user.delete', Crypt::encrypt($row->id)) . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-danger btn-sm editCustomer">Delete</a> ';
                    return $btn;
                })
                ->addColumn('date', function ($row) {
                    $date = $row->created_at->format('d-m-Y');
                    return $date;
                })
                ->rawColumns(['action', 'date'])
                ->make(true);
        }
        return view('admin.user.index');
    }

    function detail(Request $request, $id)
    {
        $user = UserApps::find(Crypt::decrypt($id));
        if ($request->ajax()) {
            $user = HistoryCheck::where('user_id', $user->id)->get();
            return DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    $date = $row->created_at->format('d-m-Y');
                    return $date;
                })
                ->rawColumns(['date'])
                ->make(true);
        }
        return view('admin.user.detail', compact('user'));
    }

    function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = UserApps::find($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    function delete($id)
    {
        $user = UserApps::find($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
