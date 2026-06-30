# Allo! Pizza MVP

Уеб система за онлайн поръчки на пицария — Laravel + Filament + Livewire + MySQL.

## Работна директория

```
~/Projects/pizzeria-orders
```

Всичка разработка се прави тук. Копие на външния диск (`SK/pizzeria-orders`) е само backup — не се работи директно оттам.

## Инсталация

### С Docker (препоръчително)

```bash
cd ~/Projects/pizzeria-orders
docker compose up --build
```

Първото стартиране отнема няколко минути (composer, migrate, npm build).

| URL | Адрес |
|-----|-------|
| Сайт | http://localhost:8000 |
| Админ | http://localhost:8000/admin |

**Админ:** `admin@pizzeria.local` / `password`

Спиране: `docker compose down`

Повторна инициализация (migrate + seed отново):
```bash
docker compose down -v
rm -f .docker/initialized
docker compose up --build
```

Полезни команди:
```bash
docker compose exec app php artisan test
docker compose exec app php artisan migrate:fresh --seed
docker compose logs -f app
```

MySQL е достъпна на `localhost:3307` (user: `pizzeria`, pass: `secret`).

### Без Docker (локално)

```bash
cd ~/Projects/pizzeria-orders
./setup.sh
```

## Технологии

- Laravel 11, Filament 3, Livewire 3, Breeze, Sanctum, Tailwind, MySQL

## Достъп

| Роля | URL | Данни |
|------|-----|-------|
| Админ | `/admin` | `admin@pizzeria.local` / `password` |
| Клиент | `/login` | Регистрация през `/register` |

## API

```
GET  /api/categories
GET  /api/products
GET  /api/products/{id}
GET  /api/banners
GET  /api/lunch-menu
GET  /api/settings
POST /api/orders
GET  /api/orders/{id}
```

## Тестове

```bash
php artisan test
```

## Backup на външен диск (по избор)

```bash
rsync -a --delete \
  --exclude 'vendor' --exclude 'node_modules' --exclude '.env' \
  ~/Projects/pizzeria-orders/ \
  ~/.mounty/Expansion/SK/pizzeria-orders/
```
