<!doctype html>
<html lang="en" dir="ltr">
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title data-setting="" data-rightJoin="">{{$title}} </title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="AmazingWeb">
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{url('images/logo/rlogo.png')}}">
        
        <!-- Library / Plugin Css Build -->
        <link rel="stylesheet" href="{{asset('assets/backend/css/core/libs.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/backend/vendor/sheperd/dist/css/sheperd.css')}}">
        
        <!-- Flatpickr css -->
        <link rel="stylesheet" href="{{asset('assets/backend/vendor/flatpickr/dist/flatpickr.min.css')}}">
        <!-- qompac-ui Design System Css -->
        <link rel="stylesheet" href="{{asset('assets/backend/css/qompac-ui.minf700.css?v=1.0.1')}}">
        
        <!-- Custom Css -->
        <link rel="stylesheet" href="{{asset('assets/backend/css/custom.minf700.css?v=1.0.1')}}">
        <!-- Dark Css -->
        <link rel="stylesheet" href="{{asset('assets/backend/css/dark.minf700.css?v=1.0.1')}}">
        
        <!-- Customizer Css -->
        <link rel="stylesheet" href="{{asset('assets/backend/css/customizer.minf700.css?v=1.0.1')}}" >
        
        <!-- RTL Css -->
        <link rel="stylesheet" href="{{asset('assets/backend/css/rtl.minf700.css?v=1.0.1')}}">
        <link rel="stylesheet" href="{{asset('assets/backend/vendor/swiperSlider/swiper-bundle.min.css')}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">   
        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">    
        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- ck editor cdn -->
        <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
        {{-- <link href="{{asset('assets/email/fm.selectator.jquery.css?cb=29')}}" rel="stylesheet" type="text/css"> --}}
        {{-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> --}}
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="{{asset('assets/email/email.multiple.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dropdown/vendor/libs/select2/select2.css')}}" />
        <style>
            .form-error{
                color:#ff0000;
            }
        </style>
    </head>

    <body class="  ">
        <!-- loader Start -->
        <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body ">
                <img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/loader.webp" alt="loader" class="image-loader img-fluid ">
            </div>
        </div>
        </div>
        <!-- loader END -->  
        @include('admin.layout.sidebar')
        @include('admin.layout.header')

        @yield('content')
        
    </div>
</main>
@include('admin/layout/footer')
