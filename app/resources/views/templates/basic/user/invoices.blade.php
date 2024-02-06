@extends($activeTemplate.'layouts.master')
@section('content')
<!-- booking history Starts Here -->
<section class="dashboard-section padding-top padding-bottom">
    <div class="container">
        <div class="dashboard-wrapper">
            <div class="booking-table-wrapper">
                <div class="col-md-12">
                    <div class="card b-radius--10">
                        <div class="card-body p-0">
                            <div class="table-responsive--sm table-responsive" id="element-to-print">
                                <table class="table table--light style--two">
                                    <thead>
                                    <tr>
                                        <th>@lang('Period')</th>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Estate')</th>
                                        <th>@lang('Unit')</th>
                                        <th>@lang('User')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Month')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($invoices as $deposit)
                                       
                                        <tr>
                                            <td data-label="@lang('Period')">                               
                                                <small> {{ $deposit->period }} </small>
                                           </td>
                                            <td data-label="@lang('Trx')">                               
                                                 <small> {{ $deposit->invoice_code }} </small>
                                            </td>
            
                                            <td data-label="@lang('Date')">
                                                {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                            </td>
                                            <td data-label="@lang('Estate')">                               
                                                <small> {{ $deposit->estate->name }} </small>
                                           </td>
                                           <td data-label="@lang('Unit')">                               
                                            <small> {{ $deposit->unit->name }} </small>
                                       </td>
                                            <td data-label="@lang('User')">
                                                <span class="font-weight-bold">{{ __($deposit->user->name) }}</span>
                                                <br>
                                                <span class="small">
                                                <a href="{{ route('admin.users.detail', $deposit->user_id) }}"><span>@</span>{{ __($deposit->user->username) }}</a>
                                                </span>
                                            </td>
                                            <td data-label="@lang('Amount')">
                                               
                                                <strong data-toggle="tooltip" data-original-title="@lang('Total Rent')">
                                                {{ showAmount($deposit->amount+$deposit->total) }} {{ __($general->cur_text) }}
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
                                            <td data-label="@lang('Action')" class="print" style="cursor: pointer;">
                                               Print
                                                    <i class="la la-print"></i>
                                                </a>
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
            @if ($invoices->hasPages())
                {{ paginateLinks($invoices) }}
            @endif
        </div>
    </div>
</section>
<!-- booking history end Here -->

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('Pay')</h5>
                <button type="button" class="w-auto btn--close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
            </div>
            <div class="modal-body p-4">
                
            </div>
            <div class="modal-footer">
                <a href="" 
                class="btn btn--base btn--md w-100 radius-5 mt-3 pay_button">@lang('Pay
                    Now')</a>

                <button type="button" class="btn btn--danger w-auto btn--sm px-3  close_button" data-bs-dismiss="modal">
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
<script src="{{asset($activeTemplateTrue.'js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/html2pdf.bundle.min.js')}}"></script>
<script>
    const options = {
            margin: 0.3,
            filename: `{{ $fileName }}`,
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'A4',
                orientation: 'landscape'
            }
        }

   $(document).ready(function(e){

$('.print').on('click', function() {
var element = document.getElementById('element-to-print');
//html2pdf(element);
html2pdf().from(element).set(options).save();

});
})
      
    /*
    "use strict"

    $('.checkinfo').on('click', function() {
        var info = $(this).data('info');
        var url = $(this).data('url');

        var modal = $('#infoModal');
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
       var url2=$(".pay_button").attr("href");
       //problem solved
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
    */
</script>

