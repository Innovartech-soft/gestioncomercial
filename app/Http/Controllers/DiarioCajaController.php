<?php

namespace App\Http\Controllers;

use App\Exports\CajaDiariaExportExcel;
use Illuminate\Http\Request;
use App\DiarioCaja;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DiarioCajaController extends Controller
{
    public function __construct() {
        $this->middleware('administrador');
    }

    public function index()
    {
        $cajaDiarias = DiarioCaja::all()->sortByDesc('fecha');

        return view('pages.cajadiaria.index', compact('cajaDiarias'));
    }

    public function  exportarExcel(){
        return Excel::download(new CajaDiariaExportExcel, Carbon::now()->toDateString().'-Caja-Diaria.xlsx');

    }
}
