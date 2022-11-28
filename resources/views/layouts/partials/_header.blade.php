<header class='site-header'>
    <nav class='container site-header__nav'>
        <div class='site-header__name'>
            <a href='{{ url('/') }}' class='without-after without-focus'>
                <h1 class='site-title'>
                    <strong class='highlight'>{A}</strong> <strong class='highlight'>//alysonsilva</strong>.dev
                </h1>
            </a>
        </div>

        <div class='site-header__categories'>
            <x-menu-categories/>
        </div>

        <div class='site-header__search'>
            <div class="site-header__search-input">
                <svg fill='currentColor' viewBox='0 0 24 24' class='icon-search'><path d='M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z'></path></svg>
                <form action="{{ route('web.blog.posts') }}" method="GET">
                    {{-- @csrf --}}

                    <input type='text' name="search" placeholder='Search posts (Press "Enter" to search)' class='input-search'>
                    <input type="submit" hidden />
                </form>
            </div>

            {{-- <div class="site-header__search-filter" style="display: none;">
                <section>
                    @foreach(range(1, 15) as $item)
                        <a href="#" class="search__link without-style">
                            <h1 class="search__title">Title</h1>
                            <h2 class="search__subtitle">Content...</h2>
                        </a>
                    @endforeach
                </section>
            </div> --}}
        </div>

        <div class='site-header__menu'>
            <ul class='site-header__menu-links'>
                <li class='link-item'><a href='{{ route('web.about-me') }}'>Hi</a></li>
                <li class='link-item'><a href='{{ route('web.blog.posts') }}'>Posts</a></li>
                {{-- <li class='link-item'><a href='{{ route('web.tags.show') }}'>Tags</a></li> --}}
                {{-- <li class='icon-item'>
                    <a href='#' aria-label='twitter' class='icon-item__link color-twitter without-style' target='_blank' rel='noopener'>
                        <svg viewBox='0 0 24 24' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' aria-hidden='true'>
                            <path d='M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z'></path>
                        </svg>
                    </a>
                </li>
                <li class='icon-item'>
                    <a href='#' aria-label='github' class='icon-item__link color-github without-style' target='_blank' rel='noopener'>
                        <svg viewBox='0 0 24 24' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' aria-hidden='true'>
                            <path d='M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22'></path>
                        </svg>
                    </a>
                </li>
                <li class='icon-item'>
                    <a href='mailto:hi@alyson.dev' aria-label='email' class='icon-item__link color-email without-style' target='_blank'
                        rel='noopener'>
                        <svg viewBox='0 0 24 24' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' aria-hidden='true'>
                            <path d='M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z'></path><polyline points='22,6 12,13 2,6'></polyline>
                        </svg>
                    </a>
                </li> --}}
                <li class='search-link-item'>
                    <a href="#" class="search-link without-style">
                        <svg class="search-link-svg" width="10" height="10" viewBox="0 0 40 40">
                            <path d="M26.804 29.01c-2.832 2.34-6.465 3.746-10.426 3.746C7.333 32.756 0 25.424 0 16.378 0 7.333 7.333 0 16.378 0c9.046 0 16.378 7.333 16.378 16.378 0 3.96-1.406 7.594-3.746 10.426l10.534 10.534c.607.607.61 1.59-.004 2.202-.61.61-1.597.61-2.202.004L26.804 29.01zm-10.426.627c7.323 0 13.26-5.936 13.26-13.26 0-7.32-5.937-13.257-13.26-13.257C9.056 3.12 3.12 9.056 3.12 16.378c0 7.323 5.936 13.26 13.258 13.26z"></path>
                        </svg>
                    </a>
                </li>
                <li id="menu-toggle-item" class="menu-toggle-item">
                    <svg class="menu-icon-svg" width="1000px" height="1000px">
                        <path id="pathA" d="M 300 400 L 700 400 C 900 400 900 750 600 850 A 400 400 0 0 1 200 200 L 800 800"></path>
                        <path id="pathB" d="M 300 500 L 700 500"></path>
                        <path id="pathC" d="M 700 600 L 300 600 C 100 600 100 200 400 150 A 400 380 0 1 1 200 800 L 800 200"></path>
                    </svg>
                    <button id="menu-icon-trigger" class="menu-icon-trigger"></button>
                </li>
                <li class='dark-mode-item'>
                    <a id="js-theme-toggle" href='#' class='mode-light icon solid fa-moon without-style' title='Ativar/Desativar Dark Mode'></a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<nav id="menu-mobile" class="menu-mobile">
    <ul>
        <li class="menu-mobile__item">
            <div class='site-header__categories menu-mobile__item__categories'>
                <x-menu-categories/>
            </div>
        </li>

        <li class="menu-mobile__item">
            <a href="{{ route('web.about-me') }}" title="Um pouco sobre mim" class="btn btn--light without-style">Hi</a>
        </li>

        <li class="menu-mobile__item">
            <a href="{{ route('web.blog.posts') }}" title="Ver todos os posts" class="btn btn--light without-style">Posts</a>
        </li>
    </ul>
</nav>
