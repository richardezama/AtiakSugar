<div class="sidebar">
    <!-- 
        {{ sidebarVariation()['sidebar'] }} 
{{ @sidebarVariation()['overlay'] }}
        
        data-background="{{getImage('assets/admin/images/sidebar/2.jpg','400x800')}}"-->
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
      

<a href="{{route('admin.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{asset('arnet.jpg')}}" alt="@lang('image')"></a>
            <a href="{{route('admin.dashboard')}}" class="sidebar__logo-shape"><img
                src="{{asset('arnet.png')}}" alt="@lang('image')"></a>
        
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('admin.dashboard')}}">
                    <a href="{{route('admin.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
            
                 <!-- for admin -->

               <li class="sidebar-menu-item sidebar-dropdown">
                <a href="javascript:void(0)" class="{{menuActive('admin.drugs*',3)}}">
                    <i class="menu-icon las la-clinic-medical"></i>
                    <span class="menu-title">@lang('Job Management')</span>

                    @if($banned_users_count > 0 || $email_unverified_users_count > 0 || $sms_unverified_users_count > 0)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                    @endif
                </a>
                <div class="sidebar-submenu {{menuActive('admin.drugs*',2)}}">
                    <ul>
                        @if ( Auth::guard("admin")->user()->role_id==1 || Auth::guard("admin")->user()->role_id==5 )              
                         <li class="sidebar-menu-item {{menuActive('admin.repair.create')}}">
                            <a href="{{route('admin.repair.create')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('New Defect Form')</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-menu-item {{menuActive('admin.repair.list')}} ">
                            <a href="{{route('admin.repair.list')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Jobs')</span>
                            </a>
                        </li>
                        @if (Auth::guard("admin")->user()->role_id==1 ||Auth::guard("admin")->user()->role_id==2
                        ||Auth::guard("admin")->user()->role_id==5) 

                        <li class="sidebar-menu-item {{menuActive('admin.repair.jobcards')}} ">
                            <a href="{{route('admin.repair.jobcards')}}" class="nav-link">
                                <i class="menu-icon las la-dot-circle"></i>
                                <span class="menu-title">@lang('Print Job Card')</span>
                            </a>
                        </li>

                        @endif
                      
                    </ul>
                </div>
            </li>
            @if (Auth::guard("admin")->user()->role_id==1) 
<li class="sidebar-menu-item sidebar-dropdown">
    <a href="javascript:void(0)" class="{{menuActive('admin.farms*',0)}}">
        <i class="menu-icon las la-box"></i>
        <span class="menu-title">@lang('Farm Management')</span>
    </a>
       <div class="sidebar-submenu {{menuActive('admin.estate*',0)}} ">
        <ul>
        
       
       
            <li class="sidebar-menu-item {{menuActive('admin.farms.list')}} ">
                <a href="{{route('admin.farms.list')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Farm Management')</span>
                </a>
            </li> 
            
            <li class="sidebar-menu-item {{menuActive('admin.farms.maps')}} ">
                <a href="{{route('admin.farms.maps')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Fieldwork Map')</span>
                </a>
            </li> 
          
           
        </ul>
    </div>
</li>

<li class="sidebar-menu-item sidebar-dropdown">
    <a href="javascript:void(0)" class="{{menuActive('admin.warehousing*',0)}}">
        <i class="menu-icon las la-box"></i>
        <span class="menu-title">@lang('Stock Control')</span>
    </a>
       <div class="sidebar-submenu {{menuActive('admin.estate*',0)}} ">
        <ul>
        
    
            <li class="sidebar-menu-item {{menuActive('admin.warehousing.list')}} ">
                <a href="{{route('admin.warehousing.list')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Warehouses')</span>
                </a>
            </li> 
            
            <li class="sidebar-menu-item {{menuActive('admin.warehousing.stock')}} ">
                <a href="{{route('admin.warehousing.stock')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Stock Taking')</span>
                </a>
            </li> 

            <li class="sidebar-menu-item {{menuActive('admin.products.all')}} ">
                <a href="{{route('admin.products.all')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Products')</span>
                </a>
            </li>  
            <li class="sidebar-menu-item {{menuActive('admin.products.categories')}} ">
                <a href="{{route('admin.products.categories')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Product Categories')</span>
                </a>
            </li>    
          
           
        </ul>
    </div>
</li>



             


<li class="sidebar-menu-item sidebar-dropdown">
    <a href="javascript:void(0)" class="{{menuActive('admin.vehicles*',0)}}">
        <i class="menu-icon las la-box"></i>
        <span class="menu-title">@lang('Equipments')</span>
    </a>
       <div class="sidebar-submenu {{menuActive('admin.vehicles*',0)}} ">
        <ul>
    
            <li class="sidebar-menu-item {{menuActive('admin.vehicles.checklists')}} ">
                <a href="{{route('admin.vehicles.checklists')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Checklist Items')</span>
                </a>
            </li>  

            <li class="sidebar-menu-item {{menuActive('admin.vehicles.checklist.types')}} ">
                <a href="{{route('admin.vehicles.checklist.types')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Checklist Categories')</span>
                </a>
            </li>  

       
            <li class="sidebar-menu-item {{menuActive('admin.products.all')}} ">
                <a href="{{route('admin.vehicles.makes')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Make')</span>
                </a>
            </li>  

            <li class="sidebar-menu-item {{menuActive('admin.products.all')}} ">
                <a href="{{route('admin.vehicles.models')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Models')</span>
                </a>
            </li>  
                  
            <li class="sidebar-menu-item {{menuActive('admin.products.all')}} ">
                <a href="{{route('admin.vehicles.types')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Types')</span>
                </a>
            </li>  

            <li class="sidebar-menu-item {{menuActive('admin.vehicles.list')}} ">
                <a href="{{route('admin.vehicles.list')}}" class="nav-link">
                    <i class="menu-icon las la-dot-circle"></i>
                    <span class="menu-title">@lang('Add Equipment')</span>
                </a>
            </li>  
            
          
           
        </ul>
    </div>
</li>



                
                <!--
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.estate.companies*',1)}}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Items')</span>                       
                    </a>        
                    <div class="sidebar-submenu {{menuActive('admin.estate.companies',2)}}">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.estate.companies')}} ">
                                <a href="{{route('admin.estate.companies')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Manage Products')</span>
                                </a>
                            </li>


                        </ul>
                    </div>
                </li>
            -->

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.users*',1)}}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Manage Users')</span>                       
                    </a>
                          
                    <div class="sidebar-submenu {{menuActive('admin.users.admins',2)}}">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('admin.users.admins')}} ">
                                <a href="{{route('admin.users.admins')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('View Users')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.directorates.list')}} ">
                                <a href="{{route('admin.directorates.list')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Division')</span>
                                </a>
                            </li>


                            <li class="sidebar-menu-item {{menuActive('admin.department.list')}} ">
                                <a href="{{route('admin.department.list')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Departments')</span>
                                </a>
                            </li>

                          

                        </ul>
                    </div>
                </li>
                @endif

                @if ( Auth::guard("admin")->user()->role_id==2)  
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.tenants*',3)}}">
                        <i class="menu-icon las la-clinic-medical"></i>
                        <span class="menu-title">@lang('Requisitions')</span>

                        @if($banned_users_count > 0 || $email_unverified_users_count > 0 || $sms_unverified_users_count > 0)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>

                          
                    <div class="sidebar-submenu {{menuActive('admin.drugs*',2)}}">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.drugs.all')}} ">
                                <a href="{{route('admin.drugs.list')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Send Request')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif



               
                @if ( Auth::guard("admin")->user()->role_id==1 )  
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.reports*',1)}}">
                        <i class="menu-icon las la-clinic-medical"></i>
                        <span class="menu-title">@lang('Reports')</span>
                    </a>

                    <div class="sidebar-submenu {{menuActive('admin.reports*',1)}}">
                        <ul>                   
                             <li class="sidebar-menu-item {{menuActive('admin.reports.pending')}} ">
                                <a href="{{route('admin.reports.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Jobs')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.reports.pending_status')}} ">
                                <a href="{{route('admin.reports.pending_status')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Per Status')</span>
                                </a>
                            </li>

                         

                            <li class="sidebar-menu-item {{menuActive('admin.reports.warehousebalance')}} ">
                                <a href="{{route('admin.reports.warehousebalance')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Warehouse Report')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.reports.productbalance')}} ">
                                <a href="{{route('admin.reports.productbalance')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Stock (Consolidated)')</span>
                                </a>
                            </li>

                            
                            <li class="sidebar-menu-item {{menuActive('admin.reports.service_tracker')}} ">
                                <a href="{{route('admin.reports.service_tracker')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Service Tracker')</span>
                                </a>
                            </li>

                            
                           
                        </ul>
                    </div>
                </li>
                @endif


@if (Auth::guard("admin")->user()->role_id==1)  
                
               
                <li class="sidebar__menu-header">@lang('Settings')</li>

                <li class="sidebar-menu-item {{menuActive('admin.setting.index')}}">
                    <a href="{{route('admin.setting.index')}}" class="nav-link">
                        <i class="menu-icon las la-life-ring"></i>
                        <span class="menu-title">@lang('General Setting')</span>
                    </a>
                </li>
                <!--

                <li class="sidebar-menu-item {{menuActive('admin.setting.logo.icon')}}">
                    <a href="{{route('admin.setting.logo.icon')}}" class="nav-link">
                        <i class="menu-icon las la-images"></i>
                        <span class="menu-title">@lang('Logo & Favicon')</span>
                    </a>
                </li>

            
                <li class="sidebar-menu-item  {{menuActive(['admin.language.manage','admin.language.key'])}}">
                    <a href="{{route('admin.language.manage')}}" class="nav-link"
                       data-default-url="{{ route('admin.language.manage') }}">
                        <i class="menu-icon las la-language"></i>
                        <span class="menu-title">@lang('Language') </span>
                    </a>
                </li>
            -->




                <li class="sidebar-menu-item {{menuActive('admin.setting.optimize')}}">
                    <a href="{{route('admin.setting.optimize')}}" class="nav-link">
                        <i class="menu-icon las la-broom"></i>
                        <span class="menu-title">@lang('Clear Cache')</span>
                    </a>
                </li>
               

                @endif
            </ul>
           
        </div>
    </div>
</div>
<!-- sidebar end -->
