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
                </div>
                <div class="card-body">
                    <form action="{{url('/common-setup/update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="mailer">Mailer<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mailer" @if(!empty($cSetup)) value="{{$cSetup->mailer}}" @endif placeholder="Enter Mailer" autocomplete="off" required>
                                @if($errors->has('mailer'))
                                    <div class="form-error">{{ $errors->first('mailer') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="host">Host<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="host" @if(!empty($cSetup)) value="{{$cSetup->host}}" @endif placeholder="Enter Host" autocomplete="off" required>
                                @if($errors->has('host'))
                                    <div class="form-error">{{ $errors->first('host') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="port">Port<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="port" @if(!empty($cSetup)) value="{{$cSetup->port}}" @endif placeholder="Enter Port" autocomplete="off" required />
                                
                                @if($errors->has('port'))
                                    <div class="form-error">{{ $errors->first('port') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="username">Username<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" @if(!empty($cSetup)) value="{{$cSetup->username}}" @endif placeholder="Enter Username" autocomplete="off" required>
                                @if($errors->has('username'))
                                    <div class="form-error">{{ $errors->first('username') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="password">Password<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control pass-field" name="password" @if(!empty($cSetup)) value="{{$cSetup->password}}" @endif placeholder="Enter Password" autocomplete="off" minlength="6" required />
                                    <span id="basic-default-password2" class="input-group-text" style="cursor:pointer;">
                                        <i class="fa-solid fa-eye-slash" id="show-pass"></i>
                                    </span>
                                </div>
                                @if($errors->has('password'))
                                    <div class="form-error">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="encryption">Encryption<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="encryption" placeholder="Enter Encryption" @if(!empty($cSetup)) value="{{$cSetup->encryption}}" @endif required autocomplete="off">
                                @if($errors->has('encryption'))
                                    <div class="form-error">{{ $errors->first('encryption') }}</div>
                                @endif
                            </div>
                        
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    @if(!empty($cSetup)) Update Common Setup @else {{$title}} @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

