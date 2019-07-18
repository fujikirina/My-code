<?php
/* 退会完了 */

session_start();
$mail = $_SESSION['portEcUserMail'];

if(!empty($_POST['userDelete'])){

  /* MySQLに接続 */
  require_once('../ec.nya-n.xyz-require/db/db.php');

  /* MySQL内のデータを取得
     DB中のmailと$mailが一致するデータを削除する */
  try {
    $sql =
       "DELETE member, member_cute, order_history, cart
       FROM member
       LEFT JOIN member_cute
       ON member.mail = member_cute.mail
       LEFT JOIN order_history
       ON member_cute.mail = order_history.mail
       LEFT JOIN cart
       ON order_history.mail = cart.mail
       WHERE member.mail = :mail";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
    $stmt -> execute();
  } catch (Exception $e) {;}

  /* セッションを削除し、indexに戻る */
  unset($_SESSION['portEcUserName'],$_SESSION['portEcUserMail']);
  setcookie( session_name(), '', time()-10000);

  /* MySQLから離脱 */
  unset($dbh);

  header('Location: index.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="ポートフォリオ用サイトです。">
  <meta name="viewport" content="width=device-width">
  <title>にゃーん！</title>
  <link rel="icon" href="./img/favicon.ico">
</head>
</body>
</body>
</html>
