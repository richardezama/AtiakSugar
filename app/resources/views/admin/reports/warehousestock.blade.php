@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">

    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Warehouse')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('Stock Balance')</th>
                            <th>@lang('Action')</th>
                          
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td data-label="@lang('Equipment')">
                              
                                <span class="font-weight-bold">{{$item->warehouse}}</span>
                            </td>
                            <td data-label="@lang('Equipment')">
                              
                                <span class="font-weight-bold">{{$item->product}}</span>
                            </td>

                            <td data-label="@lang('Equipment')">
                                <span class="font-weight-bold">{{$item->total}}</span>
                               
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
                {{ paginateLinks($items) }}
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
        <form action="{{route('admin.reports.warehousebalance', $scope 
        ?? str_replace('admin.deposit.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
            <div class="input-group has_append  ">
                <input type="text" name="search" class="form-control" placeholder="@lang('Name')" value="{{ $search ?? '' }}">
                <input name="date" type="text" data-range="true" 
                data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')" autocomplete="off" value="{{ @$dateSearch }}">
            
                <div class="input-group-append">
                    <button class="btn btn--primary" type="export" value="1" name="search">Search</button>
                    &nbsp
                    <button class="btn btn--success" type="export" value="1" name="export">Export</button>
                </div>
            </div>
        </form>


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
    })(jQuery)
  </script>
@endpush
