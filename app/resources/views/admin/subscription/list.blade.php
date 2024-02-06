@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="row">
                    @forelse($types as $item)  
                    <div class="col-sm-3 subscription">
                        <h5>{{$item->name}}</h5>
                        <h1>
                            {{showAmount($item->price)}} Ushs /Year
                        </h1>
                        <ul>
                            <li><img src="{{asset('tick.png')}}" height="20" width="20"/> Number of Estates :{{$item->estate_limit}}</li>
                            <li><img src="{{asset('tick.png')}}" height="20" width="20"/> Number of Tenants :{{$item->tenant_limit}}</li>
                        </ul>
                    </div>
                    @endforeach
                    </div>
                    <hr/>
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Month Subscribed')</th>
                                    <th>@lang('Year')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Subscription Type')</th>
                                    <th>@lang('Expiry Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td data-label="@lang('Month')">
                                        Estates Supported  {{ __($item->type->estate_limit) }}<br/>
                                        Tenants limit  {{ __($item->type->tenant_limit) }}<br/>
                                    </td>
                                    <td data-label="@lang('Month')">
                                        {{ __($item->month) }}
                                    </td>
                                    <td data-label="@lang('Year')">
                                        {{ __($item->year) }}
                                    </td>
                                    <td data-label="@lang('Amount')">
                                        @if(isset($item->type))
                                        {{ showAmount((__($item->type->price))) }} Shs
                                        @else
                                        No Data
                                        @endif
                                    </td>
                                    <td data-label="@lang('Type')">
                                        @if(isset($item->type))
                                        {{ ((__($item->type->name))) }} 
                                        @else
                                        No Data
                                        @endif
                                    </td>
                                   

                                    <td data-label="@lang('Bathrooms')">
                                        {{ __(date("D  F d, Y",$item->expiry)) }}
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
                    {{ paginateLinks($items) }}
                </div>
            </div>
        </div>
    </div>


    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Subscribe Now')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.subscription.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">
                                 @lang('Select Package')</label>
                            <select name="subscription_type" class="form-control select2-basic">
                                <option value="">@lang('Select ')</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item->id }}" tooltip="{{$item->description}}">
                                      <b>  {{ __($item->name) }} </b>
                                       
                                    
                                        {{ showAmount(($item->price)) }} Shs per Year
                                        {{ __($item->description) }} 
                                    
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold"> @lang('Mobile Money Number')</label>
                            <input type="text" class="form-control" placeholder="@lang('Mobile Money Number')" 
                            name="telephone" required>
                        </div>
                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
@if (\Subscription::where("user_id",Auth::guard("admin")->user()->id)
->where("expiry",">",time())
->count()==0)
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Subscribe Now')</a>
@endif
    @endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        $('.disableBtn').on('click', function () {
            var modal = $('#disableModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.type_name').text($(this).data('type_name'));
            modal.modal('show');
        });

        $('.activeBtn').on('click', function () {
            var modal = $('#activeModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.type_name').text($(this).data('type_name'));
            modal.modal('show');
        });

        $('.addBtn').on('click', function () {
            $('.showSeat').empty();
            var modal = $('#addModal');
            modal.modal('show');
        });

        $('.select2-auto-tokenize').select2({
            tags: true,
            tokenSeparators: [',']
        });




    })(jQuery);

</script>
@endpush
