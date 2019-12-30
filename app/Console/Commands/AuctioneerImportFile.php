<?php

namespace App\Console\Commands;

use App\Services\AuctioneerImportService;
use Illuminate\Console\Command;

class AuctioneerImportFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:auctioneerimportfile {update?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Auctioneers';
    /**
     * @var AuctioneerImportService
     */
    private $auctioneerImportService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AuctioneerImportService $auctioneerImportService)
    {
        parent::__construct();
        $this->auctioneerImportService = $auctioneerImportService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $update = $this->argument('update');
        if($update)
        {
            $this->auctioneerImportService->updateFromFile();
        } else {
            $this->auctioneerImportService->importFromFile();
        }
    }
}
