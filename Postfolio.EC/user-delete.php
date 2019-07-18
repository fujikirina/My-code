<?php
/* 退会ページ */

session_start();

/* ログインしていない場合indexに飛ばす */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  $id = $_SESSION['portEcUserId'];
  }else{
  $_SESSION['noLogin'] = 'noLogin';
  header('Location: index.php');
  exit;
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

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
              <a href="mypage.php" class="list-group-item list-group-item-action">ユーザー情報</a>
              <a href="order-history.php" class="list-group-item list-group-item-action">購入履歴</a>
              <a href="charge.php" class="list-group-item list-group-item-action">CUTEチャージ</a>
              <a href="logout.php" class="list-group-item list-group-item-action">ログアウト</a>
              <a href="user-delete.php" class="list-group-item list-group-item-action text-danger disabled">退会</a>
            </div>
          </div>
          <div class="col mt-2 bg-white m-2 rounded">
            <h3 class="my-2">アカウントの削除</h3>
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading mb-2">退会後はデータの復帰が出来ません！</h4>
                <p class="my-1">本当に退会しますか？</p>
            </div>
            <div class="w-100">
              <div class="row justify-content-end mr-4">
                <form action="user-delete-submit.php" method="post">
                  <input name="userDelete" type="submit" class="btn btn-pink" value="退会する">
                </form>
              </div>
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
