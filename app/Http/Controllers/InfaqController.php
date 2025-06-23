<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infaq;

class InfaqController extends Controller
{

    public function store(Request $request)
    {

        $request->validate([
            'nominal' => 'required',
        ]);

        $infaq = Infaq::create([
            'nominal' => $request->nominal,
        ]);

        return response()->json([
            'message' => 'Infaq berhasil disimpan',
            'data' => $infaq
        ], 201);
    }
}
