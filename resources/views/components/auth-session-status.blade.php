@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success py-2 small']) }} role="alert">
        <i class="fa-solid fa-circle-check me-1"></i>{{ $status }}
    </div>
@endif
