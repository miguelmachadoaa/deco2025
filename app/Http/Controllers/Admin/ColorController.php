<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Colores;

class ColorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
    )
    {
        $this->middleware('auth');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'hex' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $color = Colores::create($request->only('nombre', 'hex'));

        return response()->json($color);
    }


}
