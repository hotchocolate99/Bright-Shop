<?php
session_start();

if ($_SESSION['login']= true) {
    $mgr = $_SESSION['mgr'];
  }
  $managers_id = $manager[0]['mgr_id'];
//------------------------------------------------

ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';
//-----------------------------------------------------------------------

$allOrders = getAllOrders();
var_dump($allOrders);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders list</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../../public/css/history.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
</head>
<body>

        <?php include './mng_header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                　<div class="typein">
                　　　<h1 class="form_title orange">Order List</h1>

        <div class="order_box">

                <?php for($i=0;$i<count($all_orders);$i++):?>
                    <?php $all_order = $all_orders[$i];?>
                   <p><?php echo $i+1;?>.&nbsp;<strong>Order no:<?php echo $all_order['id'];?>(Purchase date:<?php echo "{$all_order['ordered_at']}";?>)</strong></p>
                      
                    <div class="group_box">
                        

                        <?php $order_details = getOrderDetails($order_id);?>
                            <?php for($j=0;$j<count($order_details);$j++):?>
                                <?php $order_detail = $order_details[$j];?>

                                  <?php $product_details = getProductDetailsByDetailId($order_detail['detail_id']);?>
                                    <?php foreach($product_details as $product_detail):?>

                                              <?php $product_ids = getProductIdByDetailId($order_detail['detail_id']);?>
                                              <?php foreach($product_ids as $product_id):?>
                                                  <?php $product_datas = getProductDatasById($product_id);?>
                                                      <?php foreach($product_datas as $product_data):?>
                                                        <?php// var_dump($product_data['product_name']);?>
                                                      <?php endforeach;?>
                                              <?php endforeach;?>     
                                        
                                      <?php endforeach;?>
                            
                                          <p class="straight"><?php echo $j+1;?>.&nbsp;<strong><?php echo "{$product_data['product_name']}"?>(detail_id:<?php echo "{$product_detail['id']}";?>)</strong></p>
                                          <div class="row_box1"> 
                                          <div class="item">
                                                <div class="img_box">
                                                    <img src="/public/manage/<?php echo "{$product_data['save_path']}";?>" alt="product_image" >
                                                </div>
                                          </div>


                                          <p class="item">Price:&nbsp;￥<?php echo n("{$product_detail['price']}");?></p>
                                          <p class="item">Color:&nbsp;<?php echo "{$product_detail['color']}";?></p>
                                          <p class="item">Size:&nbsp;<?php echo "{$product_detail['size']}";?></p>
                                          <p class="item"><?php echo 'Qty:'.' '."{$order_detail['qty']}";?></p>
                                          <!--<p class="item">Total:&nbsp;¥<?php// echo n("{$s_total}");?></p>-->

                                          <div class=item>
                                            
                                            <a class="btn_b bg_orange" href="./../review.php?product_id=<?php echo $product_data['id'] ?>">Review</a>
                                          </div>

                              <?php endfor;?><!--j-->

                              
                          </div><!--row_box-->
                    </div><!--group_box->


                             <div class="row_box1">
                                <!--<p class="item"><strong>Total Qty:&nbsp;<?php// echo $sub_datas['total_qty'];?></strong></p>-->
                                <p class="item"><strong>Sub total:&nbsp;￥<?php echo n($all_order['sub_total']);?></strong></p>
                                <p class="item"><strong>Tax:&nbsp;￥<?php echo n($all_order['tax']);?></strong></p>
                                <p class="item"><strong>Shipping fee:&nbsp;￥<?php echo n($all_order['shipping_fee']);?></strong></p>
                                <p class="item"><strong>Total charge:&nbsp;￥<?php echo n($all_order['total_charge']);?></strong></p>
                            </div><!--row_box-->

                <?php endfor;?><!--i-->
        </div><!--order_box-->



                   </div><!--typein-->
                </div><!--container-->
        　　　</div><!--wrappr-->
        </label>
    </body>
</html>