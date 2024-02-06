@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        @if (Auth::guard("admin")->user()->role_id==1 ||Auth::guard("admin")->user()->role_id==2
                        ||Auth::guard("admin")->user()->role_id==5) 

                      
                        <a href="{{ route('admin.repair.create') }}">
                            <span></span>New Defect</a>

                            @endif
                      
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Jobcard')</th>
                                <th>@lang('Equipment')</th>
                                <th>@lang('Reported By')</th>
                                <th>@lang('Person Assigned')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td data-label="@lang('Equipment')">
                                    <span class="font-weight-bold">{{$user->equipment->number_plate}}</span>
                                   
                                </td>
                                <td data-label="@lang('Equipment')">
                                    <span class="font-weight-bold">{{$user->reference_number}}</span>
                                   
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
                                        @if (isset($user->persons_assigned))
                                        {{sizeof($user->persons_assigned)}}
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
                                    @if (Auth::guard("admin")->user()->role_id==1 ||Auth::guard("admin")->user()->role_id==2
                                    ||Auth::guard("admin")->user()->role_id==5) 
            
                                    <a href="{{ route('admin.jobcard.print', $user->id) }}" 
                                        class="icon-btn" data-toggle="tooltip" title="" 
                                        data-original-title="@lang('Print Job')">
                                        <i class="las la-print text--shadow"></i>
                                    </a>
                                    &nbsp
                                    @endif
                                    <a href="{{ route('admin.repair.detail', $user->id) }}" 
                                        class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
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
                    {{ paginateLinks($users) }}
                </div>
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
                <form action="{{ route('admin.repair.delete')}}" method="POST">
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
    <form action="{{ route('admin.repair.list', $scope ?? str_replace('admin.users.', '
    ', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Reference Number')"
             value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush



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

        })(jQuery);



    </script>

@endpush

