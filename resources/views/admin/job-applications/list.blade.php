@extends('front.layouts.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Job Applications</li>
                    </ol>
                </nav>
            </div>  
        </div>
        <div class="row">
            <div class="col-lg-3">
               @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Job Applications</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                {{-- <a href="{{ route('account.createJob') }}" class="btn btn-primary">Post a Job</a> --}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr> 
                                        <th scope="col">Sr No</th>
                                        <th scope="col">Job Title</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Employer</th>
                                        <th scope="col">Applied Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                   @if ($applications->isNotEmpty())
                                        @php
                                            $i = 1;
                                        @endphp
                                     @foreach ($applications as $application)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>
                                               <p>{{ $application->job->title }}</p> 
                                               {{-- <p> Applications: {{ $application->count() }}</p> --}}
                                            </td>
                                            <td>{{ $application->user->name }}</td>
                                            <td>{{ $application->employer->name }}</td>
                                            <td>{{ \carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}</td>
                                            <td>
                                               <div class="action-dots text-center">
                                                        <button class="bg-transparent border-0"   data-bs-toggle="dropdown" aria-expanded="false" style="color:#2b2b2b59">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                         
                                                            {{-- <li><a class="dropdown-item text-success" href="{{ route('admin.jobs.edit', $job->id) }}"><i class="fa fa-edit text-success" aria-hidden="true"></i> Edit</a></li> --}}
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                                 onclick="deleteJobApplication({{ $application->id }})">
                                                                <i class="fa fa-trash text-danger" aria-hidden="true"></i> 
                                                                Delete</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                            </td>
                                        </tr>
                                     @endforeach                                         
                                   @endif
                                </tbody>
                            </table>
                        </div>
                        <div>
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script type="text/javascript">

    function deleteJobApplication(id) {
       if(confirm("Are you sure you want to delete this Job?")){
        $.ajax({
            url:"{{ route('admin.jobApplications.destroy') }}",
            type:"delete",
            data:{id:id},
            datatype:"json",
            success:function(response){
                window.location.href='{{ route("admin.jobApplications") }}';
            }
        });
       }
    }
</script>
@endsection