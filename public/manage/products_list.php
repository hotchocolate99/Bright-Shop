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
//error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$AllProductsDatas = getAllProductsDatas();
//print_r($AllProductsDatas);

$count_products = getCountProducts();
//var_dump($count_products);

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products List</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/products_list.css">
        <link rel="stylesheet" href="./../css/header.css">
    
    </head>

    <body>

     　<?php include './mng_header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title blue">Products List&nbsp;(<?php echo $count_products[0];?>)</h1>
                        <br>

                        <div class="frame">
                        <table>
                            <tr>
                              <td>

                              <?php for($i=0; $i<$count_products[0]; $i++):?>
                                   <?php $AllProductsData = $AllProductsDatas[$i];?>
                                  
                                    <div class="result_box"> 
                                      <h5 class="number"><strong><?php echo $i+1;?>.</strong></P>
                                      <a class="btn_b bg_blue" href="./update_product.php?product_id=<?php echo h($AllProductsData['id']);?>">Go To Update</a>
                                            <a class="btn_b bg_gray" href="./delete_product.php?product_id=<?php echo h($AllProductsData['id']);?>">Delete Whole Data</a>

                                       
                                        <table border=1>
                                          <tr><td>Product ID</td><td><?php echo h($AllProductsData['id'])?></td></tr>
                                          <tr><td>Name</td><td><?php echo h($AllProductsData['product_name'])?></td></tr>
                                          <tr><td>Registration Date</td><td><?php echo h($AllProductsData['updated_at'])?></td></tr>
                                          <tr><td>Category</td><td><?php echo h($AllProductsData['category'])?></td></tr>
                                          <tr><td>Description</td><td><?php echo nl2br(h($AllProductsData['description']))?></td></tr>
                                          <tr><td rowspan=2>Image</td><td rowspan=2><img src="/manage/<?php echo "{$AllProductsData['save_path']}";?>"　width="120px" height="200px" alt="product_image" ></td></tr>
                                        </table>

                                          <div class="details">

                                            <?php $productDetails = getProductDetails($AllProductsData['id']);?>
                                            <?php $count_productDetails = getCountProductDetails($AllProductsData['id']);?>
                                                <?php for($j=0; $j<$count_productDetails[0]; $j++):?>
                                                  <?php $productDetail = $productDetails[$j];?>

                                                  
                                                    <div class="details_box">
                                                    <table class="details_table detail_item" border=1>
                                                      <tr><td>Details ID</td><td><?php echo h($productDetail['id'])?></td></tr>
                                                      <tr><td>Registration Date</td><td><?php echo h($productDetail['updated_at'])?></td></tr>
                                                      <tr><td>Price(¥)</td><td><?php echo n($productDetail['price'])?></td></tr>
                                                      <tr><td>Gender</td><td><?php echo setGender($productDetail['gender'])?></td></tr>
                                                      <tr><td>Color</td><td><?php echo h($productDetail['color'])?></td></tr>
                                                      <tr><td>Size(cm)</td><td><?php echo h($productDetail['size'])?></td></tr>
                                                      <?php if ($productDetail['stock'] < 3):?>
                                                          <tr><td>Stocks</td><td><p class="last"><?php echo h($productDetail['stock'])?></p></td></tr>
                                                      <?php else:?>
                                                           <tr><td>Stocks</td><td><?php echo h($productDetail['stock'])?></td></tr>
                                                      <?php endif;?>
                                                    </table>

                                                    <div class="delete_btn detail_item"><a class="btn_b bg_gray" href="./delete_product.php?productDetail_id=<?php echo h($productDetail['id']);?>"><?php echo h('Delete Detail ID'.$productDetail['id']);?></a></div>
                                                </div><!--details_box-->
                                                <?php endfor;?>
                                            </div><!--details-->
                                           
                                            <div class="dividing"></div>
                                      </div><!--result_box-->
                                      <br>
                                      <?php endfor;?>
                                      
                                      
                                  

                              </td>
                            </tr>
                         </table>
                     </div><!--frame-->
                        
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>