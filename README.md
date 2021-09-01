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



【api仕様書】

request
    method
        get
    url
        /auth_info/bytoken/?token=<string>
response
    {
        id:<int>,
        name:<string>,
        email:<string>,
        token:<string>,
    }


request
    method
        get
    url
        /auth_info/bybasic/?email=<string>&?password=<string>
response
    {
        id:<int>,
        name:<string>,
        email:<string>,
        token:<string>,
    }


request
    method
        get
    url
        /work/read/?token=<string>?year=<int>?&month=<int>?&day=<int>
response
    [
        date:<string>,
        works:[
            {
                user_id:<int>,
                user_id:<int>,
                minute:<string>,
            },
        ]
    ]
