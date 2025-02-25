<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Prueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prueba';

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
        try {
            Task::create([
                "nombre"=>"task ".Carbon::now()
            ]);
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class().'::'. __FUNCTION__, $e->getTrace());
            //return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }
}
