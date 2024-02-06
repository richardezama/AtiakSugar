@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Admin information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                           
                          
                        </li>

                      
                    </ul>
                </div>
            </div>
           
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('New Admin')</h5>

                    <form action="{{route('admin.users.admincreate')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Username')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="username" required />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Fullname')
                                         <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="fullname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" required/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="telephone" required />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                           

               
                          


                            <div class="col-xl-6 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('System Role')
                                     </label>
                                    <select name="role_id" class="form-control select2-basic" required>
                                        <option value=""
                                        >Select</option>
                                        @foreach($roles as $item)
                                           <option value="{{ $item->id }}"
                                            >{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    


                            <div class="col-xl-6 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Department')
                                     </label>
                                    <select name="department_id" class="form-control select2-basic" required>
                                        <option value=""
                                            >Select</option>
                                        @foreach($departments as $item)
                                           <option value="{{ $item->id }}"
                                            >{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    
                        </div>


                        <div class="row">
                           

                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




@endsection
@push('script')
<script>
    $(".directorate").change(function(e){
      division=$(this).val();
      if(division=="")
      {
    
      }
      else{
        departmentselect = document.getElementById('department');    
        clearSelect(departmentselect,"Select Department");
        $.ajax({
                method: 'GET',
                url: '/departments/'+division,
                dataType: 'json',
                success: function(result) {
                  $.each(result, function (key, val) {
                  //  alert(JSON.stringify(result));
                    var opt = document.createElement('option');
        opt.value = val.id;
        opt.innerHTML = val.name;
        departmentselect.appendChild(opt);
                  });          //alert(JSON.stringify(result));
                },
            });
      }
    
    });

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
