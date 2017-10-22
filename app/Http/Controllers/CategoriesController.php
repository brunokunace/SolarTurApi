<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $count = Category::count();
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


        $categories = Category::offset($from)->limit($per_page)->get();
        if ($to > $count) {$to = $count;}
        $return = [
            'current_page' => $current_page,
            'data' => $categories,
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
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        return response()->json($category, 200);
    }
    public function create(Request $request)
    {
        $name = $request->name;
        $marker = $request->marker;
        $color = $request->color;
        $category = Category::create([
            'name'    => $name,
            'marker'  => $marker,
            'color'   => $color
        ]);
        if (!$category) {
            return response()->json(['error' => 'Erro ao criar recurso'], 400);
        }
        return response()->json(['success' => 'Recurso criado com sucesso'], 200);
    }
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Erro ao encontrar o recurso'], 400);
        }
        $category->name = $request->name;
        $category->marker = $request->marker;
        $category->color = $request->color;
        $category->save();
        if (!$category) {
            return response()->json(['error' => 'Erro ao atualizar recurso'], 400);
        }
        return response()->json(['success' => 'Recurso atualizado com sucesso'], 200);
    }
    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        $category->delete();
        return response()->json(['success' => 'Recurso deletado com sucesso'], 200);
    }
    public function list()
    {
        $categories = Category::all();
        if (!$categories) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        return response()->json($categories, 200);
    }
    public function listWithEstablishments()
    {
        try {
            $categories = Category::all();
            if (!$categories) {
                return response()->json(['error' => 'Não foi possível completar a operação'], 400);
            }
            $cat = [];
            foreach ($categories as $key => $category) {
                $category->establishments;
                if ($category->establishments()->count() > 0) {
                    array_push($cat, $category);
                }
            }
            return response()->json($cat, 200);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
    }
    public function listOnlyCategories()
    {
        try {
            $categories = Category::all();
            if (!$categories) {
                return response()->json(['error' => 'Não foi possível completar a operação'], 400);
            }
            $cat = [];
            foreach ($categories as $key => $category) {
                if ($category->establishments()->count() > 0) {
                    array_push($cat, $category);
                }
            }
            return response()->json($cat, 200);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
    }
    public function markers()
    {
       $markers = [
           ['name' => 'animais', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'animais'.'.png'],
           ['name' => 'automotivo', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'automotivo'.'.png'],
           ['name' => 'biblioteca', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'biblioteca'.'.png'],
           ['name' => 'cafe', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'cafe'.'.png'],
           ['name' => 'casas', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'casas'.'.png'],
           ['name' => 'ciencia', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'ciencia'.'.png'],
           ['name' => 'comercial', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'comercial'.'.png'],
           ['name' => 'comida', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'comida'.'.png'],
           ['name' => 'consertos', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'consertos'.'.png'],
           ['name' => 'educacao', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'educacao'.'.png'],
           ['name' => 'eletronicos', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'eletronicos'.'.png'],
           ['name' => 'empregos', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'empregos'.'.png'],
           ['name' => 'entretenimento', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'entretenimento'.'.png'],
           ['name' => 'escola', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'escola'.'.png'],
           ['name' => 'esportes', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'esportes'.'.png'],
           ['name' => 'esportes2', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'esportes2'.'.png'],
           ['name' => 'evento', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'evento'.'.png'],
           ['name' => 'fashion', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'fashion'.'.png'],
           ['name' => 'financeiro', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'financeiro'.'.png'],
           ['name' => 'fotografia', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'fotografia'.'.png'],
           ['name' => 'hotel', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'hotel'.'.png'],
           ['name' => 'igreja', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'igreja'.'.png'],
           ['name' => 'ingressos', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'ingressos'.'.png'],
           ['name' => 'livros', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'livros'.'.png'],
           ['name' => 'lounges', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'lounges'.'.png'],
           ['name' => 'medico', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'medico'.'.png'],
           ['name' => 'museu', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'museu'.'.png'],
           ['name' => 'parque', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'parque'.'.png'],
           ['name' => 'pizza', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'pizza'.'.png'],
           ['name' => 'praia', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'praia'.'.png'],
           ['name' => 'presentes', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'presentes'.'.png'],
           ['name' => 'restaurantes', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'restaurantes'.'.png'],
           ['name' => 'revistas', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'revistas'.'.png'],
           ['name' => 'roupas', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'roupas'.'.png'],
           ['name' => 'salao', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'salao'.'.png'],
           ['name' => 'servicos', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'servicos'.'.png'],
           ['name' => 'shopping', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'shopping'.'.png'],
           ['name' => 'telefone', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'telefone'.'.png'],
           ['name' => 'trabalho', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'trabalho'.'.png'],
           ['name' => 'transporte', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'transporte'.'.png'],
           ['name' => 'viagem', 'marker' => 'http://'.$_SERVER['HTTP_HOST'].'/uploads/markers/'.'viagem'.'.png']
       ];
       return response()->json($markers, 200);
    }

}
