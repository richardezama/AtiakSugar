@extends($activeTemplate.$layout)
@section('content')
<div class="padding-top padding-bottom">
    <div class="container">
        <div class="row gx-xl-5 gy-4 gy-sm-5 justify-content-left">
            <div class="col-lg-6 col-md-6 hide">
                <table width="100%">
                    <tr>
                        <td>
                            <img width="50" height="120" alt="." height="50"
                            width="100%" src="{{asset('storage/')."/".$book->logo}}"
                             style="border-radius:50%;width:150px"/>
                          
                        </td>
                        <td>
                            <h5 class="bus-name">{{ __($book->title)}}</h5>
                            <span class="bus-info">@lang('No Seats - ') {{ __($book->author['name']) }}</span>
                            <span class="ratting">{{ __($book->booktype['name']) }}</span>
                                
                        </td>
                    </tr>
                </table>

            </div>
            <div class="col-sm-5">
                <img src="{{asset('storage/')."/".$book->logo}}"  width="98%"/>
            </div>
            <div class="col-sm-7">         
               <input type="hidden" class="bookfile" value="{{asset('storage/')."/".$book->book}}"/>
 
     <h5 class="bus-name">{{ __($book->title)}}</h5>
     <span class="bus-info">@lang('Author-') {{ __($book->author['name']) }}</span>
     <span class="ratting"> @lang('Book Type-'){{ __($book->booktype['name']) }}</span>
     <span class="bus-info">@lang('Language-') {{ __($book->language) }}</span>

     <hr/>
     <h5 class="bus-name">Abstract</h5>
     <span class="bus-info">@lang('Author-') {{ __($book->abstract) }}</span>
     <hr/>
     <a href="{{$link}}" target="_empty">
        <button class="btn-success btn">Read Online</button>

     </a>

            </div>
           
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    (function($) {
        "use strict";
        $('.date_of_journey_return').on('change', function() {
            var date_of_journey_return=$('.date_of_journey_return').val();
            var date_of_journey=$('.date_of_journey').val();
            //alert(date_of_journey_return);
            const date1 = new Date(date_of_journey);
            const date2 = new Date(date_of_journey_return);
            const diffTime = Math.abs(date1 - date2);
           const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))+1;
           //alert(diffDays);
            $('.days').val(diffDays);

        });

      
    })(jQuery)
</script>
@endpush

