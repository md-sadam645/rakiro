@extends('admin.layout.index')

@section('content')


<div class="content-inner container-fluid pb-0" id="page_layout">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-5 gap-3">

        </div>
        <div class="row">
            @if(Auth::user()->role == 1)
                <div class="col-lg-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="mb-5">
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <div class="header-title">
                                        <h4 class=" card-title">Quick Links</h4>           
                                    </div>
                                    {{-- <span class="text-dark">Quick Links</span> --}}
                                    {{-- <a class="badge rounded-pill bg-soft-primary" href="javascript:void(0);">
                                        View Report
                                    </a> --}}
                                </div>
                                {{-- <div class=""> --}}
                                    {{-- <h2 class="counter mb-2" style="visibility: visible;">$46,996</h2> --}}
                                    {{-- <p>Show Total Data</p> --}}
                                {{-- </div> --}}
                            </div>
                            <div>
                                <div class="d-flex gap flex-column">
                                    <div class="d-flex align-items-center gap-3">
                                        
                                        <a href="javascript:void(0)">
                                            <div class="bg-soft-secondary avatar-60 rounded">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        </a>
                                        
                                        <div style="width: 100%;">
                                            <div>
                                                <h6 class="mb-2">Total Customer</h6>
                                                <h6 class="text-body">{{$customer}}</h6>
                                            </div>
                                            {{-- <div class="progress bg-soft-primary shadow-none w-100" style="height: 6px">
                                                @php $t_customer = 100 * $customer / 100 @endphp
                                                <div class="progress-bar bg-primary" data-toggle="progress-bar" role="progressbar" aria-valuenow="{{$t_customer}}" aria-valuemin="0" aria-valuemax="100" style="width: 23%;transition: width 2s ease 0s;"></div>
                                            </div> --}}
                                        </div> 
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        
                                        <a href="{{url('create-list/detail')}}">
                                            <div class="bg-soft-primary avatar-60 rounded">
                                                <i class="fa-solid fa-user-group"></i>
                                            </div>
                                        </a>
                                        
                                        <div style="width: 100%;">
                                            <div>
                                                <h6 class="mb-2">Total Group</h6>
                                                <h6 class="text-body">{{$group}}</h6>
                                            </div>
                                            {{-- <div class="progress bg-soft-primary shadow-none w-100" style="height: 6px">
                                                @php $t_group = 100 * $group / 100 @endphp
                                                <div class="progress-bar bg-primary" data-toggle="progress-bar" role="progressbar" aria-valuenow="{{$t_group}}" aria-valuemin="0" aria-valuemax="100" style="width: 23%;transition: width 2s ease 0s;"></div>
                                            </div> --}}
                                        </div> 
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                                                
                                        <a href="{{url('sub-admin/view')}}">
                                            <div class="bg-soft-info avatar-60 rounded"> 
                                                <i class="fa-solid fa-user-tie"></i>
                                            </div>
                                        </a>                       
                                        
                                        <div style="width: 100%;">
                                            <div>
                                                <h6 class="mb-2">Total SubAdmin</h6>
                                                <h6 class="text-body">{{$sAdmin}}</h6>
                                            </div>
                                            {{-- <div class="progress bg-soft-info shadow-none w-100" style="height: 6px">
                                                @php $t_sAdmin = 100 * $sAdmin / 100 @endphp
                                                <div class="progress-bar bg-info" data-toggle="progress-bar" role="progressbar" aria-valuenow="{{$t_sAdmin}}" aria-valuemin="0" aria-valuemax="100" style="width: 40%;transition: width 2s ease 0s;"></div>
                                            </div> --}}
                                        </div> 
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                            
                                        <a href="{{url('email-compose/view')}}">
                                            <div class="bg-soft-success avatar-60 rounded"> 
                                                <i class="fa-solid fa-envelope" ></i>
                                            </div>
                                        </a>
                                        
                                        <div style="width: 100%;">
                                            <div>
                                                <h6 class="mb-2">Total Schedule Email</h6>
                                                <h6 class="text-body">{{$sEmail}}</h6>
                                            </div>
                                            {{-- <div class="progress bg-soft-success shadow-none w-100" style="height: 6px">
                                                @php $t_sEmail = 100 * $sEmail / 100 @endphp
                                                <div class="progress-bar bg-success" data-toggle="progress-bar" role="progressbar" aria-valuenow="{{$t_sEmail}}" aria-valuemin="0" aria-valuemax="100" style="width: 82%;transition: width 2s ease 0s;"></div>
                                            </div> --}}
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-block card-stretch card-height">
                        <div class="flex-wrap card-header d-flex justify-content-between border-0">
                            <div class="header-title">
                                <h4 class=" card-title">New SubAdmin</h4>           
                            </div>
                            <div class="dropdown">
                                <a class="badge rounded-pill bg-soft-primary" href="/sub-admin/view">
                                    View More
                                </a>
                                {{-- <span class="dropdown" role="button" style="border-radius: :5px;border:1px solid"> View 
                                </span> --}}
                            
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class=" table-responsive border rounded">
                                <table id="basic-table" class="table mb-0 table-striped" role="grid">
                                    <thead>
                                        <tr>
                                            <th>USER NAME </th>
                                            <th>EMAIL</th>
                                            <th>MOBILE</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($user))
                                            @foreach($user as $row)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center text-capitalize">
                                                        <h6>{{$row->name}}</h6>
                                                    </div>
                                                </td>
                                                <td><h6>{{$row->email}}</h6> </td>
                                                <td>{{$row->mobile}}</td>
                                                <td>
                                                    @if($row->status==1)
                                                        <span class="badge bg-soft-primary p-2 text-primary">Active</span>
                                                    @elseif($row->status==0)
                                                        <span class="badge bg-soft-danger p-2 text-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">No Data Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height">
                    <div class="flex-wrap card-header d-flex justify-content-between border-0">
                        <div class="header-title">
                            <h4 class=" card-title">Invalid Email List</h4>           
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="fancy-table table-responsive border rounded">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Group Name</th>
                                        <th scope="col">Invalid Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($list))
                                        @foreach($list as $key => $val)
                                            @if(count($invalidEmail[$val->id]) > 0)
                                                @foreach($invalidEmail[$val->id] as $invalEmail)
                                                    <tr>
                                                        <td class="text-capitalize">{{$val->group_name}}</td>
                                                            <td>
                                                                {{$invalEmail->email_id}}
                                                            </td>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        {{-- <div class="col-lg-12">
            <div class="card card-block card-stretch card-height">
                <!-- start pagination -->
                @if(!empty($list))
                <center>
                    {{ $list->links('vendor.pagination.custom') }}
                </center>
                @endif
                <!-- end pagination -->
            </div>
        </div> --}}

    </div>
</div> 
</div>
@php

$dataPoints = array(
	array("x" => 1672570322000 , "y" => 650),
	array("x" => 1675248722000 , "y" => 700),
	array("x" => 1677667922000 , "y" => 710),
	array("x" => 1680346322000 , "y" => 658),
	array("x" => 1682938322000 , "y" => 734),
	array("x" => 1685616722000 , "y" => 963),
	array("x" => 1688208722000 , "y" => 847),
	array("x" => 1690887122000 , "y" => 853),
	array("x" => 1693565522000 , "y" => 869),
	array("x" => 1696157522000 , "y" => 943),
	array("x" => 1698835922000 , "y" => 970),
	array("x" => 1701427922000 , "y" => 869),

 );
 $orderDataPoint = array(
	array("x" => 1672570322000 , "y" => 650),
	array("x" => 1675248722000 , "y" => 1000),
	array("x" => 1677667922000 , "y" => 710),
	array("x" => 1680346322000 , "y" => 500),
	array("x" => 1682938322000 , "y" => 980),
	array("x" => 1685616722000 , "y" => 363),
	array("x" => 1688208722000 , "y" => 547),
	array("x" => 1690887122000 , "y" => 753),
	array("x" => 1693565522000 , "y" => 969),
	array("x" => 1696157522000 , "y" => 1643),
	array("x" => 1698835922000 , "y" => 1770),
	array("x" => 1701427922000 , "y" => 1869),

 );
 
@endphp
<script>
    window.onload = function () {
     
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light1",
        title:{
            text: "User Registration"
        },
        axisX: {
            valueFormatString: "DD MMM"
        },
        axisY: {
            title: "Total Number of Registration",
            includeZero: true,
            maximum: 1200
        },
        data: [{
            type: "splineArea",
            color: "#6599FF",
            xValueType: "dateTime",
            xValueFormatString: "DD MMM",
            yValueFormatString: "#,##0 Visits",
            dataPoints: <?php echo json_encode($dataPoints); ?>
        }]
    });

    var sellerchart = new CanvasJS.Chart("sellerRegistration", {
        animationEnabled: true,
        theme: "light1",
        title:{
            text: "Seller Registration"
        },
        axisX: {
            valueFormatString: "DD MMM"
        },
        axisY: {
            title: "Total Number of Registration",
            includeZero: true,
            maximum: 1200
        },
        data: [{
            type: "splineArea",
            color: "#6599FF",
            xValueType: "dateTime",
            xValueFormatString: "DD MMM",
            yValueFormatString: "#,##0 Visits",
            dataPoints: <?php echo json_encode($dataPoints); ?>
        }]
    });
     
    var b2bsellerchart = new CanvasJS.Chart("b2bsellerRegistration", {
        animationEnabled: true,
        theme: "light1",
        title:{
            text: "B2B Seller Registration"
        },
        axisX: {
            valueFormatString: "DD MMM"
        },
        axisY: {
            title: "Total Number of Registration",
            includeZero: true,
            maximum: 1200
        },
        data: [{
            type: "splineArea",
            color: "#6599FF",
            xValueType: "dateTime",
            xValueFormatString: "DD MMM",
            yValueFormatString: "#,##0 Visits",
            dataPoints: <?php echo json_encode($dataPoints); ?>
        }]
    });
     
    var orderchart = new CanvasJS.Chart("orderStat", {
        animationEnabled: true,
        theme: "light1",
        title:{
            text: "Montly Orders"
        },
        axisX: {
            valueFormatString: "DD MMM"
        },
        axisY: {
            title: "Total Number of Orders",
            includeZero: true,
            maximum: 2000
        },
        data: [{
            type: "splineArea",
            color: "#6599FF",
            xValueType: "dateTime",
            xValueFormatString: "DD MMM",
            yValueFormatString: "#,##0 Visits",
            dataPoints: <?php echo json_encode($orderDataPoint); ?>
        }]
    });
     
    chart.render();
    sellerchart.render();
    b2bsellerchart.render();
    orderchart.render();

     
    }
    </script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
@endsection