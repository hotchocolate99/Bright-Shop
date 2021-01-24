<?php
//----ログイン状態-----------------
session_start();

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  $users_id = $user[0]['usr_id'];
//--------------------------------
//var_dump($user);
ini_set('display_errors',true);


require_once './../../private/database.php';
require_once './../../private/functions.php';
//var_dump($_SESSION['user']);


//session_destroy();

//-----------------------------------------------------------------------------------------
$boys_all_products_ids = getAllProductsIdByGender(1);
//var_dump($girls_all_products_ids);
foreach($boys_all_products_ids as $boys_all_products_id){
    //var_dump($girls_all_products_id['product_id']);


  $boys_product_id = $boys_all_products_id['product_id'];

  $boys_all_products_datas = getProductsDataById($boys_product_id);
  //var_dump($boys_all_products_datas);
  foreach($boys_all_products_datas as $boys_all_products_data){
       var_dump($boys_all_products_data['product_name']);
  }
}

$total_in_cart = 0;
if($_SESSION['shopping_cart']){
    foreach($_SESSION['shopping_cart'] as $detail){
      if(!empty($details)){
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

<!--image-->
    <div class="image">
          <div class="message"><p class="anime">Light Up Your Life<br>With Bright-Shop.</p></div>
    </div>

    <div class="menu">
      <ul>
        <li><a class="link_aa" href="/public/view/boys.php"><h2 class="form_title green">Boys</h2></a></li>

        <li><a class="link_aa" href="/public/view/girls.php"><h2 class="form_title pink">Girls</h2></a></li>
        <li>
          <div class="search">
              <form action="/public/view/search_result.php" method="post">
                  <input type="text" name="search_word" class="sample2Text" placeholder="Search">
              </form>
          </div>
        </li>
      </ul>

    </div>

    <div class="wrapper">
                <div class="container">
                
                    <div class="typein">
                    <h1 class="form_title green">Boys</h1><br>
                    <div class="product_info">
                    <?php $boys_all_products_ids = getAllProductsIdByGender(1);?>
<?php //var_dump($girls_all_products_ids);?>
<?php foreach($boys_all_products_ids as $boys_all_products_id):?>
    <?php //var_dump($boys_all_products_id['product_id']);?>

       <?Php $boys_product_id = $boys_all_products_id['product_id'];?>

        <?php $boys_all_products_datas = getProductsDataById($boys_product_id);?>
         <?php //var_dump($boys_all_products_datas);?>
<?php foreach($boys_all_products_datas as $boys_all_products_data):?>
<?php// var_dump($boys_all_products_data['product_name']);?>
<?php $prices = getPriceByProductId($boys_all_products_data['id']);?>
<?php// var_dump($prices);?>


                        
                        


                            <div class="product_box">
                                <div class="main_part">
                                      <div class="img_box">
                                         <img src="/public/manage/<?php echo "{$boys_all_products_data['save_path']}";?>"　width="240px" height="400px" alt="product_image" >
                                         <a class="link_aa favorite" href="./?favorite_product_id=<?php echo h($boys_all_products_data['id'])?>"><span><i class="fas fa-heart"></i></span></a>
                                      </div>
                                </div>
                                <div class="text_part">
                                  <h2 class="product_name"><?php echo $boys_all_products_data['product_name'];?></h2>
                                  <h2>¥&nbsp;<?php echo n($prices['price']);?>&nbsp;(Tax not included)</h2>
                                  <h3><?php echo $boys_all_products_data['description'];?></h3>
                                  <a class="button" href="/public/view/product_details.php?id=<?php echo h($boys_all_products_data['id'])?>">Go To Details</a>
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