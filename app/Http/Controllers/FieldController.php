<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use \App\Models\DataLayer;
use \App\Models\Surface;
use \App\Models\Field;

class FieldController extends Controller
{
    /**
     * Display a listing of the fields.
     */
    public function index()
    {
        $dl = new DataLayer();
        $fields = $dl->listFields();

        return view('field.fields')->with('fields', $fields);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dl = new DataLayer();
        $fields = $dl->listFields();
        $surfaces = $dl->getAllSurfaces();

        return view('field.editField')->with('surfaces', $surfaces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dl = new DataLayer();

        $nome_campo = $request->input('nome_campo');
        $descrizione = $request->input('descrizione');
        $id_superficie = $request->input('id_superficie');
        $orario_apertura = $request->input('orario_apertura');
        $orario_chiusura = $request->input('orario_chiusura');

        $dl->addField($nome_campo, $descrizione, $id_superficie, $orario_apertura, $orario_chiusura);

        return Redirect::to(route('field.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dl = new DataLayer();
        $field = $dl->findFieldById($id);

        if ($field != null)
        {
            return view('field.details')->with('field', $field);
        } else {
            return view('errors.404')->with('message', 'Wrong field ID has been used.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dl = new DataLayer();
        $field = $dl->findFieldById($id);
        $surfaces = $dl->getAllSurfaces();

        if ($field !== null) {
            return view('field.editField')->with('field', $field)->with('surfaces', $surfaces);
        } else {
            return view('errors.404')->with('message','Wrong field ID has been used!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dl = new DataLayer();
        $nome_campo = $request->input('nome_campo');
        $descrizione = $request->input('descrizione');
        $id_superficie = $request->input('id_superficie');
        $orario_apertura = $request->input('orario_apertura');
        $orario_chiusura = $request->input('orario_chiusura');
        $dl->editField($id, $nome_campo, $descrizione, $id_superficie, $orario_apertura, $orario_chiusura);

        return Redirect::to(route('field.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dl = new DataLayer();
        $field = $dl->deleteField($id);

        return Redirect::to(route('field.index'));
    }

    public function confirmDestroy($id)
    {
        $dl = new DataLayer();
        $field = $dl->findFieldById($id);
        if ($field !== null) {
            return view('field.deleteField')->with('field', $field);
        } else {
            return view('errors.404')->with('message','Wrong field ID has been used!');
        }
    }

    public function bookThisField(string $id) {
        $dl = new DataLayer();
        $field = $dl->findFieldById($id);

        if($field !== null) {
            return view('reservation.addReservation')->with('field', $field);
        } else {
            return view('errors.404')->with('message','Wrong field ID has been used.');
        }
    }

    public function ajaxCheckForFields(Request $request) {
        
        $dl = new DataLayer();
        
        if($dl->findFieldByName($request->input('nome_campo')))
        {
            $response = array('found'=>true);
        } else {
            $response = array('found'=>false);
        }
        return response()->json($response);
    }

}
