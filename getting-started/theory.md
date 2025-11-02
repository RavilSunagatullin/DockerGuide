# Getting started. Установка Docker и Docker Compose

## Для кого этот материал
Эта тема — отправная точка мини-курса. После прохождения ты сможешь установить Docker и Docker Compose на Windows, macOS или Linux, понимать что именно поставилось и чем контейнеры отличаются от виртуальных машин. В конце ты проверишь окружение и подготовишь машину к дальнейшим модулям.

---

## 1. Перед началом: что проверить
1. **Аппаратная виртуализация.** Убедись, что в BIOS/UEFI включены технологии Intel VT-x/AMD-V (на Windows/Mac это обязательное условие для Docker Desktop, на Linux — для систем с `systemd-nspawn` не требуется, но крайне желательно).
2. **64-битная ОС и ядро.** Docker не поддерживает 32-битные системы.
3. **Права администратора.** Понадобятся для установки и добавления пользователя в группу `docker` на Linux.
4. **Интернет-доступ.** Без него не получится подтянуть пакеты и тестовые образы.

---

## 2. Windows 10/11: Docker Desktop + WSL2
Docker Desktop использует подсистему Windows для Linux (WSL) как облегчённый гипервизор. Выполни шаги по порядку.

### 2.1 Проверка и включение виртуализации
1. Открой «Диспетчер задач» → вкладка «Производительность». Внизу должна быть строка «Виртуализация: Включено».
2. Если значение «Отключено», перезагрузи ПК и включи виртуализацию в BIOS/UEFI (пункты `Intel Virtualization Technology`, `SVM`, `AMD-V`).

### 2.2 Включение компонентов Windows
В PowerShell от имени администратора выполни:
```powershell
wsl --install
wsl --set-default-version 2
```
Эта команда установит WSL, компонент «Платформа виртуальной машины» и скачает образ Ubuntu по умолчанию. Если WSL уже установлен, убедись, что используется версия 2:
```powershell
wsl --list --verbose
```
Во второй колонке должно быть `Version: 2`. При необходимости переведи дистрибутив:
```powershell
wsl --set-version <DistributionName> 2
```

### 2.3 Обновление ядра WSL (если требуется)
Если при запуске Docker Desktop появится предупреждение про устаревшее ядро, скачай обновление с [официальной страницы](https://aka.ms/wsl2kernel) и установи его.

### 2.4 Установка Docker Desktop
1. Скачай последнюю версию с [docker.com](https://www.docker.com/products/docker-desktop/).
2. Запусти инсталлятор и убедись, что выбран пункт **Use WSL 2 instead of Hyper-V** (Hyper-V нужен только для корпоративных ограничений).
3. После завершения установки перезагрузи систему и запусти Docker Desktop.
4. При первом старте авторизуйся в Docker Hub (опционально) и разреши интеграцию с WSL-дистрибутивом (обычно Ubuntu). Галочка `Enable integration with additional distros` включает доступ Docker CLI внутри WSL.

### 2.5 Docker Compose на Windows
Compose v2 идёт вместе с Docker Desktop. Проверь версию:
```powershell
docker compose version
```
Если команда не найдена, обнови Docker Desktop до последней версии.

### 2.6 Где выполнять команды
- В PowerShell или Windows Terminal.
- Внутри WSL (Ubuntu). Там Docker CLI проксирует команды в демон Docker Desktop. Проверь, что переменная окружения `DOCKER_HOST` не переопределена.

---

## 3. macOS: Docker Desktop
### 3.1 Системные требования
- macOS 12 Monterey и новее (для Apple Silicon — не ниже 12.4).
- Аппаратная виртуализация: Hypervisor Framework (активна по умолчанию).
- Минимум 4 ГБ RAM, свободное место на диске 2–4 ГБ.

### 3.2 Установка
1. Выбери дистрибутив под свою архитектуру: `Mac with Intel chip` или `Mac with Apple chip`.
2. Скачай `.dmg` с [docker.com](https://www.docker.com/products/docker-desktop/).
3. Открой образ и перетащи Docker в `Applications`.
4. Первый запуск потребует ввода пароля администратора для установки вспомогательных компонентов.

### 3.3 Настройка
- В меню китёнка зайди в **Settings → Resources**, при необходимости выдели больше CPU/RAM.
- Compose v2 ставится автоматически. Проверь: `docker compose version`.
- Если пользуешься `brew`, убедись, что в PATH отсутствуют старые бинарники `docker` или `docker-compose` — они могут конфликтовать.

---

## 4. Linux: Docker Engine + Compose plugin
На Linux Docker состоит из нескольких пакетов: `docker-ce` (клиент и демон), `containerd`, `docker-compose-plugin`. Ниже инструкции для популярных дистрибутивов.

### 4.1 Ubuntu / Debian
```bash
sudo apt-get remove docker docker-engine docker.io containerd runc 2>/dev/null || true
sudo apt-get update
sudo apt-get install -y ca-certificates curl gnupg lsb-release
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/$(. /etc/os-release && echo "$ID")/gpg | \
  sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/$(. /etc/os-release && echo "$ID") \
$(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```
После установки добавь пользователя в группу `docker` и перезайди:
```bash
sudo usermod -aG docker $USER
newgrp docker
```

### 4.2 Fedora / CentOS / RHEL
```bash
sudo dnf remove -y docker docker-client docker-client-latest docker-common docker-latest docker-latest-logrotate \
  docker-logrotate docker-engine 2>/dev/null || true
sudo dnf -y install dnf-plugins-core
sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo
sudo dnf install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
```
Для CentOS/RHEL путь к репозиторию аналогичный, но может потребоваться включить `extras` и установить `containerd.io` из rpm.

### 4.3 Arch Linux
```bash
sudo pacman -Sy --noconfirm docker docker-compose
sudo systemctl enable --now docker.service
sudo usermod -aG docker $USER
```
В Arch пакет `docker-compose` содержит Compose v2.

### 4.4 Проверка службы
```bash
systemctl status docker
```
Статус должен быть `active (running)`. Если служба не стартует, проверь журнал: `journalctl -u docker`.

---

## 5. Проверяем установку и Compose
Выполни команды в терминале своей ОС.

```bash
docker --version
docker compose version
```
Команды должны вернуть версии клиента и Compose v2. Если `docker compose` недоступен, на Linux установи `docker-compose-plugin`, а на Windows/macOS обнови Docker Desktop.

```bash
docker info
```
Проверь, что поле `Server Version` заполнено и нет ошибок подключения.

```bash
docker run hello-world
```
Команда скачает тестовый образ и выведет сообщение об успешном запуске контейнера. На медленном интернете загрузка займёт несколько минут.

Для интерактивной проверки попробуй:
```bash
docker run -it --name alpine-lab alpine sh
# внутри контейнера
cat /etc/os-release
exit
```

После выхода контейнер перейдёт в статус `Exited`. Убедись в этом:
```bash
docker ps -a | grep alpine-lab
```

---

## 6. Что установилось и зачем это нужно
- **Docker Engine** — серверная часть (демон `dockerd` + runtime `containerd`), которая управляет контейнерами.
- **Docker CLI** — клиент, через который мы вызываем команды `docker ...`.
- **Docker Compose v2** — плагин к Docker CLI, позволяющий описывать несколько сервисов в `compose.yaml` и поднимать их одной командой.
- **WSL2 интеграция (Windows)** или **Hypervisor Framework (macOS)** — слой виртуализации, предоставляющий Linux-окружение для контейнеров.

### Контейнер vs виртуальная машина
| Характеристика | Контейнер | Виртуальная машина |
| --- | --- | --- |
| Изоляция | Разделяет ядро с хостом, использует пространства имён. | Полная виртуализация — своё ядро и виртуальное железо. |
| Время запуска | Секунды. | Минуты. |
| Размер | Сотни мегабайт (слои файловой системы). | Гигабайты (образ ОС). |
| Управление | `docker run`, `docker stop`, `docker rm`. | Через гипервизор/менеджер ВМ. |

### Базовые термины
- **Образ (image)** — слоистый шаблон файловой системы + метаданные запуска.
- **Слой (layer)** — шаг сборки образа; слои переиспользуются, поэтому важно оптимизировать Dockerfile.
- **Реестр (registry)** — хранилище образов (Docker Hub, GitHub Container Registry, частные реестры).

---

## 7. Чек-лист готовности
- [ ] Виртуализация включена, WSL2 настроен (для Windows).
- [ ] `docker --version` и `docker compose version` возвращают актуальные версии.
- [ ] `docker run hello-world` завершается без ошибок.
- [ ] Ты понимаешь, что такое контейнер, образ и как Docker использует Compose.

Если все пункты выполнены — переходи к следующей теме, где будем изучать жизненный цикл контейнера.
