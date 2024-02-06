@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <a href="{{ route('admin.users.addadmin') }}">
                            <span></span>New Admin</a>
                            <hr/>
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Email-Phone')</th>
                                <th>@lang('Role')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold">{{$user->name}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.admindetail', $user->id) }}">
                                        <span>@</span>{{ $user->username }}</a>
                                    </span>
                                </td>


                                <td data-label="@lang('Email-Phone')">
                                    {{ $user->email }}<br>{{ $user->telephone }}
                                </td>
                                <td data-label="@lang('Role')">
                                    <span 
                                     data-original-title="{{ @$user->role["name"] }}">
                                     {{ @$user->role["name"] }}
                                    </span>
                                </td>


                                <td data-label="@lang('Joined At')">
                                    {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                </td>

                                <td data-label="@lang('Action')">
                                   
                                
                                    <a href="{{ route('admin.users.admindetail', $user->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                    &nbsp
                                    <a href="{{ route('admin.users.reset', $user->id) }}" 
                                        class="icon-btn" data-toggle="tooltip" title=""
                                        data-original-title="@lang('Reset')">
                                       <i class="las la-lock text--shadow"></i>
                                   </a>
                                    <button type="button"
                                                class="icon-btn btn--danger ml-1 disableBtn"
                                                data-toggle="modal" data-target="#disableModal"
                                                data-id="{{ $user->id }}"
                                                data-type_name="{{ $user->name }}"
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
                <form action="{{ route('admin.users.deleteadmin')}}" method="POST">
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
    <form action="{{ route('admin.users.adminsearch', $scope ?? str_replace('admin.users.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or email')" value="{{ $search ?? '' }}">
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

