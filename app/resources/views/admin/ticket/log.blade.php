@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Vehicle')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('PNR Number')</th>
                                    <th>@lang('Journey Date')</th>                                  
                                    <th>@lang('Status')</th>
                                    <th>@lang('Ticket Count')</th>
                                    <th>@lang('Fare')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $item)
                                <tr>
                                    <td data-label="@lang('PNR Number')">
                                        <span class="text-muted">{{ __($item->vehicle->nick_name) }}</span><br/>
                                        <img width="80" class="img-rouded" 
                                        height="70" src="{{asset('storage/')."/".$item->vehicle->logo}}"/>
                                        &nbsp   
                                    </td>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ __(@$item->user->fullname) }}</span>
                                    <br>
                                    <span class="small"> <a href="{{ route('admin.users.detail', $item->user_id) }}"><span>@</span>{{ __(@$item->user->username) }}</a> </span>

                                    </td>
                                    <td data-label="@lang('PNR Number')">
                                        <span class="text-muted">{{ __($item->pnr_number) }}</span>
                                    </td>
                                    <td data-label="@lang('Journey Date')">
                                        {{ __(showDateTime($item->date_of_journey, 'd M, Y')) }}
                                    </td>
                                  
                                   
                                    <td data-label="@lang('Status')">
                                        @if ($item->status == 1)
                                            <span class="badge badge--success font-weight-normal text--samll">@lang('Booked')</span>
                                        @elseif($item->status == 2)
                                            <span class="badge badge--warning font-weight-normal text--samll">@lang('Pending')</span>
                                       
                                            @elseif($item->status == 0)
                                            <span class="badge badge--warning font-weight-normal text--samll">@lang('Not Processed')</span>            
                                            @else
                                            <span class="badge badge--danger font-weight-normal text--samll">@lang('Rejected')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Fare')">
                                      {{ __($item->seats) }}
                                    </td>


                                    <td data-label="@lang('Fare')">
                                        {{ __(showAmount($item->sub_total)) }} {{ __($item->vehicle->cost) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($tickets) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
<form action="{{route('admin.vehicle.ticket.search', $scope ?? str_replace('admin.vehicle.ticket.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('Search PNR Number')" value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>
@endpush
