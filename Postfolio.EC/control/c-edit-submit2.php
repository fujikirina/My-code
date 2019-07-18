<?php
/* ユーザー情報編集 */

session_start();

/* MySQLに接続 */
require_once('../../ec.nya-n.xyz-require/db/db.php');

/* POSTの取得 */
if(!empty($_POST['id'])){
  $id = $_POST['id'];
}

/* POSTの受け取り、更新処理 */
if(!empty($_POST['name']) && !empty($_POST['mail']) || !empty($_POST['cute'])){
  $name = $_POST['name'];
  $mail = $_POST['mail'];
  $cute = $_POST['cute'];

  /* MySQL内のデータを取得、データを格納する */
  $sql = "UPDATE member SET name = :name, mail = :mail WHERE id = :id; UPDATE member_cute SET cute = :cute WHERE user_id = :user_id";
  $stmt = $dbh->prepare($sql);
  try{
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":cute", $cute, PDO::PARAM_INT);
    $stmt->bindValue(":user_id", $id, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(Exception $e) {;}

  $msg = '更新が完了しました！';

}else{;}

/* SESSIONの取得 */
if(!empty($_SESSION['editId'])){
  $id = $_SESSION['editId'];
  unset($_SESSION['editId']);}

if(!empty($id)){
  /* MySQL内のデータを取得 */
  $stmt = $dbh->prepare("SELECT id, name, member.mail, cute FROM member JOIN member_cute ON member.id = member_cute.user_id WHERE id=:id");
  try{
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt -> execute();
  }catch(PDOException $e){;}
}

/* MySQLから離脱 */
unset($dbh);

/* データの取得 */
foreach($stmt as $data){
  break;
}

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
              <form action="c-edit-submit2.php" method="post" class="form-inline" enctype="multipart/form-data">
                <tr><th colspan="2"><h3 class="mb-2">情報変更</h3><h6>変更したい項目を入力してください。</h6>
                  <?php if(!empty($msg)): ?>
                  <div class="alert alert-success" role="alert">
                    <p class="alert-heading mb-2"><?php echo $msg; ?></p>
                  </div>
                  <?php endif ?>
                </th></tr>
                <tr><th>ID</th><td><input class="form-control" name="id" value="<?php echo $data['id']; ?>" readonly></td></tr>
                <tr><th>名前</th><td><input type="text" name="name" class="form-control" value="<?php echo $data['name']; ?>" autocomplete="off"></td></tr>
                <tr><th>メールアドレス</th><td><input type="mail" name="mail" class="form-control" value="<?php echo $data['mail']; ?>" autocomplete="off"></td></tr>
                <tr><th>所持CUTE数</th><td><input type="number" name="cute" class="form-control" value="<?php echo $data['cute']; ?>" autocomplete="off"></td></tr>
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
