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
                    <a href="{{url('sub-admin/view')}}">
                        <button  class="btn btn-primary">View </button>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{url('subAdmin/save')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        {{-- <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Profile<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="profile" autocomplete="off" required>
                            @if($errors->has('profile'))
                                <div class="form-error">{{ $errors->first('profile') }}</div>
                            @endif
                        </div> --}}
                    
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name" autocomplete="off" required />
                            @if($errors->has('name'))
                                <div class="form-error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" autocomplete="off" required />
                            @if($errors->has('email'))
                                <div class="form-error">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        {{-- <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Mobile<span class="text-danger">*</span></label>
                            <input type="Number" class="form-control" name="mobile" placeholder="Enter Mobile" autocomplete="off" maxlength="10" minlength="10" required />
                            @if($errors->has('mobile'))
                                <div class="form-error">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div> --}}

                      
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control pass-field" name="password" placeholder="Enter Password" autocomplete="off" minlength="6" required />
                                <span id="basic-default-password2" class="input-group-text" style="cursor:pointer;">
                                    <i class="fa-solid fa-eye-slash" id="show-pass"></i>
                                </span>
                            </div>
                            @if($errors->has('password'))
                                <div class="form-error">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="plan">Status<span class="text-danger">*</span></label>
                            <select class="form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">InActive</option>
                            </select>
                            @if($errors->has('status'))
                                <div class="form-error">{{ $errors->first('status') }}</div>
                            @endif
                        </div>

                        {{-- <div class="col-md-12 mt-3">
                            <label class="form-label" for="plan">Address</label>
                            <textarea class="form-control" name="address"></textarea>
                            @if($errors->has('address'))
                                <div class="form-error">{{ $errors->first('address') }}</div>
                            @endif
                        </div> --}}
                    
                        <div class="col-md-6 mt-3">
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

