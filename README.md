# アプリ概要

SearchConsoleAPI, PageSpeedInsightAPI, CustomeSearchAPIを使って、ブログのパフォーマンスやページスピード、インデックス登録情報、サイトマップ提出状況などを一括で取得できるサービス。
<br>本来はGmailAPIを使用して問い合わせメールも取得したかったが、実装できず断念。

# 使用方法

1, GoogleCloudで上記のAPIを有効にする<br>
2, ClientID, ClientSecret, APIKEY, SearchEngineID, oAuthのCredential.json の5つを取得する。<br>
3, .envに下記を追加<br>
<br>
###############################################<br>
GOOGLE_CLIENT_ID="xxxxxxxxxx.com"<br>
GOOGLE_CLIENT_SECRET="xxxxxxxxx"<br>
GOOGLE_SEARCH_ENGINE_ID="xxxxxxxx"<br>
GOOGLE_API_KEY="xxxx xxxx xxxx xxxx"<br>
GOOGLE_APPLICATION_CREDENTIALS="app/xxxxxxx.json"<br>
<br>
VITE_YOUR_URL=https://xxxxxxxxx.com<br>
VITE_YOUR_EMAIL_ADDRESS=example@example.com<br>
VITE_YOUR_EMAIL_PASSWORD=Abcd1234<br>
###############################################<br>
<br>
上記を追加したら<br>
npm run build<br>
php artisan serve<br>
# 使用技術
## フロント
Vue.js, TailwindCSS
<br>
## バックエンド
Laravel, Inertia
<br>
## ツール
GoogleCloudAPI
