<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        $saveCount = 0;
        if (Auth::user()) {
            $saveCount = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id,
            ])->count();
        }

        // featch Applications

        $applications = JobApplication::where('job_id', $id)->with('user')->get();
        return view('front.jobDetail', compact('job', 'saveCount', 'applications'));
    }


    public function applyJob(Request $req)
    {
        $id = $req->id;
        $job = Job::where('id', $id)->first();

        // If job not found in db
        if ($job == null) {
            $message = 'Job does not exist';
            session()->flash('error', $message);
            return response()->json(['status' => false]);
        }
        // You can not apply on your own job
        $employer_id = $job->user_id;
        if ($employer_id == Auth::user()->id) {
            $message = 'You can not apply on your own job';
            session()->flash('error', $message);
            return response()->json(['status' => false]);
        }
        // You can Not apply on a job twice
        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id,
        ])->count();
        if ($jobApplicationCount > 0) {
            $message = 'You have already applied for this job';
            session()->flash('error', $message);
            return response()->json(['status' => false]);
        }

        // Save the job application
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        // Send Notification to the Employer
        $employer = User::where('id', $employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
        $message = 'You have successfully applied for this job';
        session()->flash('success', $message);
        return response()->json(['status' => true]);
    }

    public function saveJob(Request $req)
    {
        $id = $req->id;

        $job = Job::find($id);

        // If job not found in db
        if ($job == null) {
            $message = 'Job does not exist';
            session()->flash('error', $message);
            return response()->json(['status' => false]);
        }

        // check if the job is already saved
        $saveCount = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id,
        ])->count();
        if ($saveCount > 0) {
            $message = 'You Have Already Saved On This Job';
            session()->flash('error', $message);
            return response()->json(['status' => false]);
        }

        // You can not Save on your own job
        $employer_id = $job->user_id;
        if ($employer_id == Auth::user()->id) {
            $message = 'You can not Save on your own job';
            session()->flash('error', $message);
            return response()->json(['status' => false]);
        }

        $SavedJob = new SavedJob();
        $SavedJob->user_id = Auth::user()->id;
        $SavedJob->job_id = $id;
        $SavedJob->save();

        $message = 'You Have Successfully Saved On This Job';
        session()->flash('success', $message);
        return response()->json(['status' => true]);
    }
}
