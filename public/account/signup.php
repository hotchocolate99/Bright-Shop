<?php
ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$errors =[];

ini_set('display_errors', true);

if(!empty($_POST)){
    //var_dump($_POST);

    //都道府県のvalueを分割して、都道府県と送料区分を出す。
    $separate = explode('-',$_POST['addrPref-shipArea']);
    $addr_pref = $separate[0];
    $ship_area = $separate[1];

    $title = $_POST['title'];
    if(!$title){
        $errors[] = '敬称を選択して下さい。';
    }

    $usr_name = $_POST['usr_name'];
    if(!$usr_name || 20 < strlen($usr_name)){
        $errors[] = '名前を入力して下さい。';
    }

    $usr_email = $_POST['usr_email'];
    if(!$usr_email || !filter_var($usr_email,FILTER_VALIDATE_EMAIL)){
        $errors[] = 'メールアドレスを入力して下さい。';
    }

    $dbh = dbconnect();
    $usr = findUserByEmail($dbh, $usr_email);
    if($usr){
        $errors[] = 'このメールアドレスは使えません。';
    }

    $usr_pass = $_POST['usr_pass'];
    if(!preg_match("/\A[a-z\d]{8,100}+\z/i",$usr_pass)){
        $errors['usr_pass'] = 'パスワードは英数字８文字以上１００文字以下にしてください。';
    }

    $usr_pass_conf = $_POST['usr_pass_conf'];
    if($usr_pass !== $usr_pass_conf){
        $errors[] = '確認用パスワードが間違っています。';
    }

    //どうやってバリデーションかける？？
    $tel = $_POST['tel'];
    if(!$tel){
        $errors[] = '電話番号を入力して下さい。';
    }

    $postal = $_POST['postal'];
    if(!$postal){
        $errors[] = '郵便番号を入力して下さい。';
    }

    //$addr_pref = $_POST['addr_pref'];
    if(!$addr_pref){
        $errors[] = '都道府県を選択して下さい。';
    }

    $addr_city = $_POST['addr_city'];
    if(!$addr_city){
        $errors[] = '市群区(島)を入力して下さい。';
    }

    $addr_last = $_POST['addr_last'];
    if(!$addr_last){
        $errors[] = 'それ以降の住所を入力して下さい。';
    }


    if(count($errors) === 0){

        $hasCreated = createUser($_POST, $addr_pref, $ship_area);
        header('Location: ./login.php');

        if(!$hasCreated){
            $errors[] = '登録に失敗しました';
        }
    }

}

    
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../../public/css/form.css">
        <link rel="stylesheet" href="./../../public/css/header.css">
    
    </head>

    <body>

    　<?php include './../../public/header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title orange">Create Account</h1>
                        <br>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="signup.php" method="post">

                        　　<div class="form_item">
                                    <input class="radio" type="radio" name="title" value="Mr.">Mr.
                                    <input class="radio" type="radio" name="title" value="Ms.">Ms.
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Name<br>
                                　　<input type="text" name="usr_name" value="<?php if(isset($_POST['usr_name'])){echo h($mgr_name);}?>" placeholder="Name"required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>Email<br>
                                　　<input type="text" name="usr_email" value="<?php if(isset($_POST['usr_email'])){echo h($usr_email);}?>" placeholder="Email"required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Password<br>
                                　　<input type="password" name="usr_pass" value="<?php if(isset($_POST['usr_pass'])){ echo h($usr_pass);}?>" placeholder="Password" required>
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Confirm Password<br>
                                　　<input type="password" name="usr_pass_conf" value="<?php if(isset($_POST['usr_pass_conf'])){echo h($usr_pass_conf);}?>" placeholder="Password" required>
                                </label>
                        　　</div>
                        　　<br>

                            <div class="form_item">
                                <label>Phone Number<br>
                                　　<input type="text" name="tel" value="<?php if(isset($_POST['tel'])){echo h($tel);}?>" placeholder="Phone Number"required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>Postal Code<br>
                                　　<input type="text" name="postal" value="<?php if(isset($_POST['postal'])){echo h($postal);}?>" placeholder="Postal Code"required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>Prefecture<br>
                                    <select name="addrPref-shipArea">
                                        <option value=Hokkaido-1>北海道</option>

                                        <option value=Aomori-2>青森県</option>
                                        <option value=Iwate-2>岩手県</option>
                                        <option value=Miyagi-2>宮城県</option>
                                        <option value=Akita-2>秋田県</option>
                                        <option value=Yamagata-2>山形県</option>
                                        <option value=Fukushima-2>福島県</option>

                                        <option value=Ibaragi-3>茨城県</option>
                                        <option value=Tochigi-3>栃木県</option>
                                        <option value=Gunma-3>群馬県</option>
                                        <option value=Saitama-3>埼玉県</option>
                                        <option value=Chiba-3>千葉県</option>
                                        <option value=Tokyo-3>東京都</option>
                                        <option value=Kanagawa-3>神奈川県</option>

                                        <option value=Niigata-4>新潟県</option>
                                        <option value=Toyama-4>富山県</option>
                                        <option value=Ishikawa-4>石川県</option>
                                        <option value=Fukui-4>福井県</option>
                                        <option value=Yamanashi-4>山梨県</option>
                                        <option value=Nagano-4>長野県</option>
                                        <option value=Gifu-4>岐阜県</option>
                                        <option value=Shizuoka-4>静岡県</option>
                                        <option value=Aichi-4>愛知県</option>

                                        <option value=Mie-5>三重県</option>
                                        <option value=Shiga-5>滋賀県</option>
                                        <option value=Kyoto-5>京都府</option>
                                        <option value=Osaka-5>大阪府</option>
                                        <option value=Hyogo-5>兵庫県</option>
                                        <option value=Nara-5>奈良県</option>
                                        <option value=Wakayama-5>和歌山県</option>

                                        <option value=Tottori-6>鳥取県</option>
                                        <option value=Shimane-6>島根県</option>
                                        <option value=Okayama-6>岡山県</option>
                                        <option value=Hiroshima-6>広島県</option>
                                        <option value=Yamaguchi-6>山口県</option>

                                        <option value=Tokushima-7>徳島県</option>
                                        <option value=Kagawa-7>香川県</option>
                                        <option value=Ehime-7>愛媛県</option>
                                        <option value=Kochi-7>高知県</option>

                                        <option value=Fukuoka-8>福岡県</option>
                                        <option value=Saga-8>佐賀県</option>
                                        <option value=Nagasaki-8>長崎県</option>
                                        <option value=Kumamoto-8>熊本県</option>
                                        <option value=Oita-8>大分県</option>
                                        <option value=Miyazaki-8>宮崎県</option>
                                        <option value=Kagoshima-8>鹿児島県</option>

                                        <option value=Okinawa-9>沖縄県</option>

                                        <option value=Other-10>その他</option>


                                    </select>
                                </label>
                            </div>
                            <br>


                            <div class="form_item">
                                <label>City<br>
                                　　<input type="text" name="addr_city" value="<?php if(isset($_POST['addr_city'])){echo h($addr_city);}?>" placeholder="City"required>
                                </label>
                        　　</div>
                            <br>

                            
                            <div class="form_item">
                                <label>After City<br>
                                　　<input type="text" name="addr_last" value="<?php if(isset($_POST['addr_last'])){echo h($addr_last);}?>" placeholder="After City"required>
                                </label>
                        　　</div>
                            <br>
                        
                            <div class="form_item">
                                    <input class="radio" type="radio" name="ad_request" value="1">I request to receive news letters.
                            </div>
                            <br>

                        　　<input class="btn bg_green" type="submit" value="Confirm">

                        </form>
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>