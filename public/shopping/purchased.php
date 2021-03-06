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

date_default_timezone_set('Asia/Tokyo');


$errors = [];

$csrfToken = generateCsrfToken();

//セッションの名前をショッピングカートからチェックアウトに変更した。
//var_dump($_SESSION['checkout']);

//sub dataとしてカートの商品数と請求額
//var_dump($_SESSION['sub_datas']);

//var_dump($_POST['pay_ways']);

  if (empty($_POST['token']) || $_POST['token'] !== $csrfToken) {
    $errors[] = '';
  }

  // エラーがない場合は処理を行う
  if (empty($errors)) {

        $checkouts = $_SESSION['checkout'];
        //var_dump($checkouts);//中身は、$checkout['detail_id],$checkout['detail_count']

       //まず先に、在庫数の変更をする
       for($i=0;$i<count($checkouts);$i++){
            $checkout = $checkouts[$i];
            updateStock($checkout['detail_id'], $checkout['detail_count']);
       }

       //次に注文時間を出す
       $ordered_at = date("Y-m-d H:i:s");

       //購入品の総数、小計、消費税、送料、最終請求額
       $sub_datas = $_SESSION['sub_datas'];
       //$sub_datas['total_qty'],$sub_datas['sum_total_s_total'],$sub_datas['tax'],$sub_datas['shipping_fee'],$sub_datas['total_charge']

       // 注文履歴に追加
       $_SESSION['shopping_history'][] = [
            'ordered_at' => $ordered_at,
            'user' => $user_data,
            'orders' => $checkouts,
            'sub-datas' => $sub_datas,
            'pay_ways' => $_POST['pay_ways'],
       ];

       //$_SESSION['shopping_history']を使って、orders tableにデータを入れる。戻り値としてorders table　のidとordered_atを受け取る。ordered_atを使って、照合して合っていれば、order_details tableに詳細データを入れる。

       $orders_ids = putOrderDatas($user_id, $sub_datas['shipping_fee'],$sub_datas['sum_total_s_total'], $sub_datas['tax'], $sub_datas['total_charge'], $_POST['pay_ways'], $ordered_at);
           //getNewestId();
           //var_dump($ordersId_and_orderedAts);
        foreach($orders_ids as $orders_id){
             //echo $orders_id['id'];
        }

        for($i=0;$i<count($checkouts);$i++){

            $checkout = $checkouts[$i];

            $detail_datas = getAllByDetailId($checkout['detail_id']);

                foreach($detail_datas as $detail_data){
                    // echo $detail_data['product_name'];
                }

                putOrderDetails($orders_id['id'], $checkout['detail_id'], $detail_data['price'], $checkout['detail_count']);
        }

        

        //使い終わった配列を空にする。　　注意　$_SESSION['shopping_history']は次のページで使うかもしれないのでまだ残しておく。
        $_SESSION['shopping_cart'] = [];
        $_SESSION['checkout'] = [];
        $_SESSION['sub_datas'] = [];

  }//if empty($errors)


$total_in_cart = '';
foreach($_SESSION['shopping_cart'] as $detail){
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
        <title>Purchased</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/purchased.css">
        <link rel="stylesheet" href="./../css/header.css">
    </head>

    <body>
        <?php include './../header.php';?>

        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">

                        <?php if(!empty($errors)): ?>

                            <h1 class="form_title orange">Oops!</h1>
                            <br>
                            <h2>Something went wrong.<br>Your order(s) could not to be completed.<br>Please try again.</h2>
                            <ul class="error-box">
                                <?php foreach($errors as $error): ?> 
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?> 
                            </ul>

                        <?php else: ?>

                            <h1 class="form_title orange">Thank you</h1>
                            <h2 class="purchased_msg">We really appreciate your purchase.<br>Please let us hear your feedback.<br>Have a bright day!</h2>
                            <br>
                            <a class="link_a line_color_green to_history" href="./shopping_history.php">Go to your purchase history</a>
                
                         <?php endif; ?>
                    

                    </div><!--typein-->
                </div><!--container-->
            </div> <!--wrapper-->
        </label>
    </body>
</html>