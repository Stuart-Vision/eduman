@php $user = auth()->user(); @endphp

<header class="topbar">
    <button type="button" class="icon-btn" id="sidebar-toggle" aria-label="Toggle sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="topbar-search d-none d-md-block">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="search" class="form-control" placeholder="Search students, courses..." aria-label="Search">
    </div>

    <div class="ms-auto d-flex align-items-center gap-1">
        <button type="button" class="icon-btn" id="theme-toggle" aria-label="Toggle dark mode">
            <i class="fa-solid fa-moon"></i>
        </button>

        {{-- Notifications --}}
        @php $unread = $user->unreadNotifications()->count(); @endphp
        <div class="dropdown">
            <button type="button" class="icon-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <i class="fa-regular fa-bell"></i>
                @if ($unread > 0)
                    <span class="badge-dot">{{ $unread > 9 ? '9+' : $unread }}</span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-menu">
                <div class="menu-head d-flex justify-content-between align-items-center">
                    <span>Notifications</span>
                    <span class="badge text-bg-primary rounded-pill">{{ $unread }}</span>
                </div>
                @forelse ($user->unreadNotifications()->limit(5)->get() as $notification)
                    <div class="notification-item">
                        <span class="avatar avatar-sm"><i class="fa-regular fa-envelope"></i></span>
                        <div>
                            <div>{{ $notification->data['message'] ?? 'New notification' }}</div>
                            <small class="text-body-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="notification-item justify-content-center text-body-secondary py-4">
                        <i class="fa-regular fa-bell-slash me-2"></i> You're all caught up
                    </div>
                @endforelse
            </div>
        </div>

        {{-- User menu --}}
        <div class="dropdown">
            <button type="button" class="btn border-0 d-flex align-items-center gap-2 px-2" data-bs-toggle="dropdown" aria-expanded="false">
                @if ($user->avatarUrl())
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="avatar">
                @else
                    <span class="avatar">{{ $user->initials() }}</span>
                @endif
                <span class="d-none d-lg-block text-start">
                    <span class="d-block fw-semibold" style="font-size: .85rem;">{{ $user->name }}</span>
                    <span class="badge {{ $user->role->badgeClass() }}" style="font-size: .6rem;">{{ $user->role->label() }}</span>
                </span>
                <i class="fa-solid fa-chevron-down d-none d-lg-inline" style="font-size: .65rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ $user->email }}</h6></li>
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Sign out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
