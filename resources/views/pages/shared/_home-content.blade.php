<section id='about' class='about-me'>
    <div class='about-me__intro'>
        <h4 class='about-me__hi'>Olá! <span class='hand'>👋</span></h4>
        <h6 class='about-me__info'>
            Me chamo Alyson Silva, e sou <strong>Desenvolvedor Back-end</strong>.
            Este blog contêm os estudos que eu faço no meu dia a dia e estou compartilhando com a comunidade.
        </h6>
    </div>
    <div class='about-me__image'>
        <figure>
            <img src='{{ mix('/images/me/AlysonSilva.png') }}' alt='My image'>
        </figure>
    </div>
</section>

<section class='list-posts'>
    <h2 class='text-info'> Posts 📚 </h2>
    <hr>

    @foreach ($articles as $article)
        <article class='post-preview'>
            <header class='post-preview__header'>
                <h1 class='post-preview__title'>
                    <a href='{{ $article->url }}' title="{{ $article->title }}">{{ $article->title }}</a>
                </h1>
                <div class='post-preview__meta'>
                    <time class='post-preview__date' datetime='{{ $article->human_readable_created }}'><i class='far fa-calendar-alt'></i>&nbsp;&nbsp;{{ $article->long_created }}<span> · ☕️&nbsp;&nbsp;{{ $article->read_duration }}</span></time>
                </div>
            </header>

            <div class='post-preview__content'>
                <h2 class='post-preview__subtitle'>{{ $article->summary }}</h2>
            </div>

            <div class='post-preview__footer'>
                <div class='post-preview__link'>
                    <a href='{{ $article->url }}' class='btn without-style'>
                        Continuar Leitura&nbsp;&nbsp;<i class='fas fa-arrow-circle-right'></i>
                    </a>
                </div>

                @if ($article->tags->isNotEmpty())
                    <div class="post-preview__tags">
                        <i class="fas fa-tags fa-fw"></i>
                        @foreach ($article->tags as $tag)
                            <a href="{{ route('web.blog.posts', ['tag' => $tag->slug]) }}" class="without-style">#{{ $tag->title }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </article>
    @endforeach
</section>

<div class='link-see-all-posts'>
    <a href="{{ route('web.blog.posts') }}" class='btn without-style'>Ver todos os posts</a>
</div>
