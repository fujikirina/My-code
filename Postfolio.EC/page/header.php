<?php
/* header */

session_start();
/* MySQLに接続 */
require_once('../ec.nya-n.xyz-require/db/db.php');

/* ログインの確認 */
if(!empty($_SESSION['portEcUserMail']) && !empty($_SESSION['portEcUserName']) && !empty($_SESSION['portEcUserId'])){
  $name = $_SESSION['portEcUserName'];
  $id = $_SESSION['portEcUserId'];
  $login = 'nya-n!';
}

/* カートの処理 */
if(!empty($id)){
    $sql = "SELECT COUNT(user_id) FROM cart WHERE user_id = :id";
    $stm = $dbh->prepare($sql);
  try{
    $stm ->bindValue(":id", $id, PDO::PARAM_STR);
    $stm -> execute();
    foreach ($stm as $number){break;}
    $cart = $number['COUNT(user_id)'];
    $sql = "SELECT cute FROM member_cute WHERE user_id = :id";
    $stm = $dbh->prepare($sql);
    $stm ->bindValue(":id", $id, PDO::PARAM_STR);
    $stm -> execute();
    foreach ($stm as $number){break;}
    $haveCute = $number['cute'];
  }catch(PDOException $e){;}
}else{$cart = 0;}

/* MySQLから離脱 */
unset($dbh);

?>

<nav class="navbar navbar-expand-md navbar-light m-0">
  <a class="navbar-brand text-lightpink text-shadow-b" href="https://ec.nya-n.xyz"><h1 class="mb-0">にゃーん！</h1></a>
  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#Navber" aria-controls="Navber" aria-expanded="false">
    <span class="navbar-toggler-icon"></span>
  </button>
  <?php if($login === 'nya-n!'){echo
  '<div class="collapse navbar-collapse" id="Navber">
    <ul class="navbar-nav ml-auto mt-2 mt-md-0">
      <li class="order-md-2 m-1 d-flex align-items-end">
        <a class="btn btn-info shadow-sm ml-2" href="cart.php" role="button">カート<span class="badge badge-light">'.$cart.'</span></a>
      </li>
      <li class="nav-item active dropdown order-md-1">
        <p class="text-lightpink m-0 mt-1 ml-3">所持CUTE：'.$haveCute.'</p>
        <a href="#" class="nav-link dropdown-toggle dropdown-menu-right text-light lead text-shadow-b" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          ようこそ、'.$name.'さん
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="mypage.php">ユーザー情報</a>
          <a class="dropdown-item" href="order-history.php">購入履歴</a>
          <a class="dropdown-item" href="charge.php">CUTEチャージ</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger" href="logout.php">ログアウト</a>
        </div>
      </li>
    </ul>
  </div>';}else{echo
    '<div class="collapse navbar-collapse" id="Navber">
      <ul class="navbar-nav ml-auto mt-2 mt-md-0">
        <li class="m-1">
          <a class="btn btn-outline-pink backlight ml-2" href="discription.php" role="button">このサイトの説明</a>
        </li>
        <li class="m-1">
          <a class="btn btn-info shadow-sm ml-2" href="signup.php" role="button">会員登録</a>
        </li>
        <li class="m-1">
          <a class="btn btn-info shadow-sm ml-2" href="login.php" role="button">ログイン</a>
        </li>
      </ul>
    </div>';} ?>
</nav>
