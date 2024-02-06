
<table class="table table--light style--two">
    <thead>
        <tr>
            <th>@lang('Name')</th>
           <th>@lang('Item')</th>
           <th>@lang('Yes/No')</th>
            <th>@lang('Recommendation')</th>
            
        </tr>
    </thead>
    <tbody>
    @forelse($checklisttypes as $item)
        <tr>
       
            <td data-label="@lang('Type')">
                {{ __($item->name) }}
          
            </td>
            <td data-label="@lang('Type')">
                {{ __($item->type->name) }}
          
            </td>
            <td data-label="@lang('Option')"> 
                <select class="select2-basic" name="selected_item[]">
                <option value="">@lang('No Option')</option>
                <option value="Yes">@lang('Yes')</option>
                <option value="No">@lang('No')</option>
                </select>
               
            </td>
         
            <td data-label="@lang('Issued')">
                <input class="form-control" type="text" placeholder="Recommendation"
                  name="recommendation[]">
                 <input type="hidden" name="ids[]" value="{{$item->id}}"/>
               
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
