@extends('front.layouts.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                                <h3 class="fs-4 mb-1">Users</h3>
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
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($users->isNotEmpty())
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($users as  $user)
                                            <tr class="active">
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->mobile }}</td>
                                                <td>
                                                    <div class="action-dots text-center">
                                                        <button class="bg-transparent border-0"   data-bs-toggle="dropdown" aria-expanded="false" style="color:#2b2b2b59">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                         
                                                            <li><a class="dropdown-item text-success" href="{{ route('admin.users.edit', $user->id) }}"><i class="fa fa-edit text-success" aria-hidden="true"></i> Edit</a></li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                                 onclick="deleteJob({{ $user->id }})">
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
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')

@endsection