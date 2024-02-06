@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
       
        <div class="col-xl-9 col-lg-8 col-md-8 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    <form action="{{route('admin.vehicles.savevehicle')}}" 
                    method="POST"
                          enctype="multipart/form-data">
                        @csrf
                            
                        <div class="row">
                            

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Name')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Chasis Number')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="chasis">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Engine Number')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="engine_no">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Registration Number')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="number_plate">
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold"> @lang('Operator')</label>
                                    <div class="input-group">
                                        <select class="select2-basic" name="operator" required>
                                            <option value="">@lang('Select Operator')</option>
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
                    
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Add')
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
