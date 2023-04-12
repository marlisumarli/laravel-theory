<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function author(): JsonResponse
    {
        return response()->json([
            'nama' => 'Sumarli',
            'nim' => '21416255201201',
            'kelas' => 'IF21F'
        ]);
    }
}
