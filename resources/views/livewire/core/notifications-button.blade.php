<li class="nav__item nav__item--dropDown" wire:poll.60s="refreshNotifications">
    <div id="notifications-btn" tabindex="0" class="nav__btn nav__btn--dropdown" aria-expanded="false"
        aria-controls="subItems" aria-label="Show Notifications menu" aria-labelledby="notifications-btn">
        <i class="fa-solid fa-bell" aria-hidden="true"></i>

        @if ($notificationsCount > 0)
            <span class="badge">{{ $notificationsCount }}</span>
        @endif
    </div>

    @if ($notificationsCount > 0)
        <ul id="subItems" class="nav__items--sub" hidden role="menu">
            @foreach ($this->notifications() as $n)
                <li role="none" wire:click="manageNotification({{ $n->id }})">
                    @lang($this->getNotificationMessageKey($n))
                </li>
            @endforeach
        </ul>
    @endif
</li>

@push('scripts')
    <script>
        $wire.on('refresh-notifications', () => {
            $wire.$refresh();
        })
    </script>
@endpush
