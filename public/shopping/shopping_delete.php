<?php
session_start();

echo 'ゲット';
var_dump($_GET);

echo 'カート';

var_dump($_SESSION['shopping_cart']);
foreach($_SESSION['shopping_cart'] as $details){
  echo'カートの中身';
  var_dump($details['detail_id']);//detail_idがちゃんと出てくる。
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
header('location: /public/shopping/shopping_cart.php');
//--------------------------------------------------------------
