<?php

namespace App\Console\Commands;

use App\Services\DealerService;
use Illuminate\Console\Command;
use Quantum\blog\Services\BlogService;

class CleanImageSpaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantum:cleanImageSpaces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up spaces in dealer images.';

    /**
     * @var DealerService
     */
    private $dealerService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DealerService $dealerService)
    {
        parent::__construct();
        $this->dealerService = $dealerService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dealerService->cleanImageSpaces();
    }
}
