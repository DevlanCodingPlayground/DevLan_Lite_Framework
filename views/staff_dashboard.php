<?php
/*
 * Created on Tue Feb 08 2022
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
require_once("../config/dbcontroller.php");
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');
check_login();
/* Initiate DB Controller */
$db_handle = new DBController();
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (!empty($_POST["quantity"])) {
                $productByCode = $db_handle->runQuery("SELECT * FROM products WHERE product_id='" . $_GET["product_code"] . "'");
                /* Fetch All Products And Add Them In An Array */
                if (!empty($_POST['Discount'])) {
                    /* Check If Discount Is Empty */
                    $Discount = $_POST['Discount'];
                    /* Hold Discount */
                } else {
                    $Discount = 0;
                }
                $itemArray = array(
                    $productByCode[0]["product_code"] => array(
                        'product_name' => $productByCode[0]["product_name"],
                        'product_code' => $productByCode[0]["product_code"],
                        'quantity' => $_POST["quantity"],
                        'product_sale_price' => ($productByCode[0]["product_sale_price"] - $Discount),
                        'product_description' => $productByCode[0]["product_description"],
                        'product_id' => $productByCode[0]["product_id"],
                        'product_quantity_limit' => $productByCode[0]["product_quantity_limit"],
                        'Discount' => $Discount
                    )
                );

                if (!empty($_SESSION["cart_item"])) {
                    if (in_array($productByCode[0]["product_code"], array_keys($_SESSION["cart_item"]))) {
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            if ($productByCode[0]["product_code"] == $k) {
                                if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                                    $_SESSION["cart_item"][$k]["quantity"] = 0;
                                }
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                            }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["product_code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if (empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}

/* Post Data */
if (isset($_POST['add_sale'])) {
    $sale_payment_method = $_POST['sale_payment_method'];
    $cart_products = $_SESSION["cart_item"];

    if ($sale_payment_method == 'MPESA') {
        /* Handle MPESA Transaction */
        include('../helpers/staff_mpesasale_helper.php');
    } else if ($sale_payment_method == 'card') {
        /* Handle Debit Card */
        include('../helpers/staff_card_helper.php');
    } else {
        /* Handle Cash Sale */
        include('../helpers/staff_cashsale_helper.php');
    }
}
/* Load Session Alerts Via Helper */
require_once('../functions/alerts.php');
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
            <?php require_once('../partials/staff_topbar.php'); ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <!-- Sidebar -->
                    <div class="">
                        <!-- Page-header start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">POS Module</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="staff_dashboard"> <i class="fa fa-home"></i> </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href=""> POS </a>
                                            </li>
                                            <li class="breadcrumb-item"><a href="">Sales</a>
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
                                        <div class="row">
                                            <div class="col-12">
                                                <input class="form-control" type="text" id="Product_Search" onkeyup="FilterFunction()" placeholder="Search Products">
                                                <br>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border border-primary">
                                                    <div class="card-body">
                                                        <div class="row" style="overflow: auto; height: 500px;">
                                                            <?php
                                                            $query = htmlspecialchars($_POST['querry']);
                                                            $product_array = $db_handle->runQuery("SELECT * 
                                                            FROM products WHERE product_status ='active'");
                                                            if (!empty($product_array)) {
                                                                foreach ($product_array as $key => $value) {
                                                            ?>
                                                                    <div class="col-6 Product_Name">
                                                                        <form method="post" class="form-inline my-2 my-lg-0" action="staff_dashboard?action=add&product_code=<?php echo $product_array[$key]["product_id"]; ?>">
                                                                            <div class="card border border-success text-dark">
                                                                                <div class="card-body">
                                                                                    <h5 id="product_details" class="card-title"><?php echo $product_array[$key]["product_name"]; ?> </h5>
                                                                                    <p class="card-text"><?php echo $product_array[$key]["product_description"]; ?></p>
                                                                                    <!-- Notify User If Product Has Reached Restock Limit -->
                                                                                    <?php if ($product_array[$key]["product_quantity"] <= 0) { ?>
                                                                                        <p class="card-text text-danger">Kindly Restock This Product, Current Qty is
                                                                                            <?php echo $product_array[$key]["product_quantity"]; ?></p>

                                                                                    <?php } else if ($product_array[$key]["product_quantity"] <= $product_array[$key]["product_quantity_limit"]) { ?>
                                                                                        <p class="card-text text-danger">Kindly Restock This Product, Current Qty is
                                                                                            <?php echo $product_array[$key]["product_quantity"]; ?>
                                                                                        </p>

                                                                                        <p class="card-text"><b><?php echo "Ksh " . $product_array[$key]["product_sale_price"]; ?></b></p>
                                                                                        <input type="text" class="form-control mr-sm-2" name="quantity" value="1" size="2" />

                                                                                        <!-- <label>Discount</label><br>-->
                                                                                        <input type="hidden" class="form-control mr-sm-4" name="Discount" placeholder="Discount" size="8" />
                                                                                        <input type="submit" value="Add" class="btn btn-outline-success my-2 my-sm-0" />
                                                                                    <?php } else { ?>
                                                                                        <p class="card-text text-success">Current Item Quantity is
                                                                                            <?php echo $product_array[$key]["product_quantity"]; ?>
                                                                                        </p>
                                                                                        <p class="card-text"><b><?php echo "Ksh " . $product_array[$key]["product_sale_price"]; ?></b></p>
                                                                                        <input type="text" class="form-control mr-sm-2" name="quantity" value="1" size="2" />
                                                                                        <input type="hidden" class="form-control mr-sm-4" name="Discount" placeholder="Discount" size="8" />
                                                                                        <input type="submit" value="Add" class="btn btn-outline-success my-2 my-sm-0" />
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                            <?php }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                if (isset($_SESSION["cart_item"])) {
                                                    $total_quantity = 0;
                                                    $total_price = 0;
                                                ?>
                                                    <div class="card border border-primary text-dark">
                                                        <div class="card-header">
                                                            <h5>Cart Items</h5>
                                                            <div class="text-right">
                                                                <a class="btn btn-outline-danger btn-sm" href="staff_dashboard?action=empty">
                                                                    <i class="fas fa-trash"></i>
                                                                    Clear Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="card-block">
                                                            <table class="table" cellpadding="10" cellspacing="1">
                                                                <tbody>
                                                                    <tr>
                                                                        <th style="text-align:left;">#</th>
                                                                        <th style="text-align:left;">Item</th>
                                                                        <th style="text-align:right;" width="5%">QTY</th>
                                                                        <th style="text-align:right;" width="10%">Unit Price</th>
                                                                        <th style="text-align:right;" width="10%">Price</th>
                                                                        <th style="text-align:right;" width="10%">Action</th>
                                                                    </tr>
                                                                    <?php
                                                                    foreach ($_SESSION["cart_item"] as $item) {
                                                                        $item_price = $item["quantity"] * $item["product_sale_price"];
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $item["product_code"]; ?></td>
                                                                            <td><?php echo  $item["product_name"]; ?></td>
                                                                            <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                                                                            <td style="text-align:right;"><?php echo "Ksh " . number_format($item["product_sale_price"], 2); ?></td>
                                                                            <td style="text-align:right;"><?php echo "Ksh " . number_format($item_price, 2); ?></td>
                                                                            <td style="text-align:right;">
                                                                                <a class="btn btn-outline-danger btn-sm" href="staff_dashboard?action=remove&product_code=<?php echo $item["product_code"]; ?>">
                                                                                    <i class="fas fa-trash"></i> Remove
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                        $total_quantity += $item["quantity"];
                                                                        $total_price += ($item["product_sale_price"] * $item["quantity"]);
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="2" align="right"><b>Total:</b></td>
                                                                        <td align="right"><b><?php echo $total_quantity; ?></b></td>
                                                                        <td align="right" colspan="2"><strong><?php echo "Ksh " . number_format($total_price, 2); ?></strong></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <form method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="sale_customer_phoneno" class="form-control">
                                                            <!-- Hide This -->
                                                            <input type="hidden" name="total_payable_price" value="<?php echo $total_price; ?>">
                                                            <input type="hidden" name="sale_payment_method" value="Cash">
                                                            <input type="hidden" name="sale_customer_name" class="form-control">
                                                            <div class="text-right">
                                                                <button name="add_sale" class="btn btn-primary" type="submit">
                                                                    Save Sale
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <br>
                                                <?php } else {
                                                ?>
                                                    <div class="card border border-danger text-dark">
                                                        <div class="card-block">
                                                            <h4 class="text-center text-danger">
                                                                There Are No Items In Cart
                                                            </h4>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
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
</body>

<!-- Scripts -->
<?php
require_once('../partials/scripts.php');
require_once('../partials/filter_js.php');
?>
</body>

</html>