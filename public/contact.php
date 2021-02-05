<?php
//----ログイン状態-----------------
session_start();

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

//ini_set('display_errors', true);
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../private/database.php';
require_once './../private/functions.php';

$errors =[];

if(!empty($_POST)){

    $title = $_POST['title'];
    if(!$title){
        $errors[] = '敬称を選択して下さい。';
    }

    $name = $_POST['name'];
    if(!$name || 20 < strlen($name)){
        $errors[] = '名前を入力して下さい。';
    }

    $email = $_POST['email'];
    if(!$email || !filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[] = 'メールアドレスを入力して下さい。';
    }

    $subject = $_POST['subject'];
    if(!$subject){
        $errors[] = '用件を選択して下さい。';
    }

    $content = $_POST['content'];
    if(!$content || 400 < strlen($content)){
        $errors[] = 'お問い合わせ内容は400字以下にして下さい。';
    }

    if(count($errors) === 0){

        $hasPosted = contact($title, $name, $email, $subject, $content);
        header('Location: ./../contacted.php');

        if(!$hasPosted){
            $errors[] = '送信に失敗しました';
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
        <title>Contact</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./css/form.css">
        <link rel="stylesheet" href="./css/header.css">
    </head>

    <body>

        <?php include './header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title orange">Inquiry Form</h1>
                        <br>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="contact.php" method="post">

                        　　<div class="form_item">
                                    <input class="radio" type="radio" name="title" value="Mr.">Mr.
                                    <input class="radio" type="radio" name="title" value="Ms.">Ms.
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Name<br>
                                　　<input type="text" name="name" placeholder="Name" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>E-mail<br>
                                　　<input type="text" name="email" placeholder="Email" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form_item">
                                <label>Subject<br>
                                    <select class="pref" name="subject">
                                        <option value="Products" selected>Products</option>
                                        <option value=Payment">Payment</option>
                                        <option value="Shipping">Shipping</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </label>
                            </div>
                            <br>

                            <div class="form_item">
                                <label>Message(in 400 words or less)<br>
                            　　　　　<div class="textarea"><textarea class="content" name="content" cols="80" rows="6"></textarea></div>
                            　　</label>
                                
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