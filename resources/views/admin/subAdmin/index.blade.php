@extends('admin.layout.index')

@section('content')

    <div class="content-inner pb-0 container" id="page_layout">
<div class="row">
<div class="col-sm-12">
 <div class="card">
    <div class="card-header d-flex justify-content-between">
       <div class="header-title">
            <h4 class="card-title">{{$title}} 
                @if(!empty($totalSubadmin))
                    <span class="badge bg-primary">
                        {{count($totalSubadmin)}}
                    </span>
                @endif
            </h4>
       </div>
       <a href="{{url('sub-admin/create')}}">
       <button  class="btn btn-primary">Create</button>
       </a>
    </div>
    <div class="card-body">
        <div class="fancy-table table-responsive border rounded">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        {{-- <th scope="col">Profile</th> --}}
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        {{-- <th scope="col">Mobile</th>
                        <th scope="col">Address</th> --}}
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($list))
                    @foreach($list as $val)
                    <tr>
                        {{-- <td>
                            <div class="d-flex align-items-center">
                                <a href="{{url($val->photo)}}" target="_blank"><img class="rounded img-fluid avatar-80 me-3" src="{{url($val->photo)}}" alt="{{$val->photo}}" loading="lazy" /></a>
                            </div>
                        </td> --}}
                        <td>{{$val->name}}</td>
                        <td>{{$val->email}}</td>
                        {{-- <td>{{$val->mobile}}</td>
                        <td>{{$val->address}}</td> --}}
                        <td>
                            @if($val->status==1) <span class="badge bg-soft-primary p-2 text-primary"> Active 
                            @elseif($val->status==0) <span class="badge bg-soft-primary p-2 text-danger"> Inactive @endif</span>
                        </td>
                        <td>
                            <div class="d-flex ">
                                <a class="btn btn-primary btn-icon btn-sm rounded-pill ms-2" title="Edit" href="{{url('sub-admin/edit/'.$val->id)}}" role="button">
                                    <span class="btn-inner">
                                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                            <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </a>
                                <a class="btn btn-danger btn-icon btn-sm rounded-pill ms-2" title="Delete" href="{{url('sub-admin/delete/'.$val->id)}}" role="button">
                                    <span class="btn-inner">
                                        <svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4" d="M19.643 9.48851C19.643 9.5565 19.11 16.2973 18.8056 19.1342C18.615 20.8751 17.4927 21.9311 15.8092 21.9611C14.5157 21.9901 13.2494 22.0001 12.0036 22.0001C10.6809 22.0001 9.38741 21.9901 8.13185 21.9611C6.50477 21.9221 5.38147 20.8451 5.20057 19.1342C4.88741 16.2873 4.36418 9.5565 4.35445 9.48851C4.34473 9.28351 4.41086 9.08852 4.54507 8.93053C4.67734 8.78453 4.86796 8.69653 5.06831 8.69653H18.9388C19.1382 8.69653 19.3191 8.78453 19.4621 8.93053C19.5953 9.08852 19.6624 9.28351 19.643 9.48851Z" fill="currentColor"></path>
                                            <path d="M21 5.97686C21 5.56588 20.6761 5.24389 20.2871 5.24389H17.3714C16.7781 5.24389 16.2627 4.8219 16.1304 4.22692L15.967 3.49795C15.7385 2.61698 14.9498 2 14.0647 2H9.93624C9.0415 2 8.26054 2.61698 8.02323 3.54595L7.87054 4.22792C7.7373 4.8219 7.22185 5.24389 6.62957 5.24389H3.71385C3.32386 5.24389 3 5.56588 3 5.97686V6.35685C3 6.75783 3.32386 7.08982 3.71385 7.08982H20.2871C20.6761 7.08982 21 6.75783 21 6.35685V5.97686Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
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