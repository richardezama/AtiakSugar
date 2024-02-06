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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Telephone')</th>   
                                    <th>@lang('Address')</th>    
                                    <th>@lang('Action')</th>                               
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td data-label="@lang('Name')">
                                        {{ __($item->name) }}
                                    </td>   
                                    <td data-label="@lang('Name')">
                                        {{ __($item->telephone) }}
                                    </td>   
                                    <td data-label="@lang('Name')">
                                        {{ __($item->address) }}
                                    </td>   
                                    <td data-label="@lang('Action')">
                                       

                                        <button type="button"
                                                class="icon-btn btn--danger ml-1 disableBtn"
                                                data-toggle="modal" data-target="#disableModal"
                                                data-id="{{ $item->id }}"
                                                data-fleet_type = "{{ $item }}"
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
                    <h5 class="modal-title"> @lang('Add Hospital')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.estate.storecompany')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter  Name')" name="name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Telephone')</label>
                            <input type="text" class="form-control" placeholder="@lang('Telephone')" name="telephone" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Address')</label>
                            <input type="text" class="form-control" placeholder="@lang('Address')" name="address" required>
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
                    <h5 class="modal-title"> @lang('Update Hospital')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" >
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter  Name')" name="name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label font-weight-bold"> @lang('Telephone')</label>
                        <input type="text" class="form-control" placeholder="@lang('Telephone')" name="telephone" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label font-weight-bold"> @lang('Address')</label>
                        <input type="text" class="form-control" placeholder="@lang('Address')" name="address" required>
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
                <form action="{{ route('admin.estate.deletecompany')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete') <span class="font-weight-bold type_name"></span> @lang('Estate Type')?</p>
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
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')

    <script>
        (function ($) {
            "use strict";

            $('.disableBtn').on('click', function () {
                var modal = $('#disableModal');
                modal.find('input[name=id]').val($(this).data('id'));
               // modal.find('.type_name').text($(this).data('type_name'));
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
                var link    = `{{ route('admin.estate.updatecompany', '') }}/${data.id}`;
                var deckNumber = data.deck;
                modal.find('input[name=name]').val(data.name);
                modal.find('input[name=telephone]').val(data.telephone);
                modal.find('input[name=address]').val(data.address);
                modal.find('input[name=id]').val(data.id);
                modal.find('form').attr('action', link);
                var fields =``;

                $.each(data.deck_seats, function (i, val) {
                    fields +=`<div class="form-group">
                            <label class="form-control-label font-weight-bold"> Seats of Deck - ${i + 1} </label>
                            <input type="text" class="form-control" value="${val}" placeholder="@lang('Enter Number of Seat')" name="deck_seats[]" required>
                        </div>`;
                });
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
