@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                <th>@lang('Receipt No')</th>
                                    <th>@lang('Estate')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Amout')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                <td data-label="@lang('Estate')">
                                        {{ __($item->receipt_no) }}:
                                        @if($item->photo!="")
                                        <a href="{{asset('uploads/'.$item->photo)}}" target="_blank">image</a>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Estate')">
                                        {{ __($item->estate->name) }}:
                                   <br/>
                                        {{ __($item->unit->name) }}
                                    </td>

                                    <td data-label="@lang('Description')">
                                        {{ __($item->description) }}
                                    </td>
                                    <td data-label="@lang('Type')">
                                        {{ __($item->type->name) }}
                                    </td>
                                    <td data-label="@lang('Amount')">
                                        <strong data-toggle="tooltip" data-original-title="@lang('Amount')">
                                            {{ showAmount($item->amount) }} {{ __($general->cur_text) }}
                                            </strong>
                                    </td>
                                   
<!--
                                    <td data-label="@lang('Staff')">
                                        {{ __($item->staff->name) }}
                                    </td>-->
                                    <td data-label="@lang('Date')">
                                        {{ showDateTime($item->created_at) }}<br>{{ diffForHumans($item->created_at) }}
                                    </td>

                                    
                                    <td data-label="@lang('Action')">
                                        <button type="button" class="icon-btn ml-1 editBtn"
                                                data-toggle="modal" data-target="#editModal"
                                                data-expense = "{{ $item }}"
                                                data-original-title="@lang('Update')">
                                            <i class="la la-pen"></i>
                                        </button>

                                      
                                            <button type="button"
                                                class="icon-btn btn--danger ml-1 disableBtn"
                                                data-toggle="modal" data-target="#disableModal"
                                                data-id="{{ $item->id }}"
                                                data-type_name="{{ $item->name }}"
                                                data-original-title="@lang('Disable')">
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
                    </div>
                </div>

                <div class="card-footer py-4">
                    {{ paginateLinks($items) }}
                </div>
            </div>
        </div>
    </div>


  
    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Expense')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.expenses.store')}}" method="POST"
                enctype="multipart/form-data"
                >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Amount')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter  Amount')" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Type')</label>
                            <div class="input-group">
                                <select class="select2-basic" name="type" required>
                                    <option value="">@lang('Type')</option>
                                    @foreach ($types as $item)
                                        <option value="{{ $item->id }}" 
                                        >{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
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

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Unit')</label>
                            <select class="department form-control form_values select2-basic"
                             name="unit_id" id="department" required >
                                <option value="">Select Unit</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Description')</label>
                            <textarea type="text" class="form-control" placeholder="@lang('Description')" 
                            name="description" required></textarea>
                        </div>

                        <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Expense Date') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control datepicker1" type="text" name="date" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Receipt') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="receipt" />
                                </div>
                            </div>

                             
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Photo')</label>
                            <input type="file" class="form-control"  name="uploadfile"
                             />
                        </div>



                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Add METHOD MODAL --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Edit Expense')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.expenses.store')}}" method="POST"
                enctype="multipart/form-data"
                >
                    @csrf
                    <input type="text" name="id" hidden="true">
                    
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Amount')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter  Amount')" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Type')</label>
                            <div class="input-group">
                                <select class="select2-basic" name="type" required>
                                    <option value="">@lang('Type')</option>
                                    @foreach ($types as $item)
                                        <option value="{{ $item->id }}" 
                                        >{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
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

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Unit')</label>
                            <select class="department form-control form_values select2-basic"
                             name="unit_id" id="department2" required >
                                <option value="">Select Unit</option>
                            </select>
                        </div>


                           <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Expense Date') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control datepicker1" type="text" name="date" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Receipt') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="receipt" />
                                </div>
                            </div>
                    

                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- delete METHOD MODAL --}}
    <div id="disableModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Remove')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.expenses.remove')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete') <span class="font-weight-bold type_name"></span> @lang('Expense')?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" class="url" value="{{route('ajax.units','_a_c')}}"/>
    
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBtn">
        <i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
<script src="{{asset('assets/admin/js/vendor/jquery-ui.js')}}"></script>


    <script>
        (function ($) {
            "use strict";
            $( ".datepicker1" ).datepicker();
            $('.disableBtn').on('click', function () {
                var modal = $('#disableModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.type_name').text($(this).data('type_name'));
                modal.modal('show');
            });

            $('.activeBtn').on('click', function () {
                var modal = $('#activeModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.type_name').text($(this).data('type_name'));
                modal.modal('show');
            });

            $('.addBtn').on('click', function () {
                $('.showSeat').empty();
                var modal = $('#addModal');
                modal.modal('show');
            });

            $('.select2-auto-tokenize').select2({
                tags: true,
                tokenSeparators: [',']
            });


            $(document).on('click', '.editBtn', function () {
                var modal   = $('#editModal');
                var data    = $(this).data('expense');
               var link    = `{{ route('admin.expenses.update', '') }}/${data.id}`;
                 modal.find('input[name=amount]').val(data.amount);
                modal.find('input[name=expense_type]').val(data.expense_type);
                modal.find('input[name=description]').val(data.description);
                modal.find('input[name=receipt]').val(data.receipt_no);
                modal.find('input[name=date]').val(data.expense_date);
                modal.find('input[name=id]').val(data.id);
                modal.find('form').attr('action', link);
                var fields =``;
                
                $('.showSeat').html(fields);
                modal.modal('show');
            });

            $('input[name=deck]').on('input', function(){
                $('.showSeat').empty();
                for(var deck=1; deck<= $(this).val(); deck++){
                    $('.showSeat').append(`
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> Seats of Deck - ${deck} </label>
                            <input type="text" class="form-control" placeholder="@lang('Enter Number of Seat')" name="deck_seats[]" required>
                        </div>
                    `);
                }
            })

        })(jQuery);


        $(".estate").change(function(e){
     division=$(this).val();
     if(division=="")
     {
   
     }
     else{
       var url=$(".url").val();
       var uri=url.replace("_a_c",division);
      // alert(uri);
       departmentselect = document.getElementById('department');    
       departmentselect2 = document.getElementById('department2');    
       clearSelect(departmentselect2,"Select Unit");
       clearSelect(departmentselect,"Select Unit");
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
      
      var opt2 = document.createElement('option');
       opt2.value = val.id;
       opt2.innerHTML = val.name;
       departmentselect2.appendChild(opt2);

       
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
