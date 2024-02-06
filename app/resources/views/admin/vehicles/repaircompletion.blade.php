
    <div class="row mb-none-30">
        <div class="col-xl-8 col-lg-8 col-md-8 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    
                    <form action="{{route('admin.repair.workdone',[$id])}}" 
                    method="POST"
                          enctype="multipart/form-data">
                        @csrf          
                        <div class="row">                     
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Work Done')
                                         <span class="text-danger">*</span></label>
                                         <table class="td_append">
                                            <tr>
                                                <td><select class="select2-basic" name="operator[]" required><option value="">@lang('Select Operator')</option>@foreach ($staffs_assigned as $operator)<option value="{{ $operator->id }}" >{{ __($operator->name) }}</option>@endforeach</select></td>
                                              
                                                <td><input class="form-control" type="text" placeholder="Workdone"  name="workdone[]"required></td>
                                                <td><input class="form-control" type="text" placeholder="Time started"name="time_started[]" equired></td>
                                                <td><input class="form-control" type="text" placeholder="Time Finished"name="time_finished[]" equired></td>
                                                <td><input class="form-control" type="text" placeholder="Hours Worked"name="hours_worked[]" equired></td>
                                              
                                                <td><input type="button" value="+" class="btn btn--primary btn-block btn-lg trigger_field"></td></td>
                                            </tr>
                                         </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save and Workdone')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" class="url" value="{{route('ajax.models','_a_c')}}"/>


@push('script')
<script src="{{asset('assets/admin/js/vendor/jquery-ui.js')}}"></script>

<script>
     $( ".datepicker1" ).datepicker();
     $('.trigger_field').click(function(e){
        var formchild='<tr class="victim_field" style="margin:10px;"><td><select class="select2-basic" name="operator[]" required><option value="">@lang('Select Operator')</option>@foreach ($staffs_assigned as $operator)<option value="{{ $operator->id }}" >{{ __($operator->name) }}</option>@endforeach</select></td><td><input class="form-control" type="text" placeholder="Workdone"  name="workdone[]"required></td> <td><input class="form-control" type="text" placeholder="Time started"name="time_started[]" equired></td><td><input class="form-control" type="text" placeholder="Time Finished"name="time_finished[]" equired></td><td><input class="form-control" type="text" placeholder="Hours Worked"name="hours_worked[]" equired></td><td><input type="button" value="x" class="btn btn--danger btn-block btn-lg remove_field"></td></tr>';
       $(".td_append").append(formchild);
     });

     $(document).on("click", ".remove_field", function(){
      
       $( ".victim_field:last").remove();
     });
     /*
    $(".estate").change(function(e){
     
      division=$(this).val();
      if(division=="")
      {
    
      }
      else{
        var url=$(".url").val();
        var uri=url.replace("_a_c",division);
        //alert(uri);
       // alert(division);
        departmentselect = document.getElementById('department');    
        clearSelect(departmentselect,"Select Model");
        $.ajax({
                method: 'GET',
                url: uri,
                dataType: 'json',
                success: function(result) {
                  $.each(result, function (key, val) {
                 // alert(JSON.stringify(result));
                    var opt = document.createElement('option');
        opt.value = val.id;
        opt.innerHTML = val.name;
        departmentselect.appendChild(opt);
                  });         
                },
            });
      }
    
    });*/

    function clearSelect(select,title)
    {
      var length = select.options.length;
    for (i = length-1; i >= 0; i--) {
      select.options[i] = null;
    }
    //then add default
    var opt = document.createElement('option');
        opt.value = "";
        opt.innerHTML =title;
        select.appendChild(opt);
    }
    </script>
    @endpush
