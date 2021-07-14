# Для установки понадобится

1. docker
2. сконфигурированные файлы .env в корне и в папке src

## Запуск проекта

1. Заполнить основной .env для docker-compose файла

```
MYSQL_DATABASE=meshgroup
MYSQL_USER=mesh
MYSQL_PASSWORD=
MYSQL_ROOT_PASSWORD=
# credentials for rabbit mq
RABBITMQ_DEFAULT_USER=rabbitmq
RABBITMQ_DEFAULT_PASS=
```

2. Заполнить src/.env (скопировать .env.example и вставить credentials)
3. Запустить команду docker-compose up -d --build

### Выполненые пукнты

Выполненны все пункты за исключением евентов для бродкастинга. Ориентировался на rest api сервис. Реализовать его не сложно.
