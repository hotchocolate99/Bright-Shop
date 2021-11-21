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

$count_inquiries = getCountInquiries();
$count_inquiry = $count_inquiries[0];

$inquiryDatas = getAllinquirysDatas();
//var_dump($usrDatas);

foreach($inquiryDatas as $inquiryData){
       
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inquiries List</title>
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
                        <h1 class="form_title blue">Inquiries List&nbsp;(<?php echo $count_inquiry;?>)</h1>
                        <br>

                        <div class="frame">
                            <table>
                                <tr><td class="inquiry_no"><strong>No</strong></td><td class="inquiry_name">Name</td><td class="inquiry_subject">Subject</td><td class="inquiry_date">Date</td></tr>
                                    <?php for($i=0; $i<$count_inquiry; $i++):?>
                                        <?php $inquiryData = $inquiryDatas[$i];?>
                                        <tr><td class="inquiry_no"><a class="link_aa" href="./mng_inquiry_detail.php?id=<?php echo h($inquiryData['id'])?>"><strong><?php echo $i+1;?>.</strong></a></td><td class="inquiry_name"><?php echo h($inquiryData['title'].$inquiryData['name'])?></td><td class="inquiry_subject"><?php echo h($inquiryData['subject'])?></td><td class="inquiry_date"><?php echo h($inquiryData['created_at'])?></td></a></tr>
                                    <?php endfor;?>
                             </table>
                         </div><!--frame-->

                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>