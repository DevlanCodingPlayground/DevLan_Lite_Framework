<?php
/*
 * Created on Mon Feb 07 2022
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
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');
check_login();

/* Add Product */
if (isset($_POST['add_product'])) {
    $product_id = $ID;
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_purchase_price = $_POST['product_purchase_price'];
    $product_sale_price  = $_POST['product_sale_price'];
    $product_quantity = $_POST['product_quantity'];
    $product_quantity_limit  = $_POST['product_quantity_limit'];
    $product_code   = $_POST['product_code'];


    /* Activity Logged */
    $log_type = "Registered $product_code - $product_name, With A Total Quantity Of : $product_quantity";

    $query = 'INSERT INTO products (product_id,product_name ,product_description,product_purchase_price,product_sale_price,product_quantity,product_quantity_limit,product_code)
    VALUES (?,?,?,?,?,?,?,?)';
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param(
        'ssssssss',
        $product_id,
        $product_name,
        $product_description,
        $product_purchase_price,
        $product_sale_price,
        $product_quantity,
        $product_quantity_limit,
        $product_code

    );
    $stmt->execute();
    /* Load Log Helper */
    require_once('../functions/logs.php');
    if ($stmt) {
        $success = "$product_name Added ";
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}
/* Update Product */
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_purchase_price = $_POST['product_purchase_price'];
    $product_sale_price  = $_POST['product_sale_price'];
    $product_quantity = $_POST['product_quantity'];
    $product_quantity_limit  = $_POST['product_quantity_limit'];

    /* Activity Logged */
    $log_type = "Updated $product_name, With A Total Quantity Of : $product_quantity";


    $query = 'UPDATE products SET  product_name =?,product_description =?, product_quantity_limit =?, product_purchase_price =?, product_sale_price =?,product_quantity =? WHERE product_id =?';
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $product_name, $product_description, $product_quantity_limit, $product_purchase_price, $product_sale_price, $product_quantity, $product_id);
    $stmt->execute();
    /* Load Log Helper */
    require_once('../functions/logs.php');
    if ($stmt) {
        $success = "$product_name Details Updated";
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}

/* Delete Product */
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $product_status  = 'inactive';
    $product_details = $_POST['product_details'];
    $user_id = $_SESSION['user_id'];
    $user_password = sha1(md5($_POST['user_password']));
    /* Activity Logged */
    $log_type = "Deleted $product_details";

    /* Check if the password matches with record */
    $ret = "SELECT * FROM  users WHERE user_id  ='$user_id'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($user = $res->fetch_object()) {
        /* Entered password */
        $password_from_db = $user->user_password;
        if ($user_password == $password_from_db) {
            $query = 'UPDATE products SET  product_status=? WHERE product_id =?';
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $product_status, $product_id);
            $stmt->execute();
            /* Load Log Helper */
            require_once('../functions/logs.php');
            if ($stmt) {
                $success = "$product_details Deleted";
            } else {
                //inject alert that task failed
                $err = 'Please Try Again Or Try Later';
            }
        } else {
            $err = 'Wrong password. Try again';
        }
    }
}

/* Load Header Partial */
require_once('../partials/head.php')
?>

<body>
    <!-- Pre-loader start -->
    <?php require_once('../partials/preloader.php'); ?>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <!-- Top Navigation Bar -->
            <?php require_once('../partials/topbar.php'); ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <!-- Sidebar -->
                    <?php require_once('../partials/manager_navbar.php'); ?>
                    <div class="pcoded-content">
                        <!-- Page-header start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">Products</h5>
                                            <p class="m-b-0">Manage Products</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="manager_dashboard"> <i class="fa fa-home"></i> </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href=""> Products </a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="manager_dashboard_products">Manage</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Page-header end -->
                        <div class="pcoded-inner-content">
                            <div class="text-right">
                                <a class="btn btn-primary" href="manager_dashboard_system_products_pdf_dump"><i class="fas fa-file-pdf"></i> Export To PDF</a>
                                <a class="btn btn-primary" href="manager_dashboard_system_products_xls_dump"><i class="fas fa-file-spreadsheet"></i> Export To Excel</a>
                                <button type="button" data-toggle="modal" data-target="#add_modal" class="btn btn-primary">Register New Product</button>
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
                                                    <div class="form-group col-md-6">
                                                        <label>Name</label>
                                                        <input type="text" name="product_name" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Code</label>
                                                        <input type="text" value="<?php echo $a . $b; ?>" name="product_code" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Quantity</label>
                                                        <input type="text" name="product_quantity" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Purchase Price (Ksh)</label>
                                                        <input type="text" name="product_purchase_price" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Retail Sale Price (Ksh)</label>
                                                        <input type="text" name="product_sale_price" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Restock Limit</label>
                                                        <input type="text" value="5" name="product_quantity_limit" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Description</label>
                                                        <textarea type="text" name="product_description" rows="3" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button name="add_product" class="btn btn-primary" type="submit">
                                                        Register Product
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
                                                        <h5>Registered Products</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <table class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Code</th>
                                                                    <th>Name</th>
                                                                    <th>Quantity</th>
                                                                    <th>Purchase Price</th>
                                                                    <th>Retail Price</th>
                                                                    <th>Margin</th>
                                                                    <th>Manage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $ret = "SELECT * FROM products WHERE product_status = 'active'";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($products = $res->fetch_object()) {
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $products->product_code; ?></td>
                                                                        <td><?php echo $products->product_name; ?></td>
                                                                        <td><?php echo $products->product_quantity; ?></td>
                                                                        <td>Ksh <?php echo number_format($products->product_purchase_price, 2); ?></td>
                                                                        <td>Ksh <?php echo number_format($products->product_sale_price, 2); ?></td>
                                                                        <td>Ksh <?php echo number_format(($products->product_sale_price) - ($products->product_purchase_price), 2); ?></td>
                                                                        <td>
                                                                            <a data-toggle="modal" href="#update_<?php echo $products->product_id; ?>" class="badge badge-primary"><i class="fas fa-edit"></i> Edit</a>
                                                                            <a data-toggle="modal" href="#delete_<?php echo $products->product_id; ?>" class="badge badge-danger"><i class="fas fa-trash"></i> Delete</a>
                                                                        </td>

                                                                        <!-- Udpate Modal -->
                                                                        <div class="modal fade" id="update_<?php echo $products->product_id; ?>">
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
                                                                                                <div class="form-group col-md-8">
                                                                                                    <label>Name</label>
                                                                                                    <input type="text" name="product_name" value="<?php echo $products->product_name; ?>" required class="form-control">
                                                                                                    <input type="hidden" name="product_id" value="<?php echo $products->product_id; ?>" required class="form-control">
                                                                                                </div>

                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>Quantity</label>
                                                                                                    <input type="text" name="product_quantity" value="<?php echo $products->product_quantity; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>Purchase Price (Ksh)</label>
                                                                                                    <input type="text" name="product_purchase_price" value="<?php echo $products->product_purchase_price; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>Retail Sale Price (Ksh)</label>
                                                                                                    <input type="text" name="product_sale_price" value="<?php echo $products->product_sale_price; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>Restock Limit</label>
                                                                                                    <input type="text" value="<?php echo $products->product_quantity_limit; ?>" name="product_quantity_limit" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label>Description</label>
                                                                                                    <textarea type="text" name="product_description" rows="3" class="form-control"><?php echo $products->product_description; ?></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="text-right">
                                                                                                <button name="update_product" class="btn btn-primary" type="submit">
                                                                                                    Update Product
                                                                                                </button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Modal -->

                                                                        <!-- Delete Modal -->
                                                                        <div class="modal fade" id="delete_<?php echo $products->product_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETE</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                                            <span>&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <form method="POST">
                                                                                        <div class="modal-body text-center text-danger">
                                                                                            <h4>
                                                                                                Delete <?php echo $products->product_code . ' ' . $products->product_name; ?> ?
                                                                                                <hr>
                                                                                                This operation is irreversible. Please confirm your password before deleting above product
                                                                                            </h4>
                                                                                            <br>
                                                                                            <!-- Hide This -->
                                                                                            <input type="hidden" name="product_id" value="<?php echo $products->product_id; ?>">
                                                                                            <input type="hidden" name="product_details" value="<?php echo $products->product_code . ' ' . $products->product_name; ?>">
                                                                                            <div class="form-group col-md-12">
                                                                                                <input type="password" required name="user_password" class="form-control">
                                                                                            </div>
                                                                                            <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                            <input type="submit" name="delete_product" value="Delete" class="text-center btn btn-danger">
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Delete Modal -->
                                                                    </tr>
                                                                <?php
                                                                }
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
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>