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
                                <th>@lang('Client Name')</th>
                                <th>@lang('Contact')</th>
                                <th>@lang('Hotel/Room')</th>
                                   <th>@lang('Start Date')</th>
                                    <th>@lang('End Date')</th>
                                       <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                <td data-label="@lang('Estate')">
                                        {{ __($item->name) }}:
                                      
                                    </td>
                                    <td data-label="@lang('Phone')">
                                        {{ __($item->telephone) }}:
                                      
                                    </td>
                                    <td data-label="@lang('Estate')">
                                        {{ __($item->hotel->name) }}:
                                   <br/>
                                        {{ __($item->room->name) }}
                                    </td>

                                    <td data-label="@lang('Description')">
                                        {{ __($item->start_date) }}
                                    </td>
                                    <td data-label="@lang('Description')">
                                        {{ __($item->end_date) }}
                                    </td>
                                 
                                    <td data-label="@lang('Action')">
                                        
                                    <a href="{{ route('admin.pos.roomservice', $item->id) }}"
                                             class="icon-btn" data-toggle="tooltip" title=""
                                         data-original-title="@lang('Add Cart')">
                                        <i class="las la-shopping-cart text--shadow"></i>
                                    </a>
                                        <button type="button" class="icon-btn ml-1 editBtn"
                                                data-toggle="modal" data-target="#editModal"
                                                data-expense = "{{ $item }}"
                                                data-original-title="@lang('Update')">
                                            <i class="la la-eye"></i>
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
                    <h5 class="modal-title"> @lang('New Checking')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pos.checkingstore')}}" method="POST"
                enctype="multipart/form-data"
                >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Customer Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter  Name')" name="name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Telephone')</label>
                            <input type="text" class="form-control" placeholder="@lang('Telephone')" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Hotel')</label>
                            <div class="input-group">
                                <select class="select2-basic estate" name="hotel_id" required>
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
                             name="room_id" id="department" required >
                                <option value="">Select Unit</option>
                            </select>
                        </div>


                        <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Start Date') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control datepicker1" type="text" name="startdate" />
                                </div>
                         </div>
                       

                         <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('End Date') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control datepicker1" type="text" name="enddate" />
                                </div>
                         </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Checking')</button>
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
                    <h5 class="modal-title"> @lang('Edit Checking')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST"
                enctype="multipart/form-data"
                >
                    @csrf
                    <input type="text" name="id" hidden="true">
                    
                    <div class="modal-body">
                        
                    <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Customer Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter  Name')" name="name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Telephone')</label>
                            <input type="text" class="form-control" placeholder="@lang('Telephone')" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Hotel')</label>
                            <div class="input-group">
                                <select class="select2-basic estate" name="hotel_id" required>
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
                             name="room_id" id="department2" required >
                                <option value="">Select Unit</option>
                            </select>
                        </div>


                        <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Start Date') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control datepicker1" type="text" name="startdate" />
                                </div>
                         </div>
                       

                         <div class="form-group">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('End Date') 
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control datepicker1" type="text" name="enddate" />
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
                <form action="{{ route('admin.pos.removechecking')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete') <span class="font-weight-bold type_name"></span> @lang(' Checking')?</p>
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
               var link    = `{{ route('admin.pos.updatecheckin', '') }}/${data.id}`;
                modal.find('input[name=name]').val(data.name);
                modal.find('input[name=telephone]').val(data.telephone);
                modal.find('input[name=startdate]').val(data.start_date);
                modal.find('input[name=enddate]').val(data.end_date);
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
