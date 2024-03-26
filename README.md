# アプリ概要

SearchConsoleAPI, PageSpeedInsightAPI, CustomeSearchAPIを使って、ブログのパフォーマンスやページスピード、インデックス登録情報、サイトマップ提出状況などを一括で取得できるサービス。
本来はGmailAPIを使用して問い合わせメールも取得したかったが、実装できず断念。

# 使用方法

1, GoogleCloudで上記のAPIを有効にする
2, ClientID, ClientSecret, APIKEY, SearchEngineID, oAuthのCredential.json の5つを取得する。
3, .envに下記を追加

###############################################
GOOGLE_CLIENT_ID="xxxxxxxxxx.com"
GOOGLE_CLIENT_SECRET="xxxxxxxxx"
GOOGLE_SEARCH_ENGINE_ID="xxxxxxxx"
GOOGLE_API_KEY="xxxx xxxx xxxx xxxx"
GOOGLE_APPLICATION_CREDENTIALS="app/xxxxxxx.json"

VITE_YOUR_URL=https://xxxxxxxxx.com
VITE_YOUR_EMAIL_ADDRESS=example@example.com
VITE_YOUR_EMAIL_PASSWORD=Abcd1234
###############################################

上記を追加したら

npm run build
php artisan serve
