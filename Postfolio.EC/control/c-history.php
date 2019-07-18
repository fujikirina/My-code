<?php
/* 販売履歴確認 */

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
require_once('../../ec.nya-n.xyz-require/db/db.php');

/* MySQL内のデータを取得 */
try{
  $stmt = $dbh->query(
    "SELECT product.productCode, productName, quantity, name, order_history.date
    FROM order_history LEFT JOIN product
    ON order_history.productCode = product.productCode
    LEFT JOIN member ON order_history.user_id = member.id
    ORDER BY order_history.date DESC LIMIT 60 OFFSET ".$offset.""
  );
}catch(PDOException $e){;}

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
              <a href="c-top.php" class="list-group-item list-group-item-action">ショップ情報</a>
              <a href="c-history.php" class="list-group-item list-group-item-action disabled">販売履歴</a>
              <a href="c-regis.php" class="list-group-item list-group-item-action">商品登録</a>
              <a href="c-product.php" class="list-group-item list-group-item-action">商品情報</a>
              <a href="c-user.php" class="list-group-item list-group-item-action">会員情報</a>
              <a href="c-edit.php" class="list-group-item list-group-item-action text-danger">情報変更</a>
            </div>
          </div>
          <div class="col mt-2">
            <table class="table bg-white table-bordered">
              <tr><th colspan="5"><h3>販売履歴</h3></th></tr>
              <tr><th>商品コード</th><th>商品名</th><th>販売額</th><th>購入者</th><th>販売日</th></tr>
              <?php foreach ($stmt as $data): ?>
                <tr>
                  <td><?php echo $data['productCode'] ?></td>
                  <td><a class="text-dark" href="https://ec.nya-n.xyz/product.php?code=<?php echo $data['productCode'] ?>"><?php echo $data['productName'] ?></a></td>
                  <td><?php echo $data['quantity'] ?></td>
                  <td><?php echo $data['name'] ?></td>
                  <td><?php echo $data['date'] ?></td>
                </tr>
              <?php endforeach ?>
            </table>
          </div>
        </div>
        <div class="w-100">
          <div class="row justify-content-end mr-3 mb-1">
            <form action="c-history.php" method="post" class="form-inline">
              <input type="submit" class="btn btn-pink m-1" name="before" value="前の60件">
              <input type="submit" class="btn btn-pink m-1" name="after" value="次の60件">
              <input type="hidden" name="offset" value="<?php echo $offset ?>">
            </form>
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
