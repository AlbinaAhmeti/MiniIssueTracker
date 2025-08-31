<nav x-data="{ open:false, userMenu:false }"
  class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="h-16 flex items-center justify-between">

      <div class="flex items-center gap-6">
        <a href="{{ route('projects.index') }}" class="flex items-center gap-2 group">
          <svg class="h-6 w-6 text-indigo-600 group-hover:scale-110 transition" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l9 4.9v9.1L12 22 3 16.9V7z" />
          </svg>
          <span class="font-semibold tracking-tight">Mini Tracker</span>
        </a>

        <div class="hidden md:flex items-center gap-1">
          <a href="{{ route('projects.index') }}"
            class="px-3 py-2 rounded-lg text-sm transition
             {{ request()->routeIs('projects.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            Projects
          </a>
          <a href="{{ route('issues.index') }}"
            class="px-3 py-2 rounded-lg text-sm transition
             {{ request()->routeIs('issues.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            Issues
          </a>
          <a href="{{ route('tags.index') }}"
            class="px-3 py-2 rounded-lg text-sm transition
             {{ request()->routeIs('tags.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            Tags
          </a>
        </div>
      </div>

      <div class="flex items-center gap-3">
        @auth

        <div class="relative">
          <button @click="userMenu=!userMenu" @click.outside="userMenu=false"
            class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm
                           text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600/10 text-indigo-700">
              {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </span>
            <span class="block whitespace-nowrap">{{ auth()->user()->name }}</span>

            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
              <path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
            </svg>
          </button>

          <div x-cloak x-show="userMenu"
            class="absolute right-0 mt-2 w-40 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Log out</button>
            </form>
          </div>
        </div>
        @else
        <a href="{{ route('login') }}"
          class="px-3 py-2 text-sm rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
          Log in
        </a>
        <a href="{{ route('register') }}"
          class="px-3 py-2 text-sm rounded-lg ring-1 ring-slate-200 hover:bg-slate-50 transition">
          Register
        </a>
        @endauth

        <button @click="open=!open" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg
                                           text-slate-500 hover:bg-slate-100 ring-1 ring-slate-200">
          <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <div x-cloak x-show="open" class="md:hidden border-t border-slate-200">
    <div class="px-4 py-3 space-y-1">
      <a href="{{ route('projects.index') }}"
        class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('projects.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }}">
        Projects
      </a>
      <a href="{{ route('issues.index') }}"
        class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('issues.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }}">
        Issues
      </a>
      <a href="{{ route('tags.index') }}"
        class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('tags.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }}">
        Tags
      </a>

      @auth
      <a href="{{ route('projects.create') }}"
        class="mt-2 block text-center rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500 text-white px-3 py-2 text-sm">
        New Project
      </a>
      <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button class="w-full text-left rounded-lg px-3 py-2 text-sm text-rose-600 hover:bg-rose-50">Log out</button>
      </form>
      @endauth
    </div>
  </div>
</nav>