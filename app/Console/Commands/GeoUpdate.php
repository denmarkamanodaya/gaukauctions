<?php

namespace App\Console\Commands;

use App\Services\AuctioneerImportService;
use App\Services\GeoService;
use Illuminate\Console\Command;

class GeoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:geoupdate {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Long/Lat for auctioneers / events';
    /**
     * @var GeoService
     */
    private $geoService;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GeoService $geoService)
    {
        parent::__construct();
        $this->geoService = $geoService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $updateType = $this->argument('type');
        if($updateType == 'auctioneers')
        {
            $this->geoService->updateAllAuctioneers();
        } else if($updateType == 'events') {
            $this->geoService->updateAllEvents();
        }
    }
}
