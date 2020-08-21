## Project Setup

**Install composer dependencies**
```bash
docker run --rm -v $(pwd):/app composer install
```
**Add user permission**
```bash
sudo chown -R $USER:$USER ~/projectfolder
```
**Generate .env**
```bash
cp .env.example .env
```
**Run Application**
```bash
docker-compose up -d
```
**Generate Laravel key**
```bash
docker-compose exec ebanx_app php artisan key:generate
```
**Cache Config**
```bash
docker-compose exec ebanx_app php artisan config:cache
```
**Run Migrations**
```bash
docker-compose exec ebanx_app php artisan migrate
```
**Open project**
(http://localhost/)