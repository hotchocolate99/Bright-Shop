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
                                <label>E-mail<br>
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
                                        <option value="北海道-1">北海道</option>

                                        <option value=青森県-2">青森県</option>
                                        <option value="岩手県-2">岩手県</option>
                                        <option value="宮城県-2">宮城県</option>
                                        <option value="秋田県-2">秋田県</option>
                                        <option value="山形県-2">山形県</option>
                                        <option value="福島県-2">福島県</option>

                                        <option value="茨城県-3">茨城県</option>
                                        <option value="栃木県-3">栃木県</option>
                                        <option value="群馬県-3">群馬県</option>
                                        <option value="埼玉県-3">埼玉県</option>
                                        <option value="千葉県-3">千葉県</option>
                                        <option value="東京-3">東京都</option>
                                        <option value="神奈川県-3">神奈川県</option>
                                        <option value="山梨県-3">山梨県</option>

                                        <option value="新潟県-4">新潟県</option>
                                        <option value="富山県-4">富山県</option>
                                        <option value="石川県-4">石川県</option>
                                        <option value="福井県-4">福井県</option>
                                        <option value="長野県-4">長野県</option>

                                        <option value="岐阜県-5">岐阜県</option>
                                        <option value="静岡県-5">静岡県</option>
                                        <option value="愛知県-5">愛知県</option>
                                        <option value="三重県-5">三重県</option>

                                        <option value="滋賀県-6">滋賀県</option>
                                        <option value="京都府-6">京都府</option>
                                        <option value="大阪府-6">大阪府</option>
                                        <option value="兵庫県-6">兵庫県</option>
                                        <option value="奈良県-6">奈良県</option>
                                        <option value="和歌山県-6">和歌山県</option>

                                        <option value="鳥取県-7">鳥取県</option>
                                        <option value="島根県-7">島根県</option>
                                        <option value="岡山県-7">岡山県</option>
                                        <option value="広島県-7">広島県</option>
                                        <option value="山口県-7">山口県</option>

                                        <option value="徳島県-8">徳島県</option>
                                        <option value="香川県-8">香川県</option>
                                        <option value="愛媛県-8">愛媛県</option>
                                        <option value="高知県-8">高知県</option>

                                        <option value="福岡県-9">福岡県</option>
                                        <option value="佐賀県-9">佐賀県</option>
                                        <option value="長崎県-9">長崎県</option>
                                        <option value="熊本県-9">熊本県</option>
                                        <option value="大分県-9">大分県</option>
                                        <option value="宮崎県-9">宮崎県</option>
                                        <option value="鹿児島県-9">鹿児島県</option>

                                        <option value="沖縄県-10">沖縄県</option>

                                        <option value="その他-11>その他</option>


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