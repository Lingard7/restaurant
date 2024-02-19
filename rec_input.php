<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>受付ページ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>受付ページへようこそ！</h1>

    <script>
        function check() {
            var name = document.getElementById("name").value;			//　名前の入力値を取得
            var pnum = document.getElementById("pnum").value;			//　人数の入力値を取得

            // エラーメッセージをクリア
            document.getElementById("validate_name_message").innerHTML = "";
            document.getElementById("validate_pnum_message").innerHTML = "";

			//　入力が空の場合
            if (name.length == 0 && pnum.length == 0) {
                var validateName = " ?名前が未入力";		//エラーメッセージ
                var validatePnum = " ?人数が未入力";		//エラーメッセージ

                document.getElementById("validate_name_message").innerHTML = validateName;
                document.getElementById("validate_pnum_message").innerHTML = validatePnum;
                return false;
            } else if (name.length == 0) {
                var validateName = "?名前が未入力";
                document.getElementById("validate_name_message").innerHTML = validateName;		//　エラーメッセージを表示
                return false;
            } else if (pnum.length == 0) {
                var validatePnum = "?人数が未入力";
                document.getElementById("validate_pnum_message").innerHTML = validatePnum;		//　エラーメッセージを表示
                return false;
            } else if (isNaN(pnum) || pnum == 0) {
                var validatePnum = "?人数には0以外の数字を入力";
                document.getElementById("validate_pnum_message").innerHTML = validatePnum;		//　エラーメッセージを表示
                return false;
            } else {
                var flg = window.confirm("受付情報を登録してもよろしいですか？");				//　登録確認ダイアログを表示
                return flg;
            }
        }

        function clearMessages() {
            // エラーメッセージをクリア

            document.getElementById("validate_name_message").innerHTML = "";
            document.getElementById("validate_pnum_message").innerHTML = "";
        }
    </script>

    <form action="rec_exe.php" method="post" onsubmit="return check()">
        <?php
        require_once './DbManager.php';		//　データベース接続用ファイル読み込み

        try {
            $db = getDb();					//　データベース接続の取得

            $sql = "select * from rec";		//　レコード取得のためのSQLクエリ

            $stmt = $db->query($sql);		//　クエリ実行
			
			//　名前入力フィールド
            echo "<div class='cp_iptxt' id='validate_name'>";
            echo "<label class='ef'>";
            echo "<input placeholder='名前' type='text' name='name' id='name'>";
            echo "</label>";
            echo "<div class='error-message' id='validate_name_message'></div>";	// エラーメッセージ表示フィールド
            echo "</div>";

			//　人数入力フィールド
            echo "<div class='cp_iptxt' id='validate_pnum'>";
            echo "<label class='ef'>";
            echo "<input placeholder='人数' type='text' name='pnum' id='pnum'>";
            echo "</label>";
            echo "<div class='error-message' id='validate_pnum_message'></div>";	// エラーメッセージ表示フィールド
            echo "</div>";
            
            $i = 0;

			//　待ちリスト表示フィールド
            echo "<div class='wait_state'>";
            echo "<table align='center' border='0'>";
            echo "<th>順番</th> <th>受付番号</th> <th>名前</th>";

			//　結果をループして$rowに格納し表示
            foreach($stmt as $row){
                $i++;
                echo "<tr><td>". $i . "</td>";
                echo "<td>" . $row['id']. "</td>";
                echo "<td>" . $row['name']. " 様</td></tr>";
            }
            echo "</div>";

        } catch (PDOException $e) {
            print "接続エラー:{$e->getMessage()}";		//　接続エラー
        } finally {
            $db = null;									//　データベース接続クローズ
        }
        ?>
        <br>
        <div class="btn btn-border">
            <input type='submit' value='登録'>
            <input type='reset' value='クリア' onclick="clearMessages()">
        </div>
    </form>
</body>
</html>
