


<!doctype html>
<html lang="en" dir="ltr">
  
<head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Login</title>
      <meta name="description" content="">
      <meta name="keywords" content="">
      <meta name="author" content="AmazignWeb Design">
    
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{url('images/logo/rlogo.png')}}">
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="{{url('assets/backend/css/core/libs.min.css')}}">
      <!-- qompac-ui Design System Css -->
      <link rel="stylesheet" href="{{url('assets/backend/css/qompac-ui.minf700.css?v=1.0.1')}}">
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{url('assets/backend/css/custom.minf700.css?v=1.0.1')}}">
      <!-- Dark Css -->
      <link rel="stylesheet" href="{{url('assets/backend/css/dark.minf700.css?v=1.0.1')}}">
      <!-- Customizer Css -->
      <link rel="stylesheet" href="{{url('assets/backend/css/customizer.minf700.css?v=1.0.1')}}" >
      <!-- RTL Css -->
      <link rel="stylesheet" href="{{url('assets/backend/css/rtl.minf700.css?v=1.0.1')}}">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      <!-- Google Font -->
      <link rel="preconnect" href="https://fonts.googleapis.com/">
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">  </head>
  <body class=" ">
    <!-- loader Start -->
   
    <!-- loader END -->
    <div class="wrapper">
      <section class="login-content overflow-hidden">
         <div class="row no-gutters align-items-center bg-white">            
            <div class="col-md-12 col-lg-6 align-self-center">
               <a href="../index.html" class="navbar-brand d-flex align-items-center mb-3 justify-content-center text-primary">
                  <div class="logo-normal">
                    {{-- <img src="https://i.postimg.cc/mhxsNbMQ/qfreemart.jpg" style="height:90px"/> --}}
                    <img src="{{url('images/logo/logo.png')}}" style="width:250px;" />
                     {{-- <svg class="" viewBox="0 0 32 32" width="80px" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25333 2H22.0444L29.7244 15.2103L22.0444 28.1333H7.25333L0 15.2103L7.25333 2ZM11.2356 9.32316H18.0622L21.3334 15.2103L18.0622 20.9539H11.2356L8.10669 15.2103L11.2356 9.32316Z" fill="currentColor"/>
                           <path d="M23.751 30L13.2266 15.2103H21.4755L31.9999 30H23.751Z" fill="#3FF0B9"/>
                     </svg> --}}
                  </div>
                  {{-- <h2 class="logo-title ms-3 mb-0" data-setting="app_name">QFreeMart</h2> --}}
               </a>
               <div class="row justify-content-center pt-5">
                  <div class="col-md-9">
                     <div class="card  d-flex justify-content-center mb-0 auth-card iq-auth-form">
                        <div class="card-body">                          
                           <h2 class="mb-2 text-center">Sign In</h2>
                           <p class="text-center">Login to stay connected.</p>
                           <form  action="{{route('adminvalidation')}}" method="post">
                              {{csrf_field()}}
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Email</label>
                                       <input type="email" class="form-control" name="email" id="email" aria-describedby="email" placeholder="xyz@example.com">
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <label for="password" class="form-label">Password</label>   
                                    <div class="input-group">
                                       <input type="password" class="form-control pass-field" name="password" id="password" aria-describedby="password" placeholder="xxxx">
                                       <span id="basic-default-password2" class="input-group-text" style="cursor:pointer;">
                                          <i class="fa fa-eye-slash" style="font-size: 20px;" id="show-pass"></i>
                                       </span>
                                    </div>
                                 </div>
                                 {{-- <div class="col-lg-12 d-flex justify-content-between mt-3">
                                    <a href="javascript:void(0);">Forgot Password?</a>
                                 </div> --}}
                              </div>
                              <div class="d-flex justify-content-center mt-3">
                                 <button type="submit" class="btn btn-primary">Sign In</button>
                              </div>
                       
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 d-lg-block d-none bg-primary p-0  overflow-hidden">
               <img src="{{url('assets/backend/images/auth/01.png')}}" style="height:100vh;" class="img-fluid gradient-main" alt="images" loading="lazy" >
            </div>
         </div>
      </section>
    </div>
    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
    <script src="{{asset('assets/backend/js/password_validation.js')}}"></script>
    <!-- Plugin Scripts -->
   
    <!-- Slider-tab Script -->
    <script src="{{asset('assets/js/plugins/slider-tabs.js')}}"></script>
    
    <!-- Lodash Utility -->
    <script src="{{asset('assets/backend/vendor/lodash/lodash.min.js')}}"></script>
    <!-- Utilities Functions -->
    <script src="{{asset('assets/backend/js/iqonic-script/utility.min.js')}}"></script>
    <!-- Settings Script -->
    <script src="{{asset('assets/backend/js/iqonic-script/setting.min.js')}}"></script>
    <!-- Settings Init Script -->
    <script src="{{asset('assets/backend/js/setting-init.js')}}"></script>
    <!-- External Library Bundle Script -->
    <script src="{{asset('assets/backend/js/core/external.min.js')}}"></script>
    <!-- Widgetchart Script -->
    <script src="{{asset('assets/backend/js/charts/widgetchartsf700.js?v=1.0.1')}}" defer></script>
    <!-- Dashboard Script -->
    <script src="{{asset('assets/backend/js/charts/dashboardf700.js?v=1.0.1')}}" defer></script>
    <!-- qompacui Script -->
    <script src="{{asset('assets/backend/js/qompac-uif700.js?v=1.0.1')}}" defer></script>
    <script src="{{asset('assets/backend/js/sidebarf700.js?v=1.0.1')}}" defer></script>
    
  </body>

</html>
