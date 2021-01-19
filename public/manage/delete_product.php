<?php
session_start();
if ($_SESSION['login']= true) {
    $mgr = $_SESSION['mgr'];
  }
  $managers_id = $manager[0]['mgr_id'];
//--------------------------------
ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

var_dump($_GET['productDetail_id']);


if(isset($_GET['product_id'])){
   $product_id = $_GET['product_id'];
   //products tableから削除
   $deletedProduct = deleteProduct($product_id);

}

if(isset($_GET['productDetail_id'])){
   $productDetail_id = $_GET['productDetail_id'];
   //details tableから削除
  $deletedDetail = deleteDetails($productDetail_id);
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
        <link rel="stylesheet" href="./../../../public/css/header.css">
    </head>

    <body>

        <?php include './mng_header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                　  <div class="typein">
                
                <!--上手く反映されない。----------------------------------        
                <?php// if(!empty($deletedProduct)):?>
                            <p class="form_title"><?php// echo "Whole this product data has been deleted.";?></p>
                        <?php// elseif(!empty($deletedDetail)):?>
                            <p class="form_title"><?php// echo "One details part of the product has been deleted.";?></p>
                        <?php// endif;?>
                    ---------------------------------------------------------------->
                    <h2 class="form_title">Deleted successfuly.</h2>

                    </div>
                </div>
            </div>
        </label>
    </body>
</html>