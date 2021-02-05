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
//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors',true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

//-----------------------------------------------------------------------------------------
$boys_all_products_ids = getAllProductsIdByGender(1);
//var_dump($girls_all_products_ids);
foreach($boys_all_products_ids as $boys_all_products_id){
    //var_dump($girls_all_products_id['product_id']);


  $boys_product_id = $boys_all_products_id['product_id'];

  $boys_all_products_datas = getProductsDataById($boys_product_id);
  //var_dump($boys_all_products_datas);
  foreach($boys_all_products_datas as $boys_all_products_data){
      // var_dump($boys_all_products_data['product_name']);
  }
}

if($_SESSION['shopping_cart']){
    $total_in_cart = 0;
    foreach($_SESSION['shopping_cart'] as $detail){
      if(!empty($_SESSION['shopping_cart'])){
        $total_in_cart += $detail['detail_count'];
      }
    }
  }

?>




<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Boys</title>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
      <link rel="stylesheet" href="./../css/boysgirls.css">
      <link rel="stylesheet" href="./../css/header.css">
  </head>

  <body>

    <?php include './../header.php';?>

    <div class="menu">
        <ul>
            
            <li><a class="link_aa" href="/view/girls.php"><h2 class="form_title pink">Girls</h2></a></li>
        </ul>
    </div>

    <div class="wrapper">
        <div class="container">
            <div class="typein">
                <h1 class="form_title green">Boys</h1><br>
                <div class="product_info">
                    <?php $boys_all_products_ids = getAllProductsIdByGender(1);?>
                    <?php foreach($boys_all_products_ids as $boys_all_products_id):?>

                        <?Php $boys_product_id = $boys_all_products_id['product_id'];?>

                        <?php $boys_all_products_datas = getProductsDataById($boys_product_id);?>

                        <?php foreach($boys_all_products_datas as $boys_all_products_data):?>
                              <?php $prices = getPriceByProductId($boys_all_products_data['id']);?>

                                  <div class="product_box">
                                      <div class="main_part">
                                          <div class="img_box">
                                              <img src="/manage/<?php echo "{$boys_all_products_data['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                                          </div>
                                      </div>

                                      <div class="text_part">
                                          <h2 class="product_name"><?php echo $boys_all_products_data['product_name'];?></h2>
                                          <h2>¥&nbsp;<?php echo n($prices['price']);?>&nbsp;(Tax not included)</h2>
                                          <h3><?php echo $boys_all_products_data['description'];?></h3>
                                          <a class="button" href="/view/product_details.php?id=<?php echo h($boys_all_products_data['id'])?>">Go To Details</a>
                                      </div>
                                  </div><!--product_box-->

                        <?php endforeach;?>
                    <?php endforeach;?>
                </div><!--product_info-->
            </div><!--typein-->
        </div><!--container-->
    </div><!--wrappr-->

  </body>
</html>