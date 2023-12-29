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

    public function show($id)
    {
        $pessoa = Pessoa::findOrFail($id);

        return response()->json($pessoa);
    }

    public function search(Request $request)
    {
        if ($request->busca == '') {
            return response()->json([]);
        }

        $pessoas = Pessoa::where('nome', 'like', '%'.$request->busca.'%')->get();

        return response()->json($pessoas);
    }

    public function count()
    {
        $count = Pessoa::count();

        return response()->json($count);
    }
}
