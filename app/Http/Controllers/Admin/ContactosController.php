<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Contacto;
use Auth;
use Illuminate\Support\Str;
use View;
use Datetime;


class ContactosController extends Controller
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
    public function index()
    {
        $contactos = Contacto::orderBy('id', 'Desc')->get();

        return view('admin.contacto.home', compact('contactos'));
    }


}
