<nav>
    <a href="{{ route('dashboard') }}" class="brand">🗓️ KontenKu</a>
    @auth
        <ul>
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">Klien</a></li>
            <li><a href="{{ route('campaigns.index') }}" class="{{ request()->routeIs('campaigns.*') ? 'active' : '' }}">Campaign</a></li>
            <li><a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') && ! request()->routeIs('posts.jadwal') ? 'active' : '' }}">Post</a></li>
            <li><a href="{{ route('posts.jadwal') }}" class="{{ request()->routeIs('posts.jadwal') ? 'active' : '' }}">Jadwal</a></li>
            @if (auth()->user()->role === 'admin')
                <li><a href="{{ route('platforms.index') }}" class="{{ request()->routeIs('platforms.*') ? 'active' : '' }}">Platform</a></li>
            @endif
        </ul>
        <div class="navbar-user">
            <a href="{{ route('profile.show') }}" style="color: inherit;">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    @endauth
</nav>
