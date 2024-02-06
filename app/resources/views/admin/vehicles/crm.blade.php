@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
       
        <div class="col-xl-9 col-lg-8 col-md-8 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    <form action="{{route('admin.repair.store')}}" 
                    method="POST"
                          enctype="multipart/form-data">
                        @csrf
                            
                        <div class="row">

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold"> @lang('Delivered By')</label>
                                    <div class="input-group">
                                        <select class="select2-basic" name="delivered_by" required>
                                            <option value="">@lang('Select Operator')</option>
                                            @foreach ($operators as $item)
                                                <option value="{{ $item->id }}" 
                                                >{{ __($item->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold"> @lang('Equipment')</label>
                                        <div class="input-group">
                                            <select class="select2-basic" name="vehicle_id" required>
                                                <option value="">@lang('Select Equipment')</option>
                                                @foreach ($vehicles as $item)
                                                    <option value="{{ $item->id }}" 
                                                    >{{ __($item->name) }} | {{ __($item->number_plate) }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                            
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Odometer In')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="odometer_in">
                                </div>
                            </div>

                           

                                <div class="col-xl-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold"> @lang('Technicians Assigned')</label>
                                        <div class="input-group">
                                            <select class="select2-basic" name="engineer_assigned[]" required multiple>
                                                <option value="">@lang('Select Technicians')</option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}" 
                                                    >{{ __($item->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold"> @lang('Defect Type')</label>
                                            <div class="input-group">
                                                <select class="select2-basic service_type" name="service_category" required >
                                                    <option value="">@lang('Select Service Type')</option>
                                                         <option value="1" 
                                                        >Intervention</option>
                                                        <option value="2" 
                                                        >Service</option>
                                                        <option value="3" 
                                                        >Repair</option>
                                                
                                                </select>
                                            </div>
                                        </div>

                                        
                                        <div class="form-group service_div" style="display: none;">
                                            <label class="form-control-label font-weight-bold"> @lang('Service Type')</label>
                                            <div class="input-group">
                                                <select class="select2-basic" name="service_type" required>
                                                    <option value="">@lang('Select Category')</option>
                                                    @foreach ($service_types as $item)
                                                        <option value="{{ $item->id }}" 
                                                        >{{ __($item->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label class="form-control-label font-weight-bold">@lang('Defects as Recorded')
                                                     <span class="text-danger">*</span></label>
                                                <textarea class="form-control" type="text" name="defects_reported"></textarea>
                                            </div>
                                        </div>

                                       

                            <!--

                            <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Make')</label>
                                <div class="input-group">
                                    <select class="select2-basic estate" name="make_id" required>
                                        <option value="">@lang('Select Make')</option>
                                        @foreach ($makes as $item)
                                            <option value="{{ $item->id }}" 
                                            >{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                    
                            <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Model')</label>
                                <select class="department form-control form_values select2-basic" name="model_id" id="department" >
                                    <option value="">Select Model</option>
                                </select>
                            </div>
                            </div>
                        -->
                    
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Record')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" class="url" value="{{route('ajax.models','_a_c')}}"/>




@endsection
@push('script')
<script src="{{asset('assets/admin/js/vendor/jquery-ui.js')}}"></script>

<script>
     $( ".datepicker1" ).datepicker();


     
     $(".service_type").change(function(e){
     var type=$(this).val();
     if(type==2)
     {

        $(".service_div").show();
     }
     else{
        $(".service_div").hide();
     }
      
     });



    $(".estate").change(function(e){
     
      division=$(this).val();
      if(division=="")
      {
    
      }
      else{
        var url=$(".url").val();
        var uri=url.replace("_a_c",division);
        //alert(uri);
       // alert(division);
        departmentselect = document.getElementById('department');    
        clearSelect(departmentselect,"Select Model");
        $.ajax({
                method: 'GET',
                url: uri,
                dataType: 'json',
                success: function(result) {
                  $.each(result, function (key, val) {
                 // alert(JSON.stringify(result));
                    var opt = document.createElement('option');
        opt.value = val.id;
        opt.innerHTML = val.name;
        departmentselect.appendChild(opt);
                  });         
                },
            });
      }
    
    });

    function clearSelect(select,title)
    {
      var length = select.options.length;
    for (i = length-1; i >= 0; i--) {
      select.options[i] = null;
    }
    //then add default
    var opt = document.createElement('option');
        opt.value = "";
        opt.innerHTML =title;
        select.appendChild(opt);
    }
    </script>
    @endpush
