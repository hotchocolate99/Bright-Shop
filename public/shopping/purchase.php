<?php
session_start();
if (!$_SESSION['login']) {
    header('Location: /public/account/login.php');
    exit();
  }

  if ($_SESSION['login']= true) {
    $user = $_SESSION['user'];
  }
  //var_dump($user);

  foreach($user as $user_data){
      //echo $user_data['tel'];
  }

//--------------------------------
ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';


//against click junction ボタンがある時はこれいる？？いらない？？
header('X-FRAME-OPTION:DENY');

$csrfToken = generateCsrfToken();
//var_dump($csrfToken);

//session_destroy();

//セッションの名前をショッピングカートからチェックアウトに変更
//var_dump($_SESSION['shopping_cart']);
$_SESSION['checkout']=[];
$_SESSION['checkout'] = $_SESSION['shopping_cart'];
$checkouts = $_SESSION['checkout'];
//var_dump($checkouts);
//var_dump(count($checkouts));//商品の種類の数



//sub dataとしてカートの商品数と請求額
var_dump($_SESSION['sub_datas']);//["total_qty"]=> int(3) ["sum_total_s_total"]=> int(1500) ["tax"]=> float(150) ["shipping_fee"]=> int(800) ["total_charge"]=> string(7) "¥2,450"
$sub_datas = $_SESSION['sub_datas'];
var_dump($sub_datas['tax']);

//商品の在庫数（detail_idとstock)
//var_dump($_SESSION['stock_count']);


$total_in_cart = 0;
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
        <link rel="stylesheet" href="./../../public/css/purchase.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
    
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

                            <h2>Deliverly infomation</h2>

                            <div class="form_item">
                            <dt>Nmae:</dt>
                            <dd><?php echo $user_data['title'].' '.$user_data['usr_name'];?></dd>
                            </div>

                            <div class="form_item">
                            <dt>E-mail:</dt>
                            <dd><?php echo $user_data['usr_email'];?></dd>
                            </div>

                            <div class="form_item">
                            <dt>Phone number:</dt>
                            <dd><?php echo $user_data['tel'];?></dd>
                            </div>
                            <br>

                            <div class="form_item">
                            <dt>Postal Code:</dt>
                            <dd><?php echo $user_data['postal'];?></dd>
                            </div>

                            <div class="form_item">
                            <dt>Address:</dt>
                            <dd><?php echo $user_data['addr_pref'].' '.$user_data['addr_city'].' '.$user_data['addr_last'];?></dd>
                            </div>
                            <br>
                            <br>



　　　　　　　　　　　　　　　　　<!--連想配列の要素の数をcountする時は、第二引数に　COUNT_RECURSIVE　を入れること！  勘違い。第二引数を入れると要素全ての数になるので、入れなくて良い。-->
                            <h2>Your order(s)</h2>
                  　　　　　　<div>

                                   
                              <?php $errors = [];?>
                                   <?php for($i=0;$i<count($checkouts);$i++):?>
                                       <?php $checkout = $checkouts[$i];?>
                                       <?php //var_dump($checkout['detail_id']);?>
                                       <?php $detail_datas = getAllByDetailId($checkout['detail_id']);?>
                                       <?php //var_dump($detail_datas);?>
                                            <?php foreach($detail_datas as $detail_data):?>
                                               <?php// echo $detail_data['product_name'];?>
                                            <?php endforeach;?>

                                          <p><?php echo $i+1;?>.&nbsp;<strong><?php echo "{$detail_data['product_name']}"?>(stock:<?php echo $detail_data['stock'];?>)(detail_id:<?php echo "{$checkout['detail_id']}";?>)</strong></p>

                                          <div class="group_box">
                                                  <div class="row_box1">
                                                       <div class="item">
                                                          <div class="img_box">
                                                            <img src="/public/manage/<?php echo "{$detail_data['save_path']}";?>" alt="product_image" >
                                                          </div>
                                                       </div>

                                                        <p class="item">Price:&nbsp;￥<?php echo n("{$detail_data['price']}");?></p>
                                                        <p class="item">Color:&nbsp;<?php echo "{$detail_data['color']}";?></p>
                                                        <p class="item">Size:&nbsp;<?php echo "{$detail_data['size']}";?></p>
                                                        <p class="item"><?php echo 'Qty:'.' '."{$checkout['detail_count']}";?></p>
                                                  </div>
                                          </div><!--group_box-->
                                          <br>

                                      <!--もし、在庫数より注文数の方が多かったらエラーメッセージを$error配列に入れる。-->
                                          <?php if($detail_data['stock'] < $checkout['detail_count']):?>
                                            <?php $errors[] = 'Stock shortage of'.' '.$detail_data['product_name'].'('.$checkout['detail_id'].').';?>
                                          <?php endif ;?>

                                      <?php endfor;?>


                                                  <br>
                                                  <div class="row_box1">
                                                        <p class="item"><strong>Total Qty:&nbsp;<?php echo $sub_datas['total_qty'];?></strong></p>
                                                        <p class="item"><strong>Sub total:&nbsp;￥<?php echo n($sub_datas['sum_total_s_total']);?></strong></p>
                                                        <p class="item"><strong>Tax:&nbsp;￥<?php echo n($sub_datas['tax']);?></strong></p>
                                                        <p class="item"><strong>Shipping fee:&nbsp;￥<?php echo n($sub_datas['shipping_fee']);?></strong></p>
                                                        <p class="item"><strong>Total charge:&nbsp;￥<?php echo n($sub_datas['total_charge']);?></strong></p>
                                                  </div>

　　　　　　　　　　　　　　　　　　　　　　　　　　<!--もしエラーメッセージがあったら表示させる。そしてオーダーをキャンセルする-->
                                               <?php if(!empty($errors)):?>
                                                  <?php foreach($errors as $error):?>
                                                       <p class="error-box"><strong><?php echo $error;?></strong></p>
                                                  <?php endforeach;?>
                                                  <p class="error-box"><strong>Please cancel your order and go back to the shopping cart.</strong></p>
                                                  <div class="row_box2">
                                                     <a class="btn_b bg_orange item spase" href="./../account/update_account.php">Change your info</a>
                                                     <a class="btn_b bg_gray item" href="./shopping_cart.php">Cancel all orders</a>
                                                     <form class="item" action="./purchase.php" method="post" id="pay_ways">
                                        　　             <input class="btn bg_green" type="submit" value="Finalize your orders">
                                                        <input type="hidden" name="token" value="<?php echo $csrfToken;?>">
                                                     </form>
                                                     </div>

                                                <!--もしエラーメッセージがなかったら、注文を決定する。purchased.phpで在庫数を更新する。-->
                                                <?php else:?>
                                                  <div class="row_box2">
                                                     <a class="btn_b bg_orange item spase" href="./../account/update_account.php">Change your info</a>
                                                     <a class="btn_b bg_gray item" href="./shopping_cart.php">Cancel all order(s)</a>
                                                     <form class="item" action="./purchased.php" method="post" id="pay_ways">
                                          　　           <input class="btn bg_green" type="submit" value="Finalize your order(s)">
                                                        <input type="hidden" name="token" value="<?php echo $csrfToken;?>">
                                                    </form>
                                                  </div>
                                                <?php endif;?>
                                                

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>