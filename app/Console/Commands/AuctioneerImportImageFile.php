<?php

namespace App\Console\Commands;

use App\Services\AuctioneerImportService;
use Illuminate\Console\Command;

class AuctioneerImportImageFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gauk:auctioneerimportimagefile {dealers?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Auctioneer Images';
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
        $dealers = $this->argument('dealers');
        if($dealers == 'dealers')
        {
            $this->auctioneerImportService->updateFromFileImageDealers();
        } else if($dealers == 'getimages') {
            $this->auctioneerImportService->importActualImages();
        } else if($dealers == 'setimages') {
            $this->auctioneerImportService->setDealerFeaturedImages();
        } else {
            $this->auctioneerImportService->importFeaturedImages();
        }
    }
}
