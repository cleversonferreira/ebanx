## Project Setup

**Install Docker**
(https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04)

**Install Docker Compose**
(https://www.digitalocean.com/community/tutorials/how-to-install-docker-compose-on-ubuntu-18-04)

**Generate SSH key**
(https://support.atlassian.com/bitbucket-cloud/docs/set-up-an-ssh-key/)

**Clone project from Gitlab**
```bash
git clone git@gitlab.com:clefrancodev/budd.git 
```
**Install composer dependencies**
```bash
docker run --rm -v $(pwd):/app composer install
```
**Add user permission**
```bash
sudo chown -R $USER:$USER ~/development/budd
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
docker-compose exec app php artisan key:generate
```
**Cache Config**
```bash
docker-compose exec app php artisan config:cache
```
**Open project**
(http://localhost/)