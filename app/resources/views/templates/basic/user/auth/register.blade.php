@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $content = getContent('sign_in.content', true);
    @endphp
    <!-- Account Section Starts Here -->
    <section class="account-section bg_img" 
    style="background: url({{getImage('assets/images/frontend/sign_in/crm.webp', "1920x1280") }}) bottom right;">
  
        <div class="account-wrapper">
            <div class="account-form-wrapper">
                <div class="account-header">
                    <div class="left-content">
                    
                         <h3 class="title">Create an Account</h3>
                    </div>
                </div>
                    <form method="POST" class="account-form row" 
                    action="{{ route('admin.signup')}}" onsubmit="return submitUserForm();">
                    @csrf
                    <div class="col-lg-12">
                        <div class="form--group">
                            <label for="username">@lang('Patient')</label>
                            <input id="username" name="name" type="text" 
                            class="form--control" placeholder="@lang('Name')" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form--group">
                            <label for="username">@lang('Email')</label>
                            <input id="username" name="email" type="text" 
                            class="form--control" placeholder="@lang('Email')" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form--group">
                            <label for="username">@lang('Telephone')</label>
                            <input id="username" name="telephone" type="text" 
                            class="form--control" placeholder="@lang('Telelphone')" required>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="form--group">
                            <label for="password">@lang('Password')</label>
                            <input id="password" type="password" name="password"
                             class="form--control" placeholder="@lang('Enter Your Password')" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form--group">
                            <label for="password">@lang('Confirm Password')</label>
                            <input id="password" type="password" name="confirmpass"
                             class="form--control" placeholder="@lang('Confirm Your Password')" required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form--group">
                            @php echo loadReCaptcha() @endphp
                        </div>
                    </div>
                    @include($activeTemplate.'partials.custom_captcha')
                    <div class="col-lg-12 d-flex justify-content-between">
                        <div class="form--group custom--checkbox">
                            <input type="checkbox" name="remember" id="remember" required>
                            <label for="remember">@lang('Accept Terms')</label>
                        </div>
                      
                    </div>
                    <div class="col-md-12">
                        <div class="form--group">
                            <button class="account-button w-100" type="submit">@lang('Register')</button>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </section>
    <!-- Account Section Ends Here -->
@endsection

@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush
