@extends('layouts.default')

@section('seo')
    <x-seo>
        <x-slot:title>Sobre mim 🤓</x-slot>
        <x-slot:description>Veja um pequeno resumo de quem sou eu e dos meus trabalhos 🚀</x-slot>
    </x-seo>
@endsection

@section('mainClass', 'site-about')
@section('containerAroundClass', 'container container-page')

@section('content')
    <section class="about-page">
        <div class="about-page__intro">
            <div class="about-page__intro-hi">
                <h1 class="about-page__intro-hey">Oi, me chamo <strong class="about-page__intro-name">Alyson Silva</strong> <span class="hand">👋</span></h1>
                <h2 class="about-page__intro-skills"></h2>
            </div>
            <div class="about-page__image">
                <figure>
                    <img src="{{ mix("/images/pages/developer.svg") }}">
                </figure>
            </div>
        </div>

        <div class="about-page__me">
            <p>Meu nome é Alyson Silva. Trabalho como Desenvolvedor Back-end desde 2014. Sou Cearense, de Fortaleza, mas moro em São Paulo há alguns anos. Gosto de ajudar a comunidade disseminando conhecimento por meio de projetos open source e por meio de posts (como esse blog), pois acredito que a disseminação de conhecimento e boas ideias inspira e melhora o mundo.</p>
            <p>Sou apaixonado por estudar, aprender e por desenvolver / arquitetar soluções complexas de softwares, pois me inspira a sair da zona de conforto / simplicidade e crescer como pessoa e profissional.</p>
            <p>Atualmente estou trabalhando como Engenheiro de Software na Pie Tax.</p>
            <p>No tempo livre gosto de estudar e aperfeiçoar meus conhecimentos. Gosto mais ainda de estar com minha esposa e meu filho 👨‍👩‍👦 fazendo coisas em família.</p>
        </div>

        <div class="about-page__info">
            <ul>
                <li><i class="fab fa-github"></i> &nbsp;<a target="_blank" href="https://github.com/allysonsilva" title="GitHub: Alyson Silva">@allysonsilva</a></li>
                <li><i class="fab fa-twitter"></i> &nbsp;<a target="_blank" href="https://twitter.com/alysonsilvadev" title="Twitter: Alyson Silva">@alysonsilvadev</a></li>
                <li><i class="fab fa-linkedin"></i> &nbsp;<a target="_blank" href="https://linkedin.com/in/alysonsilvadev" title="LinkedIn: Alyson Silva">@alysonsilvadev</a></li>
                <li><i class="far fa-envelope"></i> &nbsp;<a target="_blank" href="mailto:hi@alyson.dev">hi@alyson.dev</a></li>
                <li><i class="fas fa-map-marker-alt"></i> &nbsp;São Paulo, Brasil</li>
            </ul>
        </div>

        <div class="about-page__info-text">
            <h2 class="about-page__info-text-title">Algumas tecnologias que trabalho ou já trabalhei</h2>
            <p class="about-page__info-text-content">I have <strong>more than {{ now()->diffInYears(now()->parse('September 1nd, 2014')) }} years of experience</strong> working in a wide variety of projects: from small <strong>personal websites</strong> to <strong>full-fledge web applications</strong> or complex projects built with <strong>microservices and REST APIs</strong>. Here are some of the technologies I use regularly:</p>
        </div>

        <div class="about-page__interests">
            <div class="about-page__interests-wrapper">
                <table class="about-page__interests-table">
                    <thead>
                        <tr>
                            <th>Back-end</th>
                            <th>Databases</th>
                            <th>CI/CD</th>
                            <th>Cloud/Servers</th>
                            <th>Others</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="{{ mix("/storage/images/icons/php-plain.svg") }}" alt="PHP">&nbsp;<span class="skill-label">PHP</span></td>
                            <td><img src="{{ mix("/storage/images/icons/mysql-plain.svg") }}" alt="MySQL"><span class="skill-label">MySQL</span></td>
                            <td><img src="{{ mix("/storage/images/icons/github.svg") }}" alt="GitHub Actions"><span class="skill-label">GitHub Actions</span></td>
                            <td><img src="{{ mix("/storage/images/icons/traefikio-icon.svg") }}" alt="Traefik"><span class="skill-label">Traefik</span></td>
                            <td><img src="{{ mix("/storage/images/icons/rabbitmq-plain.svg") }}" alt="RabbitMQ"><span class="skill-label">RabbitMQ</span></td>
                        </tr>
                        <tr>
                            <td><img src="{{ mix("/storage/images/icons/laravel-plain.svg") }}" alt="Laravel"><span class="skill-label">Laravel</span></td>
                            <td><img src="{{ mix("/storage/images/icons/mongodb-plain.svg") }}" alt="MongoDB"><span class="skill-label">MongoDB</span></td>
                            <td><img src="{{ mix("/storage/images/icons/docker-plain.svg") }}" alt="Docker"><span class="skill-label">Docker</span></td>
                            <td><img src="{{ mix("/storage/images/icons/nginx-plain.svg") }}" alt="Nginx"><span class="skill-label">Nginx</span></td>
                            <td><img src="{{ mix("/storage/images/icons/git-plain.svg") }}" alt="Git"><span class="skill-label">Git</span></td>
                        </tr>
                        <tr>
                            <td><img src="{{ mix("/storage/images/icons/golang.svg") }}" alt="Go"><span class="skill-label">Go</span></td>
                            <td><img src="{{ mix("/storage/images/icons/redis-plain.svg") }}" alt="Redis"><span class="skill-label">Redis</span></td>
                            <td><img src="{{ mix("/storage/images/icons/kubernetes.svg") }}" alt="Kubernetes"><span class="skill-label">Kubernetes</span></td>
                            <td><img src="{{ mix("/storage/images/icons/aws.svg") }}" alt="AWS"><span class="skill-label">AWS</span></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="about-page__interests-linkedin-text">If you want to know more details about my experience, you can visit <a href="https://linkedin.com/in/alysonsilvadev/" target="_blank" rel="noreferrer">my LinkedIn profile</a>.</p>
        </div>

        <div class="about-page__info-text">
            <h2 class="about-page__info-text-title">Meus projetos / Open Source</h2>
            <p class="about-page__info-text-content">Nos últimos anos desenvolvi um monte de projetos open source, como:</p>
        </div>

        <div class="about-page__repositories">
            @foreach ($repositoriesData as $repository)
                <div class="about-page__repositories-component">
                    <h4 class="repository-title">
                        <a href="{{ $repository->url }}" target="_blank" class="repository-link without-heading-link-style">
                            <svg height="20" fill="#586069" aria-label="repo" viewBox="0 0 12 16" version="1.1" width="15" role="img"><path fill-rule="evenodd" d="M4 9H3V8h1v1zm0-3H3v1h1V6zm0-2H3v1h1V4zm0-2H3v1h1V2zm8-1v12c0 .55-.45 1-1 1H6v2l-1.5-1.5L3 16v-2H1c-.55 0-1-.45-1-1V1c0-.55.45-1 1-1h10c.55 0 1 .45 1 1zm-1 10H1v2h2v-1h3v1h5v-2zm0-10H2v9h9V1z"/></svg>{{ $repository->name }}
                        </a>
                    </h4>
                    <p class="repository-description">{{ $repository->description }}</p>
                    <div class="repository-info">
                        <div class="language-wrapper">
                            <span class="language-color background-color-{{ strtolower($repository->language) }}"></span>
                            <span class="language-name" itemprop="programmingLanguage">{{ $repository->language }}</span>
                        </div>
                        <a target="_blank" href="{{ $repository->url }}/stargazers" class="repository-link-stars without-style">
                            <svg class="repo-language-svg-icon" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true" role="img"><path fill-rule="evenodd" d="M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25zm0 2.445L6.615 5.5a.75.75 0 01-.564.41l-3.097.45 2.24 2.184a.75.75 0 01.216.664l-.528 3.084 2.769-1.456a.75.75 0 01.698 0l2.77 1.456-.53-3.084a.75.75 0 01.216-.664l2.24-2.183-3.096-.45a.75.75 0 01-.564-.41L8 2.694v.001z"/></svg>{{ $repository->stargazersCount }}
                        </a>
                        <a target="_blank" href="{{ $repository->url }}/network/members" class="repository-link-forks without-style">
                            <svg class="repo-language-svg-icon" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true" role="img"><path fill-rule="evenodd" d="M5 3.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm0 2.122a2.25 2.25 0 10-1.5 0v.878A2.25 2.25 0 005.75 8.5h1.5v2.128a2.251 2.251 0 101.5 0V8.5h1.5a2.25 2.25 0 002.25-2.25v-.878a2.25 2.25 0 10-1.5 0v.878a.75.75 0 01-.75.75h-4.5A.75.75 0 015 6.25v-.878zm3.75 7.378a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm3-8.75a.75.75 0 100-1.5.75.75 0 000 1.5z"/></svg>{{ $repository->forksCount }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="about-page__contact">
            <h3 class="about-page__contact-title">Contact <span>Me</span></h3>
            <h4 class="about-page__contact-subtitle">Contracting &amp; Freelance</h4>
            <p class="about-page__contact-text">Looking for a developer? I'd love to chat through your requirements &amp; help you build something awesome. Please reach out to me via <a href="mailto:hi@alyson.dev">email</a> and I'll get back to you as soon as I can.</p>
            <h4 class="about-page__contact-subtitle">Anything Else</h4>
            <p class="about-page__contact-text">If you want to get in touch to chat about programming, product or anything else, flick me an <a href="mailto:hi@alyson.dev">email</a> or send me a DM on <a href="https://twitter.com/alysonsilvadev">twitter</a>. I'd love to hear from you!</p>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
    <script>
        "use strict";

        let aboutPageDesc = document.querySelector('.about-page__intro-skills');
        let typewriter = new Typewriter(aboutPageDesc, {
            loop: true,
            autoStart: true,
            delay: 75,
            strings: [
                "",
                'um <span class="about-page__intro-skills-label">Engenheiro de Software</span>',
                'um <span class="about-page__intro-skills-label">Dev Blogger</span>'
            ],
        });
    </script>
@endpush
