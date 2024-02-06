@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
              
                        <form action="{{ route('admin.invoice.store')}}" method="POST">
                            @csrf
                            <input type="hidden" name="id"/>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">
                                     @lang('Select Tenant')</label>
                                <select name="tenant" class="form-control select2-basic" required>
                                    <option value="">@lang('Select Tenant')</option>
                                    @foreach ($tenants as $item)
                                        <option 
                                        value="{{ $item->id }}">{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>  

                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">
                                     @lang('Select Month')</label>
                                <select name="month" class="form-control select2-basic" required>
                                    @foreach ($months as $month)
                                        <option 
                                        value="{{ $month}}">{{ __($month) }}</option>
                                    @endforeach
                                </select>
                            </div>  

                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">
                                     @lang('Select Year')</label>
                                <select name="year" class="form-control select2-basic" required>
                                    @foreach ($years as $year)
                                        <option 
                                        value="{{ $year}}">{{ __($year) }}</option>
                                    @endforeach
                                </select>
                            </div>  

                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary">@lang('Create Invoice')</button>
              
                            </div>
    

                        </div>
                    </form>

                   
                </div>

               
            </div>
        </div>
    </div>


  
@endsection

@push('breadcrumb-plugins')

@endpush

