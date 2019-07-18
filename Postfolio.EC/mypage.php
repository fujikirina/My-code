<?php
/* マイページTOP、ユーザー情報 */

session_start();

/* ログインしていない場合indexに飛ばす */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  $id = $_SESSION['portEcUserId'];
  $name = $_SESSION['portEcUserName'];
  $mail = $_SESSION['portEcUserMail'];
}else{
  $_SESSION['noLogin'] = 'noLogin';
  header('Location: index.php');
  exit;
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得
   DB:member_cuteのuser_idと$idが一致するデータを取り出す */
  $stmt = $dbh->prepare("SELECT cute FROM member_cute WHERE user_id = :id");
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){;}

/* 取得したデータを取り出す */
foreach ($stmt as $data){
  break;
}

$cute = 0;
$cute = $data['cute'];

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="猫の可愛さ販売サイトです。">
    <title>にゃーん！</title>
    <link rel="icon" href="./img/favicon.ico">
    <link href="./read/bootstrap4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./read/style.css">
  </head>

  <body>
    <div class="container">

    <header>
      <?php
      require_once('./page/header.php');
      ?>
    </header>

    <main>
      <div class="container backlight py-1">
        <div class="my-3"><h2><img class="pb-2 mr-1" src="./img/icon.png">マイページ</h2></div>
        <div class="row">
          <div class="w-30">
            <div class="list-group col mt-2 ml-3">
              <a href="mypage.php" class="list-group-item list-group-item-action disabled">ユーザー情報</a>
              <a href="order-history.php" class="list-group-item list-group-item-action">購入履歴</a>
              <a href="charge.php" class="list-group-item list-group-item-action">CUTEチャージ</a>
              <a href="logout.php" class="list-group-item list-group-item-action">ログアウト</a>
              <a href="user-delete.php" class="list-group-item list-group-item-action text-danger">退会</a>
            </div>
          </div>
          <div class="col mt-2">
            <table class="table bg-white table-bordered">
              <tr><th colspan="2"><h3>ユーザー情報</h3></th></tr>
              <tr><th>名前</th><td><?php echo $name ?></td></tr>
              <tr><th>メール</th><td><?php echo $mail ?></td></tr>
              <tr><th>パスワード</th><td>********</td></tr>
              <tr><th>現在のCUTE</th><td><?php echo $cute ?></td></tr>
            </table>
          </div>
          <div class="w-100">
            <div class="row justify-content-end mr-4 mb-2">
              <a class="btn btn-pink" href="user-edit.php" role="button">ユーザー情報変更</a>
            </div>
          </div>
        </div>
      </div><!-- container /div -->
    </main>

    <footer>
      <?php
      require_once('./page/footer.php');
      ?>
    </footer>

    </div>

    <script type="text/javascript" src="./read/jquery-3.4.1.min.js"></script>
    <script src="./read/bootstrap4/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
