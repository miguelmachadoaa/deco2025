<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\DolarRepository;
use Auth;
use Illuminate\Support\Str;
use View;
use Datetime;


class DolarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly DolarRepository $dolarRepository
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
        $dolars = $this->dolarRepository->all();

        return view('admin.dolar.home', compact('dolars'));
    }

    public function add()
    {
        return view('admin.dolar.add');
    }


    public function store(Request $request)
    {

        $this->dolarRepository->create($request->all());
  
        return redirect('admin/dolar');
        
    }


    public function edit($id)
    {
        $dolar = $this->dolarRepository->find($id);

        return view('admin.dolar.edit', compact('dolar'));
    }


    public function update($id, Request $request)
    {

        $this->dolarRepository->update($id, $request->all());

        return redirect('admin/dolar');
        
    }

    public function delete($id)
    {

        $this->dolarRepository->delete($id);

        return redirect('admin/dolar');
        
    }
    

}
