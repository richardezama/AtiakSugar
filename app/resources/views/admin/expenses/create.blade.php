@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
       
        <div class="col-xl-9 col-lg-8 col-md-8 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('New Expense')</h5>

                    <form action="{{route('admin.expenses.store')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Username')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="username" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Firstname')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Last Name')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="telephone" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('National ID/Passport') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="nin" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Country of Origin') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="country" />
                                </div>
                            </div>

                 

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold"> @lang('Place of Birth')</label>
                                    <div class="input-group">
                                        <select class="select2-basic" name="placeoforigin" required>
                                            <option value="">@lang('Select District of origin')</option>
                                            @foreach ($districts as $item)
                                                <option value="{{ $item->districtid }}" 
                                                >{{ __($item->districtname) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>



                            <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Estate')</label>
                                <div class="input-group">
                                    <select class="select2-basic estate" name="estate_id" required>
                                        <option value="">@lang('Select Estate')</option>
                                        @foreach ($estates as $item)
                                            <option value="{{ $item->id }}" 
                                            >{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                    
                            <div class="col-xl-6 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold"> @lang('Unit')</label>
                                <select class="department form-control form_values select2-basic" name="unit_id" id="department" >
                                    <option value="">Select Unit</option>
                                </select>
                            </div>
                            </div>
                    
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Add Tenant')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" class="url" value="{{route('ajax.units','_a_c')}}"/>



@endsection
@push('script')
<script>
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
       clearSelect(departmentselect,"Select Unit");
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
