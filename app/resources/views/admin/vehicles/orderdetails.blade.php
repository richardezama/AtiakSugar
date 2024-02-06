<table class="table table--light style--two">
    <thead>
        <tr>
           <th>@lang('Name')</th>
           <th>@lang('Description')</th>
            <th>@lang('Qty')</th>
            <th>@lang('Part Number')</th>
            <th>@lang('Remark')</th>
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
            <td data-label="@lang('Quantity')">
                {{ __($item->quantity) }}<br/>

          
            </td>
            <td data-label="@lang('Number')">
                {{ __($item->part_number) }}
          
            </td>

            <td data-label="@lang('Remark')">
                {{ __($item->remark) }}
          
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
