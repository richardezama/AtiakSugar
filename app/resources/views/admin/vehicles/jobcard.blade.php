@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                   

                    <div id="element-to-print" style="border: solid; padding: 5px;">
                        <center><h3>Department : Agric/Workshop</h3></center>
                        <hr/>
         
<div width="col-sm-6">
    <div class="row">
        <div class="col-sm-3">Serial Number</div>
        <div class="col-sm-4">{{$users[0]->reference_number}}</div>
    </div>
    <div class="row">
        <div class="col-sm-3">Prepared By</div>
        <div class="col-sm-4">{{$user->completedby->name}}</div>
    </div>
  

    <div class="row">
        <div class="col-sm-3">Vehicle Number</div>
        <div class="col-sm-4">{{$user->equipment->number_plate}}</div>
    </div>



    <div class="row">
        <div class="col-sm-3">Vehicle Description</div>
        <div class="col-sm-4">{{$user->equipment->engine_no}}</div>
    </div>
   


    <div class="row">
        <div class="col-sm-3">Operator</div>
        <div class="col-sm-4">{{$user->operator->name}}</div>
    </div>

    <div class="row">
        <div class="col-sm-3">Intervention</div>
        <div class="col-sm-4">
            {{$intervention}} <input type="checkbox" checked disabled/>
        </div>
    </div>
</div>
<hr>
<div width="col-sm-6">

    <h3 class="card-title border-bottom pb-2" >@lang('Workdone') </h3>        
    @include('admin.vehicles.workdone')
    <h3 class="card-title border-bottom pb-2" >@lang('Spare Parts') </h3>        
    
    @include('admin.vehicles.returnedreport')
</div>

<hr/>
            </div><!-- print field-->
            @if ($user->status==7)
            <h5 class="card-title border-bottom pb-2" >@lang(' Materials Issued By') {{$user->issuedby->name}}  @lang('......................................')
            </h5>
            
            <h5 class="card-title border-bottom pb-2" >@lang('Tested By') {{$user->testedBy->name}}  @lang('......................................')
            </h5>

            <h5 class="card-title border-bottom pb-2" >@lang('Verified & Certified By') {{$user->VerifiedBy->name}}  @lang('......................................')
            </h5>

          
        
            <div class="row mt-2">
             <div class="col-md-3">
                 <div class="form-group">
                     <button type="button" class="btn btn--success btn-block btn-lg job_card">
                         @lang('Print Job Card')
                     
                     </button>
                 </div>
             </div>
         
         </div>
         @endif
            
        </div>
        </div>
    </div>
    </div>
@endsection


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
                var element = document.getElementById('element-to-print');
html2pdf(element);
            });
        })(jQuery);

    </script>

@endpush

