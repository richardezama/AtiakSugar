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
                            <th>@lang('Status')</th>
                            <th>@lang('Total')</th>
                            <th>@lang('Action')</th>
                          
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($jobs as $user)
                        <tr>
                            <td data-label="@lang('Equipment')">
                                @if($user->status == 9)
                                <span class="badge badge--success">{{$user->name}}</span>
                            @else
                            <span class="badge badge--warning"></span>
                            <span class="badge badge--warning">{{$user->name}}</span>
                          
                            @endif
                               
                            </td>

                            <td data-label="@lang('Equipment')">
                                <span class="font-weight-bold">{{$user->total}}</span>
                               
                            </td>
                           


                            <td data-label="@lang('Action')">
                                <a href="{{ route('admin.repair.list', ['status'=>$user->status]) }}" 
                                    class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('View')">
                                    <i class="las la-eye text--shadow"></i>
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
