<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Producto;

class CsvImporter implements ToModel
{
    public function model(array $row)
    {
        return new Producto([
            'id' => $row[0],
            'nombre' => $row[1],
            'precio_costo' => $row[2],
            'bulto' => $row[3],
            'id_rubro' => $row[4],
            'id_marca' => $row[5],
            'id_proveedor' => $row[6],

            // Agrega aquí las columnas correspondientes y sus índices en el array $row
        ]);
    }
}
