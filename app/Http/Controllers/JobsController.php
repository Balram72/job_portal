<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    // this methods will show the jobs page
    public function index()
    {

        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $jobTypes = JobType::where('status', 1)->orderBy('name', 'asc')->get();
        $Jobs = Job::where('status', 1)->orderBy('Created_at', 'DESC')
            ->with('jobType')->paginate(9);
        return view('front.jobs', compact('categories', 'jobTypes', 'Jobs'));
    }
}
