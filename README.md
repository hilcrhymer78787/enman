git clone https://github.com/hilcrhymer78787/enman.git

cd enman

docker-compose up -d --build

docker-compose exec app bash

cp .env.example .env

composer install

php artisan key:generate

php artisan migrate:refresh --seed

もう一つターミナルを開く

docker-compose exec next bash

cp .env.example .env

npm install

npm run dev

<!-- node v16.13.2 -->

nvm use v16.13.2;npm install;npm run dev