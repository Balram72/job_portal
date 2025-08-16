<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    // this methods will show the jobs page
    public function index(request $req)
    {

        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $jobTypes = JobType::where('status', 1)->orderBy('name', 'asc')->get();

        $Jobs = Job::where('status', 1);

        // search using keywords
        if (!empty($req->keyword)) {
            $Jobs = $Jobs->where(function ($query) use ($req) {
                $query->orWhere('title', 'like', '%' . $req->keyword . '%');
                $query->orWhere('keywords', 'like', '%' . $req->keyword . '%');
            });
        }

        //search using Location

        if (!empty($req->location)) {
            $Jobs = $Jobs->where('location', $req->location);
        }

        //search using category

        if (!empty($req->category)) {
            $Jobs = $Jobs->where('category_id', $req->category);
        }

        //search using job type
        $jobTypeArray = [];
        if (!empty($req->jobType)) {
            $jobTypeArray = explode(',', $req->jobType);
            $Jobs = $Jobs->whereIn('job_type_id', $jobTypeArray);
        }

        // search using the experience
        if (!empty($req->experience)) {
            $Jobs = $Jobs->where('experience', $req->experience);
        }

        $Jobs = $Jobs->with(['jobType']);
        if ($req->sort == '0') {
            $Jobs = $Jobs->orderBy('created_at', 'ASC');
        } else {
            $Jobs = $Jobs->orderBy('created_at', 'DESC');
        }
        $Jobs = $Jobs->paginate(9);


        return view('front.jobs', compact('categories', 'jobTypes', 'Jobs', 'jobTypeArray'));
    }

    //This method will show the job detail page
    public function detail($id)
    {
        $job = Job::where(['id' => $id, 'status' => 1])->with(['jobType', 'category'])->first();

        if ($job === null) {
            abort(404);
        }

        return view('front.jobDetail', compact('job'));
    }
}
