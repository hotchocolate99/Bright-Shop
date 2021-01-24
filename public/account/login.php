<?php
session_start();
$total_in_cart = 0;
foreach($_SESSION['shopping_cart'] as $detail){
  if(!empty($details)){
    $total_in_cart += $detail['detail_count'];
  }
}

ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$errors =[];
var_dump($_POST);
if(!empty($_POST)){

    $dbh = dbconnect();
    $user = findUserByEmail($dbh, $_POST['usr_email']);

    if(password_verify($_POST["usr_pass"], $user["0"]["usr_pass"])){
        session_regenerate_id(true);
        $_SESSION['login'] = true;
        $_SESSION['user'] = $user;
        header('Location: ./../../../public/index.php');
        exit();

      }else{
        $errors[] = 'パスワードが違います。';
      }

    if(!$user){
       $errors[] = 'メールアドレスが違います。';
    }

}



?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In</title>
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
                        <h1 class="form_title orange">Log In</h1>
                        <br>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="./login.php" method="post">
                            <div class="form_item">
                                <label>Email<br>
                                　　<input type="text" name="usr_email" value="<?php if(isset($_POST['usr_email'])){echo h($_POST['usr_email']);}?>" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Password<br>
                                　　<input type="password" name="usr_pass" value="<?php if(isset($_POST['usr_pass'])){ echo h($_POST['usr_pass']);}?>" required>
                                </label>
                            </div>
                            <br>
                        
                        　　<input class="btn bg_green" type="submit" value="Log In">

                        </form>
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>