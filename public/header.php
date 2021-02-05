<!--header for pc-->
<div class="header_pc">
<header>

    <div class="h_container">

            <div class="logo"><a href="/index.php" class="link_a"><span><i class="fas fa-paw"></i>Bright-Shop</span></a></div>

            <div class="navi">
                <ul>
                  　<li class="adj"><a href="/shopping/shopping_cart.php" class="link_a"><span><i class="fas fa-shopping-cart"></i><?php echo $total_in_cart;?></span></a></li>

                    <li class="adj"><a href="/contact.php" class="link_a"><span><i class="fas fa-paper-plane"></i>Contact Us</span></a></li>

                    <?php if(empty($_SESSION['user'])):?>
                        <li><a href="/account/signup.php" class="link_a"><span><i class="fas fa-user"></i>Sign Up</span></a></li>
                  　    <li><a href="/account/login.php" class="link_a"><span><i class="fas fa-lock"></i>Log In</span></a></li>
                    <?php else:?>
                        <li class="listH"><strong><?php echo $_SESSION['user'][0]['title'].' '.$_SESSION['user'][0]['usr_name'];?></strong>
                            <ul>
                                <li><a href="/account/logout.php" class="link_a"><span><i class="fas fa-sign-out-alt"></i>Log Out</span></a></li>
                                <li><a href="/account/update_account.php" class="link_a"><span><i class="fas fa-user"></i>Account</span></a></li>
                                <li><a class="link_a" href="/shopping/shopping_history.php?id=<?php echo $_SESSION['user'][0]['id'];?> "><span><i class="fas fa-history"></i>Shopping History<br><div class="space"></div><span></a></li>
                            </ul>
                        </li>
                    <?php endif;?>
                </ul>
            </div><!--navi-->
    </div><!--container-->

</header>
