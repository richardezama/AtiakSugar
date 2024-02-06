@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    <div class="table-responsive--md  table-responsive">                 

                       
                        
                            
                        <h3 style="margin-left: 25px;">Additional Defects Recorded</h3>
                        <table class="table table--light ">
                          
                            <tbody>
                            @forelse($extra_diognosis; as $additional)
                            <tr>
                                <td data-label="@lang('Checklist')">
                                    <span class="font-weight-bold">
                                        <img src="{{asset('tick.png')}}" height="20" width="20"/>
                                 
                                        {{$additional}}</span>
                                   
                                </td>
                               
                            </tr>
                            @endforeach
                      
                            </tbody>
                        </table><!-- table end -->
                       
                       
                        
                       
                    </div>
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Product No')</th>
                                  <th>@lang('Quantity')</th>
                                  <th>@lang('Part Number')</th>
                                  <th>@lang('Remark')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                <td data-label="@lang('No')">
                                {{ __($item->name) }}<br/>
                                        @if($item->photo!="")
                                        <img src="{{asset('uploads/'.$item->photo)}}" class="img-responsive"  
                                        width="100" height="70"/>
                                        @endif
                                    </td>
                                    <form action="{{ route('admin.pos.add')}}" method="POST">
                                        @csrf
                                    <td data-label="@lang('Amount')">
                                       
                                        <input type="hidden" name="repair_id" value="{{$checkid}}"/>
                                        <input type="number" name="quantity"  
                                        class="form-control"
                                        value="" required style="width: 70px;">
                   
                                    </td>
                                    <td data-label="@lang('Part Number')">
                                       
                                        <input type="text" name="part_number"  
                                        class="form-control" placeholder="Part Number"
                                        value="" required style="width: 70px;">
                   
                                    </td>
                                    <td data-label="@lang('Remark')">
                                       
                                        <input type="text" name="remark"  
                                        class="form-control" placeholder="Remark"
                                        value="" required style="width: 70px;">
                   
                                    </td>
                                   
                                    
                                    <td data-label="@lang('Action')">
                                        <!--<button type="button" class="icon-btn ml-1 cartMinus"
                                                data-toggle="modal" data-target="#editModal"
                                                data-product = "{{ $item }}"
                                                data-original-title="@lang('Update')">
                                            <i class="la la-shopping-cart"></i>
                                        </button>-->

                                      
                                      
                                            <input type="text" name="id" hidden="yes" value="{{ $item->id}}">
                   
                                            <button type="submit"
                                                class="icon-btn btn--danger ml-1"
                                               
                                                data-id="{{ $item->id }}"
                                                data-type_name="{{ $item->name }}"
                                                data-original-title="@lang('Disable')">
                                                <i class="la la-shopping-cart"></i>+
                                            </button>
                                      
                                           
                                    </td>
                                </form>
                                </tr>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
               
                <div class="card-footer py-4">
                    {{ paginateLinks($items) }}
                </div>
            </div>
        </div>
        @if(sizeof($cart)>0)
        <div class="col-md-4">
        <div class="card b-radius--12 ">
                <div class="card-body">
                    <h4>Cart {{sizeof($cart)}} Item(s)</h4>
                    <table class="table" with="100%">
                            <thead>
                                <tr>
                                   <th>@lang('Name')</th>
                                    <th>@lang('Qty')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($cart as $item)
                                <tr>
                               
                                    <td data-label="@lang('Name')">
                                        {{ __($item->product->name) }}
                                  
                                    </td>
                                    <td data-label="@lang('Name')">
                                        {{ __($item->quantity) }}<br/>
                                        {{ __($item->part_number) }}
                                  
                                    </td>

                                   <!-- <td data-label="@lang('Amount')">
                                        <strong data-toggle="tooltip" data-original-title="@lang('Amount')">
                                            @ {{ showAmount($item->unit_price) }} {{ __($general->cur_text) }}
                                            </strong><br/>
                                    
                                        <strong data-toggle="tooltip" data-original-title="@lang('Amount')">
                                            {{ showAmount($item->unit_price* $item->quantity) }} {{ __($general->cur_text) }}
                                            </strong>
                                    </td>-->
                                    
                                    <td data-label="@lang('Action')">
                                            <button type="button"
                                                class="icon-btn btn--danger ml-1 deleteBtn"
                                                data-toggle="modal"
                                                data-id="{{ $item->id }}"
                                                data-type_name="{{ $item->name }}">
                                                <i class="la la-trash"></i>
                                            </button>
                                       
                                    </td>
                                </tr>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <hr/>
                        <h4 style="text-align:right;">Total {{number_format($total)}} {{ __($general->cur_text) }}</h4>
                        <hr/>
                        <form id="deleteform" action="{{ route('admin.pos.deletecart')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="yes" >
                      </form>

                      
                </div>
                <form id="deleteform"
                      style="text-align:right;"
                      action="{{ route('admin.pos.checkout')}}" method="POST">
                  @csrf

                  <input type="hidden" name="id" value="{{$checkid}}"/>

                <div class="input-group">
                 <input class="form-control" required type="text" name="description" placeholder="Comment">
                       
                          </div>
                  <button class="btn btn--primary" type="submit" style="margin-top:10px;">Send Request!</button>
                    </form>
                </div>
        </div>
        @endif
    </div>


    {{-- add METHOD MODAL --}}
    <div id="disableModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add Item')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pos.add')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="yes">
                    <input type="text" name="checking_id" value={{$checkid}} hidden="yes" />
                    <div class="modal-body">
                    <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Hotel')</label>
                            <div class="input-group">
                                <select class="select2-basic estate" name="estate_id" required>
                                    <option value="">@lang('Select Hotel')</option>
                                    @foreach ($estates as $item)
                                        <option value="{{ $item->id }}" 
                                        >{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Room')</label>
                            <select class="department form-control form_values select2-basic"
                             name="unit_id" id="department" required >
                                <option value="">Select Room</option>
                            </select>
                        </div>



                    <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Quantity')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" value="1" required type="number" name="quantity">
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Add to Cart')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" class="url" value="{{route('ajax.units','_a_c')}}"/>
    
@endsection

@push('script')
<script src="{{asset('assets/admin/js/vendor/jquery-ui.js')}}"></script>
    <script>
         "use strict";
       $(document).ready(function(e)
       {
        
        $('.deleteBtn').click(function () {
            var deleteform=$("#deleteform");     
            deleteform.find('input[name=id]').val($(this).data('id'));
            deleteform.submit();
        });

        $('.cartBtn').click(function () {
               var modal = $('#disableModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.type_name').text($(this).data('type_name'));
                modal.modal('show');
            });

            $('.select2-auto-tokenize').select2({
                tags: true,
                tokenSeparators: [',']
            });
      
            //$( ".datepicker1" ).datepicker();
           
        $(".estate").change(function(e){
     var division=$(this).val();
     if(division=="")
     {
   
     }
     else{
       var url=$(".url").val();
       var uri=url.replace("_a_c",division);
      // alert(uri);
      var departmentselect = document.getElementById('department');     
       clearSelect(departmentselect,"Select Room");
       $.ajax({
               method: 'GET',
               url: uri,
               dataType: 'json',
               success: function(result) {
                 $.each(result, function (key, val) {
                 //alert(JSON.stringify(result));
                   var opt = document.createElement('option');
       opt.value = val.id;
       opt.innerHTML = val.name;
       departmentselect.appendChild(opt);
      

       
                 });         
               },
           });
     }
   
   });
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
