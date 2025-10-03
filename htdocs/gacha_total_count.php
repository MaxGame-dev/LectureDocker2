<?php
// データベースへ接続するために必要な情報
require 'db_config.php'; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 1. SQLクエリの定義
    $sql = "
        SELECT 
            item_id, 
            COUNT(id) AS count,
            (SELECT COUNT(id) FROM gacha_histories) AS total,
            COUNT(id) / (SELECT COUNT(id) FROM gacha_histories) * 100 AS rate
        FROM 
            gacha_histories 
        GROUP BY 
            item_id 
        ORDER BY 
            item_id;
    ";

    // 2. プリペアドステートメントの準備と実行
    // ※このクエリにはユーザー入力が含まれないため、bindParam/bindValueは不要です。
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 3. 結果の取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. 結果の表示（HTMLテーブル）
    echo "<h2>ガチャ結果 集計レポート</h2>";
    
    // 全体の合計回数を取得（最初の行から 'total' の値を取得すればOK）
    $total_count = !empty($results) ? $results[0]['total'] : 0;
    echo "<p>集計対象の履歴総数: <strong>" . number_format($total_count) . "</strong> 件</p>";
    
    if ($total_count > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Item ID</th>";
        echo "<th>出現回数 (count)</th>";
        echo "<th>出現率 (rate)</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // 結果をループして表示
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['item_id']) . "</td>";
            echo "<td style='text-align: right;'>" . number_format($row['count']) . " 回</td>";
            // 出現率を小数点以下2桁で表示
            echo "<td style='text-align: right;'>" . number_format($row['rate'], 2) . " %</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>集計対象の履歴がありませんでした。</p>";
    }

} catch (PDOException $e) {
    // エラー処理
    echo "エラーが発生しました: " . $e->getMessage();
}
echo "<br>";
echo "<a href=\"http://localhost/gacha_form.html\">" . "戻る" . "</a>";

?>