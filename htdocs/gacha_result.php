<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'lecture_db';
try {
    // PDOでMySQLに接続
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // データの取得
    $stmt = $pdo->query("SELECT gi.item_id, gi.weight, it.item_name FROM gacha_items gi left outer join items it on gi.item_id = it.item_id where gacha_id = 1");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // weghtの合計
    $total = 0;
    foreach ($results as $v) {
        $total += $v['weight'];
    }

    // 10連ガチャ実行処理
    $hit_items = [];
    for ($i=1; $i<=10; $i++) {
        $t = $total;
        $r = rand(1, $t);
        foreach ($results as $v) {
            $t -= $v['weight'];
            if ($t < $r) {
                array_push($hit_items, $v);
                break;
            }
        }
    }

    // history_idの採番
    $max_history_id = "SELECT MAX(history_id) FROM gacha_histories";
    $stmt_max_id = $pdo->query($max_history_id);
    $max_id = $stmt_max_id->fetchColumn();
    $next_history_id = ($max_id === null) ? 1 : $max_id + 1;
    
    // 結果表示＆ヒストリーへ保存
    echo "ガチャ結果<br>";
    foreach ($hit_items as $v) {
        echo $v['item_id'] . " : " . $v['item_name'] . "<br>";
        // gacha_historiesに登録
        $sql = "INSERT INTO gacha_histories (history_id, gacha_id, item_id) VALUES (:history_id, 1, :item_id)";
        $stmt = $pdo->prepare($sql);
        // パラメータをバインド
        $stmt->bindParam(':history_id', $next_history_id, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $v['item_id'], PDO::PARAM_INT);
        // SQLクエリを実行
        $stmt->execute();
    }

    echo "<a href=\"http://localhost/gacha_form.html\">" . "戻る" . "</a>";
    exit();

} catch (PDOException $e) {
    // エラー処理
    echo "データベースエラー: " . $e->getMessage();
}
?>