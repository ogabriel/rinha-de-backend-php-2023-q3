<?php

namespace App\Http\Controllers;

class PessoaController extends Controller
{
    public function index()
    {
        $pessoas = Pessoa::all();

        return response()->json($pessoas);
    }

    public function store(Request $request)
    {
        $pessoa = new Pessoa();
        $pessoa->nome = $request->nome;
        $pessoa->save();

        return response()->json($pessoa);
    }

    // search by field 'busca' and return nothing if the field is empty
    public function search(Request $request)
    {
        if ($request->busca == '') {
            return response()->json([]);
        }

        $pessoas = Pessoa::where('nome', 'like', '%'.$request->busca.'%')->get();

        return response()->json($pessoas);
    }

    // count all pessoas on database
    public function count()
    {
        $count = Pessoa::count();

        return response()->json($count);
    }
}
