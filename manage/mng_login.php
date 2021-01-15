<?php
session_start();

ini_set('display_errors', true);

require_once './../private/database.php';
require_once './../private/functions.php';

$errors =[];
var_dump($_POST);
if(!empty($_POST)){

    $dbh = dbconnect();
    $mgr = findManagerByName($dbh, $_POST['mgr_name']);

        if($_POST['mgr_name'] !== $mgr[0]['mgr_name']){
             $errors[] = '名前が違います。';
        }else if(password_verify($_POST["mgr_pass"], $mgr["0"]["mgr_pass"])){
              session_regenerate_id(true);
              $_SESSION['login'] = true;
              $_SESSION['mgr'] = $mgr;
              header('Location: ./add_product.php');
              exit();

        }else{
             $errors[] = 'パスワードが違います。';
        }
 }

?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manager Log In</title>
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
                        <h1 class="form_title blue">Manager Log In</h1>
                        <br>

                        <?php if(isset($errors)): ?> 
                            <ul class="error-box">
                            <?php foreach($errors as $error): ?> 
                                <li><?php echo $error; ?></li>
                            <?php endforeach ?> 
                            </ul>
                        <?php endif ?>
                        <br>

                        <form action="./mng_login.php" method="post">
                            <div class="form_item">
                                <label>Name<br>
                                　　<input type="text" name="mgr_name" value="<?php if(isset($_POST['mgr_name'])){echo h($_POST['mgr_name']);}?>" required>
                                </label>
                        　　</div>
                            <br>

                            <div class="form-item">
                                <label>Password<br>
                                　　<input type="password" name="mgr_pass" value="<?php if(isset($_POST['mgr_pass'])){ echo h($_POST['mgr_pass']);}?>" required>
                                </label>
                            </div>
                            <br>
                        
                        　　<input class="btn bg_blue" type="submit" value="Log In">

                        </form>
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>