<?php

namespace geolocation\Console\Commands;

use geolocation\Coordinate;
use geolocation\Stop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetCoordinates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coordinates:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all coordinates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('|-----------------------------|');
        $this->info('|---- RESET COORDINATES ----|');
        $this->info('|-----------------------------|');

        $coordinates = Coordinate::all();

        if (!count($coordinates)) {
            $this->error('No coordinates found to be reset');
            exit;
        }

        $bar = $this->output->createProgressBar(count($coordinates));

        foreach ($coordinates as $coordinate) {
            $coordinate->update([
                'stop_id' => NULL,
                'processed' => 0
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->info('All coordinates have been reset');
    }
}
