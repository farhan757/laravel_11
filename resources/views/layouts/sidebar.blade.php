@php
    use App\Http\Controllers\Menu;
    $activemenu = new Menu();
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h1 class="navbar-brand navbar-brand-autodark">
        <a href="#">
          <img src="{{ asset('tabler/static/logo.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
        </a>
      </h1>
      <div class="collapse navbar-collapse" id="sidebar-menu">
        <ul class="navbar-nav pt-lg-3">
          <li class="nav-item {{ $activemenu->activeMenu('home') !== '' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}" >
              <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
              </span>
              <span class="nav-link-title">
                Home
              </span>
            </a>
          </li>

          @if (Session::get('menu'))
            @foreach (Session::get('menu') as $parent)
              @if (count($parent->child) > 0)
                <li class="nav-item {{ $activemenu->activeTreeMenu($parent->id) }} dropdown">
                  <a class="nav-link dropdown-toggle {{ $activemenu->activeTreeMenu($parent->id) !== '' ? 'show' : '' }}" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                      {{-- icon --}}
                      {!! $parent->icon_menu !!}
                    </span>
                    <span class="nav-link-title">
                      {{ $parent->nama_menu }}
                    </span>
                  </a>
                  @foreach ($parent->child as $child)
                    <div class="dropdown-menu {{ $activemenu->activeMenu($child->nama_route) }}">
                      <div class="dropdown-menu-columns">
                        <div class="dropdown-menu-column">
                          <a class="dropdown-item" href="{{ route($child->nama_route) }}">
                            {{ $child->nama_menu }}
                          </a>
                      </div>
                    </div>
                  @endforeach
                </li>
              @else
              <li class="nav-item {{ $activemenu->activeMenu($parent->nama_route) !== '' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route($parent->nama_route) }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    {!! $parent->icon_menu !!}
                  </span>
                  <span class="nav-link-title">
                    {{ $parent->nama_menu }}
                  </span>
                </a>
              </li>
              @endif
            @endforeach
          @endif

        </ul>
      </div>
    </div>
  </aside>