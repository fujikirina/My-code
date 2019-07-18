<?php
/* 管理ページTOP、ショップ情報 */

/* MySQLに接続 */
require_once('../../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得、データを取り出す */

  $stmt_p = $dbh->query("SELECT COUNT(productCode) FROM product");
  $stmt_h = $dbh->query("SELECT SUM(quantity) FROM order_history");
  $stmt_m = $dbh->query("SELECT COUNT(id) FROM member");
  $stmt_c = $dbh->query("SELECT SUM(cute) FROM member_cute");

/* 取得したデータを取り出す */
foreach ($stmt_p as $product){break;}
foreach ($stmt_h as $history){break;}
foreach ($stmt_m as $member){break;}
foreach ($stmt_c as $cute){break;}

/* MySQLから離脱 */
unset($dbh);

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="猫の可愛さ販売サイトです。">
    <title>にゃーん！</title>
    <link rel="icon" href="../img/favicon.ico">
    <link href="../read/bootstrap4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../read/style.css">
  </head>

  <body>
    <div class="container">

    <header>
      <?php
      require_once('./header.php');
      ?>
    </header>

    <main>
      <div class="container backlight py-1">
        <div class="my-3"><h2>管理ページ</h2></div>
        <div class="row">
          <div class="w-30">
            <div class="list-group col mt-2 ml-3">
              <a href="c-top.php" class="list-group-item list-group-item-action disabled">ショップ情報</a>
              <a href="c-history.php" class="list-group-item list-group-item-action">販売履歴</a>
              <a href="c-regis.php" class="list-group-item list-group-item-action">商品登録</a>
              <a href="c-product.php" class="list-group-item list-group-item-action">商品情報</a>
              <a href="c-user.php" class="list-group-item list-group-item-action">会員情報</a>
              <a href="c-edit.php" class="list-group-item list-group-item-action text-danger">情報変更</a>
            </div>
          </div>
          <div class="col mt-2">
            <table class="table bg-white table-bordered">
              <tr><th colspan="2"><h3>ショップ情報</h3></th></tr>
              <tr><th>商品数</th><td><?php echo $product['COUNT(productCode)'] ?></td></tr>
              <tr><th>総販売額</th><td><?php echo $history['SUM(quantity)'] ?></td></tr>
              <tr><th>会員数</th><td><?php echo $member['COUNT(id)'] ?></td></tr>
              <tr><th>総CUTE数</th><td><?php echo $cute['SUM(cute)'] ?></td></tr>
            </table>
          </div>
        </div>
      </div><!-- container /div -->
    </main>

    <footer>
      <?php
      require_once('../page/footer.php');
      ?>
    </footer>

    </div>

    <script type="text/javascript" src="../read/jquery-3.4.1.min.js"></script>
    <script src="../read/bootstrap4/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
