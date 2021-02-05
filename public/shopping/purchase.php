<?php
session_start();
if (!$_SESSION['login']) {
  header('Location: /public/account/login.php');
  exit();
}

if ($_SESSION['login']= true) {
  $users = $_SESSION['user'];
}

foreach($users as $user){
  //var_dump($user['id']);
}
$user_id = $user['id'];
//--------------------------------

//against click junction
header('X-FRAME-OPTION:DENY');

//ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$csrfToken = generateCsrfToken();

//------------------------------------------------------------------------
//セッションの名前をショッピングカートからチェックアウトに変更
$_SESSION['checkout']=[];
$_SESSION['checkout'] = $_SESSION['shopping_cart'];
$checkouts = $_SESSION['checkout'];
//var_dump(count($checkouts));//商品の種類の数

//------------------------------------------------------------------------
//sub datasとして購入品の総数、小計、消費税、送料、最終請求額
//var_dump($_SESSION['sub_datas']);//["total_qty"]=> int(3) ["sum_total_s_total"]=> int(1500) ["tax"]=> float(150) ["shipping_fee"]=> int(800) ["total_charge"]=> string(7) "¥2,450"
$sub_datas = $_SESSION['sub_datas'];

//------------------------------------------------------------------------
//商品の在庫数（detail_idとstock)
//var_dump($_SESSION['stock_count']);


$total_in_cart = '';
foreach($_SESSION['checkout'] as $detail){
  if(!empty($details)){
    $total_in_cart += $detail['detail_count'];
  }
}

?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/purchase.css">
        <link rel="stylesheet" href="./../../css/header.css">
    
    </head>

    <body>

     　<?php include './../header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title orange">Purchase</h1>
                        <br>
                        <div class="form_item">
                            <dt><h2>How would you like to pay?</h2></dt>
                                <dd><input class="radio" type="radio" id="paymentoption" form="pay_ways" name="pay_ways" value="1" checked>Credit Card
                                    <input class="radio right" type="radio" id="paymentoption" form="pay_ways" name="pay_ways" value="2">PayPal</dd>
                        </div>
                        <br>
                        <br>

                        <h2>Delivery Information</h2>

                        <div class="form_item">
                            <dt>Nmae:</dt>
                            <dd><?php echo $user['title'].' '.$user['usr_name'];?></dd>
                        </div>

                        <div class="form_item">
                            <dt>E-mail:</dt>
                            <dd><?php echo $user['usr_email'];?></dd>
                        </div>

                        <div class="form_item">
                            <dt>Phone Number:</dt>
                            <dd><?php echo $user['tel'];?></dd>
                        </div>
                        <br>

                        <div class="form_item">
                            <dt>Postal Code:</dt>
                            <dd><?php echo $user['postal'];?></dd>
                        </div>

                        <div class="form_item">
                            <dt>Address:</dt>
                            <dd><?php echo $user['addr_pref'].' '.$user['addr_city'].' '.$user['addr_last'];?></dd>
                        </div>
                        <br>
                        <br>
                        <br>

                        <h2>Your Order</h2>
                  　　　　<div>
                              <?php $errors = [];?>
                                  <?php for($i=0;$i<count($checkouts);$i++):?>
                                      <?php $checkout = $checkouts[$i];?>
                                      <?php $detail_datas = getAllByDetailId($checkout['detail_id']);?>

                                          <?php foreach($detail_datas as $detail_data):?>
                                              <?php// echo $detail_data['product_name'];?>
                                          <?php endforeach;?>

                                          <p><?php echo $i+1;?>.&nbsp;<strong><?php echo "{$detail_data['product_name']}"?>&nbsp&nbsp<?php $detail_data['stock'];?></strong></p>

                                          <div class="group_box">
                                              <div class="row_box1">
                                                  <div class="item">
                                                      <div class="img_box">
                                                          <img src="/manage/<?php echo "{$detail_data['save_path']}";?>" alt="product_image" >
                                                      </div>
                                                  </div>

                                                  <div class="item">Price:&nbsp;￥<?php echo n("{$detail_data['price']}");?></div>
                                                  <div class="item">Color:&nbsp;<?php echo "{$detail_data['color']}";?></div>
                                                  <div class="item">Size:&nbsp;<?php echo "{$detail_data['size']}";?></div>
                                                  <div class="item"><?php echo 'Qty:'.' '."{$checkout['detail_count']}";?></div>
                                              
                                              </div><!--row_box1-->
                                          </div><!--group_box-->
                                         

                                          <!--もし、在庫数より注文数の方が多かったらエラーメッセージを$error配列に入れる。-->
                                          <?php if($detail_data['stock'] < $checkout['detail_count']):?>
                                              <?php $errors[] = 'Stock shortage of'.' '.$detail_data['product_name'].'('.$checkout['detail_id'].').';?>
                                          <?php endif ;?>

                                  <?php endfor;?><!--$i-->

                                 
                                  <div class="row_box2">
                                      <div class="item">Total Qty:&nbsp;<?php echo $sub_datas['total_qty'];?></div>
                                      <div class="item">Sub Total:&nbsp;￥<?php echo n($sub_datas['sum_total_s_total']);?></div>
                                      <div class="item">Tax:&nbsp;￥<?php echo n($sub_datas['tax']);?></div>
                                      <div class="item">Shipping Fee:&nbsp;￥<?php echo n($sub_datas['shipping_fee']);?></div>
                                      <div class="item"><strong>Total Charge:&nbsp;￥<?php echo n($sub_datas['total_charge']);?></strong></div>
                                  </div>

　　　　　　　　　　　　　　　　　　　　     <!--もしエラーメッセージがあったら表示させる。そしてオーダーをキャンセルする-->
                                      <?php if(!empty($errors)):?>

                                          <?php foreach($errors as $error):?>
                                              <p class="error-box"><strong><?php echo $error;?></strong></p>
                                          <?php endforeach;?>

                                          <p class="error-box"><strong>Please cancel your order and go back to the shopping cart.</strong></p>
                                                  
                                          <div class="row_box2">
                                              <div class="item3"><a class="btn_b bg_orange" href="./../account/update_account.php">Change your info</a></div>
                                              <div class="item3"><a class="btn_b bg_gray" href="./shopping_cart.php">Go back to the cart</a></div>
                                              <div class="item3">
                                                   <form action="./purchase.php" method="post" id="pay_ways">
                                                        <input class="btn_b bg_green" type="submit" value="Finalize your order">
                                                        <input type="hidden" name="token" value="<?php echo $csrfToken;?>">
                                                   </form>
                                              </div>
                                          </div><!--row_box2-->

                                                
                                      <!--もしエラーメッセージがなかったら、注文を決定する。purchased.phpで在庫数を更新する。-->
                                      <?php else:?>

                                          <div class="link_box">
                                              <a class="btn_b bg_orange item spase" href="./../account/update_account.php">Change your info</a>
                                              <a class="btn_b bg_gray item" href="./shopping_cart.php">Go back to the cart</a>
                                              <form class="item" action="./purchased.php" method="post" id="pay_ways">
                                                  <input class="btn bg_green" type="submit" value="Finalize your order">
                                                  <input type="hidden" name="token" value="<?php echo $csrfToken;?>">
                                              </form>
                                          </div><!--link_box-->

                                      <?php endif;?>

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>