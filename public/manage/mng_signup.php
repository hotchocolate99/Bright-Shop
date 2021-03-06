<?php
ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$errors =[];

ini_set('display_errors', true);
//error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

//if(!empty($_POST)){}がないと、最初からフォーム画面にエラーメッセージが表示される。
if(!empty($_POST)){

    $mgr_name = $_POST['mgr_name'];
    if(!$mgr_name || 20 < strlen($mgr_name)){
        $errors[] = 'Please type your name.';
    }

    $mgr_pass = $_POST['mgr_pass'];
    if(!preg_match("/\A[a-z\d]{8,100}+\z/i",$mgr_pass)){
        $errors['mgr_pass'] = 'Password must to be 8 to 100 alphanumeric characters.';
    }

    $mgr_pass_conf = $_POST['mgr_pass_conf'];
    if($mgr_pass !== $mgr_pass_conf){
        $errors[] = 'Passowrd confirmation does not match password.';
    }


    var_dump($_POST);
    if(count($errors) === 0){

        //require '../functions/classes.php';

        $hasCreated = createManager($_POST);
        header('Location: /manage/mng_signedup.php');

        if(!$hasCreated){
            $errors[] = 'Sign up failed.';
        }
    }

}

?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manager Sign Up</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../css/header.css">
    
    </head>

    <body>

     　<?php include './mng_header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title blue">Manager Sign Up</h1>
                        <br>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="mng_signup.php" method="post">
                            <div class="form_item">
                                <label>Name<br>
                                　　<input type="text" name="mgr_name" value="<?php if(isset($_POST['mgr_name'])){echo h($mgr_name);}?>" placeholder="Name"required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Password<br>
                                　　<input type="password" name="mgr_pass" value="<?php if(isset($_POST['mgr_pass'])){ echo h($mgr_pass);}?>" placeholder="Password" required>
                                </label>
                            </div>
                            <br>

                            <div class="form-item">
                                <label>Confirm Password<br>
                                　　<input type="password" name="mgr_pass_conf" value="<?php if(isset($_POST['mgr_pass_conf'])){echo h($mgr_pass_conf);}?>" placeholder="Password" required>
                                </label>
                        　　</div>
                        　　<br>

                        　　<input class="btn_b bg_blue" type="submit" value="Sign Up">

                        </form>
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>