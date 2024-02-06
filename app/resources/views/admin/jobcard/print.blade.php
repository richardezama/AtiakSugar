
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{  ('JOBCARD') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <style media="all">
        @page {
            margin: 0;
            padding:0;
        }
        body{
            font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            
            padding:0;
            margin:0; 
        }
        .gry-color *,
        .gry-color{
            color:#000;
        }
        table{
            width: 100%;
        }
        table th{
            font-weight: normal;
        }
        table.padding th{
            padding: .25rem .7rem;
        }
        table.padding td{
            padding: .25rem .7rem;
        }
        table.sm-padding td{
            padding: .1rem .7rem;
        }
        .border-bottom td,
        .border-bottom th{
            border-bottom:1px solid #eceff4;
        }
        .text-left{
            text-align:left;
        }
        .text-right{
            text-align:right;
        }
    </style>
</head>
<body>
    <div>

        @php
            $logo = asset('arnet.jpg');
        @endphp

        <div style="background: #eceff4;padding: 1rem;">
            <table>
                <tr>
                    <td style="font-size: 1.5rem;" class="text-left strong">{{  ('Jobcard') }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-size: 1rem;" class="strong">Atiak Sugar</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">Amuru</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{  ('Email') }}: info@aAtiak</td>
                    <td class="text-right small"><span class="gry-color small">
                        {{  ('Jobcard No:') }}:</span> <span class="strong">{{ $job->reference_number }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{  ('Phone') }}: +256</td>
                    <td class="text-right small"><span class="gry-color small">{{  ('Date Created') }}
                        :</span> <span class=" strong">{{ date('d-m-Y', strtotime($job->created_at)) }}</span></td>
                </tr>

                <tr><td class="strong small gry-color">{{ ('Card Prepared By') }}:{{ $job->preparedby->name }}</td>
               
                <td class="text-right small">{{ ('Vehicle Number') }}: {{ $job->equipment->name }}</td></tr>
                <tr><td class="gry-color small">{{ ('Operator') }}: {{ $job->operator->name }}</td></tr>
            </table>

        </div>

        <div style="padding: 1rem;padding-bottom: 0">
            <table>
               
               
            </table>
        </div>

        <div style="padding: 1rem;padding-bottom: 0">
            <table>
               
                 <tr><td class="gry-color small">{{ ('Date Diognised') }}: {{ $job->diognised_on }}</td></tr>
            </table>
        </div>

        <div style="padding: 1rem;">
            <h4>Issues Reported</h4>
            <table class="padding text-left small border-bottom" border="1">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="15%" class="text-left">{{ ('No:') }}</th>
                        <th width="35%" class="text-left">{{ ('Issues Reported') }}</th>
                      
                    </tr>
                </thead>
                <tbody class="strong">
                
@if (sizeof($extra_diognosis)==0)
@for ($x = 1; $x <= 8; $x++)
<tr>
    <td class="">{{ $x }}</td>
    <td class=""></td>
    </tr>
@endfor
@endif
@php
                    $x=1;
                    @endphp

                    @foreach ($extra_diognosis as $line)
                    <tr>
                    <td class="">{{ $x }}</td>
                   
                    <td class="">{{ $line }}</td>
                         
                    </tr>
                    @php
                    $x++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>






        <div style="padding: 1rem;">
            <h4>Staff Assigned</h4>
            <table class="padding text-left small border-bottom border--gray jobcard_table" border="1">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="10%" class="text-left">{{ ('No:') }}</th>
                        <th width="10%" class="text-left">{{ ('Name') }}</th>
                        <th width="10%" class="text-left">{{ ('Signature') }}</th>
                       
                    </tr>
                </thead>
                <tbody class="strong">



                    @if (sizeof($staffs)==0)
@for ($x = 1; $x <= 5; $x++)
<tr>
    <td class="">{{ $x }}</td>
   
    <td data-label="@lang('Staff')">
  
    </td>

    <td data-label="@lang('Signature')">
                      
        
    </td>
    </tr>
@endfor
@endif



                    @php
                    $x=1;
                    @endphp
                    @foreach ($staffs as $item)
                    <tr>
                    <td class="">{{ $x }}</td>
                   
                    <td data-label="@lang('Staff')">
                        {{ __($item->name) }}
                  
                    </td>
                    <td data-label="@lang('Signature')">
                      
        
                    </td>
                    </tr>
                    @php
                    $x++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>








 

        <div style="padding: 1rem;">
            <h4>Workdone</h4>
            <table class="padding text-left small border-bottom border--gray jobcard_table" border="1">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="10%" class="text-left">{{ ('No:') }}</th>
                        <th width="10%" class="text-left">{{ ('Workdone') }}</th>
                        <th width="10%" class="text-left">{{ ('Start Time') }}</th>
                        <th width="10%" class="text-left">{{ ('End Time') }}</th>
                        <th width="10%" class="text-left">{{ ('Hours Worked') }}</th>
                        <th width="10%" class="text-left">{{ ('Staff') }}</th>
                      
                    </tr>
                </thead>
                <tbody class="strong">



                    @if (sizeof($workdone)==0)
@for ($x = 1; $x <= 5; $x++)
<tr>
    <td class="">{{ $x }}</td>
   
    <td data-label="@lang('Description')">

  
    </td>
    <td data-label="@lang('Started')">
      
  
    </td>
    <td data-label="@lang('Finished')">
      
  
    </td>
    <td data-label="@lang('Hours Worked')">
       
  
    </td>

    <td data-label="@lang('User')">
      
  
    </td>
    </tr>
@endfor
@endif



                    @php
                    $x=1;
                    @endphp
                    @foreach ($workdone as $item)
                    <tr>
                    <td class="">{{ $x }}</td>
                   
                    <td data-label="@lang('Description')">
                        {{ __($item->description) }}
                  
                    </td>
                    <td data-label="@lang('Started')">
                        {{ __($item->time_started) }}
                  
                    </td>
                    <td data-label="@lang('Finished')">
                        {{ __($item->time_finished) }}<br/>
        
                  
                    </td>
                    <td data-label="@lang('Hours Worked')">
                        {{ __($item->hours_worked) }}
                  
                    </td>

                    <td data-label="@lang('User')">
                        {{ __($item->user->name) }}
                  
                    </td>
                    </tr>
                    @php
                    $x++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>




        
        <div style="padding: 1rem;">
            <h4>Spares</h4>

            <table class="padding text-left small border-bottom" border="1">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="10%" class="text-left">{{ ('Item:') }}</th>
                        <th width="10%" class="text-left">{{ ('Description') }}</th>
                        <th width="10%" class="text-left">{{ ('Quantity Ordered') }}</th>
                        <th width="10%" class="text-left">{{ ('Qty Supplied') }}</th>
                        <th width="10%" class="text-left">{{ ('Qty Returned') }}</th>
                        <th width="10%" class="text-left">{{ ('Unit Cost') }}</th>
                        <th width="10%" class="text-left">{{ ('Amount') }}</th>
                      
                    </tr>
                </thead>
                <tbody class="strong">
                    @php
                    $x=1;
                    @endphp

                    @if (sizeof($spares)==0)

                    @for ($x = 1; $x <= 7; $x++)
                               
                    <tr>
                        <td class="">{{ $x }}</td>
                       
                        <td data-label="@lang('Name')">
                         
                      
                        </td>
                       
                        <td data-label="@lang('Quantity Ordered')">
                         
                      
                        </td>
                        <td data-label="@lang('Quantity Ordered')">
                          
                      
                        </td>
                        <td data-label="@lang('Returned')">
                           
                      
                        </td>
                        <td data-label="@lang('Unit Cost')">
                           
                      
                        </td>
                        <td data-label="@lang('Total')">
                              
                        </td>        
                        </tr>
                    @endfor
                        
                    @endif


                    @foreach ($spares as $item)
                    <tr>
                    <td class="">{{ $x }}</td>
                   
                    <td data-label="@lang('Name')">
                        {{ __($item->product->name) }}
                  
                    </td>
                   
                    <td data-label="@lang('Quantity Ordered')">
                        {{ __($item->quantity) }}<br/>
        
                  
                    </td>
                    <td data-label="@lang('Quantity Ordered')">
                        {{ __($item->issued) }}<br/>
                  
                    </td>
                    <td data-label="@lang('Returned')">
                        {{ __($item->returned) }}<br/>
                  
                    </td>
                    <td data-label="@lang('Unit Cost')">
                        {{ __($item->product->amount) }}<br/>
                  
                    </td>
                    <td data-label="@lang('Total')">
                        {{ __($item->product->amount * $item->issued) }}<br/>    
                    </td>        
                    </tr>
                    @php
                    $x++;
                    @endphp
                    @endforeach


                    <tr>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th> 
                    </tr>

                    <tr>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left">Tested By</th>
                        <th width="10%" class="text-left">Title</th>
                        <th width="10%" class="text-left">Date</th>
                        <th width="10%" class="text-left">Sign</th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th> 
                    </tr>

                    <tr>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left">
                            @if (isset($job->testedBy))
                            {{$job->testedBy->name}}</th>
                            @endif
                           <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th> 
                    </tr>



                    <tr>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left">Certified By</th>
                        <th width="10%" class="text-left">Title</th>
                        <th width="10%" class="text-left">Date</th>
                        <th width="10%" class="text-left">Sign</th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th> 
                    </tr>

                    <tr>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left">
                            @if (isset($job->VerifiedBy))
                            {{$job->VerifiedBy->name}}</th>
                          
                            @endif 

                           </th>
                        <th width="10%" class="text-left">..</th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th>
                        <th width="10%" class="text-left"></th> 
                    </tr>


                </tbody>
            </table>
        </div>

        <div style="padding: 1rem;">
            <h4>Remarks by mechanic/Engineer .................................</h4>
            <h4>General Condition...............................{{$job->bodycondition}}...............</h4>

        </div>
        <div style="padding:0 1.5rem;">
            <table class="text-right sm-padding small strong">
                <thead>
                    <tr>
                        <th width="60%"></th>
                        <th width="40%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">
                            @php
                                $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                            @endphp
                            {!! str_replace($removedXML,"", SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
                            ->generate($job->reference_number)) !!}
                        </td>
                        <td>
                            <table class="text-right sm-padding small strong">
                              
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>



    </div>
</body>
</html>