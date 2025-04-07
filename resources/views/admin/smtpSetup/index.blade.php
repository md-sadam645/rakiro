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
                    <a href="{{url('smtp-setup/view')}}">
                        <button  class="btn btn-primary">View </button>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{url('/smtp-setup/add')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="plan">From Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" autocomplete="off" required>
                                @if($errors->has('name'))
                                    <div class="form-error">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="from_address">From Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="from_address" placeholder="Enter Email" autocomplete="off" required>
                                @if($errors->has('from_address'))
                                    <div class="form-error">{{ $errors->first('from_address') }}</div>
                                @endif
                            </div>
                        
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">{{$title}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

