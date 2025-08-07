<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This method will show the home page
    public function index()
    {

        $categories = Category::where('status', 1)->orderBy('name', 'asc')->take(8)->get();
        $featuredJobs = Job::where('status', 1)->orderBy('Created_at', 'DESC')
            ->with('jobType')->where('isFeatured', 1)->take(6)->get();
        $latesJobs =  Job::where('status', 1)->orderBy('Created_at', 'DESC')->with('jobType')->take(6)->get();

        return view('front.home', compact('categories', 'featuredJobs', 'latesJobs'));
    }
}
