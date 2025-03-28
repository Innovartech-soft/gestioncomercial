<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Productos;
use Illuminate\Support\Facades\Log;
use App\CsvImporter;
class importCSVProductos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-csv-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leemos el archivo csv a importar y cargamos en la tabla productos';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Importación de productos -> Inicio');

        $archivo = 'EXCEL_PRODUCTOS.csv'; // Ruta al archivo CSV

        Excel::import(new CsvImporter, $archivo);

        Log::info('Importación de productos -> Finalizado');
    }
}
