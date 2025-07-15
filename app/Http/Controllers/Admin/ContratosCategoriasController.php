<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ContractoCategoriaRepository;
use Auth;
use Illuminate\Support\Str;
use View;


class ContratosCategoriasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ContractoCategoriaRepository $contractoCategoriaRepository
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
        $contratosCategorias = $this->contractoCategoriaRepository->all();

        return view('admin.contrato_categorias.home', compact('contratosCategorias'));
    }

    public function add()
    {
        return view('admin.contrato_categorias.add');
    }


    public function store(Request $request)
    {

        $this->contractoCategoriaRepository->create($request->all());
  
        return redirect('admin/contratos_categorias');
        
    }


    public function edit($id)
    {
        $contratoCategoria = $this->contractoCategoriaRepository->find($id);

        return view('admin.contrato_categorias.edit', compact('contratoCategoria'));
    }


    public function update($id, Request $request)
    {

        $contratoCategoria = $this->contractoCategoriaRepository->update($id, $request->all());

        return redirect('admin/contratos_categorias');
        
    }

    public function delete($id)
    {

        $this->contractoCategoriaRepository->delete($id);

        return redirect('admin/contratos_categorias');
        
    }
    

}
