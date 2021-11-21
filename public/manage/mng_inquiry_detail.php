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
//--------------------------------

ini_set('display_errors', true);
//error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$inquiry_id = $_GET['id'];

$inquiry_details = getInquiryDetails($inquiry_id);
//var_dump($usrDatas);

foreach($inquiry_details as $inquiry_detail){
    if($inquiry_id === $inquiry_detail['id']){
    }

}



?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inquiry Detail</title>
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
                        <h1 class="form_title blue">Inquiry Detail</h1>
                        <br>

                        <div class="frame">
                            <table>
                                <tr><td class="inquiry_no"><strong>ID</strong></td><td class="inquiry_name"><?php echo $inquiry_detail['id'];?></td><td class="inquiry_subject">Subject</td><td><?php echo $inquiry_detail['subject'];?></td><td class="inquiry_date">Date</td><td><?php echo $inquiry_detail['created_at'];?></td></tr>
                                <tr><td colspan = 4><?php echo $inquiry_detail['content'];?></td></tr>

                             </table>
                         </div><!--frame-->

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>