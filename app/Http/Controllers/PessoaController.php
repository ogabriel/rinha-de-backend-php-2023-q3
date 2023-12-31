<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PessoaController extends BaseController
{
    public function store(Request $request)
    {
        if ($request->apelido == '' || $request->nome == '' || $request->nascimento == '') {
            return response('', 422);
        }

        if (! is_string($request->apelido) || ! is_string($request->nome) || ! is_string($request->nascimento)) {
            return response('', 400);
        }

        if (strlen($request->apelido) > 32 || strlen($request->nome) > 100 || strlen($request->nascimento) != 10) {
            return response('', 400);
        }

        $date = date_parse_from_format('Y-n-j', $request->nascimento);
        if ($date['error_count'] > 0 || $date['warning_count'] > 0) {
            return response('', 422);
        }

        if ($request->stack != null) {
            if (! is_array($request->stack)) {
                return response('', 400);
            }

            foreach ($request->stack as $stack) {
                if (! is_string($stack)) {
                    return response('', 400);
                }

                if (strlen($stack) > 32) {
                    return response('', 400);
                }
            }
        }

        $pessoa = new Pessoa();
        $pessoa->apelido = $request->apelido;
        $pessoa->nome = $request->nome;
        $pessoa->nascimento = $request->nascimento;
        $pessoa->stack = $request->stack;

        if ($request->stack == [] || $request->stack == null) {
            $pessoa->busca = strtolower($request->apelido).' '.strtolower($request->nome);
        } else {
            $busca = array_map('strtolower', $request->stack);
            $busca = array_merge($busca, [strtolower($request->apelido), strtolower($request->nome)]);
            $pessoa->busca = implode(' ', $busca);
        }

        try {
            $pessoa->save();
        } catch (\Throwable $th) {
            return response('', 422);
        }

        return response()->make('', 201)->header('Location', "/pessoas/$pessoa->id");
    }

    public function show($id)
    {
        if (! is_string($id) || strlen($id) != 36) {
            return response('', 404);
        }

        try {
            $pessoa = Pessoa::findOrFail($id);
        } catch (\Throwable $th) {
            return response('', 404);
        }

        return response()->json($pessoa);
    }

    public function search(Request $request)
    {
        $term = $request->query('t');

        if ($term == '') {
            return response('', 400);
        }

        $term = strtolower($term);

        $pessoas = Pessoa::where('busca', 'ilike', '%'.$term.'%')->limit(50)->get();

        return response()->json($pessoas);
    }

    public function count()
    {
        $count = Pessoa::count();

        return response()->json($count);
    }
}
