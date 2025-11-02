# Примеры команд для жизненного цикла контейнера

## Запуск и проброс портов
```bash
docker run --name web -d -p 8080:80 nginx
```

## Логи
```bash
docker logs -f web
docker logs --tail 50 web
```

## Интерактивный доступ
```bash
docker exec -it web sh
```

## Копирование файлов
```bash
docker cp web:/etc/nginx/nginx.conf ./nginx.conf
docker cp ./custom.conf web:/etc/nginx/conf.d/default.conf
```

## Управление жизненным циклом
```bash
docker stop web
docker start web
docker restart web
docker rm web
docker container prune
```
