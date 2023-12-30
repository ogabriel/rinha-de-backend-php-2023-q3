<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PessoaController extends BaseController
{
    public function store(Request $request)
    {
        $pessoa = new Pessoa();
        $pessoa->apelido = $request->apelido;
        $pessoa->nome = $request->nome;
        $pessoa->nascimento = $request->nascimento;
        $pessoa->stack = $request->stack;

        if ($request->stack == [] || $request->stack == null) {
            $pessoa->busca = "$request->apelido $request->nome";
        } else {
            $stack = implode(' ', $request->stack);
            $pessoa->busca = "$request->apelido $request->nome $stack";
        }

        try {
            $pessoa->save();
        } catch (\Throwable $th) {
            return response()->make('', 422);
        }

        return response()->make('', 201)->header('Location', "/pessoas/$pessoa->id");
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
