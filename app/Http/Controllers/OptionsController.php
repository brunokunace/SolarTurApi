<?php

namespace App\Http\Controllers;

use App\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function index(Request $request)
    {
        $count = Options::count();
        if (!$count) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 200);
        }

        $per_page = intval($request->query('per_page', 5));
        $current_page = intval($request->query('page', 1));

        $to = $per_page * $current_page;
        $from = $to - $per_page;

        $path = $request->url();
        $next_page_url = null;
        $prev_page_url = null;
        $last_page = ceil($count / $per_page);
        if ($current_page < $last_page) {
            $next_page_url = urldecode($request->fullUrl());
            $next_page = '&page=' . ($current_page + 1);
            $next_page_url = str_replace('&page=' . $current_page, $next_page, $next_page_url);
        }
        if ($current_page > 1) {
            $prev_page_url = urldecode($request->fullUrl());
            $prev_page = '&page=' . ($current_page - 1);
            $prev_page_url = str_replace('&page=' . $current_page, $prev_page, $prev_page_url);
        }


        $options = Options::offset($from)->limit($per_page)->get();
        if ($to > $count) {$to = $count;}
        $return = [
            'current_page' => $current_page,
            'data' => $options,
            'from' => $from + 1,
            'last_page' => $last_page,
            'next_page_url' => $next_page_url,
            'path' => $path,
            'per_page' => $per_page,
            'prev_page_url' => $prev_page_url,
            'to' => $to,
            'total' => $count
        ];

        return response()->json( $return, 200);
    }
    public function show($id)
    {
        $options = Options::find($id);
        if (!$options) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        return response()->json($options, 200);
    }
    public function create(Request $request)
    {
        $name = $request->name;
        $value = $request->value;

        $options = Options::create([
            'name'     => $name,
            'value'    => $value
        ]);

        if (!$options) {
            return response()->json(['error' => 'Erro ao criar recurso'], 400);
        }
        return response()->json(['success' => 'Recurso criado com sucesso'], 200);
    }
    public function update(Request $request, $id)
    {
        $options = Options::find($id);
        if (!$options) {
            return response()->json(['error' => 'Erro ao encontrar o recurso'], 400);
        }
        $options->name = $request->name;
        $options->value = $request->value;

        $options->save();
        if (!$options) {
            return response()->json(['error' => 'Erro ao atualizar recurso'], 400);
        }
        return response()->json(['success' => 'Recurso atualizado com sucesso'], 200);
    }
    public function delete($id)
    {
        $options = Options::find($id);
        if (!$options) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        $options->delete();
        return response()->json(['success' => 'Recurso deletado com sucesso'], 200);
    }
    public function list()
    {
        $options = Options::all();
        if (!$options) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        return response()->json($options, 200);
    }
}