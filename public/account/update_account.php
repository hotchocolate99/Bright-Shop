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

$users = findUserByUserId($user_id);
//var_dump($users);
foreach($users as $user){
//var_dump($user);
}


$errors =[];

if(isset($_POST['update'])){
    var_dump($_POST);

    //都道府県のvalueを分割して、都道府県と送料区分を出す。
    $separate = explode('-',$_POST['addrPref-shipArea']);
    $addr_pref = $separate[0];
    $ship_area = $separate[1];

    $title = $_POST['title'];
    if(!$title){
        $errors[] = 'Please choose your title.';
    }

    $usr_name = $_POST['usr_name'];
    if(!$usr_name || 20 < strlen($usr_name)){
        $errors[] = 'Please type your name.';
    }

    $usr_email = $_POST['usr_email'];
    if(!$usr_email || !filter_var($usr_email,FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Please type your E-mail address.';
    }

    //$dbh = dbconnect();
    //$usr = findUserByEmail($dbh, $usr_email);
    //if($usr){
       // $errors[] = 'このメールアドレスは使えません。';
    //}

    $usr_pass = $_POST['usr_pass'];
    if(!preg_match("/\A[a-z\d]{8,100}+\z/i",$usr_pass)){
        $errors['usr_pass'] = 'Password must to be 8 to 100 alphanumeric characters.';
    }

    $usr_pass_conf = $_POST['usr_pass_conf'];
    if($usr_pass !== $usr_pass_conf){
        $errors[] = 'Passowrd confirmation does not match password.';
    }

    //どうやってバリデーションかける？？
    $tel = $_POST['tel'];
    if(!$tel){
        $errors[] = 'Please type your phone number.';
    }

    $postal = $_POST['postal'];
    if(!$postal){
        $errors[] = 'Please type your postal code.';
    }

    //$addr_pref = $_POST['addr_pref'];
    if(!$addr_pref){
        $errors[] = 'Please choose your prefecture.';
    }

    $addr_city = $_POST['addr_city'];
    if(!$addr_city){
        $errors[] = 'Please type your city or ward.';
    }

    $addr_last = $_POST['addr_last'];
    if(!$addr_last){
        $errors[] = 'Please type the rest of your address.';
    }


    if(count($errors) === 0){

        $hasUpdated = updateUser($user_id, $_POST, $addr_pref, $ship_area);
         if(!empty($_SESSION['checkout'])){
            header('Location: ./../../shopping/purchase.php');
         }else{header('Location: /account/updated_account.php');}
        

        if(!$hasUpdated){
            $errors[] = 'Update account failed.';
        }
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
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Account Info</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../css/header.css">
    
    </head>

    <body>

    　<?php include './../header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title orange">Update Account</h1>
                        <br>
                        <?php var_dump($details) ?> 
                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="update_account.php" method="post">

                        　　<div class="form_item">
                        　      <?php if (!empty($_POST['title'])):?>
                                    <input class="radio" type="radio" name="title" value="Mr." <?php echo $_POST['title'] == 'Mr.' ? 'checked' : '' ?>>Mr.
                                    <input class="radio" type="radio" name="title" value="Ms." <?php echo $_POST['title'] == 'Ms.' ? 'checked' : '' ?>>Ms.
                                <?php else:?>
                                    <input class="radio" type="radio" name="title" value="Mr." <?php echo $user['title'] == 'Mr.' ? 'checked' : '' ?>>Mr.
                                    <input class="radio" type="radio" name="title" value="Ms." <?php echo $user['title'] == 'Ms.' ? 'checked' : '' ?>>Ms.
                                <?php endif;?>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Name<br>
                                　　<input type="text" name="usr_name" value="<?php if(!empty($_POST['usr_name'])){echo h($_POST['usr_name']);}elseif(isset($user['usr_name'])){echo h($user['usr_name']);}?>">
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>E-mail<br>
                                　　 <input type="text" name="usr_email" value="<?php if(!empty($_POST['usr_email'])){echo h($_POST['usr_email']);}elseif(isset($user['usr_email'])){echo h($user['usr_email']);}?>">
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Password<br>
                                　　<input type="password" name="usr_pass" placeholder="Password" required>
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Confirm Password<br>
                                　　<input type="password" name="usr_pass_conf" placeholder="Password" required>
                                </label>
                        　　</div>
                        　　<br>

                            <div class="form_item">
                                <label>Phone Number<br>
                                　　<input type="text" name="tel" value="<?php if(!empty($_POST['tel'])){echo h($_POST['tel']);}elseif(isset($user['tel'])){echo h($user['tel']);}?>">
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>Postal Code<br>
                                　　<input type="text" name="postal" value="<?php if(!empty($_POST['postal'])){echo h($_POST['postal']);} elseif(isset($user['postal'])){echo h($user['postal']);}?>">
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>Prefecture<br>
                                    <?php if (!empty($_POST['addrPref-shipArea'])):?>

                                        <select class="pref" name="addrPref-shipArea">
                                            <option value="北海道-1" <?php echo h($_POST['addrPref-shipArea']) == '北海道-1' ? 'selected' : '' ?>>北海道</option>

                                            <option value="青森県-2" <?php echo h($_POST['addrPref-shipArea']) == '青森県-2' ? 'selected' : '' ?>>青森県</option>
                                            <option value="岩手県-2" <?php echo h($_POST['addrPref-shipArea']) == '岩手県-2' ? 'selected' : '' ?>>岩手県</option>
                                            <option value="宮城県-2" <?php echo h($_POST['addrPref-shipArea']) == '宮城県-2' ? 'selected' : '' ?>>宮城県</option>
                                            <option value="秋田県-2" <?php echo h($_POST['addrPref-shipArea']) == '秋田県-2' ? 'selected' : '' ?>>秋田県</option>
                                            <option value="山形県-2" <?php echo h($_POST['addrPref-shipArea']) == '山形県-2' ? 'selected' : '' ?>>山形県</option>
                                            <option value="福島県-2"<?php echo h($_POST['addrPref-shipArea']) == '福島県-2' ? 'selected' : '' ?>>福島県</option>

                                            <option value="茨城県-3" <?php echo h($_POST['addrPref-shipArea']) == '茨城県-3' ? 'selected' : '' ?>>茨城県</option>
                                            <option value="栃木県-3" <?php echo h($_POST['addrPref-shipArea']) == '栃木県-3' ? 'selected' : '' ?>>栃木県</option>
                                            <option value="群馬県-3" <?php echo h($_POST['addrPref-shipArea']) == '群馬県-3' ? 'selected' : '' ?>>群馬県</option>
                                            <option value="埼玉県-3" <?php echo h($_POST['addrPref-shipArea']) == '埼玉県-3' ? 'selected' : '' ?>>埼玉県</option>
                                            <option value="千葉県-3" <?php echo h($_POST['addrPref-shipArea']) == '千葉県-3' ? 'selected' : '' ?>>千葉県</option>
                                            <option value="東京都-3" <?php echo h($_POST['addrPref-shipArea']) == '東京都-3' ? 'selected' : '' ?>>東京都</option>
                                            <option value="神奈川県-3" <?php echo h($_POST['addrPref-shipArea']) == '神奈川県-3' ? 'selected' : '' ?>>神奈川県</option>
                                            <option value="山梨県-3" <?php echo h($_POST['addrPref-shipArea']) == '山梨県-3' ? 'selected' : '' ?>>山梨県</option>

                                            <option value="新潟県-4" <?php echo h($_POST['addrPref-shipArea']) == '新潟県-4' ? 'selected' : '' ?>>新潟県</option>
                                            <option value="富山県-4" <?php echo h($_POST['addrPref-shipArea']) == '富山県-4' ? 'selected' : '' ?>>富山県</option>
                                            <option value="石川県-4" <?php echo h($_POST['addrPref-shipArea']) == '石川県-4' ? 'selected' : '' ?>>石川県</option>
                                            <option value="福井県-4" <?php echo h($_POST['addrPref-shipArea']) == '福井県-4' ? 'selected' : '' ?>>福井県</option>
                                            <option value="長野県-4" <?php echo h($_POST['addrPref-shipArea']) == '長野県-4' ? 'selected' : '' ?>>長野県</option>

                                            <option value="岐阜県-5" <?php echo h($_POST['addrPref-shipArea']) == '岐阜県-5' ? 'selected' : '' ?>>岐阜県</option>
                                            <option value="静岡県-5" <?php echo h($_POST['addrPref-shipArea']) == '静岡県-5' ? 'selected' : '' ?>>静岡県</option>
                                            <option value="愛知県-5" <?php echo h($_POST['addrPref-shipArea']) == '愛知県-5' ? 'selected' : '' ?>>愛知県</option>
                                            <option value="三重県-5" <?php echo h($_POST['addrPref-shipArea']) == '三重県-5' ? 'selected' : '' ?>>三重県</option>

                                            <option value="滋賀県-6" <?php echo h($_POST['addrPref-shipArea']) == '滋賀県-6' ? 'selected' : '' ?>>滋賀県</option>
                                            <option value="京都府-6" <?php echo h($_POST['addrPref-shipArea']) == '京都府-6' ? 'selected' : '' ?>>京都府</option>
                                            <option value="大阪府-6" <?php echo h($_POST['addrPref-shipArea']) == '大阪府-6' ? 'selected' : '' ?>>大阪府</option>
                                            <option value="兵庫県-6" <?php echo h($_POST['addrPref-shipArea']) == '兵庫県-6' ? 'selected' : '' ?>>兵庫県</option>
                                            <option value="奈良県-6" <?php echo h($_POST['addrPref-shipArea']) == '奈良県-6' ? 'selected' : '' ?>>奈良県</option>
                                            <option value="和歌山県-6" <?php echo h($_POST['addrPref-shipArea']) == '和歌山県-6' ? 'selected' : '' ?>>和歌山県</option>

                                            <option value="鳥取県-7" <?php echo h($_POST['addrPref-shipArea']) == '鳥取県-7' ? 'selected' : '' ?>>鳥取県</option>
                                            <option value="島根県-7" <?php echo h($_POST['addrPref-shipArea']) == '島根県-7' ? 'selected' : '' ?>>島根県</option>
                                            <option value="岡山県-7" <?php echo h($_POST['addrPref-shipArea']) == '岡山県-7' ? 'selected' : '' ?>>岡山県</option>
                                            <option value="広島県-7" <?php echo h($_POST['addrPref-shipArea']) == '広島県-7' ? 'selected' : '' ?>>広島県</option>
                                            <option value="山口県-7" <?php echo h($_POST['addrPref-shipArea']) == '山口県-7' ? 'selected' : '' ?>>山口県</option>

                                            <option value="徳島県-8" <?php echo h($_POST['addrPref-shipArea']) == '徳島県-8' ? 'selected' : '' ?>>徳島県</option>
                                            <option value="香川県-8" <?php echo h($_POST['addrPref-shipArea']) == '香川県-8' ? 'selected' : '' ?>>香川県</option>
                                            <option value="愛媛県-8" <?php echo h($_POST['addrPref-shipArea']) == '愛媛県-8' ? 'selected' : '' ?>>愛媛県</option>
                                            <option value="高知県-8" <?php echo h($_POST['addrPref-shipArea']) == '高知県-8' ? 'selected' : '' ?>>高知県</option>

                                            <option value="福岡県-9" <?php echo h($_POST['addrPref-shipArea']) == '福岡県-9' ? 'selected' : '' ?>>福岡県</option>
                                            <option value="佐賀県-9" <?php echo h($_POST['addrPref-shipArea']) == '佐賀県-9' ? 'selected' : '' ?>>佐賀県</option>
                                            <option value="長崎県-9" <?php echo h($_POST['addrPref-shipArea']) == '長崎県-9' ? 'selected' : '' ?>>長崎県</option>
                                            <option value="熊本県-9" <?php echo h($_POST['addrPref-shipArea']) == '熊本県-9' ? 'selected' : '' ?>>熊本県</option>
                                            <option value="大分県-9" <?php echo h($_POST['addrPref-shipArea']) == '大分県-9' ? 'selected' : '' ?>>大分県</option>
                                            <option value="宮崎県-9" <?php echo h($_POST['addrPref-shipArea']) == '宮崎県-9' ? 'selected' : '' ?>>宮崎県</option>
                                            <option value="鹿児島県-9" <?php echo h($_POST['addrPref-shipArea']) == '鹿児島県-9' ? 'selected' : '' ?>>鹿児島県</option>

                                            <option value="沖縄県-10" <?php echo h($_POST['addrPref-shipArea']) == '沖縄県-10' ? 'selected' : '' ?>>沖縄県</option>

                                            <option value="その他-11" <?php echo h($_POST['addrPref-shipArea']) == 'その他-11' ? 'selected' : '' ?>>その他</option>
                                        </select>

                                    <?php else:?>
                                        <select class="pref" name="addrPref-shipArea">
                                            
                                            <option value="北海道-1" <?php echo $user['addr_pref'].'-1' == '北海道-1' ? 'selected' : '' ?>>北海道</option>
                                            
                                            <option value="青森県-2" <?php echo $user['addr_pref'].'-2' == '青森県-2' ? 'selected' : '' ?>>青森県</option>
                                            <option value="岩手県-2" <?php echo $user['addr_pref'].'-2' == '岩手県-2' ? 'selected' : '' ?>>岩手県</option>
                                            <option value="宮城県-2" <?php echo $user['addr_pref'].'-2' == '宮城県-2' ? 'selected' : '' ?>>宮城県</option>
                                            <option value="秋田県-2" <?php echo $user['addr_pref'].'-2' == '秋田県-2' ? 'selected' : '' ?>>秋田県</option>
                                            <option value="山形県-2" <?php echo $user['addr_pref'].'-2' == '山形県-2' ? 'selected' : '' ?>>山形県</option>
                                            <option value="福島県-2" <?php echo $user['addr_pref'].'-2' == '福島県-2' ? 'selected' : '' ?>>福島県</option>

                                            <option value="茨城県-3" <?php echo $user['addr_pref'].'-3' == '茨城県-3' ? 'selected' : '' ?>>茨城県</option>
                                            <option value="栃木県-3" <?php echo $user['addr_pref'].'-3' == '栃木県-3' ? 'selected' : '' ?>>栃木県</option>
                                            <option value="群馬県-3" <?php echo $user['addr_pref'].'-3' == '群馬県-3' ? 'selected' : '' ?>>群馬県</option>
                                            <option value="埼玉県-3" <?php echo $user['addr_pref'].'-3' == '埼玉県-3' ? 'selected' : '' ?>>埼玉県</option>
                                            <option value="千葉県-3" <?php echo $user['addr_pref'].'-3' == '千葉県-3' ? 'selected' : '' ?>>千葉県</option>
                                            <option value="東京都-3" <?php echo $user['addr_pref'].'-3' == '東京都-3' ? 'selected' : '' ?>>東京都</option>
                                            <option value="神奈川県-3" <?php echo $user['addr_pref'].'-3' == '神奈川県-3' ? 'selected' : '' ?>>神奈川県</option>
                                            <option value="山梨県-3" <?php echo $user['addr_pref'].'-3' == '山梨県-3' ? 'selected' : '' ?>>山梨県</option>

                                            <option value="新潟県-4" <?php echo $user['addr_pref'].'-4' == '新潟県-4' ? 'selected' : '' ?>>新潟県</option>
                                            <option value="富山県-4" <?php echo $user['addr_pref'].'-4' == '富山県-4' ? 'selected' : '' ?>>富山県</option>
                                            <option value="石川県-4" <?php echo $user['addr_pref'].'-4' == '石川県-4' ? 'selected' : '' ?>>石川県</option>
                                            <option value="福井県-4" <?php echo $user['addr_pref'].'-4' == '福井県-4' ? 'selected' : '' ?>>福井県</option>
                                            <option value="長野県-4"<?php echo $user['addr_pref'].'-4' == '長野県-4' ? 'selected' : '' ?>>長野県</option>

                                            <option value="岐阜県-5" <?php echo $user['addr_pref'].'-5' == '岐阜県-5' ? 'selected' : '' ?>>岐阜県</option>
                                            <option value="静岡県-5" <?php echo $user['addr_pref'].'-5' == '静岡県-5' ? 'selected' : '' ?>>静岡県</option>
                                            <option value="愛知県-5" <?php echo $user['addr_pref'].'-5' == '愛知県-5' ? 'selected' : '' ?>>愛知県</option>
                                            <option value="三重県-5" <?php echo $user['addr_pref'].'-5' == '三重県-5' ? 'selected' : '' ?>>三重県</option>

                                            <option value="滋賀県-6" <?php echo $user['addr_pref'].'-6' == '滋賀県-6' ? 'selected' : '' ?>>滋賀県</option>
                                            <option value="京都府-6" <?php echo $user['addr_pref'].'-6' == '京都府-6' ? 'selected' : '' ?>>京都府</option>
                                            <option value="大阪府-6" <?php echo $user['addr_pref'].'-6' == '大阪府-6' ? 'selected' : '' ?>>大阪府</option>
                                            <option value="兵庫県-6" <?php echo $user['addr_pref'].'-6' == '兵庫県-6' ? 'selected' : '' ?>>兵庫県</option>
                                            <option value="奈良県-6" <?php echo $user['addr_pref'].'-6' == '奈良県-6' ? 'selected' : '' ?>>奈良県</option>
                                            <option value="和歌山県-6" <?php echo $user['addr_pref'].'-6' == '和歌山県-6' ? 'selected' : '' ?>>和歌山県</option>

                                            <option value="鳥取県-7" <?php echo $user['addr_pref'].'-7' == '鳥取県-7' ? 'selected' : '' ?>>鳥取県</option>
                                            <option value="島根県-7" <?php echo $user['addr_pref'].'-7' == '島根県-7' ? 'selected' : '' ?>>島根県</option>
                                            <option value="岡山県-7" <?php echo $user['addr_pref'].'-7' == '岡山県-7' ? 'selected' : '' ?>>岡山県</option>
                                            <option value="広島県-7" <?php echo $user['addr_pref'].'-7' == '広島県-7' ? 'selected' : '' ?>>広島県</option>
                                            <option value="山口県-7" <?php echo $user['addr_pref'].'-7' == '山口県-7' ? 'selected' : '' ?>>山口県</option>

                                            <option value="徳島県-8" <?php echo $user['addr_pref'].'-8' == '徳島県-8' ? 'selected' : '' ?>>徳島県</option>
                                            <option value="香川県-8" <?php echo $user['addr_pref'].'-8' == '香川県-8' ? 'selected' : '' ?>>香川県</option>
                                            <option value="愛媛県-8" <?php echo $user['addr_pref'].'-8' == '愛媛県-8' ? 'selected' : '' ?>>愛媛県</option>
                                            <option value="高知県-8" <?php echo $user['addr_pref'].'-8' == '高知県-8' ? 'selected' : '' ?>>高知県</option>

                                            <option value="福岡県-9" <?php echo $user['addr_pref'].'-9' == '福岡県-9' ? 'selected' : '' ?>>福岡県</option>
                                            <option value="佐賀県-9" <?php echo $user['addr_pref'].'-9' == '佐賀県-9' ? 'selected' : '' ?>>佐賀県</option>
                                            <option value="長崎県-9" <?php echo $user['addr_pref'].'-9' == '長崎県-9' ? 'selected' : '' ?>>長崎県</option>
                                            <option value="熊本県-9" <?php echo $user['addr_pref'].'-9' == '熊本県-9' ? 'selected' : '' ?>>熊本県</option>
                                            <option value="大分県-9" <?php echo $user['addr_pref'].'-9' == '大分県-9' ? 'selected' : '' ?>>大分県</option>
                                            <option value="宮崎県-9" <?php echo $user['addr_pref'].'-9' == '宮崎県-9' ? 'selected' : '' ?>>宮崎県</option>
                                            <option value="鹿児島県-9" <?php echo $user['addr_pref'].'-9' == '鹿児島県-9' ? 'selected' : '' ?>>鹿児島県</option>

                                            <option value="沖縄県-10" <?php echo $user['addr_pref'].'-10' == '沖縄県-10' ? 'selected' : '' ?>>沖縄県</option>

                                            <option value="その他-11"<?php echo $user['addr_pref'].'-11' == 'その他-11' ? 'selected' : '' ?>>その他</option>
                                        </select>

                                    <?php endif;?>
                                </label>
                            </div>
                            <br>


                            <div class="form_item">
                                <label>City<br>
                                　　<input type="text" name="addr_city" value="<?php if (!empty($_POST['addr_city'])){echo h($_POST['addr_city']);} elseif(isset($user['addr_city'])){echo h($user['addr_city']);}?>">
                                </label>
                        　　</div>
                            <br>

                            
                            <div class="form_item">
                                <label>After City<br>
                                　　 <input type="text" name="addr_last" value="<?php if(!empty($_POST['addr_last'])){echo h($user['addr_last']);}elseif(isset($user['addr_last'])){echo h($user['addr_last']);}?>">
                                </label>
                        　　</div>
                            <br>
                        
                            <div class="form_item">
                                <?php if (!empty($_POST['ad_request'])):?>
                                    <input class="radio" type="checkbox" name="ad_request" value= "1" <?php echo $_POST['ad_request'] == '1' ? 'checked' : '' ?>>I request to receive news letters.
                                <?php else:?>
                                    <input class="radio" type="checkbox" name="ad_request" value= "1" <?php echo $user['ad_request'] == '1' ? 'checked' : '' ?>>I request to receive news letters.
                                <?php endif;?>
                            </div>
                            <br>

                        　　<input class="btn bg_green" type="submit" name="update" value="Update">

                        </form>
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>