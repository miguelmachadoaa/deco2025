<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ContratosGruposRepository;
use Auth;
use Illuminate\Support\Str;
use View;


class ContratosGruposController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ContratosGruposRepository $contratosGruposRepository
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
        $contratosGrupos = $this->contratosGruposRepository->all();

        return view('admin.contrato_grupos.home', compact('contratosGrupos'));
    }

    public function add()
    {
        return view('admin.contrato_grupos.add');
    }


    public function store(Request $request)
    {

        $this->contratosGruposRepository->create($request->all());
  
        return redirect('admin/contratos_grupos');
        
    }

    public function save(Request $request)
    {

        $grupo = $this->contratosGruposRepository->create($request->all());

        $contratosGrupos = $this->contratosGruposRepository->all();
  
        return view('admin.contrato_grupos.option', compact('contratosGrupos', 'grupo'));
        
    }


    public function edit($id)
    {
        $contratoTipo = $this->contratosGruposRepository->find($id);

        return view('admin.contrato_grupos.edit', compact('contratoTipo'));
    }


    public function update($id, Request $request)
    {

        $contratoCategoria = $this->contratosGruposRepository->update($id, $request->all());



        return redirect('admin/contratos_grupos');
        
    }


    public function detail($id)
    {
        $grupos = $this->contratosGruposRepository->find($id);

        

        return view('admin.contrato_grupos.detail', compact('grupos'));
    }

    public function delete($id)
    {

        $this->contratosGruposRepository->delete($id);

        return redirect('admin/contratos_grupos');
        
    }
    

}
