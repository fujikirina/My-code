<?php
/* 情報編集ページ */

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
              <a href="c-history.php" class="list-group-item list-group-item-action">販売履歴</a>
              <a href="c-regis.php" class="list-group-item list-group-item-action">商品登録</a>
              <a href="c-product.php" class="list-group-item list-group-item-action">商品情報</a>
              <a href="c-user.php" class="list-group-item list-group-item-action">会員情報</a>
              <a href="c-edit.php" class="list-group-item list-group-item-action text-danger disabled">情報変更</a>
            </div>
          </div>
          <div class="col mt-2 bg-white m-2 rounded">
            <h3 class="my-2">情報変更</h3>
             <form action="c-edit-submit.php" method="post">
              <div class="input-group">
                <select class="form-control" name="type">
                  <option selected value="">選択してください</option>
                    <option value="product">商品情報の変更</option>
                    <option value="user">会員情報の変更</option>
                </select>
                <input type="number" name="id" class="form-control">
                <div class="input-group-append"><input type="submit" class="btn btn-pink" value="検索する"></div>
              </div>
              <span class="form-text text-muted">
                商品情報なら商品コード、会員情報ならIDを入力して検索してください。
              </span>
          </div>
            </form>
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
