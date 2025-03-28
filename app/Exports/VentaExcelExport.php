<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Log;

class VentaExcelExport implements FromView
{
    protected $data;
    protected $parametro;

    /*
    public function __construct($venta, $parametro)
    {
        $this->venta = $venta;
        $this->parametro = $parametro;
    }
*/
    public function __construct($data)
        {
            $this->data = $data;
        }
    /**
     * @return \Illuminate\Support\View
     */
    public function view(): View
    {
        $data = $this->data;
        return view('pages.venta.venta_excel', compact('data'));
    }
}

