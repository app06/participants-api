### Установка с docker:
Выполните команду docker-compose up --build

В отдельном окне терминала запустите команду
```
make init
```

API доступно по адресу http://localhost:8080

Запуск тестов
```
make test
```

Запуск worker-а очереди
```
make watch
```

### Установка без docker:

Создаём БД и указываем её настройки в .env

Ставим зависимости
```shell
composer install
```
```shell
npm init
```

Выполняем миграции
```shell
php artisan migrate
```

Выполняем посев данных
```shell
php artisan db:seed
```
Создаём ключи безопасности для Passport
```shell
php artisan passport:install
```

Оправка писем записывается в файл. Очереди работают через sync. Вместо этого можно указать настройки для mailtrap, соединение redis для очереди и запустить worker
```shell
php artisan queue:work
```

#### Регистрация пользователя:
Убедитесь, что API использует следующие заголовок
```
'Accept': 'application/json'
```
Запрос POST http://localhost:8000/api/register
```json
{
    "name": "Vasyta",
    "email": "test@test.ru",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```
В ответ приходит `token` сохраняем его и при дальнейших запросах к API добавляем его в заголовок
```
'Authorization': 'Bearer token'
```
#### Маршруты
Список, метод <strong>GET</strong> http://localhost:8000/api/participants

Список участников для конкретного события, метод <strong>GET</strong> http://localhost:8000/api/participants?filter=event_id:999

Создание, метод <strong>POST</strong> http://localhost:8000/api/participants 
```json
{
    "email": "my32v9@test.ru",
    "first_name": "",
    "last_name": "",
    "event_id": "1"
}
```
Участник, метод <strong>GET</strong> http://localhost:8000/api/participants/id

Обновление, метод <strong>PUT</strong> http://localhost:8000/api/participants/id

Удаление, метод <strong>DELETE</strong> http://localhost:8000/api/participants/id

Запуск тестов
```shell
php artisan test
```
Файле `Participants.postman_collection.json` - коллекция маршрутов для Postman
