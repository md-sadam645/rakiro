@extends('admin.layout.index')

@section('content')

    <div class="content-inner pb-0 container" id="page_layout">
<div class="row">
<div class="col-sm-12">
 <div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="header-title">
            <h4 class="card-title">{{$title}} 
            </h4>
        </div>
        <div>
            <a href="{{url('automail/view')}}">
                <button  class="btn btn-primary">View</button>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="fancy-table table-responsive border rounded">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col">Total User</th>
                        <th scope="col">Pending Email</th>
                        <th scope="col">Sent Email</th>
                        <th scope="col">Failed Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$list->total_cus}}</td>
                        <td>
                            @if($list->pending_jobs == 1) 
                                {{$list->total_cus}} 
                            @elseif($list->pending_jobs == 0)
                                0
                            @else
                                @php 
                                    $pencount = ($list->pending_jobs-1)*100; 
                                    echo $pencount+($list->total_cus-$pencount);
                                @endphp 
                            @endif
                        </td>

                        <td>
                            @if($list->pending_jobs == $list->total_jobs) 
                                0
                            @else 
                                @php 
                                    if($list->failed_jobs == 0)
                                    {
                                        $sentMail = $list->total_cus;
                                    }
                                    else {
                                        $sentMail = ($list->total_jobs-$list->failed_jobs)*100;
                                    }

                                    echo $sentMail;
                                 @endphp
                            @endif
                        </td>

                        <td>
                            @if($list->failed_jobs == 1) 
                                {{$list->total_cus}}
                            @elseif($list->failed_jobs == 0)
                                0
                            @elseif($list->failed_jobs == $list->total_jobs)
                                {{$list->total_cus}}
                            @else
                                @php 
                                    echo $list->total_cus-$sentMail;
                                @endphp 
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
   </div>
    </div>

    @endsection