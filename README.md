# FileManager
Файловый менеджер создан для просмотра файлов загруженных на сервер
## Где папка с файлами?
Путь до папки с файлами начиная с корня приложения
`storage/app/private/files`

Можно облегчить вам путь до неё путь через команду `ln  -sfn storage/app/private/files files`

## Как его запустить!
#### NGINX
Установка sqllite (Можете поменять на свою бд в .env mariadb/pgsql/mysql)
```shell
sudo apt install sqlite3
```

Скачивание репозитория
```shell
git clone https://github.com/Guevin1/FileManager.git
cd FileManager
```
Копирование .env
```shell
cp .env.example .env
```

Кэширование конфигов
```shell
php artisan key:generate
php artisan config:cache
php artisan route:cache
```
Собирание сайта
```shell
npm install
npm run build
```
Создание нужных вещей для Laravel
```shell
php artisan migrate --seed
php artisan storage:link
```
Выдача прав на storage
```shell
sudo chmod 0777 -R ./storage/app
```
Редактирование конфигов nginx
/etc/nginx/sites-enabled/<your_name>.conf
```
server {
    listen 443;
    server_name yourdomain.com;
    root /var/www/your-laravel-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
### Docker
Скачивание репозитория

```shell
git clone https://github.com/Guevin1/FileManager.git
cd FileManager
```
Копирование .env
```shell
cp .env.example .env
```

поднятие контейнера с laravel
```shell
./vendor/bin/sail up -d
```
Кэширование конфигов
```shell
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```
Собирание сайта
```shell
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```
Создание нужных вещей для Laravel
```shell
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail artisan storage:link
```
Сайт запустился http://localhost/
## Редактирование сайта
Для редактирования нужен docker.

Laravel 
```shell
./vendor/bin/sail up -d
```

Vue.js
```shell
./vendor/bin/sail npm run dev
```

