<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : DealerProblemService.php
 **/

namespace App\Services;


use App\Models\Problems;

class DealerProblemService
{
    public function getAllProblems()
    {
        return Problems::with('problemable')->where('problemable_type', 'App\Models\Dealers')->orderBy('id', 'ASC')->paginate(20);
    }

    public function getProblem($id)
    {
        return Problems::with('problemable')->where('problemable_type', 'App\Models\Dealers')->where('id', $id)->firstOrFail();
    }

    public function deleteProblem($id)
    {
        $problem = $this->getProblem($id);
        $problem->delete();
        flash('Problem Deleted')->success();
        \Cache::forget('problemCount');
    }
}