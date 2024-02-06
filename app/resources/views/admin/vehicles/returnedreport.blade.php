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
                {{ __($item->returned) }}<br/>    
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