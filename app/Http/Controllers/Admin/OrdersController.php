<?php

namespace App\Http\Controllers\Admin;

use App\Models\Productos;
use Auth;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
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
        return view('admin.products.home');
    }

    public function add()
    {
        return view('admin.products.add');
    }


}
