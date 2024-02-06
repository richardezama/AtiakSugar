@extends($activeTemplate.$layout)
@section('content')
@php
$counters = App\Models\Counter::get();
@endphp
<section class="ticket-section padding-bottom section-bg">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-3">
                <form action="{{ route('search') }}" id="filterForm">
                    <div class="ticket-filter">
                        <div class="filter-header filter-item">
                            <h4 class="title mb-0">@lang('Filter')</h4>
                            <button type="reset" class="reset-button h-auto">@lang('Reset All')</button>
                        </div>
                        @if($fleetType)
                        <div class="filter-item">
                            <h5 class="title">@lang('Book Type')</h5>
                            <ul class="bus-type">
                                @foreach ($booktypes as $item)
                                <li class="custom--checkbox">
                                    <input name="booktype" class="search" 
                                    value="{{ $item->id }}" id="{{ $item->name }}" type="checkbox"
                                     >
                                     @if (request()->booktype && request()->booktype==$item->id)
                                     <label class="red_text" for="{{ $item->name }}">
                                     
                                        <span>
                                            {{ $item->name }}
                                    </span></label>
                                     @else
                                     <label for="{{ $item->name }}"><span>
                                        {{ $item->name }}
                                      
                                    </span></label>
                                     @endif


                                  


                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                      
                    </div>
                </form>
            </div>
            <div class="col-lg-9 col-sm-12">
                <div class="ticket-wrapper">
                    @forelse ($books as $item)
                    @php
                    /* $price = App\Models\TicketPrice::where('vehicle_id', $trip->id
                     )
                     ->first()
                     ->get();*/
                    
                    @endphp
                    <div class="ticket-item">

                        <div class="ticket-item-inner"> 
                            <a  href="{{ route('ticket.seats',
                            [$item->id, slug($item->title)]) }}">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <img width="50" height="120" alt="." height="50"
                                        width="100%" src="{{asset('storage/')."/".$item->logo}}"
                                         style="border-radius:50%;width:130px"/>
                                      
                                    </td>
                                    <td>
                                        <h5 class="bus-name">{{ __($item->title)}}</h5>
                                        <span class="ratting">{{ __($item->author['name']) }}</span>
                                        <span class="ratting">{{ __($item->booktype["name"]) }}</span>
                     
                                    </td>
                                </tr>
                            </table>
                        </a>
                        </div>                      
                       
                     
                    </div>
                    @empty
                    <div class="ticket-item">
                        <h5>{{ __($emptyMessage) }}</h5>
                    </div>
                    @endforelse
                 
                    {{ paginateLinks($books) }}
                               
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script>
    (function($) {
        "use strict";
        $('.search').on('change', function() {
            $('#filterForm').submit();
        });

        $('.reset-button').on('click', function() {
            $('.search').attr('checked', false);
            $('#filterForm').submit();
        })
    })(jQuery)
</script>
@endpush