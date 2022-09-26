<?php
/*
 * Created on Fri Feb 04 2022
 *
 *  Devlan Agency - devlan.co.ke 
 *
 * hello@devlan.co.ke
 *
 *
 * The Devlan End User License Agreement
 *
 * Copyright (c) 2022 Devlan Agency
 *
 * 1. GRANT OF LICENSE
 * Devlan Agency hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 * install and activate this system on two separated computers solely for your personal and non-commercial use,
 * unless you have purchased a commercial license from Devlan Agency. Sharing this Software with other individuals, 
 * or allowing other individuals to view the contents of this Software, is in violation of this license.
 * You may not make the Software available on a network, or in any way provide the Software to multiple users
 * unless you have first purchased at least a multi-user license from Devlan Agency.
 *
 * 2. COPYRIGHT 
 * The Software is owned by Devlan Agency and protected by copyright law and international copyright treaties. 
 * You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 * 3. RESTRICTIONS ON USE
 * You may not, and you may not permit others to
 * (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 * (b) modify, distribute, or create derivative works of the Software;
 * (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 * otherwise exploit the Software.  
 *
 * 4. TERM
 * This License is effective until terminated. 
 * You may terminate it at any time by destroying the Software, together with all copies thereof.
 * This License will also terminate if you fail to comply with any term or condition of this Agreement.
 * Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 * 5. NO OTHER WARRANTIES. 
 * DEVLAN AGENCY  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 * DEVLAN AGENCY SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 * EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 * SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 * ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 * INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 * SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 * THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 * HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 * 6. SEVERABILITY
 * In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 * affect the validity of the remaining portions of this license.
 *
 * 7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN AGENCY  OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 * CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 * USE OF THE SOFTWARE, EVEN IF DEVLAN HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 * IN NO EVENT WILL DEVLAN  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 * TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 */

/* Handle Password Confirmation Logic */
session_start();
require_once('../config/config.php');
if (isset($_POST['update_password'])) {
    $user_password_reset_token = $_POST['user_password_reset_token'];

    $new_password = sha1(md5($_POST['new_password']));
    $confirm_password = sha1(md5($_POST['confirm_password']));

    /* Check If They Match */
    if ($new_password == $confirm_password) {
        $query = 'UPDATE users SET  user_password=? WHERE user_password_reset_token =?';
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ss', $new_password, $user_password_reset_token);
        $stmt->execute();
        if ($stmt) {
            /* You Can Pass This Message Via Session Variable To Avoid Fall Back Urls */
            $_SESSION['success'] = 'Password Updated';
            header('Location: index');
            exit;
        } else {
            //inject alert that task failed
            $err = 'Please Try Again Or Try Later';
        }
    } else {
        $err = 'Failed!, Confirmation Passwords Does Not Match';
    }
}


/* Load Header Partial */
require_once('../partials/head.php');
?>

<body themebg-pattern="theme1">
    <?php require_once('../partials/preloader.php');
    $ret = "SELECT * FROM store_settings  WHERE store_status  = 'active'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($stores = $res->fetch_object()) {
    ?>

        <section class="login-block">
            <!-- Container-fluid starts -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- Authentication card start -->

                        <form method="POST" class="md-float-material form-material">
                            <div class="text-center">
                                <!-- Load Coporate Logo -->
                                <!--                                 <img class="img-fluid" style="max-width: 100%; height: auto;" src="../public/images/login.png" alt="Theme-Logo" />
 -->
                            </div>
                            <div class="auth-box card">
                                <div class="card-block">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <h3 class="text-center"><?php echo $stores->store_name; ?></h3>
                                            <hr>
                                            <h3 class="text-center">Confirm Password</h3>
                                        </div>
                                    </div>
                                    <div class="form-group form-primary">
                                        <input type="password" required name="new_password" class="form-control">
                                        <input type="hidden" required name="user_password_reset_token" value="<?php echo $_GET['token'] ?>" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">New Password</label>
                                    </div>
                                    <div class="form-group form-primary">
                                        <input type="password" required name="confirm_password" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Confirm Password</label>
                                    </div>
                                    <div class="row m-t-30">
                                        <div class="col-md-12">
                                            <button type="submit" name="update_password" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Reset password</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- end of form -->
                    </div>
                    <!-- end of col-sm-12 -->
                </div>
                <!-- end of row -->
            </div>
            <!-- end of container-fluid -->
        </section>
    <?php } ?>
    <!-- Load Footer -->
    <?php require_once('../partials/footer.php'); ?>
    <!-- Load Scripts Partials -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>