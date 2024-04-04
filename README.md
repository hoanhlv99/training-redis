###install docker và docker compose

https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04

https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-22-04

###create network

docker network create redis-dev

cd docker

docker compose up -d --build

###sau khi chạy xong

docker exec -ti "name docker" bash

composer install

cp .env.example .env

php artisan schedule:run
