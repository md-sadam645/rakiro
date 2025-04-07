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
                    {{-- <span>
                        ID No : <span class="fw-bold">{{Auth::user()->id}}</span>
                    </span> --}}
                </div>
                <div class="card-body">
                    <form action="{{url('updatePassword')}}"  method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="col-md-12 col-lg-6 mt-3">
                            <label class="form-label" for="plan">Current Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control pass-field" name="current_password" placeholder="Current Password" autocomplete="off" minlength="6" required />
                                <span id="basic-default-password2" class="input-group-text" style="cursor:pointer;">
                                    <i class="fa fa-eye-slash" style="font-size: 20px;" id="show-pass"></i>
                                </span>
                            </div>
                            @if($errors->has('current_password'))
                                <div class="form-error">{{ $errors->first('current_password') }}</div>
                            @endif
                        </div>
                    
                        <div class="col-md-12 col-lg-6 mt-3">
                            <label class="form-label" for="plan">New Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control pass-field2 pass-new" name="password" placeholder="New Password" autocomplete="off" minlength="6" required />
                                {{-- <span id="basic-default-password2" class="input-group-text" style="cursor:pointer;">
                                    <i class="fa fa-eye-slash" style="font-size: 20px;" id="show-pass"></i>
                                </span> --}}
                            </div>
                            @if($errors->has('password'))
                                <div class="form-error">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="col-md-12 col-lg-6 mt-3">
                            <label class="form-label" for="plan">Confirm New Password<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control pass-field2 pass-new-confirm" name="password_confirmation" placeholder="Confirm New Password" autocomplete="off" minlength="6" required />
                                <span id="basic-default-password2" class="input-group-text" style="cursor:pointer;">
                                    <i class="fa fa-eye-slash" style="font-size: 20px;" id="show-pass2"></i>
                                </span>
                            </div>
                            @if($errors->has('password_confirmation'))
                                <div class="form-error">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>

                        <span class="text-muted mt-3">Minimum 6 characters</span><br>
                        <span class="text-danger confirm-warning d-none small">New Password & Confirm New Password Doesn't Match!</span>

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary change-pass-btn">Update Password</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

