@extends('admin.layout.index')
<style>
    #select4{
        height:38px;
        padding: 0px;
    }

    .selectator_element{
        width: 100% !important;
    }

    .select2-selection__choice__display
    {
        margin-left: 5px !important;
        color: black !important;
        padding-right:0px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: inherit;
        top: -0.25rem !important;
    }

    span.select2-selection.select2-selection--multiple {
        border: none !important;
    }

    .select2-container--default .select2-search--inline .select2-search__field {
        box-shadow: none;
        background: rgba(0,0,0,0);
        margin-top: 0px !important;
        /* border: 1px solid #7367f0 !important; */
        width:250px !important;
        float: left;
        outline: 0;
        -webkit-appearance: textfield;
    }

    @media(max-width : 768px)
    {
        .schedule-send-btn{
            width: 75% !important;
        }

        .attc-label{
            width: 80% !important;
        }
    }
</style>
 <!-- new group select CSS -->

{{-- <script src="{{asset('assets/email/fm.selectator.jquery.js?cb=29')}}"></script> --}}
@section('content')
<div class="content-inner pb-0 container" id="page_layout">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{$title}}</h4>
                    </div>
                    <a href="{{url('automail/view')}}">
                        <button  class="btn btn-primary">View</button>
                    </a>
                </div>
                <div class="card-body">
                    <!-- Compose Email -->
                    <div class="app-email-compose">
                        <div class="modal-dialog m-0 me-md-4 mb-4">
                            <div class="modal-content w-100 p-0">
                                <div class="modal-body w-100 flex-grow-1 pb-sm-0 p-4 py-4">
                                    <form class="email-compose-form" method="POST" action="{{url('automail/add')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="email-compose-subject d-flex align-items-center mb-3">
                                            <label for="name" class="form-label mb-0">Name:</label>
                                            <input type="text" class="form-control shadow-none flex-grow-1 ms-2" name="name" id="name" 
                                            @if(!empty(session()->get('automail_name'))) value="{{session()->get('automail_name')}}" @else placeholder="Enter Name" @endif required>
                                            @if($errors->has('name'))
                                                <div class="form-error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                        <div class="email-compose-cc mb-4">
                                            <div class="d-flex align-items-center">
                                                <label for="email-from" class="form-label mb-0">From: </label>
                                                <select class="form-control shadow-none flex-grow-1 ms-2 from-email" name="email_from" required>
                                                    <option value="choose from address">Choose From Address</option>
                                                    @if($smtp != '[]')
                                                        @foreach ($smtp as $smtpData)
                                                            <option value="{{$smtpData->from_address}}" @if(session()->get("automail_from") == $smtpData->from_address) selected @endif>{{$smtpData->from_address}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if($errors->has('email_from'))
                                                    <div class="form-error text-danger">{{ $errors->first('email_from') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        

                                        <!-- Multiple -->
                                        <div class="d-flex align-items-center col-md-12">
                                             <div style="width:128px;">
                                                <label for="cusCategory" class="form-label">Customer Category: </label>
                                            </div>
                                            <div class="border selectMultipleCon " style="width:calc(100% - 128px);">
                                                @php 
                                                    $groupArray = session()->get('cus_category');
                                                @endphp
                                                <select id="select2Multiple" name="cus_category[]" class="select2 form-select form-control" multiple>
                                                    
                                                    @if(!empty($cusCategory))
                                                        @if(!empty($groupArray))
                                                            @foreach ($cusCategory as $groupData)
                                                                @php
                                                                    $isSelected = in_array($groupData->group_no, $groupArray ?? []);
                                                                @endphp
                                                                <option value="{{ $groupData->group_no }}" @if($isSelected) selected @endif>
                                                                    {{ $groupData->group_name }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            @foreach($cusCategory as $groupData)
                                                                <option value="{{$groupData->group_no}}">{{$groupData->group_name}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </select>
                                                @if($errors->has('cus_category'))
                                                    <div class="form-error">{{ $errors->first('cus_category') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <hr class="container-m-nx my-2">
                                        <div class="email-compose-subject d-flex align-items-center mb-2">
                                            <div style="width:175px;">
                                                <label for="days-from" class="form-label mb-0">Days from Last Invoice:</label>
                                            </div>
                                            <div style="width:calc(100% - 175px);">
                                                <input type="number" class="form-control shadow-none flex-grow-1" name="days_from" id="days-from"
                                                @if(!empty(session()->get('days_from'))) value="{{session()->get('days_from')}}" @else placeholder="Enter Days" @endif required>
                                            </div> 
                                            @if($errors->has('days_from'))
                                                <div class="form-error">{{ $errors->first('days_from') }}</div>
                                            @endif
                                        </div>

                                        <hr class="container-m-nx my-2">
                                        <div class="email-compose-subject d-flex align-items-center mb-2">
                                            <label for="subject" class="form-label mb-0">Subject:</label>
                                            <input type="text" class="form-control shadow-none flex-grow-1 ms-2" name="subject" id="subject"
                                            @if(!empty(session()->get('automail_subject'))) value="{{session()->get('automail_subject')}}" @else placeholder="Enter Subject" @endif>
                                        </div>
            
                                        <div class="email-compose-message mt-3" style="position:relative;">
                                            <div style="position:absolute;top:20px;right:0px;">
                                                <a title="Click Here For Shortcut Name" data-bs-toggle="modal" data-bs-target="#shortcutName">
                                                    <i class="fa-solid fa-circle-info" title="Click Here For Shortcut Name" style="cursor:pointer;font-size:20px;"></i>
                                                </a>
                                            </div>
                                            <label for="email-message" class="form-label mb-2 mt-4">Message:</label>
                                            <textarea name="email_msg" class="mt-5" required>
                                                @if(!empty(session()->get('automail_msg'))) {{session()->get('automail_msg')}} @endif
                                            </textarea>
                                            @if($errors->has('email_msg'))
                                                <div class="form-error text-danger">{{ $errors->first('email_msg') }}</div>
                                            @endif
                                            <!-- ck editor js code -->
                                            <script>
                                                CKEDITOR.replace('email_msg');
                                            </script>
                                        </div>
            
                                        <hr class="container-m-nx my-2">
                                        <div class="email-compose-subject d-flex align-items-center mb-2">
                                            <label for="attach-file" class="mb-0 attc-label">Attachment : </label>
                                            <div class="pt-2 ms-lg-2 me-3">
                                                <i class="fa-solid fa-paperclip position-absolute" style="font-size:20px;cursor:pointer;"></i>
                                                <input type="file" name="attachment[]" multiple id="attach-file" style="width:20px;opacity:0 !important;">
                                                <input type="text" name="no_attachment" id="attach-file" class="d-none old-file" readonly value="{{session()->get('automail_attachment')}}" style="opacity:1 !important;">
                                            </div>
                                            <div class="position-relative" title="Delete File">
                                                @if(!empty(session()->get('automail_attachment')))
                                                    <div style="position:absolute;right:-15px;cursor:pointer;" id="file-delete-btn" emailID="{{session()->get('automail_attachment')}}" class="p-1 rounded-circle attch-dlt">
                                                        <i class="fa-solid fa-circle-xmark text-danger" style="font-size:17px;"></i>
                                                    </div>
                                                    <p class="ml-3 attach-file text-primary"><i class="fa-solid fa-file" style="font-size:30px;margin-top:10px;"></i></p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-4 row">
                                            <div class="col-md-12 col-lg-2 mb-2 mb-lg-0">
                                                <button type="button" class="btn btn-primary test-mail-btn" data-bs-toggle="modal" data-bs-target="#sendTestMail">
                                                    <i class="fa-regular fa-paper-plane"></i>
                                                    Send Test Mail
                                                </button>
                                            </div>
                                            <div class="col-md-12 col-lg-2">
                                                <button type="button" class="btn btn-primary schedule-btn" data-bs-toggle="modal" data-bs-target="#scheduleSend">
                                                    <i class="fa-regular fa-envelope"></i>
                                                    Create Automail
                                                </button>
                                            </div>

                                            @if(!empty(session()->get("automail_test_email")))
                                            <div class="col-md-12 col-lg-2">
                                                <a class="btn btn-primary schedule-btn" href="{{url('automail-form-reset/1')}}">
                                                    <i class="fa-solid fa-rotate-right"></i>
                                                    Reset Test Data
                                                </a>
                                            </div>
                                            @endif
                                        </div>

                                        <!--Test Email  Modal -->
                                        <div class="modal fade" id="sendTestMail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Test Email</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-md-12 col-12 mb-4">
                                                            <label for="text-email" class="form-label">To <span class="text-danger">*</span></label>
                                                            <input type="email" name="test_email" required class="form-control test_email" id="text-email" value="{{session()->get('automail_test_email')}}" placeholder="Enter Email" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary"><i class="fa-regular fa-paper-plane"></i> Send Test Mail</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--automation Schedule  Modal -->
                                        <div class="modal fade" id="scheduleSend" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Automation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-md-12 col-12 mb-4">
                                                        <label for="schedule-days" class="form-label">Schedule Days<span class="text-danger">*</span></label>
                                                        <input type="number" name="schedule_days" required class="form-control schedule_time" id="schedule-days" />
                                                    </div>
                                                    <div class="col-md-12 col-12 mb-4">
                                                        <label for="schedule-time" class="form-label">Schedule Time<span class="text-danger">*</span></label>
                                                        <input type="time" name="schedule_time" required class="form-control schedule_time" id="schedule-time" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"><i class="fa-regular fa-paper-plane"></i> Create Automail</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Compose Email --> 
                </div>
            </div>

  
        <!-- Start : Shortcut Name Modal -->
        <div class="modal fade" id="shortcutName" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">All Shortut Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h5>Customer Master</h5>
                                <ul class="mt-2">
                                    <li>bp_code</li>
                                    <li>bp_name</li>
                                    <li>mobile_phone</li>
                                    <li>email_id</li>
                                    <li>credit_limit</li>
                                    <li>account_balance_in_sc</li>
                                    <li>default_shipping</li>
                                    <li>default_billing</li>
                                    <li>bp_type</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h5>Customer Address</h5>
                                <ul class="mt-2">
                                    <li>address</li>
                                    <li>row_num</li>
                                    <li>street</li>
                                    <li>block</li>
                                    <li>location_city</li>
                                    <li>location_state</li>
                                    <li>location_country</li>
                                    <li>location_postal_code</li>
                                    <li>address_type</li>
                                    <li>gstin</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h5 class="my-2">Customer Contact</h5>
                                <ul class="mt-2">
                                    <li>contact_person_name</li>
                                    <li>con_mobile_phone</li>
                                    <li>con_email_id</li>
                                </ul>
                            </div>

                            <div class="col-md-3">
                                <h5>Price List</h5>
                                <ul class="mt-2">
                                    <li><span title="Must write ( UIC_ ) before U_ItemCode">Write U_ItemCode Ex: UIC_BROAIP</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- End : Shortcut Name Modal -->
    <!-- Core new JS -->
    {{-- <script src="{{asset('assets/dropdown/vendor/libs/select2/select2.js')}}"></script> --}}
    <script src="{{asset('assets/dropdown/js/automail-forms-selects.js')}}"></script>
    {{-- <script src="{{asset('assets/email/jquery.email.multiple.js')}}"></script> --}}
    <script>
        $(document).ready(function($){
            $("#file-delete-btn").click(function(){
                var attachFile = $(this).attr("emailID");
                var csrfToken = $("body").attr("token");
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });

                $.ajax({
                    type : "get",
                    url : "/automail/attch/empty",
                    data : {
                        file : attachFile
                    },
                    success : function(response){
                        $(".attach-file").addClass("d-none");
                        $(".attch-dlt").addClass("d-none");
                        $(".old-file").val("");
                    },
                    error : function(err) {
                        console.log('Error!', err)
                    }
                });
            });
            
            $(".test-mail-btn").click(function(){
                $(".test_email").removeAttr("disabled");
                $(".schedule_time").attr("disabled","disabled");
            });

            $(".schedule-btn").click(function(){
                $(".schedule_time").removeAttr("disabled");
                $(".test_email").attr("disabled","disabled");
            });

            //from email selector
            $(".from-email").on("change",function(){
                var fromEmail = this.value;
                var csrfToken = $("body").attr("token");
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });

                $.ajax({
                    type : "Post",
                    url : "/smtp-setup/config",
                    data : {
                        emailFrom : fromEmail
                    },
                    success : function(response){
                        //console.log(response)
                    },
                    error : function(err) {
                        console.log('Error!', err)
                    }

                });
            });

            let data = [
                
            ]
            $("#essai").email_multiple({
                data: data
                // reset: true
            });
        });
    </script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-36251023-1']);
        _gaq.push(['_setDomainName', 'jqueryscript.net']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
@endsection

 