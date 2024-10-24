<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::orderBy('id', 'desc')->get();
        return view('.index', compact('members'));
    }

    public function create()
    {
        $member = Member::create(['name' => '']);
        return response()->json(['id' => $member->id]);
    }

    public function update(Request $request)
    {
        $member = Member::find($request->id);
        $member->update([$request->modul => $request->value]);
        return response()->json([]);
    }

    public function delete(Request $request)
    {
        $member = Member::find($request->id);
        $member->delete();
        return response()->json([]);
    }
}
