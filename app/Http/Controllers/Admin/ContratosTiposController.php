<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ContractoTipoRepository;
use Auth;
use Illuminate\Support\Str;
use View;


class ContratosTiposController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ContractoTipoRepository $contractoTipoRepository
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
        $contratosTipos = $this->contractoTipoRepository->all();

        return view('admin.contrato_tipos.home', compact('contratosTipos'));
    }

    public function add()
    {
        return view('admin.contrato_tipos.add');
    }


    public function store(Request $request)
    {

        $this->contractoTipoRepository->create($request->all());
  
        return redirect('admin/contratos_tipos');
        
    }


    public function edit($id)
    {
        $contratoTipo = $this->contractoTipoRepository->find($id);

        return view('admin.contrato_tipos.edit', compact('contratoTipo'));
    }


    public function update($id, Request $request)
    {

        $contratoCategoria = $this->contractoTipoRepository->update($id, $request->all());

        return redirect('admin/contratos_tipos');
        
    }

    public function delete($id)
    {

        $this->contractoTipoRepository->delete($id);

        return redirect('admin/contratos_tipos');
        
    }
    

}
