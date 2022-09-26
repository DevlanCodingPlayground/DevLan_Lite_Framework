<?php
/*
 * Created on Sat Feb 05 2022
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
check_login();
require_once('../partials/head.php');
$user_id = $_SESSION['user_id'];
$user_store_id = $_SESSION['user_store_id'];
/* Load Analytics */
require_once('../helpers/manager_analytics.php');

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
            <!-- End Top Navigation Bar -->

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <!-- Side Navigation Bar -->
                    <?php require_once('../partials/manager_navbar.php'); ?>
                    <!-- End Side Navigation Bar -->

                    <div class="pcoded-content">
                        <!-- Page-header start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <?php
                                        $query = "SELECT * FROM store_settings s
                                        INNER JOIN users u ON u.user_store_id = s.store_id 
                                        WHERE s.store_id =? AND u.user_id =?";
                                        $stmt =$mysqli->prepare($query);
                                        $res =$stmt->bind_param('ss',$user_store_id,$user_id);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($store = $res->fetch_object()) {
                                        ?>
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">Dashboard</h5>
                                            <p class="m-b-0">Welcome <?php echo $store->user_name ?> To <?php echo $store->store_name ?></p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="manager_dashboard"> <i class="fa fa-home"></i> </a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="manager_dashboard">Dashboard</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Page-header end -->
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- Material statustic card start -->
                                            <div class="col-xl-12 col-md-12">
                                                <div class="card mat-stat-card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center b-b-default">
                                                        <div class="col-sm-4 b-r-default p-b-20 p-t-20">
                                                                <div class="row align-items-center text-center">
                                                                    <div class="col-12 p-l-0">
                                                                        <h5><?php echo  "Ksh " . number_format($today_sales, 2); ?></h5>
                                                                        <p class="text-muted m-b-0">Today's Sales</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 b-r-default p-b-20 p-t-20">
                                                                <div class="row align-items-center text-center">
                                                                    <div class="col-12 p-l-0">
                                                                        <h5><?php echo $products ?></h5>
                                                                        <p class="text-muted m-b-0">Total Products</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 p-b-20 p-t-20">
                                                                <div class="row align-items-center text-center">
                                                                    <div class="col-12 p-l-0">
                                                                        <h5><?php echo $out_of_stock ?></h5>
                                                                        <p class="text-muted m-b-0">Out Of Stock Products</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!--  sale analytics start -->
                                            <div class="col-xl-6 col-md-12">
                                                <div class="card table-card">
                                                    <div class="card-header">
                                                        <h5>Recent Sale Logs </h5>
                                                        <div class="card-header-right">
                                                            <a href="manager_dashboard_sales_reports">
                                                                <span class="badge badge-primary">View All</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-block">
                                                        <!-- Fetch All Todays Sales Logs -->
                                                        <ul class="list-group list-group-flush">
                                                            <?php
                                                            /* Fetch List Of All Products Which Are Low On Stock */
                                                            $user_store_id = $_SESSION['user_store_id'];
                                                            $ret = "SELECT  * FROM sales s
                                                            INNER JOIN products p ON p.product_id = s.sale_product_id
                                                            INNER JOIN users u ON u.user_id = s.sale_user_id 
                                                            WHERE u.user_store_id=?
                                                            ORDER BY sale_datetime DESC
                                                            LIMIT 5
                                                            ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->bind_param('s',  $user_store_id);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($sales = $res->fetch_object()) {
                                                            ?>
                                                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                                                    <div class="ms-2 me-auto">
                                                                        <?php echo $sales->user_name; ?> sold <span class="text-success"><?php echo $sales->product_name; ?></span>
                                                                        to <span class="text-success"><?php echo $sales->sale_customer_name; ?></span> on
                                                                        <span class="text-success"><?php echo date('d M Y', strtotime($sales->sale_datetime)); ?></span>
                                                                        at <span class="text-success"><?php echo date('g:ia', strtotime($sales->sale_datetime)); ?></span>
                                                                        quantity sold is <span class="text-success"><?php echo $sales->sale_quantity; ?>.</span>
                                                                    </div>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-12">
                                                <div class="card table-card">
                                                    <div class="card-header">
                                                        <h5>Low / Out Of Stock Products </h5>
                                                        <div class="card-header-right">
                                                            <a href="manager_dashboard_manage_stocks">
                                                                <span class="badge badge-primary">View All</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-block">
                                                        <!-- Fetch All Products That Are Low On Stock And Those Have Reached Limit -->
                                                        <ul class="list-group list-group-flush">
                                                            <?php
                                                            /* Fetch List Of All Products Which Are Low On Stock */
                                                            $ret = "SELECT  * FROM `products` 
                                                            WHERE product_quantity <= 1 
                                                            ORDER BY product_name ASC
                                                            LIMIT 5 ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($product = $res->fetch_object()) {
                                                            ?>
                                                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                                                    <div class="ms-2 me-auto">
                                                                        <span class="text-danger"><?php echo $product->product_code . ' ' . $product->product_name; ?> </span> is out of stock, kindly plan to restock it.
                                                                    </div>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <?php require_once('../partials/footer.php'); ?>

    <!-- Scripts -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>