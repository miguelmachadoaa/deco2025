<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\FormasPagoRepository;
use Auth;
use Illuminate\Support\Str;
use View;


class FormaspagoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly FormasPagoRepository $formasPagoRepository
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
        $formaspago = $this->formasPagoRepository->all();

        return view('admin.formas_pago.home', compact('formaspago'));
    }

    public function add()
    {
        return view('admin.formas_pago.add');
    }


    public function store(Request $request)
    {

        $this->formasPagoRepository->create($request->all());
  
        return redirect('admin/formas_pago');
        
    }


    public function edit($id)
    {
        $formapago = $this->formasPagoRepository->find($id);

        return view('admin.formas_pago.edit', compact('formapago'));
    }


    public function update($id, Request $request)
    {

        $formapago = $this->formasPagoRepository->update($id, $request->all());

        return redirect('admin/formas_pago');
        
    }

    public function delete($id)
    {

        $this->formasPagoRepository->delete($id);

        return redirect('admin/formas_pago');
        
    }
    

}
