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

        if ($request->apelido == '' || $request->nome == '' || $request->nascimento == '') {
            return response()->make('', 422);
        }

        $date = date_parse_from_format('Y-n-j', $request->nascimento);
        if ($date['error_count'] > 0 || $date['warning_count'] > 0) {
            return response()->make('', 422);
        }

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
        try {
            $pessoa = Pessoa::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->make('', 404);
        }

        return response()->json($pessoa);
    }

    public function search(Request $request)
    {
        $term = $request->query('t');

        if ($term == '') {
            return response()->json([], 400);
        }

        $pessoas = Pessoa::where('nome', 'like', '%'.$term.'%')->get();

        return response()->json($pessoas);
    }

    public function count()
    {
        $count = Pessoa::count();

        return response()->json($count);
    }
}
