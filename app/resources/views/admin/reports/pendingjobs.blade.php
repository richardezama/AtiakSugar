@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
        <div class="col-md-6 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--success">
            <div class="widget-two__content">
                <h2 class="text-white"> {{ $completed}}</h2>
                <p class="text-white">@lang('Completed Jobs')</p>
            </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-md-6 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--6">
                <div class="widget-two__content">
                    <h2 class="text-white">{{ showAmount($pending) }}</h2>
                    <p class="text-white">@lang('Pending Jobs')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
       

    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Equipment')</th>
                            <th>@lang('Reported By')</th>
                            <th>@lang('Person Assigned')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Logs')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($jobs as $user)
                        <tr>
                            <td data-label="@lang('Equipment')">
                                <span class="font-weight-bold">{{$user->equipment->number_plate}}</span>
                               
                            </td>
                            <td data-label="@lang('Reported')">
                                <span class="font-weight-bold">
                                    @if (isset($user->operator))
                                    {{$user->operator->name}}
                                    @endif
                                  </span>
                            </td>
                            <td data-label="@lang('Assigned To')">
                                <span class="font-weight-bold">
                                    @if (isset($user->assigned))
                                    {{$user->assigned->name}}
                                    @endif
                                    </span>
                            </td>

                            <td data-label="@lang('Status')">
                                @include('admin.vehicles.status')
                            </td>
                            <td data-label="@lang('Date')">
                              
                                     {{ diffForHumans($user->updated_at) }}
                                    
                              
                              
                            </td>

                            <td data-label="@lang('Action')">
                                <a href="{{ route('admin.repair.logs', $user->id) }}" 
                                    class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Logs')">
                                    <i class="las la-desktop text--shadow"></i>
                                </a>
                                <!--<button type="button"
                                            class="icon-btn btn--danger ml-1 disableBtn"
                                            data-toggle="modal" data-target="#disableModal"
                                            data-id="{{ $user->id }}"
                                            data-type_name="{{ $user->defect_reported }}"
                                            data-original-title="@lang('Disable')">
                                            <i class="la la-trash"></i>
                                        </button>-->
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
                {{ paginateLinks($jobs) }}
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<!--
        <form action="{{route('admin.reports.pending', $scope 
        ?? str_replace('admin.deposit.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
            <div class="input-group has_append  ">
                <input type="text" name="search" class="form-control" placeholder="@lang('Trx number/Username')" value="{{ $search ?? '' }}">
                <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')" autocomplete="off" value="{{ @$dateSearch }}">
              
                <select class="select2-basic" name="status" required>
                    <option value="4">@lang('All')</option>
                    <option value="0">@lang('Pending')</option>
                    <option value="1">@lang('Pending')</option>
                    <option value="3">@lang('Rejected')</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn--primary" type="export" value="1" name="export">Export</button>
                </div>
            </div>
        </form>

        -->

        

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
