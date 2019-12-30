<?php

namespace App\Http\Controllers\Admin;

use App\Services\AuctioneerImportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuctioneerImport extends Controller
{

    /**
     * @var AuctioneerImportService
     */
    private $auctioneerImportService;

    public function __construct(AuctioneerImportService $auctioneerImportService)
    {
        $this->auctioneerImportService = $auctioneerImportService;
    }

    public function importFromFile()
    {
        $imported = $this->auctioneerImportService->importFromFile();
        return view('admin.Auctioneers.Import.imported', compact('imported'));

    }

    public function updateFromFile()
    {
        $imported = $this->auctioneerImportService->updateFromFile();
        return view('admin.Auctioneers.Import.imported', compact('imported'));

    }
}
