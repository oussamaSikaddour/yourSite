<footer
    class="footer"
    x-on:logo-updated.window="$wire.$refresh()"
>
    <p class="text-light">&#169; SO 2023 - {{ $currentYear }}</p>
    <a href="#" title="Go to homepage">
        <img
            class="logo"
            src="{{ $this->logoUrl }}"
            alt="Company Logo"
        />
    </a>
</footer>
