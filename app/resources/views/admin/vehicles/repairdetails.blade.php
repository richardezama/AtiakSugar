@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--12 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <h3 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Reference Numver')  {{$users[0]->reference_number}}</h3>

                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Equipment')</th>
                                <th>@lang('Reported By')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
            
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td data-label="@lang('Equipment')">
                                    <span class="font-weight-bold">{{$user->equipment->number_plate}}</span>
                                   
                                </td>
                                <td data-label="@lang('Reported')">
                                    <span class="font-weight-bold">
                                        {{$user->operator->name}}</span>
                                </td>
                              

                                <td data-label="@lang('Status')">
                                    @include('admin.vehicles.status')
                                </td>
                                <td data-label="@lang('Date')">
                                  
                                         {{ diffForHumans($user->updated_at) }}
                                        
                                  
                                  
                                </td>

                               
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->


                    </div>
                    <div class="table-responsive--md  table-responsive">   
                    <hr/>
                    @if ($user->status!=5)
                    <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Checklists (Current)') </h5>
                   <table class="table table--light ">
                        <tbody>
                        @forelse($checklists as $checklist)
                        <tr>
                            <td data-label="@lang('Checklist')">
                                <span class="font-weight-bold">
                                    @if ($checklist["checked"]=="Yes")
                                    <img src="{{asset('tick.png')}}" height="20" width="20"/>
                             
                                    @else
                                    <img src="{{asset('error.png')}}" height="20" width="20"/>
                             
                                    @endif
                                  
                                    {{$checklist["name"]}} |   {{$checklist["recommendation"]}}</span>
                               
                            </td>
                           
                        </tr>
                        @endforeach
                  
                        </tbody>
                    </table><!-- table end -->
                    @endif
                   

                    <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Defect Reported'):  {{$user->defects_reported}}</h5>

                    <hr/>
                  

                    @if ($user->status==2 || $user->status==3)
                    {{$mechanics_assigned}}
                        
                    <h3 style="margin-left: 25px;">Additional Defects Recorded</h3>
                    <table class="table table--light ">
                      
                        <tbody>
                        @forelse($extra_diognosis as $additional)
                        @if ($additional!="")
                            
                        <tr>
                            <td data-label="@lang('Checklist')">
                                <span class="font-weight-bold">
                                    <img src="{{asset('tick.png')}}" height="20" width="20"/>
                             
                                    {{$additional}}</span>
                               
                            </td>
                           
                        </tr>
                        @else 
                        <tr>
                            <td data-label="@lang('Checklist')">
                                <span class="font-weight-bold">
                                    <img src="{{asset('tick.png')}}" height="20" width="20"/>
                             
                                   No Extra defect recorded</span>
                               
                            </td>
                           
                        </tr>
                        @endif
                       
                        @endforeach
                  
                        </tbody>
                    </table><!-- table end -->


                  


                    @if ($user->status==3)
                   
                    <h3 style="margin-left: 25px;">Spare Parts Requisition</h3>
                    <p style="margin-left: 25px;margin-top:20px;">Comment from Supervisor</p>
                    <h3 style="margin-left: 25px;margin-top:20px;text-decoration: underline;color:green;">{{$user->approved_remark}}</h3>
                  
                    <a href="{{route('admin.pos.home',[$id])}}" target="_blank" class="nav-link">
                        <i class="menu-icon las la-dot-circle"></i>
                        <span class="menu-title">@lang('Spare Parts Requisition')</span>
                    </a>
                    @endif
                   
                   @endif
                   @if ( $user->status==4)
                   <h3 style="margin-left: 25px;">Spare Parts Request</h3>
                   <hr/>
                   @include('admin.vehicles.orderdetails')
                   <div class="col-sm-8"></div>
<div class="col-sm-4">
    <form action="{{route('admin.repair.approvespares',[$id])}}" 
    method="POST"
          enctype="multipart/form-data">
        @csrf          
        <div class="row">                     
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-control-label font-weight-bold">@lang('Comment')
                         <span class="text-danger">*</span></label>
                         <table class="td_append">
                            <tr>
                                <td>
                    <input class="form-control" type="text" placeholder="Comment"  name="comment" required>
                                </td>
                              </tr>
                         </table>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-9">
                <div class="form-group">
                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Approve Spares')
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
                   @endif

                  
                </div>



                @if ($user->status==5)
                <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Issue Materials') </h5>
                <p style="margin-left: 25px;margin-top:20px;">Comment from Workshop Manager</p>
                <h3 style="margin-left: 25px;margin-top:20px;text-decoration: underline;color:green;">{{$user->approved_remark}}</h3>
             
                <p style="margin-left: 25px;margin-top:20px;">Comment from Spare Parts Approval</p>
                <h5 style="margin-left: 25px;margin-top:20px;text-decoration: underline;color:green;">{{$user->spares_approval_comment}}
                </h5>
             
                   @include('admin.vehicles.issue')
                
                @endif

                   @if ($user->status==6)
                   <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Complete Job') </h5>
                   @if (sizeof($workdone)==0)
                   <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Task List') </h5>          
                   @include('admin.vehicles.repaircompletion')
                   @else
                   @include('admin.vehicles.workdone')
                   <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Spare Part Management (Items Returned)') </h5>
                  <!-- returned here -->
                  @include('admin.vehicles.returned')
                   @endif
                   @endif

                   @if ($user->status==7 || $user->status==8)
                   <h3 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Job Completion Report') </h3>
                   <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">@lang('Issued Items')   Issued By {{$user->issuedby->name}}</h5> 
                   Completed by {{$user->completedby->name}}
                   @include('admin.vehicles.returnedreport')

                   <h5 class="card-title border-bottom pb-2" style="margin-left: 25px;">
                    @lang('Work Done') </h5>
                   <!-- returned here -->
                   @include('admin.vehicles.workdone')


                   @if ($user->status==7)             
                   <form action="{{route('admin.repair.test',[$id])}}" 
                   method="POST"
                         enctype="multipart/form-data">
                       @csrf  
                       <div class="col-md-6">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('Remark/Comment')
                                 <span class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" name="remark"></textarea>
                        </div>
                    </div>
                       <div class="row mt-2">
                           <div class="col-md-3">
                               <div class="form-group">
                                   <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Test')
                                   </button>
                               </div>
                           </div>
                       
                       </div>
                   </form>
                   @endif

                   @if ($user->status==8)             
                   <form action="{{route('admin.repair.certify',[$id])}}" 
                   method="POST"
                         enctype="multipart/form-data">
                       @csrf  
                       <div class="col-md-6">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('Remark/Comment')
                                 <span class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" name="remark"></textarea>
                        </div>
                    </div>
                       <div class="row mt-2">
                           <div class="col-md-3">
                               <div class="form-group">
                                   <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Certify and Complete')
                                   </button>
                               </div>
                           </div>
                       
                       </div>
                   </form>
                   @endif

                   <!--
                   <div class="row mt-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" class="btn btn--success btn-block btn-lg job_card">
                                <a href="{{route('admin.repair.jobcard',$id)}}" target="_blank">
                                @lang('Print Job Card')
                                </a>
                            </button>
                        </div>
                    </div>
                -->
                
                </div>
                @endif

                   @if ($user->status==2)
                   
                   <form action="{{route('admin.repair.approvedefects',[$id])}}" 
                   method="POST"
                         enctype="multipart/form-data">
                       @csrf  
                       <div class="col-md-6">
                        <div class="form-group ">
                            <label class="form-control-label font-weight-bold">@lang('Remark/Comment')
                                 <span class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" name="remark"></textarea>
                        </div>
                    </div>
                       <div class="row mt-2">
                           <div class="col-md-3">
                               <div class="form-group">
                                   <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Approve')
                                   </button>
                               </div>
                           </div>
                       
                       </div>
                   </form>
                   @endif

                   @if ($user->status==1)       
                   @include('admin.vehicles.additionaldefects')
                   @endif
            </div>
        </div>


    </div>
    
@endsection



@push('breadcrumb-plugins')
    <form action="{{ route('admin.repair.list', $scope ?? str_replace('admin.users.', '
    ', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Reference Number')"
             value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush



@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.disableBtn').on('click', function () {
                //alert($(this).data('type_name'));
                var modal = $('#disableModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.type_name').text($(this).data('type_name'));
                modal.modal('show');
            });




            $('.job_card').on('click', function () {
               

            });
            

        })(jQuery);



    </script>

@endpush

