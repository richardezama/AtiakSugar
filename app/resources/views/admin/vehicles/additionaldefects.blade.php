
    <div class="row mb-none-30">
        <div class="col-xl-10 col-lg-10 col-md-10 mb-30">
            <div class="card">
                <div class="card-body">
                  
                    <form action="{{route('admin.repair.updatediognosis',[$id])}}" id="finishdiog"
                    method="POST"
                          enctype="multipart/form-data">
                        @csrf          
                     
                        <h3>Checklist</h3>         
                        @include('admin.vehicles.checklisting')

                        <div class="row">                     
                            <div class="col-md-12">
                                <h3>Additional Defects Found</h3>  
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Extra Diognosis Detected')
                                         <span class="text-danger">*</span></label>
                                         <table class="td_append">
                                            <tr>
                                                <td>
                                    <input class="form-control" type="text" placeholder="Diognosis Item" 
                                     name="diagnosis[]">
                                                </td>
                                                <td><input type="button" value="+" class="btn btn--primary btn-block btn-lg trigger_field"></td></td>
                                            </tr>
                                         </table>
                                </div>
                            </div>
                        </div>
               
                        <div class="row mt-2">
                            <div class="col-md-5">
                                <div class="form-group">
                                    &nbsp;
                                    <button type="submit" name="submitfinal"
                                    class="btn btn--primary btn-block btn-lg final_submition">@lang('Save & Submit')
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
        var formchild='<tr class="victim_field" style="margin:10px;"><td><input class="form-control trigger_field" required type="text" placeholder="Diognosis Item"  name="diagnosis[]"></td><td><input type="button" value="x" class="btn btn--danger btn-block btn-lg remove_field"></td></tr>';
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

    $(".final_submition").click(function(e){
        e.preventDefault();
        //show a dialog
        Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, Submit!"
}).then((result) => {
  if (result.isConfirmed) {
   $("form#finishdiog").trigger("submit");
  }
});

    });
    </script>
    @endpush
