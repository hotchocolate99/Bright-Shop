<?php
//----ログイン状態-----------------
session_start();

if (!$_SESSION['login']) {
    header('Location: /public/account/login.php');
    exit();
}

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

//-----------------------------------------------------------------------
//最後に$_SESSION['shopping_history']を空にする。
$_SESSION['shopping_history'] = [];

if(isset($_SESSION['shopping_cart'])){
  $total_in_cart = 0;
    foreach($_SESSION['shopping_cart'] as $detail){
        if(!empty($_SESSION['shopping_cart'])){
            $total_in_cart += $detail['detail_count'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Purchase History</title>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
      <link rel="stylesheet" href="./../../css/history.css">
      <link rel="stylesheet" href="./../css/header.css">
  </head>

  <body>

　　　　<?php include './../header.php';?>

      <label for="check">
          <div class="wrapper">
              <div class="container">
                　 <div class="typein">
                　　　 <h1 class="form_title orange">Your Purchase History</h1>
                      <div class="order_box">

                          <!--ユーザーidでorders tableからこのユーザーの全注文データを取得-->
                          <?php $all_orders = getAllOrders($user_id);?>
                          <?php foreach($all_orders as $all_order):?>
                              <?php $order_id = $all_order['id'];?>
                          <?php endforeach;?>
                                  
                          <?php for($i=0;$i<count($all_orders);$i++):?>
                              <?php $all_order = $all_orders[$i];?>
                                  <h3 class="begin"><?php echo $i+1;?>.&nbsp;<strong>Order No:&nbsp;<?php echo $all_order['id'];?>&nbsp;&nbsp;(Purchase Date:&nbsp;<?php echo "{$all_order['ordered_at']}";?>)</strong></h3>
                                  <h4 class="shipping_status"><?php echo setShippingStatus($all_order['shipping_status']);?></h4>

                                      <!--order_details table から、order_idの時のdetail_id（product_details table のid）を取得する.それと購入時の値段と数量も-->
                                      <?php $alls_order_details = getAllOrderDetailsByOrderId($all_order['id']);?>
                                          <?php $j =0;?>
                                          <?php foreach($alls_order_details as $alls_order_detail):?>
                                              <?php $price = $alls_order_detail['price'];?>
                                              <?php $qty = $alls_order_detail['qty'];?>

                                              <?php $detail_id = $alls_order_detail['detail_id'];?>
                                                  <!--次に$detail_idを使って、product_details tableから、id,product_id,色とサイズを取得する。-->
                                                  <?php $id_productId_color_sizes = get_Id_productId_color_size_BydetailId($detail_id);?>
                                                      <?php foreach($id_productId_color_sizes as $id_productId_color_size):?>
                                                          <?php $color = $id_productId_color_size['color'];?>
                                                          <?php $size = $id_productId_color_size['size'];?>
                                                          <?php $product_id = $id_productId_color_size['product_id'];?>

                                                              <!--最後に$product_idを使って、product tableから商品名と画像を取得する。-->
                                                              <?php $productName_savePaths = getProductName_savePath($product_id);?>
                                                                  <?php foreach($productName_savePaths as $productName_savePath):?>
                                                                      <?php $product_name = $productName_savePath['product_name'];?>
                                                                      <?php $save_path = $productName_savePath['save_path'];?>

                                                                          <?php $j +=1;?>
                                                                          <p><?php echo $j;?>.&nbsp;<strong><?php echo $product_name;?></strong></p>
                                                                            <div class="row_box2">
                                                                                <div class="item">
                                                                                    <div class="img_box">
                                                                                        <img src="/manage/<?php echo $save_path;?>" alt="product_image" >
                                                                                    </div>
                                                                                </div>
                                                                                <div class="item">Price:&nbsp;￥<?php echo n($price);?></div>
                                                                                <div class="item">Color:&nbsp;<?php echo $color;?></div>
                                                                                <div class="item">Size:&nbsp;<?php echo $size;?></div>
                                                                                <div class="item"><?php echo 'Qty:'.' '.$qty;?></div>
                                                                                <div class="item">
                                                                                    <a class="btn_b bg_green" href="./../review.php?product_id=<?php echo $product_id;?>">Review</a>
                                                                                </div>
                                                                            </div><!--row-box2-->

                                                                  <?php endforeach;?><!--$productName_savePaths-->
                                                        <?php endforeach;?><!--$id_productId_color_sizes-->
                                          <?php endforeach;?><!--$alls_order_details-->
                                      
                    
                                          <div class="row_box1">
                                              <div class="item">Sub Total:&nbsp;￥<?php echo n($all_order['sub_total']);?></div>
                                              <div class="item">Tax:&nbsp;￥<?php echo n($all_order['tax']);?></div>
                                              <div class="item">Shipping Fee:&nbsp;￥<?php echo n($all_order['shipping_fee']);?></div>
                                              <div class="item"><strong>Total Charge:&nbsp;￥<?php echo n($all_order['total_charge']);?></div>
                                          </div><!--row_box-->

                          <?php endfor;?><!--i-->

                      </div><!--order_box-->
                  </div><!--typein-->
              </div><!--container-->
        　</div><!--wrappr-->
      </label>
  </body>
</html>