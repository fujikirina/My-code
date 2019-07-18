<?php
/* 管理ページTOP、ショップ情報 */

/* 接続 */
require_once('../../ec.nya-n.xyz-require/db/db.php');
require_once('../data/data.php');

$d_productName = $d_breed = $d_pattern = $d_color = $d_color2 = $d_color3 = $_path = '';

/* POSTの受け取り */
if(!empty($_POST['name']) && !empty($_POST['breed']) && !empty($_POST['pattern']) && !empty($_POST['color']) && !empty($_FILES['img'])){
  $d_productName = $_POST['name'];
  $d_breed = $_POST['breed'];
  $d_pattern = $_POST['pattern'];
  $d_color = $_POST['color'];
  if(!empty($_POST['color2'])){
    $d_color2 = $_POST['color2'];
  }else{$d_color2 = '';}
  if(!empty($_POST['color3'])){
    $d_color3 = $_POST['color3'];
  }

  /* 画像をサーバーに保存する */
  $img_name = $_FILES['img']['tmp_name'];
  $type = substr($_FILES['img']['type'],6);
  $number = uniqid();
  move_uploaded_file($img_name,'../img/productImg/'.$number.'.'.$type);
  $path = "/img/productImg/$number.$type";

  /* MySQL内のデータを取得、データを格納する */
  $sql =
    "INSERT INTO product(productName, breed, pattern, color1, color2, color3, path)
    VALUES(:productName, :breed, :pattern, :color, :color2, :color3, :path)";
  $stmt = $dbh->prepare($sql);
  try{
    $stmt->bindValue(":productName", $d_productName, PDO::PARAM_STR);
    $stmt->bindValue(":breed", $d_breed, PDO::PARAM_STR);
    $stmt->bindValue(":pattern", $d_pattern, PDO::PARAM_STR);
    $stmt->bindValue(":color", $d_color, PDO::PARAM_STR);
    $stmt->bindValue(":color2", $d_color2, PDO::PARAM_STR);
    $stmt->bindValue(":color3", $d_color3, PDO::PARAM_STR);
    $stmt->bindValue(":path", $path, PDO::PARAM_STR);
    $stmt -> execute();
  }catch(Exception $e) {;}

}else{;}

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
              <a href="c-regis.php" class="list-group-item list-group-item-action disabled">商品登録</a>
              <a href="c-product.php" class="list-group-item list-group-item-action">商品情報</a>
              <a href="c-user.php" class="list-group-item list-group-item-action">会員情報</a>
              <a href="c-edit.php" class="list-group-item list-group-item-action text-danger">情報変更</a>
            </div>
          </div>
          <div class="col mt-2">
            <table class="table bg-white table-bordered">
              <form action="c-regis.php" method="post" class="form-inline" enctype="multipart/form-data">
                <tr><th colspan="2"><h3>商品登録</h3></th></tr>
                <tr><th>商品コード</th><td><input class="form-control" placeholder="自動登録" disabled></td></tr>
                <tr><th>商品名</th><td><input type="text" name="name" class="form-control" placeholder="入力してください" autocomplete="off"></td></tr>
                <tr><th>種類</th><td>
                  <select class="form-control" name="breed">
                    <option selected value="">選択してください</option>
                    <?php foreach($breed as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>パターン</th><td>
                  <select class="form-control" name="pattern">
                    <option selected value="">選択してください</option>
                    <?php foreach($pattern as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>カラー</th><td>
                  <select class="form-control" name="color">
                    <option selected value="">選択してください</option>
                    <?php foreach($color as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>カラー2</th><td>
                  <select class="form-control" name="color2">
                    <option selected value="">(任意)</option>
                    <?php foreach($color as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>カラー3</th><td>
                  <select class="form-control" name="color3">
                    <option selected value="">(任意)</option>
                    <?php foreach($color as $value): ?>
                      <option value="<?php echo $value ?>">
                        <?php echo $value ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </td></tr>
                <tr><th>画像</th><td><input type="file" class="form-control-file" name="img"></td></tr>
            </table>
          </div>
          <div class="w-100">
            <div class="row justify-content-end mr-4 mb-2">
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
