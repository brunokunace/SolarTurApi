<?php

namespace App\Http\Controllers;

use App\Establishment;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    public function index(Request $request)
    {
        $count = Establishment::count();
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


        $establishments = Establishment::offset($from)->limit($per_page)->get();
        if ($to > $count) {$to = $count;}
        $return = [
            'current_page' => $current_page,
            'data' => $establishments,
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
        $establishment = Establishment::find($id);
        if (!$establishment) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        $establishment->category;
        return response()->json($establishment, 200);
    }
    public function create(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $facebook = $request->facebook;
        $instagram = $request->instagram;
        $site = $request->site;
        $phone = $request->phone;
        $photo = $request->photo;
        $address = $request->address;

        $categories_id = $request->categories_id;
        $address2 = str_replace(' ', '+', $address);
        $geo = json_decode($this->geocode($address2)->getContent());
        $lat = $geo->lat;
        $lng = $geo->lng;
        $establishment = Establishment::create([
            'name'           => $name,
            'description'    => $description,
            'photo'          => $photo,
            'facebook'       => $facebook,
            'instagram'      => $instagram,
            'site'           => $site,
            'phone'          => $phone,
            'address'        => $address,
            'lat'            => $lat,
            'lng'            => $lng,
            'categories_id'  => $categories_id
        ]);

        if (!$establishment) {
            return response()->json(['error' => 'Erro ao criar recurso'], 400);
        }
        return response()->json(['success' => 'Recurso criado com sucesso'], 200);
    }
    public function update(Request $request, $id)
    {
        $establishment = Establishment::find($id);
        if (!$establishment) {
            return response()->json(['error' => 'Erro ao encontrar o recurso'], 400);
        }
        $establishment->name = $request->name;
        $establishment->description = $request->description;
        $establishment->photo = $request->photo;
        $establishment->facebook = $request->facebook;
        $establishment->instagram = $request->instagram;
        $establishment->site = $request->site;
        $establishment->phone = $request->phone;
        $establishment->address = $request->address;
        $address2 = str_replace(' ', '+', $establishment->address);
        $geo = json_decode($this->geocode($address2)->getContent());
        $establishment->lat = $geo->lat;
        $establishment->lng = $geo->lng;
        $establishment->categories_id = $request->categories_id;
        $establishment->save();
        if (!$establishment) {
            return response()->json(['error' => 'Erro ao atualizar recurso'], 400);
        }
        return response()->json(['success' => 'Recurso atualizado com sucesso'], 200);
    }
    public function delete($id)
    {
        $establishment = Establishment::find($id);
        if (!$establishment) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        $establishment->delete();
        return response()->json(['success' => 'Recurso deletado com sucesso'], 200);
    }
    public function listWithCategory()
    {
        $establishments = Establishment::all();
        if (!$establishments) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        foreach ($establishments as $establishment){
            $establishment->category;
        }
        return response()->json($establishments, 200);
    }
    public function list()
    {
        $establishments = Establishment::all();
        if (!$establishments) {
            return response()->json(['error' => 'Não foi possível completar a operação'], 400);
        }
        return response()->json($establishments, 200);
    }
    public function geocode($address)
    {
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$address;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoloc = json_decode(curl_exec($ch), true);
        if (!empty($geoloc['results'])) {
            return response()->json($geoloc['results'][0]['geometry']['location'], 200);
        }
        return response()->json('Endereço não encontrado', 400);
    }
    public function photo(Request $request)
    {

        $photo = null;
        if ($request->hasFile('file')) {
            $photo = $request->file;
            $fileName = $photo->getClientOriginalName();
            $photo->move('./uploads/establishments/', $fileName);
            $photo = $_SERVER['HTTP_HOST'].'/uploads/establishments/'.$fileName;

            return response($photo);
        }

        return response()->json('Não foi enviado nenhum arquivo', 400);

    }

}
