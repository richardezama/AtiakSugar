@extends('admin.layouts.app')

@section('panel')

    <div class="row mb-none-30">

        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive table-responsive--sm">
                        <table class="table align-items-center table--light">
                            <thead>
                            <tr>
                                <th>@lang('Short Code')</th>
                                <th>@lang('Description')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th>@{{name}}</th>
                                <td>@lang('Name')</td>
                            </tr>
                            <tr>
                                <th>@{{message}}</th>
                                <td>@lang('Message')</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>



@endsection