<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Materiales;

class MaterialController extends Controller
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
            'descripcion' => 'required|string|max:1000',
            'codigo'=>'required|string'
        ]);

        $material = Materiales::create($request->only('nombre', 'descripcion', 'codigo'));

        return response()->json($material);
    }


}
