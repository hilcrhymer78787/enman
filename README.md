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
        /task/read/?year=<int>&month=<int>&day=<int>&token=<string>
response
    [
        {
            date:<string>,
            users:[
                {
                    user_id:<int>,
                    user_name:<int>,
                    ★★★user_sum_minute:<int>,
                    ★★★user_sum_point:<int>,
                },
            ],
            everydayTasks:[
                task_id:<int>,
                task_name:<string>,
                task_point_per_minute:<int>,
                task_default_minute:<int>,
                ...
                works:[
                    {
                        work_id:<int>,
                        user_id:<int>,
                        user_name:<string>,
                        user_img:<int>,
                        minute:<string>,
                    },
                ]
            ],
            bigTasks:[
                task_id:<int>,
                task_name:<string>,
                task_point_per_minute:<int>,
                task_default_minute:<int>,
                ...
                works:[
                    {
                        work_id:<int>,
                        user_id:<int>,
                        user_name:<string>,
                        user_img:<int>,
                        minute:<string>,
                    },
                ]
            ],
        }
    ]
