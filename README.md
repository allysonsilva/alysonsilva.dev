# Alyson Silva - Site pessoal

<img src="https://forge.laravel.com/build/assets/octane.1f009bcf.svg" width="200"/>

[![Continuous Deployment](https://github.com/allysonsilva/alysonsilva.dev/actions/workflows/Continuous%20Deployment.yml/badge.svg)](https://github.com/allysonsilva/alysonsilva.dev/actions/workflows/Continuous%20Deployment.yml)

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-777BB4?logo=php&cacheSeconds=30000)](https://www.php.net)

[![Laravel Version](https://img.shields.io/static/v1?label=laravel&message=%E2%89%A510.0&color=ff2d20&logo=laravel)](https://laravel.com)

[![Laravel Octane Version](https://img.shields.io/static/v1?label=laravel+octane&message=%E2%89%A52.0&color=ff2d20&logo=laravel)](https://github.com/laravel/octane)

## Tecnologias Utilizadas

- [**Workbox**](https://developer.chrome.com/docs/workbox/) para simplificar a manipulação de [**Service Worker**](resources/js/service-worker-workbox.js).
- [**Service Worker**](resources/js/install-sw-workbox.js) para manipular os artigos de forma _offline-first_.
- [**Laravel Octane**](https://github.com/laravel/octane)
- [_Web Push Notifications_](https://web.dev/articles/push-notifications-overview?hl=pt-br)

## Continuous Deployment

A cada atualização no código fonte é feito um novo deploy e atualizado de forma automática o servidor do site/blog.

O arquivo dessa configuração: [Continuous Deployment.yml](.github/workflows/Continuous%20Deployment.yml).

## Docker

O blog / site, utiliza o **docker** no repositório de [allysonsilva/blog-docker](https://github.com/allysonsilva/blog-docker).

