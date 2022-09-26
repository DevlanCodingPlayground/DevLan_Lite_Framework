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
                    <?php require_once('../partials/navbar.php'); ?>
                    <div class="pcoded-content">
                        <!-- Page-header start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">Reports</h5>
                                            <p class="m-b-0">Customers Reports</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="main_dashboard"> <i class="fa fa-home"></i> </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href=""> Reports </a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="">Customers Reports</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Page-header end -->
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="">
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>From Date</label>
                                                        <input type="date" name="start_date" required class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>To Date</label>
                                                        <input type="date" name="end_date" required class="form-control">
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button name="get_customer_reports" class="btn btn-primary" type="submit">
                                                        Get Reports
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <?php
                                        if (isset($_POST['get_customer_reports'])) {
                                            $start = date('Y-m-d g:ia', strtotime($_POST['start_date']));
                                            $end = date('Y-m-d g:ia', strtotime($_POST['end_date']));
                                        ?>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="text-center">
                                                        <a class="btn btn-primary" href="main_dashboard_system_customers_pdf_dump?from=<?php echo $_POST['start_date']; ?>&to=<?php echo $_POST['end_date']; ?>"><i class="fas fa-file-pdf"></i> Export To PDF</a>
                                                        <a class="btn btn-primary" href="main_dashboard_system_customers_xls_dump?from=<?php echo $_POST['start_date']; ?>&to=<?php echo $_POST['end_date']; ?>"><i class="fas fa-file-spreadsheet"></i> Export To Excel</a>
                                                    </div>
                                                    <br>
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>Registered Customer</h5>
                                                        </div>
                                                        <div class="card-block">
                                                            <table class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Customer Name</th>
                                                                        <th>Customer Phone</th>
                                                                        <th>No of Items</th>
                                                                        <th>Total Expenditure</th>


                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT sale_customer_name,sale_customer_phoneno,COUNT(*)AS count,SUM((p.product_sale_price-s.sale_discount)*s.sale_quantity) AS total_cost ,sale_datetime  
                                                                   FROM sales s
                                                                   LEFT JOIN products p ON  p.product_id =s.sale_product_id
                                                                   WHERE s.sale_datetime BETWEEN '$start' AND '$end' 
                                                                   GROUP BY s.sale_customer_phoneno
                                                                    ORDER BY s.sale_customer_name ASC";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($customers = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $customers->sale_customer_name ?></td>
                                                                            <td><?php echo $customers->sale_customer_phoneno ?></td>
                                                                            <td><?php echo $customers->count  ?></td>
                                                                            <td>Ksh <?php echo number_format($customers->total_cost, 2) ?></td>
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
                                        <?php } ?>
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