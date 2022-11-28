<article class="post-single" itemscope itemtype="http://schema.org/NewsArticle">
    <header class="post-header">
        <h1 itemprop="name headline" class="post-header-title" id="post-title"><x-markdown :content="$post->title"/></h1>
        @isset ($post->github_uri)
            <a href="{{ $post->github_uri }}" class="with-icon" target="_blank" rel="noopener noreferrer">Editar no GitHub <i class="fas fa-edit icon"></i></a>
        @endisset
        <h2 itemprop="description" class="post-header-subtitle without-before no-anchor">{{ $post->summary }}</h2>

        <div class="post-meta">
            <div class="post-meta-published">
                <time datetime="{{ $post->human_readable_created }}">
                    <span itemprop="datePublished" class="post-date">{{ $post->long_created }}</span>
                    <span> · ☕️&nbsp;&nbsp;{{ $post->read_duration }}</span>
                </time>
            </div>
        </div>

        @if ($post->tags->isNotEmpty())
            <div class="post-tags">
                @foreach ($post->tags as $tag)
                    <a href="{{ route('web.blog.posts', ['tag' => $tag->slug]) }}" class="post-tags-item without-style">#{{ $tag->title }}</a>&nbsp;
                @endforeach
            </div>
        @endif
    </header>

    <section class="post-content">
        <div id="markdown-content" class="wrap-content" itemprop="articleBody">
            @markdown($post->content)
        </div>

        <div class="like-container">
            <button class="btn without-style @if ($hasLike) primary @else light @endif like" @if ($hasLike) value="liked" @else value="unliked" @endif data-slug="{{ $post->slug }}">
                👍 <span class="counter" @if (! $hasLike) hidden @endif>@if ($hasLike) {{ $post->likes ?? 0 }} @endif</span>
            </button>
        </div>
    </section>

    <footer class="post-footer">
        <div class="post-info-pull-request">
            <h5 class="post-info-pull-request__label without-before no-anchor">Encontrou algum problema no texto? Me ajude a corrigir! </h5>
            <p class="post-info-pull-request__description">
                Esse projeto é open source, então basta alterar o arquivo de texto, <a href="{{ $post->github_uri }}" target="_blank" rel="noopener noreferrer" title="Alterar texto">diretamente no Github</a> e abrir um pull-request.
            </p>
        </div>

        <div class="post-info-share">
            <span> Compartilhar Post: </span>
            <div class="post-info__share-buttons">
                <div class="to-twitter" title="Share this on Twitter" onclick="window.open('http://twitter.com/home?status={{ url()->current() }}');">
                    <svg width="28px" height="28px" viewBox="0 0 28 28" fill="#ABABAB"><path d="M8.991284,24.971612 C19.180436,24.971612 24.752372,16.530224 24.752372,9.210524 C24.752372,8.970656 24.747512,8.731868 24.736496,8.494376 C25.818008,7.712564 26.758256,6.737 27.5,5.62622 C26.507372,6.067076 25.439252,6.364292 24.318752,6.498212 C25.462472,5.812628 26.340512,4.727444 26.754584,3.434036 C25.684088,4.068536 24.499004,4.53002 23.23724,4.778528 C22.226468,3.701876 20.786828,3.028388 19.193828,3.028388 C16.134404,3.028388 13.653536,5.509256 13.653536,8.567492 C13.653536,9.0023 13.702244,9.424904 13.797176,9.830552 C9.19346,9.599108 5.11106,7.39472 2.3792,4.04294 C1.903028,4.861364 1.629032,5.812628 1.629032,6.827072 C1.629032,8.74904 2.606972,10.445612 4.094024,11.438132 C3.185528,11.41016 2.331788,11.160464 1.585184,10.745096 C1.583888,10.768208 1.583888,10.791428 1.583888,10.815728 C1.583888,13.49888 3.493652,15.738584 6.028088,16.246508 C5.562932,16.373084 5.07326,16.44134 4.56782,16.44134 C4.210988,16.44134 3.863876,16.406024 3.526484,16.34144 C4.231724,18.542264 6.276596,20.143796 8.701412,20.18894 C6.805148,21.674696 4.416836,22.56008 1.821488,22.56008 C1.374476,22.56008 0.93362,22.534592 0.5,22.4834 C2.951708,24.054476 5.862524,24.971612 8.991284,24.971612"></path></svg>
                </div>
                <div class="to-linkedin" title="Share this on Linkedin" onclick="window.open('https://linkedin.com/shareArticle?mini=true&amp;url={{ url()->current() }}&amp;title=&amp;summary=&amp;source=');">
                    <svg width="28px" height="28px" viewBox="0 0 28 28" fill="#ABABAB"><path d="M2,3.654102 C2,2.69908141 2.79442509,1.92397846 3.77383592,1.92397846 L24.2261641,1.92397846 C25.2058917,1.92397846 26,2.69908141 26,3.654102 L26,24.3462148 C26,25.3015521 25.2058917,26.0760215 24.2261641,26.0760215 L3.77383592,26.0760215 C2.79442509,26.0760215 2,25.3015521 2,24.3465315 L2,3.65378524 L2,3.654102 Z M9.27526132,22.1415901 L9.27526132,11.2356668 L5.65030092,11.2356668 L5.65030092,22.1415901 L9.27557808,22.1415901 L9.27526132,22.1415901 Z M7.46341463,9.74691162 C8.72727273,9.74691162 9.51409566,8.90940767 9.51409566,7.86284447 C9.49033893,6.79252455 8.72727273,5.97846056 7.48748812,5.97846056 C6.24675325,5.97846056 5.43649034,6.79252455 5.43649034,7.86284447 C5.43649034,8.90940767 6.22299652,9.74691162 7.4396579,9.74691162 L7.46309788,9.74691162 L7.46341463,9.74691162 Z M11.2815965,22.1415901 L14.9062401,22.1415901 L14.9062401,16.0519481 C14.9062401,15.7263225 14.9299968,15.4000634 15.0256573,15.1675641 C15.2876148,14.5159962 15.8840672,13.8416218 16.8856509,13.8416218 C18.1970225,13.8416218 18.7218879,14.8416218 18.7218879,16.3078872 L18.7218879,22.1415901 L22.3465315,22.1415901 L22.3465315,15.8885017 C22.3465315,12.5388027 20.5584416,10.9800443 18.1735825,10.9800443 C16.2182452,10.9800443 15.3595185,12.072854 14.8824834,12.8172315 L14.9065569,12.8172315 L14.9065569,11.2359835 L11.2819132,11.2359835 C11.3291099,12.2591067 11.2815965,22.1419069 11.2815965,22.1419069 L11.2815965,22.1415901 Z"></path></svg>
                </div>
                <div class="to-facebook" title="Share this on Facebook" onclick="window.open('http://facebook.com/share.php?u={{ url()->current() }}');">
                    <svg width="28px" height="28px" viewBox="0 0 28 28" fill="#ABABAB"><path d="M27,14.0789648 C27,6.89925781 21.179707,1.07896484 14,1.07896484 C6.82029297,1.07896484 1,6.89925781 1,14.0789648 C1,20.5676406 5.75391211,25.9457813 11.96875,26.9210352 L11.96875,17.8367773 L8.66796875,17.8367773 L8.66796875,14.0789648 L11.96875,14.0789648 L11.96875,11.2149023 C11.96875,7.95677734 13.9095586,6.15708984 16.879043,6.15708984 C18.3013496,6.15708984 19.7890625,6.41099609 19.7890625,6.41099609 L19.7890625,9.61021484 L18.149793,9.61021484 C16.534873,9.61021484 16.03125,10.6123066 16.03125,11.640373 L16.03125,14.0789648 L19.6367188,14.0789648 L19.0603516,17.8367773 L16.03125,17.8367773 L16.03125,26.9210352 C22.2460879,25.9457813 27,20.5676406 27,14.0789648"></path></svg>
                </div>
                <div class="to-mail" title="Share this through Email" onclick="window.open('mailto:hi@alyson.dev?body={{ url()->current() }}');">
                    <svg width="28px" height="28px" viewBox="0 0 28 28" fill="#ABABAB"><path d="M25.2794292,5.59128519 L14,16.8707144 L2.72057081,5.59128519 C3.06733103,5.30237414 3.51336915,5.12857603 4,5.12857603 L24,5.12857603 C24.4866308,5.12857603 24.932669,5.30237414 25.2794292,5.59128519 Z M25.9956978,6.99633695 C25.998551,7.04004843 26,7.08414302 26,7.12857603 L26,20.871424 C26,21.0798433 25.9681197,21.2808166 25.9089697,21.4697335 L18.7156355,14.2763993 L25.9956978,6.99633695 Z M24.9498374,22.6319215 C24.6672737,22.7846939 24.3437653,22.871424 24,22.871424 L4,22.871424 C3.5268522,22.871424 3.09207889,22.7071233 2.74962118,22.432463 L10.0950247,15.0870594 L13.9848068,18.9768415 L14.1878486,18.7737996 L14.2030419,18.7889929 L17.6549753,15.3370594 L24.9498374,22.6319215 Z M2.00810114,21.0526627 C2.00273908,20.9929669 2,20.9325153 2,20.871424 L2,7.12857603 C2,7.08414302 2.00144896,7.04004843 2.00430222,6.99633695 L9.03436454,14.0263993 L2.00810114,21.0526627 Z"></path></svg>
                </div>
            </div>
        </div>
    </footer>
</article>

<aside class="post-next-prev">
    <nav class="post-next-prev-nav">
        @empty(! $previousPost)
            <div class="post-next-prev__item">
                <span class="post-next-prev__label">Anterior</span>
                <a href="{{ $previousPost->url }}" class="post-next-prev__link link--previous" title="{{ $previousPost->title }}">
                    <article class="post-next-prev__box">
                        <time class="post-next-prev__datetime" datetime="{{ $previousPost->human_readable_created }}">{{ $previousPost->long_created }}</time>
                        <h1 class="post-next-prev__title without-before no-anchor">{{ $previousPost->title }}</h1>
                    </article>
                </a>
            </div>
        @endempty

        @empty(! $nextPost)
            <div class="post-next-prev__item">
                <span class="post-next-prev__label">Próximo</span>
                <a href="{{ $nextPost->url }}" class="post-next-prev__link link--next" title="{{ $nextPost->title }}">
                    <article class="post-next-prev__box">
                        <time class="post-next-prev__datetime" datetime="{{ $nextPost->human_readable_created }}">{{ $nextPost->long_created }}</time>
                        <h1 class="post-next-prev__title without-before no-anchor">{{ $nextPost->title }}</h1>
                    </article>
                </a>
            </div>
        @endempty
    </nav>
</aside>

<section class="blog-post-comments"></section>
