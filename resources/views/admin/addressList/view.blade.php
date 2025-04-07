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
       {{-- <a href="{{url('subAdmin/add')}}">
       <button  class="btn btn-primary">Add New</button>
       </a> --}}
    </div>
    <div class="card-body">
        <div class="fancy-table table-responsive border rounded">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col">SR</th>
                        <th scope="col">Date</th>
                        <th scope="col">List Name</th>
                        <th scope="col">No of contacts</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        if(!empty(strpos(url()->full(),'?')))
                        {
                            $pageNo = explode("=",url()->full())[1];
                            $sr_no = ($pageNo*10)-9;
                        }
                        else 
                        {
                            $sr_no = 1;
                        }
                    @endphp

                    @if(!empty($list))
                    @foreach($list as $key => $val)
                    <tr>
                        <td>
                            {{$key+$sr_no}}
                        </td>
                        <td>{{$val->created_at}}</td>
                        <td class="text-capitalize">{{$val->group_name}}</td>
                        <td>
                            @php
                               echo count(json_decode($val->customer_id)); 
                            @endphp
                        </td>
                        <td>
                            <div class="d-flex ">
                                <a class="btn btn-light btn-icon btn-sm rounded-pill ms-2"  data-bs-toggle="modal" data-bs-target="#duplicateGroup{{$val->id}}" title="Duplicate" role="button">
                                    <span class="btn-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-32" width="32" viewBox="0 0 448 512">
                                            <path d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/>
                                        </svg>
                                    </span>
                                </a>
                                @if(Auth::user()->role == 1)
                                <a class="btn btn-danger btn-icon btn-sm rounded-pill ms-2" title="Delete" href="{{url('create-list/delete/'.$val->id)}}" role="button">
                                    <span class="btn-inner">
                                        <svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4" d="M19.643 9.48851C19.643 9.5565 19.11 16.2973 18.8056 19.1342C18.615 20.8751 17.4927 21.9311 15.8092 21.9611C14.5157 21.9901 13.2494 22.0001 12.0036 22.0001C10.6809 22.0001 9.38741 21.9901 8.13185 21.9611C6.50477 21.9221 5.38147 20.8451 5.20057 19.1342C4.88741 16.2873 4.36418 9.5565 4.35445 9.48851C4.34473 9.28351 4.41086 9.08852 4.54507 8.93053C4.67734 8.78453 4.86796 8.69653 5.06831 8.69653H18.9388C19.1382 8.69653 19.3191 8.78453 19.4621 8.93053C19.5953 9.08852 19.6624 9.28351 19.643 9.48851Z" fill="currentColor"></path>
                                            <path d="M21 5.97686C21 5.56588 20.6761 5.24389 20.2871 5.24389H17.3714C16.7781 5.24389 16.2627 4.8219 16.1304 4.22692L15.967 3.49795C15.7385 2.61698 14.9498 2 14.0647 2H9.93624C9.0415 2 8.26054 2.61698 8.02323 3.54595L7.87054 4.22792C7.7373 4.8219 7.22185 5.24389 6.62957 5.24389H3.71385C3.32386 5.24389 3 5.56588 3 5.97686V6.35685C3 6.75783 3.32386 7.08982 3.71385 7.08982H20.2871C20.6761 7.08982 21 6.75783 21 6.35685V5.97686Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
                                @endif
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="duplicateGroup{{$val->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Create Duplicate Group</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <form action="{{url('create-list/duplicate')}}" method="POST">
                                        @csrf
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="group_name">Group Name<span class="text-danger">*</span></label>
                                            <input type="text" name="group_name" class="form-control" required placeholder="Enter Group Name"/>
                                          
                                        </div>
                                        <input type="text" name="oldGroupId" class="form-control d-none" value="{{$val->id}}"/>
                                        <button type="submit" class="btn btn-primary mt-4">Create Duplicate Group</button>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
            
             
                </tbody>
            </table>
        </div>
    </div>
</div>
  
  
    
    <!-- start pagination -->
    @if(!empty($list))
    <center>
        {{ $list->links('vendor.pagination.custom') }}
    </center>
    @endif
    <!-- end pagination -->

    @endsection