@php
$content = getContent('contact.content', true);
@endphp
<div class="header-bottom">
    <div class="container">
        <div class="header-bottom-area" style="padding: 10px;">
            <div class="logo col-sm-2">
                <a href="{{ route('home') }}">
                    <!--<img src="{{ getImage(imagePath()['logoIcon']['path'].'/logo.png') }}" 
                        alt="@lang('Logo')">-->
                    <h4 style="color: white;">Inventory Mobi</h4>
                </a>
            </div> 
            
                <div class="col-sm-6">
                     <!-- Logo End
           
                <form action="{{ route('search') }}" class="row g-3 justify-content-left m-0">
                    <input type="text" name="search" class="form--control ticket_form_header"
                    placeholder="@lang('Search')" autocomplete="off">
                </form>
 -->

            </div>
          
            <div class="d-flex flex-wrap algin-items-center">
                <ul class="menu">
                    
                    <li><a class="sign-in" href="{{ route('admin.login') }}">@lang('Sign In')</a></li>
                    <!--<li><a class="sign-in" href="{{ route('user.register') }}">@lang('Sign Up')</a></li>
                    -->
                    <li>|</li>  
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Header Section Ends Here -->

@push('script')
<script>
    $(document).ready(function() {
        "use strict";
        $(".langSel").on("change", function() {
            window.location.href = "{{route('home')}}/change/" + $(this).val();
        });
    });
</script>
@endpush