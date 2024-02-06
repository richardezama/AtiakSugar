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
                                    <th>@lang('Requester')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                   
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                   
                                    <td data-label="@lang('Name')">
                                        @if(isset($item->user))
                                        {{ __($item->user->name) }}
                                        @else
                                        No User How
                                        @endif
                                    </td>
                                    <td data-label="@lang('Description')">
                                        {{ __($item->comment) }}
                                    </td>

                                    <td data-label="@lang('Status')">
                                  
                                    @if($item->status == 1)
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Pending Supervisor')</span>
                                    
                                    @elseif($item->status == 2)
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Pending Approval 2')</span>
                                    @elseif($item->status == 3)
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Pending Supervisor')</span>
                                  
                                    @elseif($item->status == 4)
                                    <span class="text--small badge font-weight-normal badge--success">@lang('Approved')</span>
                                    @elseif($item->status == 5)
                                    <span class="text--small badge font-weight-normal badge--danger">@lang('Rejected')</span>
                              

                                    @else
                                    <span class="text--small badge font-weight-normal badge--success">@lang('Processed')</span>
                                    @endif
                                    </td>


                                    <td>
                                        {{ showDateTime($item->created_at) }} <br> {{ diffForHumans($item->created_at) }}
                             
                                    </td>
                                   
                                    
                                    
                                    <td data-label="@lang('Action')">
                                      
                                        <!--
                                        <button type="button" class="icon-btn ml-1 editBtn"
                                                data-toggle="modal" data-target="#editModal"
                                                data-fleet_type = "{{ $item }}"
                                                data-original-title="@lang('Update')">
                                            <i class="la la-pen"></i>
                                        -->

                                            <a href="{{ route('admin.requisitions.detail', $item->id) }}" 
                                                class="icon-btn" data-toggle="tooltip" title=""
                                                data-original-title="@lang('Details')">
                                               <i class="las la-desktop text--shadow"></i>
                                           </a>
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
                    <h5 class="modal-title"> @lang('Send Request')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.drugs.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">
                                 @lang('Type')</label>
                            <select class="form-control select2-basic" name="type">
                                <option value="">@lang('Select Type')</option>
                                <option value="Refill">@lang('Refill')</option>
                                <option value="Emergency">@lang('Emergency')</option>
                               
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> 
                                @lang('Location')</label>
                            <input type="text" class="form-control" 
                            placeholder="@lang('Location')" name="location" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> 
                                @lang('Description')</label>
                            <textarea type="text" class="form-control" 
                            placeholder="@lang('Description')" name="description" required></textarea>
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

    {{-- Update METHOD MODAL --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Update Request')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" >
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">
                                 @lang('Type')</label>
                            <select class="form-control select2-basic" name="status">
                                <option value="">@lang('Select Status')</option>
                                <option value="1">@lang('Accept')</option>
                                <option value="0">@lang('Reject')</option>
                               
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> 
                                @lang('Comment')</label>
                            <textarea type="text" class="form-control" 
                            placeholder="@lang('Comment')" name="comment" required></textarea>
                        </div>
                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
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
                <form action="{{ route('admin.drugs.disable')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete') <span class="font-weight-bold type_name"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
@endsection

@push('breadcrumb-plugins')
    <!-- <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
    -->
@endpush

@push('script')

    <script>
        (function ($) {
            "use strict";

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
                var data    = $(this).data('fleet_type');
                var link    = `{{ route('admin.requisitions.update', '') }}/${data.id}`;
              
                //alert(data.estate_id);
                /*modal.find('input[name=name]').val(data.name);
                modal.find('input[name=bedrooms]').val(data.bedrooms);
                modal.find('input[name=bathrooms]').val(data.bathrooms);
                modal.find('input[name=floor]').val(data.floor);
                modal.find('input[name=block]').val(data.block);
                modal.find('input[name=rent]').val(data.rent);
                modal.find('input[name=umeme]').val(data.umeme);
                modal.find('input[name=nwsc]').val(data.nwsc);*/
                modal.find('input[name=id]').val(data.id);
                //$(".esty").val(data.estate_id);
                $("#select option[value=1]").attr('selected', 'selected');
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

    </script>

@endpush
