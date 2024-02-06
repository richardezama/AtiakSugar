@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
    @if(request()->routeIs('admin.invoice.list') 
    || request()->routeIs('admin.invoice.method'))
        <div class="col-md-6 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--success">
            <div class="widget-two__content">
                <h2 class="text-white">{{ __($general->cur_sym) }} {{ showAmount($paid) }}</h2>
                <p class="text-white">@lang('Fully Cleared Invoices')</p>
            </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-md-6 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--6">
                <div class="widget-two__content">
                    <h2 class="text-white">{{ __($general->cur_sym) }} {{ showAmount($pending) }}</h2>
                    <p class="text-white">@lang('Balance')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
       
    @endif

    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Trx')</th>
                             <th>@lang('Estate')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Paid')</th>
                            <th>@lang('Balance')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($invoices as $deposit)
                           
                            <tr>
                                <td data-label="@lang('Trx')">                               
                                     <small> {{ $deposit->invoice_code }} </small><br/>
                                     {{ showDateTime($deposit->created_at) }}<br>
                                     {{ diffForHumans($deposit->created_at) }}
                               
                                </td>

                                <td data-label="@lang('Estate')"> 
                                    
                                    @if(isset($deposit->estate))
{{$deposit->user->name}}
<small> {{ $deposit->estate->name }}/ <small> {{ $deposit->unit->name }} </small> </small>
                        
@endif

                                </td>
                               
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold">
@if(isset($deposit->user))
{{$deposit->user->name}}
@endif

                                    </span>
                                    <br>
                                    <span class="small">
                                    @if(isset($deposit->user))
                                    <a href="{{ route('admin.users.detail', $deposit->user_id) }}">
                                        <span>@</span>
                                        
                                        {{ __($deposit->user->username) }}</a>
                                        @endif
                                    </span>
                                </td>
                                <td data-label="@lang('Amount')">
                                   
                                    <strong data-toggle="tooltip" data-original-title="@lang('Total Rent')">
                                    {{ showAmount($deposit->total) }} {{ __($general->cur_text) }}
                                    </strong>
                                </td>
                                <td data-label="@lang('Amount')">
                                   
                                   <strong data-toggle="tooltip" data-original-title="@lang('Total Rent')">
                                   {{ showAmount($deposit->amountpaid) }} {{ __($general->cur_text) }}
                                   </strong>
                               </td>
                               <td data-label="@lang('Amount')">
                                   
                                   <strong data-toggle="tooltip" data-original-title="@lang('Total Rent')">
                                   {{ showAmount($deposit->balance) }} {{ __($general->cur_text) }}
                                   </strong>
                               </td>
                                <td data-label="@lang('Month')">                               
                                    <small> {{ $deposit->period }} </small>
                               </td>
                                <td data-label="@lang('Status')">
                                    @if($deposit->status == 0)
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($deposit->status == 1)
                                        <span class="badge badge--success">@lang('Cleared')</span>
                                         <br>{{ diffForHumans($deposit->updated_at) }}
                                        
                                    @elseif($deposit->status == 3)
                                        <span class="badge badge--danger">@lang('Cancelled')</span>
                                        <br>{{ diffForHumans($deposit->updated_at) }}
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.invoice.details', $deposit->id) }}"
                                       class="icon-btn ml-1 " data-toggle="tooltip" title="" 
                                       data-original-title="@lang('Detail')">
                                        <i class="la la-desktop"></i>

                                    </a>
                                    <button type="button"
                                                class="icon-btn btn--danger ml-1 disableBtn"
                                                data-toggle="modal" data-target="#disableModal"
                                                data-id="{{ $deposit->id }}"
                                                data-invoice = "{{ $deposit }}"
                                                data-type_name="{{ $deposit->name }}"
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
                    </table><!-- table end -->
                </div>
            </div>
            <div class="card-footer py-4">
                {{ paginateLinks($invoices) }}
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
        <form action="{{route('admin.invoice.search', $scope 
        ?? str_replace('admin.deposit.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
            <div class="input-group has_append  ">
                <input type="text" name="search" class="form-control" placeholder="@lang('Trx number/Username')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <form action="{{route('admin.invoice.search',$scope ?? str_replace('admin.deposit.', 
        '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append ">
                <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')" autocomplete="off" value="{{ @$dateSearch }}">
                <input type="hidden" name="method" value="{{ @$methodAlias }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

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
                <form action="{{ route('admin.invoice.delete')}}" method="POST">
                    @csrf
                    <input type="text" name="id" hidden="true">
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete') <span class="font-weight-bold type_name"></span> @lang('Invoice')?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endpush


@push('script-lib')
  <script src="{{ asset('assets/global/js/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/global/js/datepicker.en.js') }}"></script>
@endpush
@push('script')
  <script>

    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
        $('.disableBtn').on('click', function () {
                var modal = $('#disableModal');
                modal.find('input[name=id]').val($(this).data('id'));
               // modal.find('.type_name').text($(this).data('type_name'));
                modal.modal('show');
            });
    })(jQuery)
  </script>
@endpush
