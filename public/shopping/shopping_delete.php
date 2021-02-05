<?php
//----ログイン状態-----------------
session_start();

if ($_SESSION['login']= true) {
  $users = $_SESSION['user'];
}
//var_dump($users);
foreach($users as $user){
  //var_dump($user['id']);
}
$user_id = $user['id'];
//--------------------------------

//var_dump($_SESSION['shopping_cart']);
foreach($_SESSION['shopping_cart'] as $details){
  //var_dump($details['detail_id']);
}

//deleteの処理-------------------------------------------------
$delete_detail_id = $_GET['detail_id'];

// 削除する商品IDがありショッピングカートに商品が登録されている場合
if (!empty($delete_detail_id) && $_SESSION['shopping_cart']) {
  
    // 除外後の商品をいれる
    $shoppingCart = [];

    // ショッピングカートから削除する商品IDを検索し、あれば除外する
    foreach($_SESSION['shopping_cart'] as $details) {

        if ($details['detail_id'] != $delete_detail_id) {
          $shoppingCart[] = $details;
        }
    }

    // 除外後の商品で上書き
    $_SESSION['shopping_cart'] = $shoppingCart;
  
}

// cart.phpに遷移
header('location: /shopping/shopping_cart.php');
//--------------------------------------------------------------
