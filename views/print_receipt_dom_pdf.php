<?php
/*
 * Created on Wed Jan 26 2022
 *
 *  Devlan - devlan.co.ke 
 *
 * hello@devlan.info
 *
 *
 * The Devlan End User License Agreement
 *
 * Copyright (c) 2022 Devlan
 *
 * 1. GRANT OF LICENSE
 * Devlan hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 * install and activate this system on two separated computers solely for your personal and non-commercial use,
 * unless you have purchased a commercial license from Devlan. Sharing this Software with other individuals, 
 * or allowing other individuals to view the contents of this Software, is in violation of this license.
 * You may not make the Software available on a network, or in any way provide the Software to multiple users
 * unless you have first purchased at least a multi-user license from Devlan.
 *
 * 2. COPYRIGHT 
 * The Software is owned by Devlan and protected by copyright law and international copyright treaties. 
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
 * Devlan  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 * Devlan SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
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
 * 7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN  OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 * CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 * USE OF THE SOFTWARE, EVEN IF DEVLAN HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 * IN NO EVENT WILL DEVLAN  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 * TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 */
session_start();
require_once '../config/config.php';
require_once '../config/checklogin.php';
require_once '../config/codeGen.php';
check_login();
require_once('../vendor/autoload.php');

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$user_id = $_SESSION['user_id'];
$total_quantity = 0;
$total_price = 0;


$date = new DateTime("now", new DateTimeZone('EAT'));
/* Get Class Details And Class Details */

$html = '
<style>
@page {
    margin: 0px 0px 6px 0px !important;
    padding: 0px 0px 0px 0px !important;
}
.heading{
    letter-spacing: 1px;
    text-align:center;
}
.break-text{
    inline-size: 150px;
}
</style>
<body>
    <div>
        <h4 class="heading" style="font-size:10pt"><strong>
                MASTERPIECE AQUA WORKS LTD <br>
                Electrical & Hardware Supplies <br>
                Tel: 0718 086 655 <br>
                P.O. Box 396-90100, Machakos <br>
                VAT: P051737290V <br>
                Receipt No. '. $_GET["print_receipt"].' <br>
                Customer : '. $_GET["customer"].' <br>
                Date: '. $date->format('d M Y H:i').'
            </strong>
        </h4>
    </div>
    <hr>
    ';
    $html .= '
    <table cellspacing="5"  style="font-size:8.4pt">
        <thead>
            <tr>
                <th style="text-align:left;" width="2%">SL</th>
                <th width="100%" style="text-align:left;"><strong>ITEM DESC</strong></th>
                <th width="100%" style="text-align:right;"><strong>TOTAL</strong></th>
            </tr>
        </thead>
        ';
        $cnt = 1;
        foreach ($_SESSION["cart_item"] as $item) {
        $item_price = $item["quantity"] * $item["product_sale_price"];
        $html .=
        '
        <tr>
            <td style="text-align:left;"><strong>' .$cnt . '. </strong></td>
            <td style="text-align:left; overflow-wrap: break-word">
                <strong>
                    '. $item["product_name"] . '<br>
                    ' . $item["quantity"] . ' x Ksh ' . number_format($item["product_sale_price"], 2) . '
                </strong>
            </td>
            <td style="text-align:right;"><strong>' . "Ksh " . number_format($item_price, 2) . '</strong></td>
        </tr>
        ';
        $total_quantity += $item["quantity"];
        $total_price += ($item["product_sale_price"] * $item["quantity"]);
        $cnt ++;
        }
        $html .= '
        <tr>
            <td colspan="1"><strong>TOTAL:</strong></td>
            <td style="text-align:right;" colspan="2"><strong>Ksh ' . number_format($total_price, 2).'</strong></td>
        </tr>
        <br><br>
        ';
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
        $users = mysqli_fetch_assoc($res);
        $html .= '
        <p align="center"><i>Powered By DevLan Solutions LTD</i></p>
        <p align="center"><strong>You Were Served By '. $users['user_name'].'<strong></p>
        <hr>
        ';
        }
        $html .= '
</body>
';
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper(array(0, 0, 203, 650));
$dompdf->set_option('dpi', 203);
//$dompdf->set_paper('A4');
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->render();
$dompdf->stream('Sale Receipt ' . $_GET["print_receipt"], array("Attachment" => 1));
$options = $dompdf->getOptions();
$options->setDefaultFont('');
$dompdf->setOptions($options);
