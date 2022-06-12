# Запустить проект для локальной разработки (для OS Ubuntu)

1. Установить утилиту make
   ```bash
   $ sudo apt-get update
   
   $ sudo apt-get install build-essential
   ```

2. Установить docker и docker-compose
   ```bash
   $ sudo apt-get update
   
   $ sudo apt-get install \
       ca-certificates \
       curl \
       gnupg \
       lsb-release
   
   $ sudo mkdir -p /etc/apt/keyrings
   
   $ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
   
   $ echo \
     "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
     $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
   
   $ sudo apt-get update
   
   $ sudo apt-get install docker-ce docker-ce-cli containerd.io docker-compose-plugin
   
   $ sudo groupadd docker
   
   $ sudo usermod -aG docker $USER
   
   // reboot system
   ```

3. Скорпировать конфиграционный файл (и при необходимости сконфигурировать переменные для docker)
   ```bash
   $ cp .env.dist .env
   ```

4. Запустить проект в docker'е и сбилдить (установка пакетов, прогрев кеша, миграции)
   ```bash
   $ make docker-up
   
   $ make build
   ```

5. Зайти в php-контейнер можно следующим образом
   ```bash
   $ make php
   ```

6. Открыть проект в браузере можно по ссылке: http://127.0.0.1:8041
