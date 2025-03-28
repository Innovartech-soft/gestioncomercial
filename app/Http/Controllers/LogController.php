<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logs;
use Yajra\DataTables\Facades\DataTables;

class LogController extends Controller
{
    public function index()
    {
        return view('pages.log.index');
    }

    public function getData()
    {
        try {
            $logs = Logs::select(['id_usuario', 'mensaje', 'created_at']);

            return DataTables::of($logs)
                ->editColumn('created_at', function($log) {
                    return $log->created_at->format('d-m-Y H:i:s');
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los logs: ' . $e->getMessage()]);
        }
    }

}
