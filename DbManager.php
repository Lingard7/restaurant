//　PDOによるMSQL接続設定ファイル
<?php
function getDb(){
    $dsn = 'mysql:dbname=table; host=127.0.0.1; charset=utf8';		//　テーブル名，ホストIP，文字コード
    $usr = 'user';								//　ログインユーザ名
    $pass = 'password';							//　ログインパスワード

    $db = new PDO($dsn, $usr, $pass);					//　接続
    return $db;
}
?>