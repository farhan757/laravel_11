@php
    use App\Http\Controllers\Menu;

    $activemenu = new Menu();
@endphp

@if (checkauthSSO())
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header"></li>
            <li class="{{ $activemenu->activeMenu('home') }}">
                <a href="{{ route('home') }}">
                    <i class="fa fa-home"></i> <span>Home</span>
                </a>
            </li>

            @if (Session::get('menu'))
                @foreach (Session::get('menu') as $parent)
                    @if (isset($parent->child))
                        <li class="treeview menu-open {{ $activemenu->activeTreeMenu($parent->id) }}">
                            <a href="#">
                                <i class="fa {{ $parent->icon_menu }}"></i> <span>{{ $parent->nama_menu }}</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="display: block">
                                @foreach ($parent->child as $child)
                                    <li class="{{ $activemenu->activeMenu($child->nama_route) }}"><a
                                            href="{{ route($child->nama_route) }}"><i class="fa fa-circle-o"></i>
                                            <span>{{ $child->nama_menu }}</span>
                                            @php
                                                $count = 0;
                                                switch ($child->nama_route) {
                                                    case 'req.listapp':
                                                        # code...
                                                        $count = $getcount->listapp(request(), 1);
                                                        break;
                                                    case 'req.listcreator':
                                                        # code...
                                                        $count = $getcount->listcreator(request(), 1);
                                                        break;
                                                    case 'req.listKasbonSelesai':
                                                        # code...
                                                        $count = $getcount->listKasbonSelesai(request(), 1);
                                                        break;                                                   
                                                }
                                            @endphp

                                            @if ($count != 0)
                                            <span class="pull-right-container">
                                                <small style="color: rgb(255, 60, 0)"><b>{{ $count }}</b></small>
                                            </span>
                                            @endif
                                            
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="{{ $activemenu->activeMenu($parent->nama_route) }}">
                            <a href="{{ route($parent->nama_route) }}">
                                <i class="fa {{ $parent->icon_menu }}"></i> <span>{{ $parent->nama_menu }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif

            @if (Session::get('menuuser'))
                @foreach (Session::get('menuuser') as $parent)
                    @if (isset($parent->child))
                        <li  class="treeview menu-open {{ $activemenu->activeTreeMenu($parent->id) }}">
                            <a href="#">
                                <i class="fa {{ $parent->icon_menu }}"></i> <span>{{ $parent->nama_menu }}</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="display: block">
                                @foreach ($parent->child as $child)
                                    <li class="{{ $activemenu->activeMenu($child->nama_route) }}">
                                        <a href="{{ route($child->nama_route) }}"><i class="fa fa-circle-o"></i>
                                            <span>{{ $child->nama_menu }}</span>
                                            @php
                                                $countu = 0;
                                                switch ($child->nama_route) {
                                                    case 'top.applisttopup':
                                                        # code...
                                                        $countu = $getcount_apptopup->listtopupapp(request(), 1);
                                                        break;
                                                    case 'inv.listInvApp':
                                                        $countu = $getcount_appinv->ListInvApp(request(),1);
                                                        break;                                           
                                                }
                                            @endphp

                                            @if ($countu != 0)
                                            <span class="pull-right-container">
                                                <small style="color: rgb(255, 60, 0)"><b>{{ $countu }}</b></small>
                                            </span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="{{ $activemenu->activeMenu($parent->nama_route) }}">
                            <a href="{{ route($parent->nama_route)}}">
                                <i class="fa {{ $parent->icon_menu }}"></i><span>{{ $parent->nama_menu }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
    </section>
    <!-- /.sidebar -->
@endif
