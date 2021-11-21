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
$girls_all_products_ids = getAllProductsIdByGender(2);

foreach($girls_all_products_ids as $girls_all_products_id){
    //var_dump($girls_all_products_id['product_id']);

  $girls_product_id = $girls_all_products_id['product_id'];

  $girls_all_products_datas = getProductsDataById($girls_product_id);
  //var_dump($girls_all_products_datas);
  foreach($girls_all_products_datas as $girls_all_products_data){
       //var_dump($girls_all_products_data['product_name']);
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
      <title>Girls</title>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
      <link rel="stylesheet" href="./../css/boysgirls.css">
      <link rel="stylesheet" href="./../css/header.css">
  </head>

  <body>

      <?php include './../header.php';?>

      <div class="wrapper">
          <div class="container">
              <ul class="bit_left">
                  <li class="boys_girls"><a class="link_aa" href="/view/boys.php"><h2 class="form_title green">Boys</h2></a></li>
              </ul>

              <div class="typein">
                    <h1 class="form_title pink">Girls</h1><br>
                    <div class="product_info">
                        <?php $girls_all_products_ids = getAllProductsIdByGender(2);?>
                        <?php foreach($girls_all_products_ids as $girls_all_products_id):?>

                            <?Php $girls_product_id = $girls_all_products_id['product_id'];?>

                            <?php $girls_all_products_datas = getProductsDataById($girls_product_id);?>

                            <?php foreach($girls_all_products_datas as $girls_all_products_data):?>
                                <?php $prices = getPriceByProductId($girls_all_products_data['id']);?>

                                    <div class="product_box">
                                        <!--<div class="main_part">-->
                                            <div class="img_box">
                                                <img src="/manage/<?php echo "{$girls_all_products_data['save_path']}";?>"　width="240px" height="400px" alt="product_image">
                                              </div>
                                        <!--</div>-->

                                        <div class="text_part">
                                            <h2 class="product_name"><?php echo $girls_all_products_data['product_name'];?></h2>
                                            <?php $allReviews = getAllReviewsByProductId($product_id);?>
                            
                            <div class="line">
                            <?php if($star_rating <= 0.1):?>
                                <span><i class="big far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>
                                        
                            <?php if($star_rating >= 0.2 && $star_rating <= 0.6):?>
                                <span><i class="fas fa-star-half-alt checked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                            <?php endif;?>
                                          
                            <?php if($star_rating >= 0.7 && $star_rating <= 1.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                            <?php endif;?>
                                        
                            <?php if($star_rating >= 1.3 && $star_rating <= 1.7):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star-half-alt checked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                                <span><i class="fas fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 1.8 && $star_rating <= 2.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 2.3 && $star_rating <= 2.7):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star-half-alt checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star nchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 2.8 && $star_rating <= 3.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 3.3 && $star_rating <= 3.7):?>
                                <span><i class="fas fa-star big checked"></i></span>
                                <span><i class="fas fa-star big checked"></i></span>
                                <span><i class="fas fa-star big checked"></i></span>
                                <span><i class="fas fa-star-half-alt big checked"></i></span>
                                <span><i class="far fa-star big unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 3.8 && $star_rating <= 4.2):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star unchecked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 4.3 && $star_rating <= 4.7):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="far fa-star-half-alt checked"></i></span>
                            <?php endif;?>

                            <?php if($star_rating >= 4.8 && $star_rating <= 5.0):?>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                                <span><i class="fas fa-star checked"></i></span>
                            <?php endif;?>
                        </div>&nbsp;

                        <?php if(!empty($allReviews)):?>
                                <?php if($reviews_count == 1):?>
                                    <p class="line"><?php echo $star_rating."  "."out of"."  "."5"."  "."($reviews_count"." "."review)";?></p><br><br><br>
                                <?php endif;?>

                                <?php if($reviews_count > 1):?>
                                    <p class="line"><?php echo (floor($star_rating * 10) / 10)."  "."out of"."  "."5"."  "."($reviews_count"." "."reviews)";?></p><br><br><br>
                                <?php endif;?>
                            <?php endif ;?>

                            <?php if(empty($allReviews)):?>
                                 <p><?php echo 'There is no reviews of this product yet.';?></p>
                            <?php endif ;?>

                                            <h2>¥&nbsp;<?php echo n($prices['price']);?>&nbsp;(Tax not included)</h2>
                                            <h3><?php echo $girls_all_products_data['description'];?></h3>
                                            <a class="button" href="/view/product_details.php?id=<?php echo h($girls_all_products_data['id'])?>">Go To Details</a>
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