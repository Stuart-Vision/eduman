@props(['title'])

<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3 fade-up">
    <div>
        <h1>{{ $title }}</h1>
        @isset($breadcrumbs)
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    {{ $breadcrumbs }}
                </ol>
            </nav>
        @endisset
    </div>
    @isset($actions)
        <div class="d-flex gap-2">{{ $actions }}</div>
    @endisset
</div>
