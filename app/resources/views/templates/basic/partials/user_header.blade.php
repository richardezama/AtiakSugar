@php
$content = getContent('contact.content', true);
@endphp
<div class="header-bottom">
    <div class="container">
        <div class="header-bottom-area">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ getImage(imagePath()['logoIcon']['path'].'/logo.png') }}" alt="@lang('Logo')">
                </a>
            </div> <!-- Logo End -->
            <ul class="menu">
                <li>
                    <a href="{{ route('user.home') }}">@lang('Dashboard')</a>
                </li>
                <li>
                    <a href="javascript::void()">@lang('Invoices')</a>
                    <ul class="sub-menu">
                      
                        <li>
                            <a href="{{ route('user.invoices.history') }}">@lang('My Invoices')</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript::void()">@lang('Support Ticket')</a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{route('ticket.open')}}">@lang('Create New')</a>
                        </li>
                        <li>
                            <a href="{{route('support_ticket')}}">@lang('Tickets')</a>
                        </li>
                    </ul>
                </li>
                <li>
                   
                </li>
            </ul>
            <div class="d-flex flex-wrap algin-items-center">
                <ul class="menu">
                    <li>
                        <a href="{{ route('user.logout') }}">Logout</a>
                    </li>
                   
                   
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
