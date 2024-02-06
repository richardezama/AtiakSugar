@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Admin information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$user->username}}</span>
                        </li>

                      
                    </ul>
                </div>
            </div>
            <!--
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">                  
                    <a href="{{route('admin.users.adminlogin',$user->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as User')
                    </a>
                
                </div>
            </div>
        -->
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$user->name}}</h5>

                    <form action="{{route('admin.users.adminupdate',[$user->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <input class="form-control" type="hidden" name="id" value="{{$user->id}}">
                            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="fullname" value="{{$user->name}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$user->email}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="telephone" value="{{$user->telephone}}">
                                </div>
                            </div>
                       
                           

                          
               

                    
                          




                            <div class="col-xl-6 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('System Role')
                                     </label>
                                    <select name="role_id" class="form-control select2-basic">
                                        @foreach($roles as $item)
                                           <option value="{{ $item->id }}"
                                            @if ($item->id==$user->role_id)
                                            selected
                                        @endif
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
                                            @if ($item->id==$user->department_id)
                                            selected
                                        @endif
                                            >{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Available') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                        data-width="100%"
                                       name="available"
                                       @if($user->available) checked @endif>
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



    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add / Subtract Balance')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.users.add.sub.balance', $user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                 data-toggle="toggle" data-on="@lang('Add Balance')" 
                                 data-off="@lang('Subtract Balance')" name="act" checked>
                            </div>


                            <div class="form-group col-md-12">
                                <label>@lang('Amount')<span class="text-danger">*</span></label>
                                <div class="input-group has_append">
                                    <input type="text" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __($general->cur_sym) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Submit')</button>
                    </div>
                </form>
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
