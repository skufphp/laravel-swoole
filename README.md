# Laravel Swoole Lab

Тестовый проект для проверки сборки и запуска Laravel на базе boilerplate `Laravel Octane + Swoole`.

Репозиторий используется как лабораторный стенд для Laravel-приложения, собранного на связке:

- Laravel Octane + Swoole
- PostgreSQL
- Redis
- Node.js / Vite
- Docker Compose для локальной разработки, локального production-like запуска и server-side production compose

Отдельно проект подготовлен так, чтобы его было удобно использовать для деплоя через Dokploy: production-конфигурация уже вынесена в отдельные compose-файлы и ориентирована на контейнерный запуск.

## Что здесь проверяется

- Сборка Laravel-приложения с запуском через Laravel Octane и Swoole
- Dev-режим с примонтированным кодом, Vite и Xdebug
- HTTP-обслуживание запросов напрямую через Swoole без отдельного Nginx-контейнера
- Production-сборка с multi-stage Dockerfile
- Запуск очередей и scheduler в отдельных контейнерах
- Отдельные production compose-файлы для локальной проверки и серверного деплоя
- Работа healthcheck и управление воркерами через Octane
- Готовность проекта к деплою через Docker Compose / Dokploy

## Основа проекта

Этот репозиторий использует архитектуру Laravel-приложения с Laravel Octane и Swoole:

- основной HTTP-сервис поднимается внутри контейнера `laravel-swoole`
- Swoole обслуживает приложение на порту `8000` внутри контейнера
- PostgreSQL используется как основная БД
- Redis используется для кеша, сессий и очередей
- Node-контейнер отвечает за frontend-сборку и Vite HMR
- Production-сборка выполняется через multi-stage Dockerfile

## Структура

- `docker/` — Dockerfile и конфигурация PHP
- `docker-compose.yml` — окружение для разработки
- `docker-compose.prod.local.yml` — локальный запуск production-профиля с публикацией `APP_PORT`
- `docker-compose.prod.yml` — production-конфигурация для серверного деплоя
- `Makefile` — основные команды для разработки и обслуживания
- `.env.example` — шаблон переменных для dev
- `.env.production` — production-like переменные для локального запуска

## Быстрый старт для разработки

1. Скопируйте переменные окружения:

```bash
cp .env.example .env
```

2. Заполните `.env` реальными значениями.

Минимально проверьте:

- `APP_*`
- `DB_*`
- `REDIS_*`
- `APP_PORT`
- `DB_FORWARD_PORT`
- `REDIS_FORWARD_PORT`
- `PGADMIN_*`
- `SWOOLE_WORKERS`
- `SWOOLE_TASK_WORKERS`
- `SWOOLE_MAX_REQUESTS`
- `XDEBUG_*` при необходимости

3. Запустите инициализацию проекта:

```bash
make setup
```

Команда:

- соберет dev-образы
- поднимет контейнеры
- дождется готовности PostgreSQL и Redis
- установит Composer и NPM зависимости
- сгенерирует `APP_KEY`
- выполнит миграции
- выставит права на `storage/` и `bootstrap/cache`

После запуска будут доступны:

- Laravel через Swoole: `http://localhost:8050` по умолчанию, порт берется из `APP_PORT`
- Vite dev server: `http://localhost:5173`
- pgAdmin: `http://localhost:8080` по умолчанию, порт берется из `PGADMIN_PORT`

Если нужна ручная последовательность, используйте:

```bash
make build
make up
make install-deps
make artisan CMD="key:generate"
make migrate
make permissions
```

## Основные команды

### Development

```bash
make up
make down
make restart
make build
make rebuild
make logs
make logs-app
make logs-postgres
make logs-redis
make logs-node
make logs-queue
make logs-scheduler
make logs-pgadmin
make status
make validate
make info
```

### Laravel / PHP / Node

```bash
make artisan CMD="migrate"
make composer CMD="install"
make migrate
make rollback
make fresh
make tinker
make composer-install
make composer-update
make composer-require PACKAGE=vendor/package
make npm-install
make npm-dev
make npm-build
make test-php
make test-coverage
```

### Swoole

```bash
make swoole-reload
make swoole-status
```

### Утилиты и очистка

```bash
make permissions
make clean
make clean-all
make dev-reset
```

### Shell и CLI-доступ

```bash
make shell
make shell-node
make shell-postgres
make shell-redis
make shell-queue
make shell-scheduler
```

`shell-postgres` запускает `psql`, а `shell-redis` выполняет проверку через `redis-cli`.

### Production / Production-like

```bash
make up-prod
make down-prod
make rebuild-prod
make logs-prod
make logs-app-prod
make logs-postgres-prod
make logs-redis-prod
make logs-queue-prod
make logs-scheduler-prod
make shell-prod
make shell-postgres-prod
make shell-redis-prod
make shell-queue-prod
make shell-scheduler-prod
make clean-prod
make clean-all-prod
make prod-reset
```

## Как работает Swoole в этом проекте

В этом проекте HTTP-слой обслуживается напрямую через Laravel Octane и Swoole:

- приложение отвечает через Swoole на `0.0.0.0:8000`
- статус воркеров доступен через команду `php artisan octane:status`
- количество воркеров управляется через `SWOOLE_WORKERS`, лимит задач — через `SWOOLE_MAX_REQUESTS`, task-воркеры — через `SWOOLE_TASK_WORKERS`
- сервер стартует командой `php artisan octane:start --server=swoole`

Это отличается от схемы с Nginx + PHP-FPM: отдельный веб-сервер не нужен, а запросы обрабатываются Swoole-воркерами напрямую в долгоживущих процессах.

## Production-like локальный запуск

Для локальной проверки production-сценария:

1. Проверьте и при необходимости отредактируйте `.env.production`.

2. Запустите production-профиль:

```bash
make up-prod
```

Этот target использует:

- `.env.production`
- `docker-compose.prod.local.yml`
- production stage из `docker/php.Dockerfile`

Для остановки:

```bash
make down-prod
```

Для просмотра логов:

```bash
make logs-prod
```

В production-профиле:

- используется production stage из `docker/php.Dockerfile`
- Laravel собирается без dev-зависимостей
- frontend-ассеты собираются на этапе image build
- при старте приложения выполняются `php artisan optimize:clear` и `php artisan migrate --force`
- запуск HTTP-сервера выполняется командой `php artisan octane:start --server=swoole`
- queue worker и scheduler вынесены в отдельные сервисы
- локально публикуется только `APP_PORT`

## Dokploy

Проект подходит для деплоя через Dokploy как Docker Compose приложение.

Практически это означает:

- в качестве основной production-конфигурации для сервера следует использовать `docker-compose.prod.yml`
- `docker-compose.prod.local.yml` нужен именно для локальной проверки production-профиля
- переменные окружения следует задавать через production env-файл или интерфейс Dokploy
- Swoole, PostgreSQL, Redis, queue worker и scheduler уже разделены по сервисам
- production-образы собираются из этого репозитория без необходимости отдельного Dockerfile для Dokploy

Перед деплоем через Dokploy проверьте:

- заполнены `APP_KEY`, `APP_URL` и production-переменные Laravel
- корректно настроены `DB_*` и `REDIS_*`
- выделены persistent volumes для PostgreSQL и Redis
- внешний роутинг Dokploy направлен на сервис `laravel-swoole`
- если миграции не должны выполняться автоматически при каждом старте контейнера, скорректируйте `command` у `laravel-swoole` в production compose
