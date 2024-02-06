@extends($activeTemplate.'layouts.master')
@section('content')
<!-- booking history Starts Here -->
<section class="dashboard-section padding-top padding-bottom">
    <div class="container">
        <div class="dashboard-wrapper">
            <div class="row pb-60 gy-4 justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <p>@lang('Total Invoices')</p>
                            <h3 class="title">{{ __($widget['invoices']) }}</h3>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="las la-ticket-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <p>@lang('Unpaid Invoices')</p>
                            <h3 class="title">{{ __($widget['invoicespaid']) }}</h3>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="las la-ticket-alt"></i>
                        </div>
                    </div>
                </div>
           
            </div>

            <div class="booking-table-wrapper">
                <table class="booking-table">
                    <thead>
                        <tr>
                            
                            <th>@lang('Estate')</th>
                            <th>@lang('House Unit')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('NIN')</th>
                            <th>@lang('District')</th>
                            <th>@lang('Email')</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           
                            <td class="ticket-no" data-label="@lang('Estate')">{{ __($user->estate->name) }}</td>
                            <td class="ticket-no" data-label="@lang('House No')">{{ __($user->unit->name) }}</td>
                            <td class="ticket-no" data-label="@lang('Name')">{{ __($user->name) }}</td>
                            <td class="ticket-no" data-label="@lang('NIN')">{{ __($user->nin) }}</td>
                            <td class="ticket-no" data-label="@lang('District')">{{ __($user->district->districtname) }}</td>
                            
                            <td class="ticket-no" data-label="@lang('Email')">{{ __($user->email) }}</td>
                            
                            
                        </tr>
                       
                    </tbody>
                </table>
            </div>
            @if ($bookedTickets->hasPages())
            {{ paginateLinks($bookedTickets) }}
            @endif
        </div>
    </div>
</section>
<!-- booking history end Here -->

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('Ticket Booking History')</h5>
                <button type="button" class="w-auto btn--close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <a href="" 
                class="btn btn--base btn--md w-100 radius-5 mt-3 pay_button">@lang('Pay
                    Now')</a>
                <button type="button" class="btn btn--danger w-auto btn--sm px-3 close_button" data-bs-dismiss="modal"></i>
                    @lang('Close')
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
    .modal-body p:not(:last-child){
        border-bottom: 1px dashed #ebebeb;
        padding:5px 0;
    }
</style>
@endpush

@push('script')
<script>
    "use strict"

    $('.checkinfo').on('click', function() {
        var info = $(this).data('info');
        var modal = $('#infoModal');

        var url = $(this).data('url');

        var status=info.status;
        if(status==0)
        {
            $(".pay_button").show();
            $(".close_button").hide();
        }
        else{
    
           
            $(".pay_button").hide();
        }
        //the payment button must be written remotely
       
     
       $(".pay_button").attr("href",url);



        var html = '';
        html += `
        <p class="d-flex flex-wrap justify-content-between pt-0"><strong>@lang('Bus Company')</strong> 

<span>${info.trip.company.company_name}</span></p>

                    <p class="d-flex flex-wrap justify-content-between pt-0"><strong>@lang('Journey Date')</strong>  <span>${info.date_of_journey}</span></p>
                    <p class="d-flex flex-wrap justify-content-between"><strong>@lang('PNR Number')</strong>  <span>${info.pnr_number}</span></p>
                    <p class="d-flex flex-wrap justify-content-between"><strong>@lang('Route')</strong>  <span>${info.trip.start_from.name} @lang('to') ${info.trip.end_to.name}</span></p>
                    <p class="d-flex flex-wrap justify-content-between"><strong>@lang('Fare')</strong>  <span>${parseInt(info.sub_total).toFixed(2)} {{ __($general->cur_text) }}</span></p>
                    <p class="d-flex flex-wrap justify-content-between"><strong>@lang('Status')</strong>  <span>${info.status == 1 ? '<span class="badge badge--success">@lang('Successful')</span>' : info.status == 2 ? '<span class="badge badge--warning">@lang('Pending')</span>' : '<span class="badge badge--danger">@lang('Rejected')</span>'}</span></p>
                `;
        modal.find('.modal-body').html(html);
    })
</script>
@endpush