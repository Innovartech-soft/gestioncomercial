<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DatabaseDump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('DatabaseDump -> Inicio');
       // Obtener la configuración de la base de datos desde el archivo .env
       $databaseName = config('database.connections.mysql.database');
       $userName = config('database.connections.mysql.username');
       $password = config('database.connections.mysql.password');

        Log::info('Datos conexion - db_name='.$databaseName.' - user_name='.$userName.' - pwd='.$password);

       putenv("MYSQL_PWD={$password}");
       // Nombre del archivo de copia de seguridad
       $backupFileName = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';

       // Ruta de destino para la copia de seguridad
       $backupPath = 'X:/backupsistema/' . $backupFileName;

       // Comando para realizar la copia de seguridad
       $command = sprintf('mysqldump -u%s %s > %s', $userName, $databaseName, $backupPath);


       // Ejecutar el comando de copia de seguridad
       exec($command);

       // Comprobar si la copia de seguridad se creó correctamente
       if (file_exists($backupPath)) {
           $this->info('Copia de seguridad de la base de datos creada exitosamente: ' . $backupPath);
           Log::info('DatabaseDump -> Copia de seguridad de la base de datos creada exitosamente: ' . $backupPath);
       } else {
           $this->error('Error al crear la copia de seguridad de la base de datos.');
           Log::info('Error al crear la copia de seguridad de la base de datos.');
       }
       Log::info('DatabaseDump -> Fin');
    }
}
