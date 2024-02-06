@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$item->comment}}
                    
                        @if($item->status == 1)
                        <span class="text--small badge font-weight-normal badge--warning">@lang('Pending Supervisor')</span>
                        
                        @elseif($item->status == 2)
                        <span class="text--small badge font-weight-normal badge--warning">@lang('Pending Approval 2')</span>
                        @elseif($item->status == 3)
                        <span class="text--small badge font-weight-normal badge--warning">@lang('Pending Supervisor')</span>
                      
                        @elseif($item->status == 4)
                        <span class="text--small badge font-weight-normal badge--success">@lang('Completely Approved')</span>
                        @elseif($item->status == 5)
                        <span class="text--small badge font-weight-normal badge--danger">@lang('Rejected')</span>
                  

                        @else
                        <span class="text--small badge font-weight-normal badge--success">@lang('Processed')</span>
                        @endif
                    </h5>

                    <h5 class="card-title border-bottom pb-2">@lang('Date')
                        {{ showDateTime($item->created_at) }} <br> {{ diffForHumans($item->created_at) }}
                             
                    </h5>
                   

                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Product No')</th>
                              <th>@lang('Quantity')</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($details as $i)
                            <tr>
                           
                                <td>
                                    {{ ($i->product->name) }}
                                </td>
                                <td data-label="@lang('Quantity')">
                                        {{ showAmount($i->quantity) }}
                                                                           
                                </td>
                               
                                
                               
                            </tr>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr/>
                  

                    @if ($item->status!=4)
                    <form action="{{route('admin.requisitions.update',[$id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Status')<span class="text-danger">*</span></label>
                                    <select class="form-control select2-basic" name="status">
                                        <option value="">@lang('Select Status')</option>
                                        <option value="1">@lang('Accept')</option>
                                        <option value="0">@lang('Reject')</option>
                                       
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Comment') <span class="text-danger">*</span></label>
                                    <textarea type="text" class="form-control" 
                                    placeholder="@lang('Comment')" name="comment" required></textarea> 
                                
                                </div>
                            </div>
                        </div>



                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                           
                            </div>

                        </div>
                    </form>
                    @endif

                    <div class="col-sm-12">
                        Logs
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                
                                    <th>@lang('Comment')</th>
                                  <th>@lang('Date')</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $log)
                                <tr>
                                  
                                    <td>
                                        {{ ($log->user->name) }}
                                    </td>
                                    <td>
                                        {{ ($log->comment) }}
                                    </td>
                               
                                    <td data-label="@lang('Date')">
                                        {{ showDateTime($item->created_at) }} <br> {{ diffForHumans($item->created_at) }}
                     
                                                                               
                                    </td>
                                   
                                    
                                   
                                </tr>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>


    <input type="hidden" class="url" value="{{route('ajax.units','_a_c')}}"/>


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
