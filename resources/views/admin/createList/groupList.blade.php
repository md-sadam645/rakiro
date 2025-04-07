@extends('admin.layout.index')

@section('content')

    <div class="content-inner pb-0 container" id="page_layout">
<div class="row">
<div class="col-sm-12">
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
            <div class="row">
                <div class="col-md-4 mt-3">
                    {{-- <label class="form-label" for="plan">Group Name</label> --}}
                    <form action="/create-list/groupDetails" method="get">
                        <div class="input-group">
                            <select class="form-control text-capitalize" name="group_name">
                                <option value="choose group name">select group name</option>
                                @foreach($group as $groupData)
                                    <option value="{{ $groupData->id}}" @if(session()->get("group_id") == $groupData->id) selected @endif>{{ $groupData->group_name}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" style="fill:white !important;" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 mt-3">
                </div>
                <div class="col-md-5 d-flex justify-content-end mt-3">
                    @if(!empty($group_data))
                        <button type="button" class="btn btn-primary position-relative me-4">
                            Total Invalid Email
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                @if(!empty($invalidEmail))
                                    {{count($invalidEmail)}}
                                @else
                                0
                                @endif
                            </span>
                        </button>
                    @endif
                    {{-- @if(!empty($validEmail))
                        <button type="button" class="btn btn-primary position-relative me-4">
                            Total Valid Email
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                {{count($validEmail)}}
                            </span>
                        </button>
                    @endif --}}
                    
                    @if(!empty($group_data))
                        <button type="button" class="btn btn-primary position-relative">
                            Total Customer
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">
                                {{count($group_data)}}
                            </span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="fancy-table table-responsive border mt-5 rounded" style="height:60vh">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th scope="col">S/N</th>
                                <th scope="col">Customer Code</th>
                                {{-- <th scope="col">Name</th> --}}
                                <th scope="col">Email</th>
                                <th scope="col">Group Name</th>
                                {{-- <th scope="col">Mobile</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($invalidEmail))
                                @foreach($invalidEmail as $key => $invalEmail)
                                    <tr>
                                        <td class="pe-0">{{$key+1}}</td>
                                        {{-- <td><input type="checkbox" name="select[]" checked value="{{$val->rowId}}" /></td> --}}
                                        <td class="px-0">{{$invalEmail->bp_code}}</td>
                                        {{-- <td>{{$val->bp_name}}</td> --}}
                
                                        <td class="px-0">
                                            <i class="fa-solid fa-circle-xmark text-danger" title="Invalid Email"></i>
                                            
                                            {{$invalEmail->email_id}}
                                        </td>
                                        <td class="px-0">
                                            @foreach($customer_group as $key => $cusData)
                                                @if($cusData->group_no == $invalEmail->group_code)
                                                    {{$cusData->group_name}}
                                                @endif
                                            @endforeach
                                        </td>
                                        {{-- <td>{{$val->mobile_phone}}</td> --}}
                                    </tr>
                                @endforeach
                            @endif
                            @if(!empty($validEmail))
                                @foreach($validEmail as $key => $valEmail)
                                    <tr>
                                        <td class="pe-0">{{$key+1}}</td>
                                        {{-- <td><input type="checkbox" name="select[]" checked value="{{$val->rowId}}" /></td> --}}
                                        <td class="px-0">{{$valEmail->bp_code}}</td>
                                        {{-- <td>{{$val->bp_name}}</td> --}}
                
                                        <td class="px-0">
                                            <i class="fa-solid fa-circle-check text-success" title="Valid Email"></i>
                                            
                                            {{$valEmail->email_id}}
                                        </td>
                                        <td class="px-0">
                                            @foreach($customer_group as $key => $cusData)
                                                @if($cusData->group_no == $valEmail->group_code)
                                                    {{$cusData->group_name}}
                                                @endif
                                            @endforeach
                                        </td>
                                        {{-- <td>{{$val->mobile_phone}}</td> --}}
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
        </div>
        </div>
    </div>



    @endsection