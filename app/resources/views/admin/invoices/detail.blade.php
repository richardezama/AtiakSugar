@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Estate') {{ __(@$invoice->estate->name) }}</h5>
                    <h6 class="mb-20 text-muted">@lang('Unit') {{ __(@$invoice->unit->name) }}</h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date')
                            <span class="font-weight-bold">{{ showDateTime($invoice->created_at) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Transaction Number')
                            <span class="font-weight-bold">{{ $invoice->invoice_code }}</span>
                        </li>
                      
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">
                                <a href="{{ route('admin.users.detail', $invoice->user_id) }}">{{ @$invoice->user->username }}</a>
                            </span>
                        </li>
                       
                      
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="font-weight-bold">{{ showAmount($invoice->total ) }} {{ __($general->cur_text) }}</span>
                        </li>
                     
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($invoice->status == 2)
                                <span class="badge badge-pill bg--warning">@lang('Pending')</span>
                            @elseif($invoice->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Paid')</span>
                                @elseif($invoice->status == 0)
                                <span class="badge badge-pill bg--warning">@lang('Pending')</span>
                            @elseif($invoice->status == 3)
                                <span class="badge badge-pill bg--danger">@lang('Rejected')</span>
                            @endif
                        </li>
                        
                    </ul>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Invoice Control')</h5>
                 
                    @if($invoice->status == 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn--success ml-1 approveBtn"
                                        data-id="{{ $invoice->id }}"                                     
                                        data-amount="{{ showAmount($invoice->total)}} {{ __($general->cur_text) }}"
                                        data-username="{{ @$invoice->user->username }}"
                                        data-total="{{ @$invoice->balance }}"
                                        data-toggle="tooltip" 
                                        data-original-title="@lang('Add Money')"><i class="fas fa-check"></i>
                                    @lang('Add Money')
                                </button>

                                <button class="btn btn--danger ml-1 rejectBtn"
                                        data-id="{{ $invoice->id }}"
                                       data-amount="{{ showAmount($invoice->total)}} {{ __($general->cur_text) }}"
                                        data-username="{{ @$invoice->user->username }}"
                                        data-toggle="tooltip" data-original-title="@lang('Reject')"><i class="fas fa-ban"></i>
                                    @lang('Reject')
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Approve Invoice Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{route('admin.invoice.pay')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label class="form-control-label font-weight-bold"> 
                            @lang('Amount')</label>
                        <input type="text" class="form-control" 
                        placeholder="@lang('Amount')" name="amount" required>
                    </div>
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('Add Payment')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('deposit of') <span class="font-weight-bold withdraw-user"></span>?</p>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Add Payment')</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Deposit Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.invoice.reject')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('reject')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('deposit of') <span class="font-weight-bold withdraw-user"></span>?</p>

                        <div class="form-group">
                            <label class="font-weight-bold mt-2">@lang('Reason for Rejection')</label>
                            <textarea name="message" id="message" placeholder="@lang('Reason for Rejection')" class="form-control" rows="5"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Reject')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            
            $('.approveBtn').on('click', function () {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=amount]').val($(this).data('total'));
                modal.modal('show');
            });

            $('.rejectBtn').on('click', function () {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-user').text($(this).data('username'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
