<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::orderBy('created_at', 'desc')->with('user', 'applications')->paginate(10);
        return view('admin.jobs.list', compact('jobs'));
    }
    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $categorys = Category::orderBy('name', 'asc')->get();
        $jobtypes = JobType::orderBy('name', 'asc')->get();

        return view('admin.jobs.edit', compact('job', 'categorys', 'jobtypes'));
    }

    public function update(Request $req, $id)
    {
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->passes()) {
            $job = Job::find($id);
            $job->title = $req->title;
            $job->category_id = $req->category;
            $job->job_type_id  = $req->jobType;
            $job->vacancy = $req->vacancy;
            $job->salary = $req->salary;
            $job->location = $req->location;
            $job->description = $req->description;
            $job->benefits = $req->benefits;
            $job->responsibility  = $req->responsibility;
            $job->qualification = $req->qualifications;
            $job->keywords = $req->keywords;
            $job->experience = $req->experience;
            $job->company_name = $req->company_name;
            $job->company_location = $req->company_location;
            $job->company_website = $req->website;
            $job->status = $req->status;
            $job->isFeatured = (!empty($req->isFeatured)) ? $req->isFeatured : 0;
            $job->save();

            session()->flash('success', 'Job Updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $req)
    {
        $job = Job::findOrFail($req->id);
        if ($job) {
            $job->delete();
            session()->flash('success', 'Job deleted successfully.');
            return response()->json([
                'status' => true,
            ]);
        } else {
            session()->flash('error', 'Either Job deleted or not found.');
            return response()->json([
                'status' => false,
            ]);
        }
    }
}
