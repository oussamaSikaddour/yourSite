<nav class="breadcrumb" aria-label="Breadcrumb">
    <ol>
        @foreach ($breadcrumbLinks as $link)
            <li class="breadcrumb__item ">
                    <a href="{{ $link['url'] }}">
                        {{ $link['label'] }}
                    </a>
                    <svg class="breadcrumb__icon"
                         viewBox="0 0 20 20"
                         fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                              clip-rule="evenodd"/>
                    </svg>

            </li>
        @endforeach
    </ol>
</nav>
