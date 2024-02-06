@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <h3 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('System Logs')</h3>

                      
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Comment')</th>
                                <th>@lang('Remark')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Date')</th>
            
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td data-label="@lang('Equipment')">
                                    <span class="font-weight-bold">{{$log->comment}}</span>
                                   
                                </td>
                                <td data-label="@lang('Reported')">
                                    <span class="font-weight-bold">
                                        {{$log->remark}}</span>
                                </td>
                                <td data-label="@lang('Assigned To')">
                                    <span class="font-weight-bold">
                                        {{$log->user->name}}</span>
                                </td>

                                <td data-label="@lang('Date')">
                                    {{ ($log->created_at) }}||
                                  
                                         {{ diffForHumans($log->updated_at) }}
                                        
      
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
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.disableBtn').on('click', function () {
                //alert($(this).data('type_name'));
                var modal = $('#disableModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.type_name').text($(this).data('type_name'));
                modal.modal('show');
            });




            $('.job_card').on('click', function () {
               

            });
            

        })(jQuery);



    </script>

@endpush

