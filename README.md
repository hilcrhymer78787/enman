【docker】
bash起動しコンテナに入る
  docker-compose exec app bash
  cd /var/www/html/laravel
コンテナ一覧と状況確認
  docker-compose ps

【フロントエンド 開発環境】
cd enman/nuxt
npm install
npm run dev
http://localhost:3000/

【バックエンド 開発環境】
http://localhost:8000/

【バックエンド 開発環境(php myadmin)】
http://localhost:8080/


【issue】

work 担当重複阻止

room
    create
    read
    update
    delete

invitation
    create
    read
    update
    delete

invitation_status
    0:未確認
    1:確認済
    2:参加済