@props([
    'label',
    'value',
    'icon' => 'fa-chart-simple',
    'tint' => 'primary',   // primary | success | info | warning | danger | violet
    'trend' => null,       // small helper line under the value
    'trendIcon' => null,
])

<div {{ $attributes->merge(['class' => 'card stat-card h-100']) }}>
    <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon icon-tint-{{ $tint }}">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
        <div class="min-w-0">
            <div class="stat-value">{{ $value }}</div>
            <div class="stat-label text-truncate">{{ $label }}</div>
            @if ($trend)
                <div class="stat-trend text-body-secondary">
                    @if ($trendIcon)<i class="fa-solid {{ $trendIcon }} me-1"></i>@endif{{ $trend }}
                </div>
            @endif
        </div>
    </div>
</div>
