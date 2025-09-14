<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::orderBy('created_at', 'desc')
            ->with('job', 'user', 'employer')
            ->paginate(10);
        return view('admin.job-applications.list', compact('applications'));
    }

    public function destroy(Request $request)
    {
        $application = JobApplication::findOrFail($request->id);

        if ($application) {

            $application->delete();
            session()->flash('success', 'Job application deleted successfully.');
            return response()->json(['status' => true]);
        } else {
            session()->flash('error', 'Either Job application deleted or not found.');
            return response()->json(['status' => false]);
        }
    }
}
