@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="card mt-5">
                <div class="card-header bg--dark">
                    <h5 class="card-title text-white">{{ __($pageTitle) }}</h5>
                </div>
                <form action="{{ route('admin.sms.template.sms.send') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Select Template') <span class="text-danger">*</span></label>
                               
                                <select class="select2-basic" name="template" required>
                                    <option value="">@lang('Select SMS Template')</option>
                                    @foreach ($templates as $item)
                                        <option value="{{ $item->id }}" 
                                        >{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                            <!--
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold">@lang('Message') <span class="text-danger">*</span></label>
                                <textarea name="sms_body" rows="10" class="form-control" 
                                placeholder="@lang('Your message using shortcodes')"></textarea>
                            </div>
                            -->

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-block btn--primary mr-2">@lang('Send SMS')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.sms.template.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="fa fa-fw fa-backward"></i> @lang('Go Back') </a>
@endpush
