<!--<div class="header_pc">-->
<header>

    <div class="bh_container">

            <div class="blogo"><a href="/index.php" class="blink_a"><span><i class="fas fa-paw"></i>Bright-Shop</span></a></div>

            <div class="bnavi">
                <ul>
                  　<li class="badj"><a href="/shopping/shopping_cart.php" class="blink_a"><span><i class="fas fa-shopping-cart"></i><?php echo $total_in_cart;?></span></a></li>

                    <li class="badj"><a href="/contact.php" class="blink_a"><span><i class="fas fa-paper-plane"></i>Contact Us</span></a></li>

                    <?php if(empty($_SESSION['user'])):?>
                        <li><a href="/account/signup.php" class="blink_a"><span><i class="fas fa-user"></i>Sign Up</span></a></li>
                  　    <li><a href="/account/login.php" class="blink_a"><span><i class="fas fa-lock"></i>Log In</span></a></li>
                    <?php else:?>
                        <li class="blistH"><strong><?php echo $_SESSION['user'][0]['title'].' '.$_SESSION['user'][0]['usr_name'];?></strong>
                            <ul>
                                <li><a href="/account/logout.php" class="blink_a"><span><i class="fas fa-sign-out-alt"></i>Log Out</span></a></li>
                                <li><a href="/account/update_account.php" class="blink_a"><span><i class="fas fa-user"></i>Account</span></a></li>
                                <li><a class="blink_a" href="/shopping/shopping_history.php?id=<?php echo $_SESSION['user'][0]['id'];?> "><span><i class="fas fa-history"></i>Shopping History<span></a></li>
                            </ul>
                        </li>
                    <?php endif;?>
                </ul>
            </div><!--navi-->
    </div><!--container-->

</header>
