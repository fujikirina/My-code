<?php
/* ログアウトページ */

session_start();

/* セッションを削除し、indexに戻る */
unset($_SESSION['portEcUserName'],$_SESSION['portEcUserId'],$_SESSION['portEcUserMail']);
setcookie( session_name(), '', time()-10000);

header('Location: index.php');
exit;

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="icon" href="./img/favicon.ico">
  <title>にゃーん！</title>
</head>
</body>
</body>
</html>
