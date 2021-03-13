<?php
session_start();

if (!$_SESSION['login']) {
    header('Location: /manage/mng_login.php');
    exit();
  }

  if ($_SESSION['login']= true) {
      $mgrs = $_SESSION['mgr'];
    }
    //var_dump($mgrs);
    foreach($mgrs as $mgr){
      //var_dump($mgr['id']);
    }
    $managers_id = $mgr['id'];
    //var_dump($managers_id);
//------------------------------------------------

//ini_set('display_errors', true);
ini_set('display_errors', 0);
//error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

//update and Add new product details------------------------------------

//var_dump($_POST);

//var_dump($_FILES);
//var_dump($_GET['product_id']);
$product_id = $_GET['product_id'];


?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Added Product</title>
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

                    <h2 class="form_title">New product has successfully registered.</h2>
                    <br>
                    <a class="link_b line_color_orange" href="./products_list.php?product_id=<?php echo $_GET['product_id'];?>">Go to Products List</a>


                    </div>
                </div>
            </div>
        </label>
    </body>
</html>