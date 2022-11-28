<footer class='site-footer'>
    <div class='container site-footer__container'>
        <div class='site-footer__info'>
            <section class='site-footer__info-copyright'>
                Copyright © {{ config('app.hostname') }} {{ date('Y') }} <span class='copyright-bullet'>•</span>
                <a class="link" href="{{ route('web.policies.show') }}">Política de Privacidade</a> <span class='copyright-bullet'>•</span>
                <a class="link" href="https://github.com/allysonsilva/blog-posts/blob/main/LICENSE" target="_blank" rel="noopener noreferrer">Termos de Uso</a>
            </section>
            <section class='info__public'>
                <a href='{{ mix('/feed.xml') }}' class='link-rss-feed'>RSS Feed</a> |
                <a href='{{ mix('/sitemap.xml') }}' class='at-link-sitemap'>Sitemap</a> |
                <button class="btn without-style light small enable-notifications">Ativar Notificações</button>
                @auth I'm AUTHENTICATED @endauth
            </section>
        </div>

        <div class='site-footer__public'>
            <ul class='site-footer__links'>
                <li class='social-link__item'>
                    <a href='https://twitter.com/alysonsilvadev' class='color-twitter without-style' target='_blank'>
                        <i class='fab fa-twitter'></i>
                    </a>
                </li>
                <li class='social-link__item'>
                    <a href='https://github.com/allysonsilva' class='color-github without-style' target='_blank'>
                        <i class='fab fa-github'></i>
                    </a>
                </li>
                <li class='social-link__item'>
                    <a href='mailto:hi@alyson.dev' class='color-email without-style' target='_blank'>
                        <i class='far fa-envelope'></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>
