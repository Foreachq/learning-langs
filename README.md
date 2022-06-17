# Запустить проект для локальной разработки (для OS Ubuntu)

1. Установить утилиту make
   ```bash
   $ sudo apt-get update
   
   $ sudo apt-get install build-essential
   ```

2. [Установить docker и docker-compose](docs/docker-install.md)

3. Скорпировать конфиграционный файл (и при необходимости сконфигурировать переменные для docker)
   ```bash
   $ cp .env.dist .env
   ```

4. Запустить docker-контейнеры
   ```bash
   $ make docker-up
   ```

5. Зайти в php-контейнер можно следующим образом
   ```bash
   $ make php
   ```

6. Открыть проект в браузере можно по ссылке: http://127.0.0.1:8041
