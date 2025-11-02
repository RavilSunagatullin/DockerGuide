# Примеры команд после установки Docker

Повтори эти команды, чтобы убедиться, что Docker Engine и Docker Compose работают корректно.

## Проверка версий
```bash
docker --version
docker compose version
```

## Сведения о демоне
```bash
docker info
```

## Первый контейнер
```bash
docker run hello-world
```

## Интерактивная сессия в Alpine Linux
```bash
docker run -it --name alpine-lab alpine sh
# внутри контейнера
ls
cat /etc/os-release
exit
```

## Повторный запуск существующего контейнера
```bash
docker start -ai alpine-lab
```

## Удаление контейнера и образа
```bash
docker rm alpine-lab
# удаляем образ, если больше не нужен
docker image rm alpine
```

## Очистка окружения
```bash
docker container prune
```
> Осторожно: команда удалит все остановленные контейнеры.
