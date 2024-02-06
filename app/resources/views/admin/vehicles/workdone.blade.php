<table class="table table--light style--two">
    <thead>
        <tr>
            <th>@lang('Mechanic')</th>
           <th>@lang('item')</th>
           <th>@lang('Time Started')</th>
            <th>@lang('Time Finished')</th>
            <th>@lang('Hours Woeked')</th>
        </tr>
    </thead>
    <tbody>
    @forelse($workdone as $item)
        <tr>
            <td data-label="@lang('Name')">
                {{ __($item->user->name) }}
          
            </td>
       
            <td data-label="@lang('Name')">
                {{ __($item->description) }}
          
            </td>
            <td data-label="@lang('Name')">
                {{ __($item->time_started) }}
          
            </td>
            <td data-label="@lang('Quantity')">
                {{ __($item->time_finished) }}<br/>

          
            </td>
            <td data-label="@lang('Number')">
                {{ __($item->hours_worked) }}
          
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