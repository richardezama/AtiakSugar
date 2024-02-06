<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')

    <!-- BootStrap Link -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/bootstrap.min.css') }}">
    <!-- Icon Link -->
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/flaticon.css') }}">

    <!-- Plugings Link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/slick.css') }}">
    <link rel="stylesheet" href="{{asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/jquery-ui.css') }}">

    <!-- Cookie Link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/cookie.css') }}">
    <!-- Custom Link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css') }}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php?color='.$general->base_color)}}">
    <style>
        .pay_button{
            display: none;
        }
        </style>
    @stack('style-lib')

    @stack('style')
</head>
<body>
    <div class="overlay"></div>
    @stack('fbComment')

    @include($activeTemplate .'partials.user_header')
    @yield('content')
    @include($activeTemplate .'partials.footer')



<a href="javascript::void()" class="scrollToTop active"><i class="las la-chevron-up"></i></a>
<script src="{{asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{asset($activeTemplateTrue.'js/bootstrap.min.js') }}"></script>
<script src="{{asset($activeTemplateTrue.'js/slick.min.js') }}"></script>
<script src="{{asset('assets/global/js/select2.min.js') }}"></script>
<script src="{{asset($activeTemplateTrue.'js/jquery-ui.min.js') }}"></script>
<script src="{{asset($activeTemplateTrue.'js/require.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/epubjs/dist/epub.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
<script src="{{asset($activeTemplateTrue.'js/main.js') }}"></script>
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNz8YyPsJ0KzVAtOJpiS8-m9Mx0vwPtsA&callback=initMap&v=weekly"
defer
>
</script>

@stack('script-lib')

@stack('script')

@include('partials.plugins')

@include('partials.notify')
<script>

  (function ($) {
        "use strict";

        $('#cookiePolicy').hide();
        @if(@$cookie->data_values->status && !session('cookie_accepted'))
            $('#cookiePolicy').show();
        @endif

        $('a[name="cookieAccept"]').click(function(event) {
            event.preventDefault();
            var actionUrl = "{{ route('cookie.accept') }}";
            $.ajax({
                type: "GET",
                url: actionUrl,
                success: function(data)
                {
                    console.log(data);
                    $('#cookiePolicy').hide();
                    if (data.success) {
                        notify('success', data.success);
                        $('#cookiePolicy').hide();
                    }
                }
            });
        });
    })(jQuery);
</script>
</body>
</html>
