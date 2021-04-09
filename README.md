# Core_UI_Laravel8.6__only


公式サイトはこちらで確認
https://github.com/coreui/coreui-free-laravel-admin-template


データベース変更
```

DB_CONNECTION=sqlite
これ以外は削除
```

```
# in your app directory
# generate laravel APP_KEY
$ php artisan key:generate

# run database migration and seed
$ php artisan migrate:refresh --seed

# generate mixing
$ npm run dev

# and repeat generate mixing
$ npm run dev

```


## 起動方法
これで起動します
```
# start local server
$ php artisan serve

# test
$ php vendor/bin/phpunit
```


## 接続はこちらのアドレス入力

```
Open your browser with address: localhost:8000
Click "Login" on sidebar menu and log in with credentials:
```

ログイン情報はこちら
```
E-mail: admin@admin.com
Password: password
```
