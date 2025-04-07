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

    .select2-selection__choice
    {
        margin-bottom: 2px !important;
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
</style>
@section('content')

<div class="content-inner pb-0 container" id="page_layout">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{$title}}</h4>
                    </div>
                    <a href="{{url('create-list/view')}}">
                        <button  class="btn btn-primary">View </button>
                    </a>
                </div>
                <div class="card-body">
                    <form action="/create-list/filter" method="get" enctype="multipart/form-data">
                        {{-- @csrf --}}
                        {{-- start customer filter --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Customer Filter</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="cusCode">Customer Code</label>
                                        <select name="customer_code[]" id="cusCode" class="select2 form-select form-control" multiple>
                                            @if(!empty($cusCodeName))
                                            @foreach($cusCodeName as $cusCodeNameData)
                                                <option value="{{$cusCodeNameData->bp_code}}">{{$cusCodeNameData->bp_code}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('customer_code'))
                                            <div class="form-error">{{ $errors->first('customer_code') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="cusName">Customer Name</label>
                                        <select id="cusName" name="customer_name[]" class="select2 form-select form-control" multiple>
                                            @if(!empty($cusCodeName))
                                                @foreach($cusCodeName as $cusCodeNameData)
                                                    <option value="{{$cusCodeNameData->bp_name}}">{{$cusCodeNameData->bp_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('customer_name'))
                                            <div class="form-error">{{ $errors->first('customer_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="cusCategory">Customer Category</label>
                                        <select name="customer_category[]" id="cusCategory" class="select2 form-select form-control" multiple>
                                            <option value="choose country category">Choose Customer Category</option>
                                                @foreach($cusCategory as $cusCategoryData)
                                                    <option value="{{$cusCategoryData->group_no}}">{{$cusCategoryData->group_name}}</option>
                                                @endforeach
                                        </select>
                                        @if($errors->has('customer_category'))
                                            <div class="form-error">{{ $errors->first('customer_category') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="plan">Territory</label>
                                        {{-- <input type="number" name="territory" class="form-control" placeholder="Enter Territory"/> --}}
                                        <select name="territory[]" id="territory" class="select2 form-select form-control" multiple>
                                            {{-- <option value="choose territory">Choose Territory</option> --}}
                                                @foreach($territory as $territoryData)
                                                    <option value="{{$territoryData}}">{{$territoryData}}</option>
                                                @endforeach
                                        </select>
                                        @if($errors->has('territory'))
                                            <div class="form-error">{{ $errors->first('territory') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="plan">Country</label>
                                        <select name="country[]" multiple class="select2 form-select form-control" id="stopNowOnChangecountry">
    
                                            @foreach ($country as $countryData)
                                                <option value="{{$countryData->sortname}}">{{$countryData->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                        @if($errors->has('country'))
                                            <div class="form-error">{{ $errors->first('country') }}</div>
                                        @endif
                                    </div>
                                    
                                    {{-- <div class="col-md-4 mt-3">
                                        <label class="form-label" for="pincode">Pincode</label>
                                        <select name="pincode[]" class="select2 form-select form-control" multiple>
                                            @foreach ($pincode as $pincodeData)
                                                <option value="{{$pincodeData->code}}">{{$pincodeData->code}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('pincode'))
                                            <div class="form-error">{{ $errors->first('pincode') }}</div>
                                        @endif
                                    </div> --}}

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="plan">State</label>
                                        <select name="state[]" class="select2 form-select form-control" multiple>
                                            @foreach ($state as $stateData)
                                                <option value="{{$stateData->Code}}">{{$stateData->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('state'))
                                            <div class="form-error">{{ $errors->first('state') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="plan">City</label>
                                        <select name="city[]" class="select2 form-select form-control" multiple>
                                            @foreach ($city as $cityData)
                                                <option value="{{$cityData->name}}">{{$cityData->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('city'))
                                            <div class="form-error">{{ $errors->first('city') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-2 mt-3">
                                        <label class="form-label" for="active">Active<span class="text-danger">*</span></label>
                                        <div>
                                            <input class="form-check-input" type="radio" name="active" id="yes" value="yes" checked>
                                            <label class="form-check-label" for="yes">
                                            Yes
                                            </label>
                                            <input class="form-check-input" type="radio" name="active" value="no" id="no">
                                            <label class="form-check-label" for="no">
                                            No
                                            </label>
                                        </div>
                                            
                                        @if($errors->has('active'))
                                            <div class="form-error">{{ $errors->first('active') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-2 mt-3">
                                        <label class="form-label" for="active">No Filter</label>
                                        <div>
                                            <input class="form-check-input" type="checkbox" name="cus_no_filter" checked id="yes">
                                            <label class="form-check-label" for="yes">
                                            Yes
                                            </label>
                                        </div>
                                            
                                        @if($errors->has('no_filter'))
                                            <div class="form-error">{{ $errors->first('no_filter') }}</div>
                                        @endif
                                    </div>
                                
                                    
                                </div>
                            </div>
                    

                        {{-- start invoice filter --}}
                        
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Invoice Filter</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label" for="product-code">Product Code</label>
                                        {{-- <input type="text" name="product_code" class="form-control" placeholder="Enter Product Code"/> --}}
                                        <select id="product-code" name="product_code[]" class="select2 form-select form-control" multiple>
                                            @if(!empty($item_sku))
                                                @foreach($item_sku as $item_skuData)
                                                    <option value="{{$item_skuData}}">{{$item_skuData}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('product_code'))
                                            <div class="form-error">{{ $errors->first('product_code') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label" for="product_category">Product Category</label>
                                        {{-- <input type="text" name="product_category" class="form-control" placeholder="Enter Product Category"/> --}}
                                        <select id="product_category" name="product_category[]" class="select2 form-select form-control" multiple>
                                            @if(!empty($pCategory))
                                                @foreach($pCategory as $pCategoryData)
                                                    <option value="{{$pCategoryData}}">{{$pCategoryData}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('product_category'))
                                            <div class="form-error">{{ $errors->first('product_category') }}</div>
                                        @endif
                                    </div>
                                    {{-- <div class="col-md-4 mt-3">
                                        <label class="form-label" for="product_group">Product Group</label>
                                        <input type="number" name="product_group" class="form-control" placeholder="Enter Product Group"/>
                                        @if($errors->has('product_group'))
                                            <div class="form-error">{{ $errors->first('product_group') }}</div>
                                        @endif
                                    </div> --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label" for="date">Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text">From</span>
                                            <input type="date" name="fromDate" class="form-control"/>
                                            <span class="input-group-text">To</span>
                                            <input type="date" name="toDate" class="form-control"/>
                                        </div>
                                        @if($errors->has('fromDate'))
                                            <div class="form-error">{{ $errors->first('fromDate') }}</div>
                                        @endif
                                        @if($errors->has('toDate'))
                                            <div class="form-error">{{ $errors->first('toDate') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-2 mt-3"></div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label" for="active">No Filter</label>
                                        <div>
                                            <input class="form-check-input" type="checkbox" name="invoice_no_filter" checked id="yes">
                                            <label class="form-check-label" for="yes">
                                            Yes
                                            </label>
                                        </div>
                                            
                                        @if($errors->has('no_filter'))
                                            <div class="form-error">{{ $errors->first('no_filter') }}</div>
                                        @endif
                                    </div>
                                
                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary filter-btn">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/dropdown/js/createlist-forms-selects.js')}}"></script>
@endsection

