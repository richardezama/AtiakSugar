@php
$contents = getContent('banner.content',true);
$counters = App\Models\Counter::get();
@endphp
<!-- Banner Section Starts Here 
style="background: url({{ getImage('assets/images/frontend/banner/'.$contents->data_values->background_image,
  "1500x88") }}) repeat-x bottom;"
-->
<section class="banner-section hide">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
             
                <form action="{{ route('search') }}" class="ticket-form row g-3 justify-content-left m-0">
                    <div class="col-md-4">
                        <div class="form--group">
                            <i class="las la-location-arrow"></i>
                            <select class="form--control select2" name="booktype">
                                <option value="">@lang('Book Type')</option>
                                @foreach ($booktypes as $item)
                                <option value="{{ $item->id }}" 
                                >{{ __($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    

                    <div class="col-md-4">
                        <div class="form--group">
                            <i class="las la-calendar-check"></i>
                            <input type="text" name="date_of_journey" class="form--control datepicker"
                             placeholder="@lang('Leave the date')" autocomplete="off">
                        </div>
                    </div>

                   



                    <div class="col-md-4">
                        <div class="form--group">
                            <button>@lang('Find Books')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
       
    </div>
    <div class="shape" style="display:none;">
        <img src="{{ getImage('assets/images/frontend/banner/'.$contents->data_values->animation_image, "200x69") }}" 
        alt="bg">
    </div>
</section>
<!-- Banner Section Ends Here -->
