@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="card mt-5">
                <div class="card-header bg--dark">
                    <h5 class="card-title text-white">{{ __($pageTitle) }}</h5>
                </div>
                <form action="{{ route('admin.sms.template.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                                <input type="text" name="name" rows="10" class="form-control" 
                                placeholder="@lang('Name')"/>
                            </div>
                           

                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Message') <span class="text-danger">*</span></label>
                                <textarea name="sms_body" rows="10" class="form-control" 
                                placeholder="@lang('Your message using shortcodes')"></textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Shortcode') <span class="text-danger">*</span></label>
                                <input type="text" name="shortcode" rows="10" class="form-control" 
                                placeholder="@lang('Short Codes')"/>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-block btn--primary mr-2">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.sms.template.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="fa fa-fw fa-backward"></i> @lang('Go Back') </a>
@endpush
