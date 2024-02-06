@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">                  
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Operator')</th>
                                <th>@lang('Make')</th>
                                <th>@lang('Model')</th>
                                <th>@lang('Chasis')</th>
                                <th>@lang('Number pLate')</th>
                                <th>@lang('Odometer')</th>
                                <th>@lang('Next Service')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold">{{$user->name}}</span>
                                   
                                </td>

                                <td data-label="@lang('User')">
                                    @if (isset($user->operator))
                                    <span class="font-weight-bold">{{$user->operator->name}}</span>
                                    @else
                                    No operator
                                    @endif
                                    
                                   
                                </td>


                                <td data-label="@lang('Make')">
                                  {{ $user->make->name }}
                                </td>
                                <td data-label="@lang('Model')">
                                    <span>
                                        {{ $user->model->name }}
                                    </span>
                                </td>
                                <td data-label="@lang('Chasis')">
                                    <span>
                                        {{ $user->chasis }}
                                    </span>
                                </td>
                                <td data-label="@lang('Plate')">
                                    <span>
                                        {{ $user->number_plate }}
                                    </span>
                                </td>
                                <td data-label="@lang('Odometer')">
                                    <span>
                                        {{ $user->odometer }}
                                    </span>
                                </td>
                                <td data-label="@lang('Next Service')">
                                    <span>
                                        {{ $user->next_service }}
                                    </span>
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
                <form action="{{ route('admin.vehicles.delete')}}" method="POST">
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


<!--
@push('breadcrumb-plugins')
    <form action="{{ route('admin.users.adminsearch', $scope ?? str_replace('admin.users.', '
    ', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Name/Make')"
             value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
-->


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

