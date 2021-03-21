<?php
session_start();

 if (!$_SESSION['login']) {
    header('Location: /manage/mng_login.php');
    exit();
  }

  if ($_SESSION['login']= true) {
    $mgrs = $_SESSION['mgr'];
  }
  //var_dump($mgrs);
  foreach($mgrs as $mgr){
    //var_dump($mgr['id']);
  }
  $managers_id = $mgr['id'];
  //var_dump($managers_id);
//--------------------------------

ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);


require_once './../../private/database.php';
require_once './../../private/functions.php';

var_dump($_GET['productDetail_id']);


if(isset($_GET['product_id'])){
   $product_id = $_GET['product_id'];
   //products tableから削除
   $deletedProduct = deleteProduct($product_id);

  //product_details tableから削除
   $deletedDetails = deletedDetailsByProduct_id($product_id);
   header("Location: ./products_list.php");
}

if(isset($_GET['productDetail_id'])){
   $productDetail_id = $_GET['productDetail_id'];
   $productIds= getProductIdByDetailId($productDetail_id);

    $product_id = $productIds['product_id'];
   
   //details tableから削除
  $deleted_detail = deleteDetails($productDetail_id);

header("Location: ./products_list.php?product_id=".$product_id);

}



?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Product</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>

        <?php include './mng_header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                　  <div class="typein">
                
                    <h2 class="form_title">Deleted successfuly.</h2>

                    </div>
                </div>
            </div>
        </label>
    </body>
</html>