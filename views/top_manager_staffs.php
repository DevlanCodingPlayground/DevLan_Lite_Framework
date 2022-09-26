<?php
/*
 * Created on Tue Feb 15 2022
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


session_start();
require_once '../config/config.php';
require_once '../config/checklogin.php';
require_once '../config/codeGen.php';
require_once '../config/app_config.php';
check_login();

/* Add User */
if (isset($_POST['add_user'])) {
    $user_id = $staff_id;
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phoneno = $_POST['user_phoneno'];
    $user_password = sha1(md5($user_gen_password));
    $user_access_level = $_POST['user_access_level'];
    $user_store_id = $_POST['user_store_id'];
    /* Activity Logged */
    $log_type = "Registered new user : $user_name , $user_email with $user_access_level access level";

    //check whether user exists

    $ret = 'SELECT * FROM users ';
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($users = $res->fetch_object()) {
        $user_db_email = $users->user_email;
        $user_db_phoneno = $users->user_phoneno;
        if (
            $user_db_email !== $user_email &&
            $user_db_phoneno !== $user_phoneno
        ) {
            $query = 'INSERT INTO  users  (user_id,user_name ,user_email,user_phoneno,user_password,user_access_level,user_store_id)
            VALUES (?,?,?,?,?,?,?)';
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param(
                'sssssss',
                $user_id,
                $user_name,
                $user_email,
                $user_phoneno,
                $user_password,
                $user_access_level,
                $user_store_id
            );
            $stmt->execute();
            /* Load Mailer */
            require_once '../mailers/add_new_user.php';
            /* Load Log Helper */
            require_once('../functions/logs.php');

            if ($stmt && $mail->send()) {
                $success = "$user_name Account Created ";
            } else {
                //inject alert that task failed
                $success = "$user_name Account Created ";
            }
        } else {
            $err = 'User exists on the system';
        }
    }
}

/* Update User */
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phoneno = $_POST['user_phoneno'];
    $user_access_level = $_POST['user_access_level'];
    $user_store_id = $_POST['user_store_id'];

    /* Activity Logged */
    $log_type = "Updated : $user_name , $user_email account details";
    $query = 'UPDATE users SET user_name =?, user_email =?, user_phoneno =?, user_access_level =?, user_store_id =? WHERE user_id =?';
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param(
        'ssssss',
        $user_name,
        $user_email,
        $user_phoneno,
        $user_access_level,
        $user_store_id,
        $user_id
    );
    $stmt->execute();
    /* Load Log Helper */
    require_once('../functions/logs.php');
    if ($stmt) {
        $success = "Updated $user_name Details";
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}

/* Delete User */
if (isset($_POST['close_account'])) {
    $user_id = $_POST['user_id'];
    $user_status = 'inactive';
    $user_details = $_POST['user_details'];
    $userid = $_SESSION['user_id'];
    $user_password = sha1(md5($_POST['user_password']));
    /* Activity Logged */
    $log_type = "Deleted : $user_details, account";

    /* Check if the password matches with record */
    $ret = "SELECT * FROM  users WHERE user_id  ='$userid'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($user = $res->fetch_object()) {
        /* Entered password */
        $password_from_db = $user->user_password;
        if ($user_password == $password_from_db) {
            $query = 'UPDATE users SET  user_status=? WHERE user_id =?';
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $user_status, $user_id);
            $stmt->execute();
            /* Load Log Helper */
            require_once('../functions/logs.php');
            if ($stmt) {
                $success = 'Account Closed';
            } else {
                //inject alert that task failed
                $err = 'Please Try Again Or Try Later';
            }
        }
    }
    $err = 'Wrong password.Try again';
}

/* Load Header Partial */
require_once '../partials/head.php';
?>

<body>
    <!-- Pre-loader start -->
    <?php require_once '../partials/preloader.php'; ?>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <!-- Top Navigation Bar -->
            <?php require_once('../partials/topbar.php'); ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <!-- Sidebar -->
                    <?php require_once('../partials/top_manager_navbar.php'); ?>
                    <div class="pcoded-content">
                        <!-- Page-header start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">Staffs</h5>
                                            <p class="m-b-0">Manage Users</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="top_manager_dashboard"> <i class="fa fa-home"></i> </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href=""> Workforce </a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="">Manage USers</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Page-header end -->
                        <div class="pcoded-inner-content">
                            <div class="text-right">
                                <button type="button" data-toggle="modal" data-target="#add_modal" class="btn btn-primary">Register New User</button>
                            </div>
                            <hr>

                            <!-- Add Store Modal -->
                            <div class="modal fade" id="add_modal">
                                <div class="modal-dialog  modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Fill All Required Fields</h4>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <label>Name</label>
                                                        <input type="text" name="user_name" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Email Address</label>
                                                        <input type="email" name="user_email" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Phone Number</label>
                                                        <input type="text" name="user_phoneno" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Assigned Store</label>
                                                        <select name="user_store_id" style="width: 100%;" required class="basic form-control">
                                                            <?php
                                                            $ret = "SELECT * FROM store_settings WHERE store_status='active'";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($stores = $res->fetch_object()) { ?>
                                                                <option value="<?php echo $stores->store_id; ?>"><?php echo $stores->store_name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Staff Access Level</label>
                                                        <select name="user_access_level" style="width: 100%;" required class="basic form-control">
                                                            <option value="Staff">Staff</option>
                                                            <option value="Manager">Manager</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button name="add_user" class="btn btn-primary" type="submit">
                                                        Register User
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Registered Uses</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <table class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Full Name</th>
                                                                    <th>Email</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Access Level</th>
                                                                    <th>Assigned Store</th>
                                                                    <th>Manage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $user_id = $_SESSION['user_id'];
                                                                $ret = "SELECT * FROM users us INNER JOIN store_settings st ON st.store_id = us.user_store_id 
                                                                WHERE us.user_status ='active'  && us.user_id != '$user_id' && us.user_access_level !='Admin'";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($users = $res->fetch_object()) { ?>
                                                                    <tr>
                                                                        <td><?php echo $users->user_name; ?></td>
                                                                        <td><?php echo $users->user_email; ?></td>
                                                                        <td><?php echo $users->user_phoneno; ?></td>
                                                                        <td><?php echo $users->user_access_level; ?></td>
                                                                        <td><?php echo $users->store_name; ?></td>
                                                                        <td>
                                                                            <a data-toggle="modal" href="#update_<?php echo $users->user_id; ?>" class="badge badge-primary"><i class="fas fa-edit"></i> Edit</a>
                                                                            <a data-toggle="modal" href="#delete_<?php echo $users->user_id; ?>" class="badge badge-danger"><i class="fas fa-trash"></i> Delete</a>
                                                                        </td>

                                                                        <!-- Udpate Modal -->
                                                                        <div class="modal fade" id="update_<?php echo $users->user_id; ?>">
                                                                            <div class="modal-dialog  modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Fill All Required Fields</h4>
                                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                                            <span>&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <form method="post" enctype="multipart/form-data">
                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label>Name</label>
                                                                                                    <input type="text" name="user_name" value="<?php echo $users->user_name; ?>" required class="form-control">
                                                                                                    <input type="hidden" name="user_id" value="<?php echo $users->user_id; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label>Email Address</label>
                                                                                                    <input type="email" name="user_email" value="<?php echo $users->user_email; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label>Phone Number</label>
                                                                                                    <input type="text" name="user_phoneno" value="<?php echo $users->user_phoneno; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label>Assigned Store</label>
                                                                                                    <select name="user_store_id" style="width: 100%;" required class="basic form-control">
                                                                                                        <option value="<?php echo $users->user_store_id; ?>"><?php echo $users->store_name; ?></option>
                                                                                                        <?php
                                                                                                        $store_ret = "SELECT * FROM store_settings WHERE store_status='active'";
                                                                                                        $store_stmt = $mysqli->prepare($store_ret);
                                                                                                        $store_stmt->execute(); //ok
                                                                                                        $store_res = $store_stmt->get_result();
                                                                                                        while ($stores = $store_res->fetch_object()) {
                                                                                                        ?>
                                                                                                            <option value="<?php echo $stores->store_id; ?>"><?php echo $stores->store_name; ?></option>
                                                                                                        <?php } ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label>Staff Access Level</label>
                                                                                                    <select name="user_access_level" style="width: 100%;" required class="basic form-control">
                                                                                                        <?php if ($users->user_access_level == 'Staff') { ?>
                                                                                                            <option value="Staff">Staff</option>
                                                                                                        <?php } else if ($users->user_access_level == 'Manager') { ?>
                                                                                                            <option value="Staff">Manager</option>
                                                                                                        <?php } else { ?>
                                                                                                            <option value="Manager">Manager</option>
                                                                                                        <?php } ?>
                                                                                                        <option value="Staff">Staff</option>
                                                                                                        <option value="Manager">Manager</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="text-right">
                                                                                                <button name="update_user" class="btn btn-primary" type="submit">
                                                                                                    Update user
                                                                                                </button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Modal -->

                                                                        <!-- Udpate Modal -->
                                                                        <!-- Delete Modal -->
                                                                        <div class="modal fade" id="delete_<?php echo $users->user_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM CLOSE</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                                            <span>&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <form method="POST">
                                                                                        <div class="modal-body text-center text-danger">
                                                                                            <h4>
                                                                                                Delete <?php echo $users->user_name; ?> ? <br>
                                                                                                This operation is irreversible.
                                                                                            </h4>
                                                                                            <br>
                                                                                            <!-- Hide This -->
                                                                                            <input type="hidden" name="user_id" value="<?php echo $users->user_id; ?>">
                                                                                            <!-- Hide This -->
                                                                                            <input type="hidden" name="user_id" value="<?php echo $users->user_id; ?>">
                                                                                            <input type="hidden" name="user_details" value="<?php echo $users->user_name . ' ' . $users->user_email; ?>">
                                                                                            <div class="form-group col-md-12">
                                                                                                <input type="password" required name="user_password" class="form-control">
                                                                                            </div>
                                                                                            <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                            <input type="submit" name="close_account" value="Close" class="text-center btn btn-danger">
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Delete Modal -->
                                                                    </tr>
                                                                <?php }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php require_once '../partials/scripts.php'; ?>
</body>

</html>