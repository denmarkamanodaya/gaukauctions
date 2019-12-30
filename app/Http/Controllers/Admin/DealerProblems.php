<?php

namespace App\Http\Controllers\Admin;

use App\Models\Problems;
use App\Services\DealerProblemService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealerProblems extends Controller
{

    /**
     * @var DealerProblemService
     */
    private $dealerProblemService;

    public function __construct(DealerProblemService $dealerProblemService)
    {
        $this->dealerProblemService = $dealerProblemService;
    }

    public function index()
    {
        $problems = $this->dealerProblemService->getAllProblems();
        return view('admin.Auctioneers.Problems.index', compact('problems'));
    }

    public function show($id)
    {
        $problem = $this->dealerProblemService->getProblem($id);
        return view('admin.Auctioneers.Problems.show', compact('problem'));
    }

    public function delete($id)
    {
        $this->dealerProblemService->deleteProblem($id);
        return redirect('/admin/dealers/problems');
    }

}
