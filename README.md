# README

グループワーク
===================

## ディレクトリ構成

+ config 設定ファイル
+ controllers コントローラー
+ lib ライブラリ
+ logs ログ
+ migration テーブル作成sql他
+ models モデル
+ public_html ドキュメントルート
    + css
    + img
    + index.php フロントコントローラー
    +  js
+  tests テスト
    + data テストデータ
    + lib
    + models
+ vendor 
    + Slim Slim Framework本体
    + Slim-Extras SlimとTwigをつなぐライブラリ
    + Twig テンプレートエンジン
+ views ビュー

## ソースの取得
    git clone git@github.com:VG-Tech-Dojo/GroupWork.git
    cd GroupWork
    git submodule init
    git submodule update


## その他設定
public_htmlをドキュメントルートに設定する

### htaccess（もしくはhttpd.conf）に記述
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]%

### DB設定
config/database.php内を環境にあわせて設定


Modelの挙動
-------------------------------------------------

load
