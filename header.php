<!--header for pc-->
<div class="header_pc">
<header>

    <div class="h_container">

            <div class="logo"><a href="/" class="link_a"><span>Bright-Shop</span></a></div>

            <div class="navi">
            <ul>
                　<li class="adj"><a href="/public/shopping/shopping_cart.php" class="link_a"><span><i class="fas fa-shopping-cart"></i><?php// echo '('.$UnreadCommentCount['COUNT(*)'].')';?></span></a></li>

                  <li class="adj"><a href="/public/view/favorites.php" class="link_a"><span><i class="fas fa-heart"></i><?php// echo '('.$UnreadCommentCount['COUNT(*)'].')';?></span></a></li>

                  <li class="adj"><a href="/public/inquiry.php" class="link_a"><span><i class="fas fa-paper-plane"></i>Contact Us</span></a></li>

                <?php if(empty($_SESSION['user'])):?>
                  <li><a href="/public/account/signup.php" class="link_a"><span><i class="fas fa-user"></i>Sign Up</span></a></li>
                　<li><a href="/public/account/login.php" class="link_a"><span><i class="fas fa-lock"></i>Log In</span></a></li>
                <?php else:?>
                  <li class="listH"><strong><?php echo $_SESSION['user'][0]['title'].' '.$_SESSION['user'][0]['usr_name'];?></strong>
                  <ul>
                    <li><a href="/public/account/logout.php" class="link_a"><span><i class="fas fa-sign-out-alt"></i>Log Out</span></a></li>
                    <li><a href="/public/account/update_account.php" class="link_a"><span><i class="fas fa-user"></i>Account</span></a></li>
                    <li><a class="link_a" href="/public/shopping/shopping_history.php.php?id=<?php echo $_SESSION['user'][0]['id'];?> "><span><i class="fas fa-history"></i>Shopping<br><div class="space">History</div><span></a></li>
                </ul>
                  </li>
                <?php endif;?>
              </ul>
         </div><!--navi-->
    </div><!--container-->

</header>
