@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.sms.template.smsSetting') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="mb-4">@lang('Email Send Method')</label>
                                <select name="sms_method" class="form-control" >
                                    <option value="">@lang('Select SMS Provider')</option>                             
                                   <!-- <option value="clickatell" @if(@$general->sms_config->name == 'clickatell') selected @endif>@lang('Clickatell')</option>
                                    <option value="infobip" @if(@$general->sms_config->name == 'infobip') selected @endif>@lang('Infobip')</option>
                                    <option value="messageBird" @if(@$general->sms_config->name == 'messageBird') selected @endif>@lang('Message Bird')</option>
                                    <option value="nexmo" @if(@$general->sms_config->name == 'nexmo') selected @endif>@lang('Nexmo')</option>
                                    <option value="smsBroadcast" @if(@$general->sms_config->name == 'smsBroadcast') selected @endif>@lang('Sms Broadcast')</option>
                                    <option value="textMagic" @if(@$general->sms_config->name == 'textMagic') selected @endif>@lang('Text Magic')</option>
                                   -->
                                    <option value="twilio" @if(@$general->sms_config->name == 'twilio') selected @endif>@lang('Twilio')</option>
                                  
                                </select>
                            </div>
                         
                        </div>
                        
                        
                        
                        <div class="form-row mt-4 d-none configForm" id="twilio">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Twilio Configuration')</h6>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Account SID') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="@lang('Account SID')" name="account_sid" value="{{ @$general->sms_config->account_sid }}"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('Auth Token') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="@lang('Auth Token')" 
                                name="auth_token" value="{{ @$general->sms_config->auth_token }}"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">@lang('From Number') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="@lang('From Number')" 
                                name="from" value="{{ @$general->sms_config->from }}"/>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-block btn--primary mr-2">@lang('Update')</button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>


    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            
            var method = '{{ @$general->sms_config->name }}';

            if (!method) {
                method = 'clickatell';
            }

            smsMethod(method);
            $('select[name=sms_method]').on('change', function() {
                var method = $(this).val();
                smsMethod(method);
            });

            function smsMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

        })(jQuery);

    </script>
@endpush
