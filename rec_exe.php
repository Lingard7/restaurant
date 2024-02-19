<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>受付情報登録完了</title>
        <link rel="stylesheet" href="style2.css">
    </head>
    <body>
        <h2>受付情報を登録しました!</h2>
        <?php
            require_once './DbManager.php';				//　データベース接続用ファイル読み込み

            try{

                $db = getDb();							//　データベース接続の取得

				// プリペアドステートメントのエミュレーションを無効にし，エラーモードを例外モードに設定
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $name = (string)$_POST['name'];				// POSTリクエストから名前を取得
                $pnum = (int)$_POST['pnum'];				// POSTリクエストから人数を取得
                
                $in_sql = "insert into rec(name, pnum) values(?, ?)";	// 受付情報を挿入するSQLクエリ
                $in_res = $db->prepare($in_sql);
				
				// バインドパラメータの設定
                $in_res->bindValue(1, $name, PDO::PARAM_STR);
                $in_res->bindValue(2, $pnum, PDO::PARAM_INT);
                
				// クエリの実行
                $in_res->execute();
				
				// 最大の受付番号を取得するSQLクエリ
                $num_sql = "select max(id) as max from rec";
                $num_res = $db->query($num_sql);
                $max = $num_res->fetch(PDO::FETCH_BOTH);
				
                echo "<div class='recNum'>";
                echo "あなたの受付番号は";
                echo "<div class='Num'>";
                echo $max['max']."番";
                echo "</div>";
                echo "</div>";

            }catch(PDOException $e){
                print "接続エラー:{$e->getMessage()}";		// 接続エラー
            }finally{
                $db = null;									// データベース接続のクローズ
            }
        ?>
        <br>
        <a href='./rec_input.php' class='btn btn-border'>受付ページへ</a>
    </body>
</html>