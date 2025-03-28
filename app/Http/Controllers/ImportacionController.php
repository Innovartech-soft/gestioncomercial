<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportacionController extends Controller
{
    
    public function index()
    {
        return view('pages.importacion.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $file->move(public_path('importacion'),$nombre);
        return back()->with('success','Archivo subido correctamente');
    }
}
