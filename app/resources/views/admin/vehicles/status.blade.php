@if($user->draft == 1)
<span class="badge badge--danger">@lang('Draft Reported from Field')</span>
<br>{{ diffForHumans($user->created_at) }}
@else
@if($user->status == 1)
                                    <span class="badge badge--danger">@lang('Pending Diognosis')</span>
                                @elseif($user->status == 2)
                                    <span class="badge badge--warning">@lang('Pending Workshop Manager Approval')</span>
                                     <br>{{ diffForHumans($user->created_at) }}
                                    
                                     @elseif($user->status == 3)
                                     <span class="badge badge--warning">@lang('Pending Spare Parts Request')</span>
                                      <br>{{ diffForHumans($user->created_at) }}
                                     
                                      @elseif($user->status == 4)
                                      <span class="badge badge--warning">@lang('Pending Spareparts Approval')</span>
                                       <br>{{ diffForHumans($user->created_at) }}
                                   
                                       @elseif($user->status == 5)
                                       <span class="badge badge--warning">@lang('Spare Parts Approved Pending Issue')</span>
                                        <br>{{ diffForHumans($user->created_at) }}

                                        @elseif($user->status == 6)
                                        <span class="badge badge--warning">@lang('Pending Completion')</span>
                                         <br>{{ diffForHumans($user->created_at) }}
                                    
                                       @elseif($user->status == 7)
                                       <span class="badge badge--warning">@lang('Completed Pending Testing')</span>
                                        <br>{{ diffForHumans($user->created_at) }}
                                    
                              
                                        @elseif($user->status == 8)
                                        <span class="badge badge--warning">@lang('Tested Pending Certification')</span>
                                         <br>{{ diffForHumans($user->created_at) }}


                                         @elseif($user->status == 9)
                                         <span class="badge badge--success">@lang('Pending Completion')</span>
                                          <br>{{ diffForHumans($user->created_at) }}
                                @endif
                                @endif