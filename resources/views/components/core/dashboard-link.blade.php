<a class="dashboard__link" href="{{ $routeUrl }}">
    <div class="dashboard__link__img">
        <img
            src="{{ asset(($app ? 'assets/app/icons/' : 'assets/core/icons/') . $img.'.png') }}"
            alt="{{ e($label) }}"
        >
    </div>

    <strong>{{ $label }}</strong>
</a>
