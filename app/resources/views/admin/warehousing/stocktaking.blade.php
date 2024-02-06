@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
       
        <div class="col-xl-9 col-lg-8 col-md-8 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    <form action="{{route('admin.warehousing.stock.store')}}" 
                    method="POST"
                          enctype="multipart/form-data">
                        @csrf
                            
                        <div class="row">

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold"> @lang('Warehouse')</label>
                                    <div class="input-group">
                                        <select class="select2-basic" name="warehouse_id" required>
                                            <option value="">@lang('Select Warehouse')</option>
                                            @foreach ($warehouses as $item)
                                                <option value="{{ $item->id }}" 
                                                >{{ __($item->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label font-weight-bold"> @lang('Item')</label>
                                        <div class="input-group">
                                            <select class="select2-basic" name="product_id" required>
                                                <option value="">@lang('Select Item')</option>
                                                @foreach ($products as $item)
                                                    <option value="{{ $item->id }}" 
                                                    >{{ __($item->name) }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                            
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Quantity Received')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="quantity">
                                </div>
                            </div>
                    
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Stock')
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
