<?php
/* 購入履歴 */

session_start();

/* ログインしていない場合indexに飛ばす */
if(!empty($_SESSION['portEcUserId']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserMail'])){
  $id = $_SESSION['portEcUserId'];
}else{
  $_SESSION['noLogin'] = 'noLogin';
  header('Location: index.php');
  exit;
}

/* POSTの取得 */
if(!empty($_POST['offset'])){
  $offset = $_POST['offset'];
}else{
  $offset = 0;
}
if(!empty($_POST['before'])){
  $offset -= 60;
}
if(!empty($_POST['after'])){
  $offset += 60;
}

/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* 注文履歴の確認 */
try{
  $stmt = $dbh->prepare(
    "SELECT productName, quantity, order_history.productCode, order_history.date FROM order_history
    JOIN product ON order_history.productCode = product.productCode WHERE user_id = :id
    ORDER BY order_history.date DESC LIMIT 60 OFFSET :value"
  );
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  $stmt->bindValue(":value", $offset, PDO::PARAM_INT);
  $stmt -> execute();
}catch(PDOException $e){;}

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
              <a href="order-history" class="list-group-item list-group-item-action disabled">購入履歴</a>
              <a href="charge.php" class="list-group-item list-group-item-action">CUTEチャージ</a>
              <a href="logout.php" class="list-group-item list-group-item-action">ログアウト</a>
              <a href="user-delete.php" class="list-group-item list-group-item-action text-danger">退会</a>
            </div>
          </div>
          <div class="col mt-2">
              <table class="table bg-white table-bordered">
                <tr><th colspan="3"><h3>購入履歴</h3></th></tr>
                <tr><th>商品名</th><th>値段</th><th>購入日</th></tr>
                <?php foreach($stmt as $data): ?>
                  <tr><td><a class="text-dark" href="https://ec.nya-n.xyz/product.php?code=<?php echo $data['productCode'] ?>"><?php echo $data['productName'] ?></a></td><td><?php echo $data['quantity'] ?></td><td><?php echo $data['date'] ?></td></tr>
                <?php endforeach ?>
              </table>
          </div>
          <div class="w-100">
            <div class="row justify-content-end mr-3 mb-1">
              <form action="order-history.php" method="post" class="form-inline">
                <input type="submit" class="btn btn-pink m-1" name="before" value="前の60件">
                <input type="submit" class="btn btn-pink m-1" name="after" value="次の60件">
                <input type="hidden" name="offset" value="<?php echo $offset ?>">
              </form>
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
