@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
       
        <div class="col-xl-9 col-lg-8 col-md-8 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    <form action="{{route('admin.repair.storedraft')}}" 
                    method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$users[0]->id}}"/>
                        @foreach ($employees as $item)
                        @endforeach
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold"> @lang('Delivered By')</label>
                                    <div class="input-group">
                                        <select class="select2-basic" 
                                        name="delivered_by" required>
                                            <option value="">@lang('Select Operator')</option>
                                            @foreach ($employees as $item)
                                            @if (trim($item->id)==trim($users[0]->delivered_by))
                                            <option value="{{ $item->id }}" selected 
                                                >{{ __($item->name) }}</option>
                                            @else
                                            <option value="{{ $item->id }}" 
                                                >{{ __($item->nameid) }}</option>
                                            @endif
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
                                                @if (trim($item->id)==trim($users[0]->vehicle_id))
                                                <option value="{{ $item->id }}" selected 
                                                    >{{ __($item->name) }}</option>
                                                @else
                                                <option value="{{ $item->id }}" 
                                                    >{{ __($item->nameid) }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                            
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Odometer In')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="odometer_in" value="{{$users[0]->odometer_in}}">
                                </div>
                            </div>

                           

                                <div class="col-xl-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold"> @lang('Engineer Assigned')</label>
                                        <div class="input-group">
                                            <select class="select2-basic" name="engineer_assigned" required>
                                                <option value="">@lang('Select Engineer')</option>
                                                @foreach ($employees as $item)
                                                    <option value="{{ $item->id }}" 
                                                    >{{ __($item->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold"> @lang('Service Type')</label>
                                            <div class="input-group">
                                                <select class="select2-basic" name="service_type" required >
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
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label class="form-control-label font-weight-bold">@lang('Defects as Recorded')
                                                     <span class="text-danger">*</span></label>
                                                <textarea class="form-control" type="text" name="defects_reported" 
                                                >{{$users[0]->defects_reported}}</textarea>
                                            </div>
                                        </div>

                                       

                       
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Finish Draft')
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
