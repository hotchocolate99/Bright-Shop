<?php
session_start();
if ($_SESSION['login']= true) {
    $mgr = $_SESSION['mgr'];
  }
  $managers_id = $manager[0]['mgr_id'];
//--------------------------------
ini_set('display_errors', true);

require_once './../private/database.php';
require_once './../private/functions.php';

$AllProductsDatas = getAllProductsDatas();
//print_r($AllProductsDatas);

$count_products = getCountProducts();
//var_dump($count_products);

$productDtailsDatas = getProductDetails(13);
print_r($productDtailsDatas);



?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products List</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/products_list.css">
        <link rel="stylesheet" href="./../../css/header.css">
    
    </head>

    <body>

     　<?php include './mng_header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title blue">Products List&nbsp;(<?php echo $count_products[0];?>)</h1>
                        <p>Click the product to update it's infomation.</p>
                        <br>

                        <div class="frame">
                        <table>
                            <tr>
                              <td>

                              <?php for($i=0; $i<$count_products[0]; $i++):?>
                                   <?php $AllProductsData = $AllProductsDatas[$i];?>
                                  
                                    <div class="result_box"> 
                                      <h5 class="number"><strong><?php echo $i+1;?>.</strong></P>
                                      

                                       <a class="link_aa" href="./update_product.php?product_id=<?php echo h($AllProductsData['id'])?>">
                                        <table border=1>
                                          <tr><td>Product ID</td><td><?php echo h($AllProductsData['id'])?></td></tr>
                                          <tr><td>Name</td><td><?php echo h($AllProductsData['product_name'])?></td></tr>
                                          <tr><td>Category</td><td><?php echo h($AllProductsData['category'])?></td></tr>
                                          <tr><td>Description</td><td><?php echo h($AllProductsData['description'])?></td></tr>
                                          <tr><td rowspan=2>Image</td><td rowspan=2><img src="/manage/<?php echo "{$AllProductsData['save_path']}";?>"　width="120px" height="200px" alt="product_image" ></td></tr>
                                        </table>
                                      </a>
                                          <div class="details">
                                              
                                            <?php $productDtails = getProductDetails($AllProductsData['id']);?>
                                            <?php $count_productDetails = getCountProductDetails($AllProductsData['id']);?>
                                                <?php for($j=0; $j<$count_productDetails[0]; $j++):?>
                                                  <?php $productDtail = $productDtails[$j];?>

                                                  <a class="link_aa" href="./update_product.php?product_id=<?php echo h($AllProductsData['id'])?>&details_id=<?php echo h($productDtail['id'])?>">
                                                    <table class="details_table" border=1>
                                                    <tr><td>Details ID</td><td><?php echo h($productDtail['id'])?></td></tr>
                                                      <tr><td>Price(¥)</td><td><?php echo n($productDtail['price'])?></td></tr>
                                                      <tr><td>Gender</td><td><?php echo setGender($productDtail['gender'])?></td></tr>
                                                      <tr><td>Color</td><td><?php echo h($productDtail['color'])?></td></tr>
                                                      <tr><td>Size(cm)</td><td><?php echo h($productDtail['size'])?></td></tr>
                                                      <tr><td>Stocks</td><td><?php echo h($productDtail['stock'])?></td></tr>
                                                    </table>
                                                </a>
                                                <?php endfor;?>
                                            </div><!--details-->
                                            <p class="dividing"></p>
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