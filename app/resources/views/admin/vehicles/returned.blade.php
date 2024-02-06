<form action="{{route('admin.repair.returned',[$id])}}" 
method="POST"
      enctype="multipart/form-data">
    @csrf  
<table class="table table--light style--two">
    <thead>
        <tr>
           <th>@lang('Name')</th>
           <th>@lang('Description')</th>
            <th>@lang('Qty Ordered')</th>
            <th>@lang('Qty Supplied')</th>
            <th>@lang('Qty Returned')</th>
        </tr>
    </thead>
    <tbody>
    @forelse($orderdetails as $item)
        <tr>
       
            <td data-label="@lang('Name')">
                {{ __($item->product->name) }}
          
            </td>
            <td data-label="@lang('Name')">
                {{ __($item->product->description) }}
          
            </td>
            <td data-label="@lang('Quantity Ordered')">
                {{ __($item->quantity) }}<br/>

          
            </td>
            <td data-label="@lang('Quantity Ordered')">
                {{ __($item->issued) }}<br/>
          
            </td>
            <td data-label="@lang('Issued')">
                <input class="form-control" type="number" value="{{$item->returned}}" placeholder="Qty Returned" 
                required name="issued[]">
                <input class="form-control" type="hidden" value="{{$item->id}}" name="ids[]">    
            </td>
        </tr>
        </tr>
    @empty
        <tr>
            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
        </tr>
    @endforelse
    </tbody>
</table>
<hr/>
<div class="col-md-3">
    <div class="form-group ">
        <label class="form-control-label font-weight-bold">@lang('Odometer Out')
             <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="odometer_out" value="{{$user->odometer_out}}">
    </div>

    <div class="form-group">
        <label class="form-control-label font-weight-bold"> @lang('Tested By')</label>
        <div class="input-group">
            <select class="select2-basic" name="tested_by" required>
                <option value="">@lang('Select User')</option>
                @foreach ($staffs_assigned as $item)
                    <option value="{{ $item->id }}" 
                    >{{ __($item->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-control-label font-weight-bold"> @lang('Certified By')</label>
        <div class="input-group">
            <select class="select2-basic" name="certified_by" required>
                <option value="">@lang('Select User')</option>
                @foreach ($staffs_assigned as $item)
                    <option value="{{ $item->id }}" 
                    >{{ __($item->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>
<div class="form-group ">
    <label class="form-control-label font-weight-bold">@lang('Body Condition')
         <span class="text-danger">*</span></label>
    <textarea class="form-control" type="text" name="remark"></textarea>
</div>
<div class="row mt-2">
    <div class="col-md-3">
        <div class="form-group">
            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Complete Job')
            </button>
        </div>
    </div>

</div>
</form>