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
                    <span>
                        ID No : <span class="fw-bold">{{Auth::user()->id}}</span>
                    </span>
                    {{-- <a href="{{url('sub-admin/view')}}">
                        <button  class="btn btn-primary">View</button>
                    </a> --}}
                </div>
                <div class="card-body">
                    <form action="{{url('profile/update/')}}"  method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="plan">Profile</label>
                            <input type="file" class="form-control" name="photo" autocomplete="off" >
                            @if($errors->has('photo'))
                                <div class="form-error">{{ $errors->first('photo') }}</div>
                            @endif
                        </div>
                        <div class="col-md-2 mt-3">
                            @if(!empty(Auth::user()->photo))
                                <input type="hidden" name="oldimage" value="{{Auth::user()->photo}}">
                                <a href="{{url(Auth::user()->photo)}}" target="_blank">
                                    <img src="{{url(Auth::user()->photo)}}" alt="{{Auth::user()->photo}}" style="height:60px;width:60;" />
                                </a>
                            @endif
                        </div>
                    
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Name</label>
                            <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}" required autocomplete="off">
                            @if($errors->has('name'))
                                <div class="form-error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Email</label>
                            <input type="email" class="form-control" name="email" value="{{Auth::user()->email}}" autocomplete="off" @if(Auth::user()->role == 2) readonly @endif required>
                            @if($errors->has('email'))
                                <div class="form-error">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Mobile</label>
                            <input type="number" class="form-control" name="mobile" value="{{Auth::user()->mobile}}" required autocomplete="off">
                            @if($errors->has('mobile'))
                                <div class="form-error">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="address">Address</label>
                            <textarea class="form-control" name="address">{{Auth::user()->address}}</textarea>
                            @if($errors->has('address'))
                                <div class="form-error">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Update {{$title}}</button>
                        </div>
                    
                    
                    </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

