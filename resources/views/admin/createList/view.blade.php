@extends('admin.layout.index')
<style>
    td{
        font-size: 13px;
    }

    th{
        font-size: 14px;
    }
</style>
@section('content')

<div class="content-inner pb-0 container" id="page_layout">
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">{{$title}}</h4>
            </div>
            <a href="{{url('create-list/create')}}">
                <button  class="btn btn-primary">Create </button>
            </a>
        </div>
        <div class="card-body">
            <form action="/createGroup" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{$title}} <span class="badge bg-primary">
                            @if(!empty($search))
                                {{count($search)}}
                            @endif
                        </span></h4>
                    </div>
                    @if(!empty($invalidEmail))
                    <button type="button" class="btn btn-primary position-relative me-4">
                        Total Invalid Email
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{count($invalidEmail)}}
                        </span>
                    </button>
                    @endif

                    <div class="w-50 d-flex justify-content-between">
                        @if(!empty($invalidEmail))
                            <input type="text" name="group_name" placeholder="Enter Group Name" disabled class="form-control w-75" />
                            <button class="btn btn-primary" type="submit" disabled>Create Group</button>
                        @else
                            <input type="text" name="group_name" placeholder="Enter Group Name" class="form-control w-75" />
                            <button class="btn btn-primary" type="submit">Create Group</button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="fancy-table table-responsive border rounded" style="height:60vh">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Deselect</th>
                                    <th scope="col" class="px-0">Customer Code</th>
                                    <th scope="col" class="px-0">Name</th>
                                    {{-- <th scope="col">Group Name</th> --}}
                                    <th scope="col" class="px-0">Email</th>
                                    {{-- <th scope="col">Mobile</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($invalidEmail))
                                @foreach($invalidEmail as $key => $invalEmail)
                                <tr>
                                    <td class="pe-0"><input type="checkbox" name="select[]" checked value="{{$invalEmail['bp_code']}}" /></td>
                                    <td class="px-0">{{$invalEmail['bp_code']}}</td>
                                    <td class="px-0">{{$invalEmail['bp_name']}}</td>
                                    {{-- <td class="border">
                                        @foreach ($customer_group as $key => $cusData)
                                            @if($cusData->group_no == $val->group_code)
                                                {{$cusData->group_name}}
                                            @endif
                                        @endforeach
                                    </td> --}}
                                    <td class="px-0">
                                        <i class="fa-solid fa-circle-xmark text-danger" title="Invalid Email"></i>
                                        {{$invalEmail['email_id']}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(!empty($validEmail))
                                @foreach($validEmail as $key => $valEmail)
                                <tr>
                                    <td class="pe-0"><input type="checkbox" name="select[]" checked value="{{$valEmail['bp_code']}}" /></td>
                                    <td class="px-0">{{$valEmail['bp_code']}}</td>
                                    <td class="px-0">{{$valEmail['bp_name']}}</td>
                                    {{-- <td class="border">
                                        @foreach ($customer_group as $key => $cusData)
                                            @if($cusData->group_no == $val->group_code)
                                                {{$cusData->group_name}}
                                            @endif
                                        @endforeach
                                    </td> --}}
                                    <td class="ps-0">
                                        <i class="fa-solid fa-circle-check text-success" title="Valid Email"></i>
                                        
                                        {{$valEmail['email_id']}}
                                        <!-- Start : Modal -->
                                        {{-- <div class="modal fade" id="emailCorrection{{$val->rowId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Update Email</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="post" action="/update-email/{{$val->rowId}}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="col-md-12 col-12 mb-4">
                                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                            <input type="email" name="email_id" required class="form-control" id="email" value="{{$val->email_id}}" />
                                                            <input type="hidden" name="position" required class="form-control" value="{{$key+1}}" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Update Email</button>
                                                    </div>
                                                </form>
                                            </div>
                                            </div>
                                        </div> --}}
                                        <!-- End : Modal -->
                                    </td>
                                    {{-- <td class="border">{{$val->mobile_phone}}</td> --}}
                                </tr>
                              
                                @endforeach
                                @endif
                        
                        
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @endsection