<form action="{{route('admin.repair.issue',[$id])}}" 
method="POST"
      enctype="multipart/form-data">
    @csrf  
<table class="table table--light style--two">
    <thead>
        <tr>
           <th>@lang('Name')</th>
           <th>@lang('Description')</th>
            <th>@lang('Qty Ordered')</th>
            <th>@lang('Qty Available')</th>
            <th>@lang('Qty Issued')</th>
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
            <td data-label="@lang('Quantity Available')">
                {{ __($item->available) }}<br/>
            </td>
            <td data-label="@lang('Issued')">
                <input class="form-control" type="number" value="{{$item->issued}}"
                 placeholder="Qty Issued" required name="issued[]">
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
<div class="row mt-2">
    <div class="col-md-12">
        <div class="form-group ">
            <label class="form-control-label font-weight-bold">@lang('Remark/Comment')
                 <span class="text-danger">*</span></label>
            <textarea class="form-control" type="text" name="remark"></textarea>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="submit" name="hold" value="Issue & Hold" class="btn btn--success btn-block btn-lg"/>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="submit" name="final" value="Issue & Submit" class="btn btn--primary btn-block btn-lg"/>
        </div>
    </div>

</div>
</form>