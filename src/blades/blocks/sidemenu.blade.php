<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
        </div>

        @php
            $all_permissions = Cache::remember(env('APP_TYPE').'ZhyuUserOwnPermissions', now()->addMinutes(60), function(){
                return auth()->user()->permissions();
            });
            $currentRoute = Route::currentRouteName();
        @endphp

        @if(isset($parents) && count($parents))
            <ul class="nav" id="side-menu">
                @foreach($parents as $parent)
                    @php
                        $parent_route = [];
                        if(isset($parent->route) && strlen($parent->route)>0){
                            $parent_route[] = $parent->route;
                        }
                        $filters = $children->where('parent_id', $parent->id);
                        $children_ids = $filters->pluck('id');
                    @endphp
                    @if($all_permissions->whereIn('resource_id', $children_ids)->count()>0)
                        <li>
                            <a href="javascript:void(0)" class="waves-effect"><i class="{{ $parent->icon_css }}"></i><span class="hide-menu">{{ $parent->name }}<span class="fa arrow"></span></span></a>
                            @if(isset($filters) && count($filters))
                                <ul class="nav nav-second-level">
                                    @foreach($filters as $child)
                                        @if($all_permissions->where('resource_id', $child->id)->count()>0)
                                            @php
                                                $route_name = '';

                                                $route = $parent_route;
                                                if(isset($child->route) && strlen($child->route)>0){
                                                    $route[] = $child->route;
                                                }
                                                $o_route_name = count($route) ? join('.', $route) : 'home';

                                                $act = 'index';
                                                if(!route::has($o_route_name)){
                                                    $route_name = $o_route_name.'.index';
                                                    if(!route::has($route_name)){
                                                        $act = 'list';
                                                        $route_name = $o_route_name.'.list';
                                                    }
                                                }else{
                                                    $route_name = $o_route_name;
                                                }
                                            @endphp
                                            @if(route::has($route_name) && $all_permissions->where('resource_id', $child->id)->where('act', 'index')->count()>0)
                                                <li><a href="{{ route($route_name) }}" @if($currentRoute==$route_name) class="active" @endif><i class="{{ $child->icon_css }}"></i><span class="hide-menu">{{ $child->name }}</span></a></li>
                                            @else
                                            <!--li><a>{{ $route_name }}-{{ $child->id }}-{{ $act }}</a></li-->
                                            <!--li><a href="javascript:void(0)"><i class="{{ $child->icon_css }}"></i><span class="hide-menu">{{ $child->name }} {{ $route_name }}</span></a></li-->
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>
