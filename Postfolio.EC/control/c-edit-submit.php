<?php
/* 商品情報編集 */

/* MySQLに接続 */
require_once('../../ec.nya-n.xyz-require/db/db.php');
require_once('../data/data.php');

session_start();

/* POSTの取得 */
if(!empty($_POST['id'])){
  $id = $_POST['id'];
}

$d_productCode = $d_productName = $d_breed = $d_pattern = $d_color = $d_color2 = $d_color3 = $productCode = '';

/* POSTの受け取り、更新処理 */
if(!empty($_POST['productCode']) && !empty($_POST['name']) || !empty($_POST['breed']) || !empty($_POST['pattern']) || !empty($_POST['color']) || !empty($_POST['color2']) || !empty($_POST['color3'])){
  $d_productCode = $_POST['productCode'];
  $d_productName = $_POST['name'];
  $d_breed = $_POST['breed'];
  $d_pattern = $_POST['pattern'];
  $d_color = $_POST['color'];
  $d_color2 = $_POST['color2'];
  $d_color3 = $_POST['color3'];
  $_SESSION['editSubmit']='submit';

  /* MySQL内のデータを取得、データを格納する */
  $sql =
    "UPDATE product SET productName = :productName, breed = :breed, pattern = :pattern, color1 = :color, color2 = :color2, color3 = :color3 WHERE productCode = :code";
  $stmt = $dbh->prepare($sql);
  try{
    $stmt->bindValue(":productName", $d_productName, PDO::PARAM_STR);
    $stmt->bindValue(":breed", $d_breed, PDO::PARAM_STR);
    $stmt->bindValue(":pattern", $d_pattern, PDO::PARAM_STR);
    $stmt->bindValue(":color", $d_color, PDO::PARAM_STR);
    $stmt->bindValue(":color2", $d_color2, PDO::PARAM_STR);
    $stmt->bindValue(":color3", $d_color3, PDO::PARAM_STR);
    $stmt->bindValue(":code", $d_productCode, PDO::PARAM_STR);
    $stmt -> execute();
  }catch(Exception $e) {;}
  $msg = '更新が完了しました！';

}else{;}

if($_POST['type']=='product' || !empty($_SESSION['editSubmit'])){
  /* MySQL内のデータを取得 */
  $stmt = $dbh->prepare("SELECT productCode, productName, breed, pattern, color1, color2, color3 FROM product WHERE productCode=:id");
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){;}
  unset($_SESSION['editSubmit']);
}elseif($_POST['type']=="user"){
  $_SESSION['editId'] = $id;
  header('Location: c-edit-submit2.php');
  exit;
}

/* データの取得 */
foreach($stmt as $data){
  break;
}

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
        <div class="my-3"><h2>管理ページ</h2>
        <div class="row">
          <div class="w-30">
            <div class="list-group col mt-2 ml-3">
              <a href="c-top.php" class="list-group-item list-group-item-action">ショップ情報</a>
              <a href="c-history.php" class="list-group-item list-group-item-action">販売履歴</a>
              <a href="c-regis.php" class="list-group-item list-group-item-action">商品登録</a>
              <a href="c-product.php" class="list-group-item list-group-item-action">商品情報</a>
              <a href="c-user.php" class="list-group-item list-group-item-action">会員情報</a>
              <a href="c-edit.php" class="list-group-item list-group-item-action text-danger">情報変更</a>
            </div>
          </div>
          <div class="col mt-2">
            <table class="table bg-white table-bordered">
              <form action="c-edit-submit.php" method="post" class="form-inline">
                <tr><th colspan="2"><h3 class="mb-2">情報変更</h3><h6>変更したい項目を入力してください。</h6>
                  <?php if(!empty($msg)): ?>
                  <div class="alert alert-success" role="alert">
                    <p class="alert-heading mb-2"><?php echo $msg; ?></p>
                  </div>
                <?php endif ?></th></tr>
                <tr><th>商品コード</th><td><input class="form-control" name="productCode" value="<?php echo $data['productCode']; ?>" readonly></td></tr>
                <tr><th>商品名</th><td><input type="text" name="name" class="form-control" value="<?php echo $data['productName']; ?>" autocomplete="off"></td></tr>
                <tr><th>種類</th><td>
                  <select class="form-control" name="breed">
                    <option selected value="<?php echo $data['breed']; ?>"><?php echo $data['breed']; ?></option>
                    <?php foreach($breed as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>パターン</th><td>
                  <select class="form-control" name="pattern">
                    <option selected value="<?php echo $data['pattern']; ?>"><?php echo $data['pattern']; ?></option>
                    <?php foreach($pattern as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>カラー</th><td>
                  <select class="form-control" name="color">
                    <option selected value="<?php echo $data['color1']; ?>"><?php echo $data['color1']; ?></option>
                    <?php foreach($color as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>カラー2</th><td>
                  <select class="form-control" name="color2">
                    <option selected value="<?php echo $data['color2']; ?>"><?php echo $data['color2']; ?></option>
                    <?php foreach($color as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>カラー3</th><td>
                  <select class="form-control" name="color3">
                    <option selected value="<?php echo $data['color3']; ?>"><?php echo $data['color3']; ?></option>
                    <?php foreach($color as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>画像</th><td>変更できません</td></tr>
            </table>
          </div>
          <div class="w-100">
            <div class="row justify-content-end mr-4 mb-2">
              <input type="hidden" name="id" class="btn btn-pink" role="button" value="<?php echo $data['productCode']; ?>">
              <input type="submit" name="edit" class="btn btn-pink" role="button" value="登録する">
            </div>
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
