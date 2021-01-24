<?php
session_start();
if ($_SESSION['login']= true) {
    $mgr = $_SESSION['mgr'];
  }
  $managers_id = $manager[0]['mgr_id'];
//--------------------------------
ini_set('display_errors', true);

require_once './../../private/database.php';
require_once './../../private/functions.php';

$count_usrss = getCountUsers();
$count_usrs = $count_usrss[0];

$usrDatas = getAllusersDatas();
//var_dump($usrDatas);

foreach($usrDatas as $usrData){
        //echo $usrData['usr_name'];
}




?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Members List</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="./../css/form.css">
        <link rel="stylesheet" href="./../../../public/css/header.css">
    
    </head>

    <body>

     　<?php include './mng_header.php';?>

   
        <label for="check">
            <div class="wrapper">
                <div class="container">
                    <div class="typein">
                        <h1 class="form_title blue">Members List&nbsp;(<?php echo $count_usrs;?>)</h1>
                        <br>

                        <div class="frame">
                        <table>
                            <tr>
                              <td>

                                 <?php for($i=0; $i<$count_usrs; $i++):?>
                                   <?php $usrData = $usrDatas[$i];?>
                                  
                                    <div class="result_box"> 
                                      <strong><?php echo $i+1;?>.</strong><br>

                                      <table border=1>
                                          <tr><td>ID</td><td><?php echo h($usrData['id'])?></td></tr>
                                          <tr><td>Name</td><td><?php echo h($usrData['usr_name'])?>&nbsp;さん</td></tr>
                                          <tr><td>E-mail</td><td><?php echo h($usrData['usr_email'])?></td></tr>
                                          <tr><td>Phone number</td><td><?php echo h($usrData['tel'])?></td></tr>
                                          <tr><td>Shopping history</td><td>---</td></tr>
                                          <tr><td>Order</td><td>---</td></tr>
                                          <tr><td rowspan=2>Address</td><td rowspan=2>〒<?php echo h($usrData['postal'])?><br><?php echo h($usrData['addr_pref'].$usrData['addr_city'].$usrData['addr_last'])?></td></tr>
                                          
                                        </table>
                                          <br>
                                      </div><!--result_box-->

                                      
                                  <?php endfor;?>

                              </td>
                            </tr>
                         </table>
                     </div><!--frame-->
                        
                    </div><!--typein-->
                </div><!--container-->
        　　 </div><!--wrappr-->
   　　　</label>
   　</body>
</html>