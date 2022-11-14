<?php
/*
 *   Crafted On Sat Nov 05 2022
 *
 * 
 *   www.devlan.co.ke
 *   hello@devlan.co.ke
 *
 *
 *   The Devlan Solutions LTD End User License Agreement
 *   Copyright (c) 2022 Devlan Solutions LTD
 *
 *
 *   1. GRANT OF LICENSE 
 *   Devlan Solutions LTD hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 *   install and activate this system on two separated computers solely for your personal and non-commercial use,
 *   unless you have purchased a commercial license from Devlan Solutions LTD. Sharing this Software with other individuals, 
 *   or allowing other individuals to view the contents of this Software, is in violation of this license.
 *   You may not make the Software available on a network, or in any way provide the Software to multiple users
 *   unless you have first purchased at least a multi-user license from Devlan Solutions LTD.
 *
 *   2. COPYRIGHT 
 *   The Software is owned by Devlan Solutions LTD and protected by copyright law and international copyright treaties. 
 *   You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 *
 *   3. RESTRICTIONS ON USE
 *   You may not, and you may not permit others to
 *   (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 *   (b) modify, distribute, or create derivative works of the Software;
 *   (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 *   otherwise exploit the Software. 
 *
 *
 *   4. TERM
 *   This License is effective until terminated. 
 *   You may terminate it at any time by destroying the Software, together with all copies thereof.
 *   This License will also terminate if you fail to comply with any term or condition of this Agreement.
 *   Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 *
 *   5. NO OTHER WARRANTIES. 
 *   DEVLAN SOLUTIONS LTD  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 *   DEVLAN SOLUTIONS LTD SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 *   EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 *   SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 *   ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 *   INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 *   SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 *   THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 *   HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 *
 *   6. SEVERABILITY
 *   In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 *   affect the validity of the remaining portions of this license.
 *
 *
 *   7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN SOLUTIONS LTD OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 *   CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 *   USE OF THE SOFTWARE, EVEN IF DEVLAN SOLUTIONS LTD HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 *   IN NO EVENT WILL DEVLAN SOLUTIONS LTD  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 *   TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 *
 */

session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');



/* Get Pets Reports */
if ($_GET['module'] == 'Pets') {
    /* Get Summarized Report */
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    /* Excel File Name */
    $fileName = 'Pets.xls';

    /* Excel Column Name */
    $fields = array(
        '#',
        'Breed',
        'Type',
        'Age',
        'Owner Name',
        'Owner Email',
        'Owner Contacts',
        'Health Status',
        'Adoption Status',
        'Pet Details'
    );

    /* Implode Excel Data */
    $excelData = implode("\t", array_values($fields)) . "\n";

    /* Fetch All Records From The Database */
    $query = $mysqli->query("SELECT * FROM pet p INNER JOIN pet_owner po ON p.pet_owner_id = po.pet_owner_id");
    if ($query->num_rows > 0) {
        /* Load All Fetched Rows */
        $cnt = 1;
        while ($row = $query->fetch_assoc()) {
            $lineData = array(
                $cnt,
                $row['pet_breed'],
                $row['pet_type'],
                $row['pet_age'],
                $row['pet_owner_name'],
                $row['pet_owner_email'],
                $row['pet_owner_contacts'],
                $row['pet_health_status'],
                $row['pet_adoption_status'],
                $row['pet_description']
            );
            $cnt = $cnt + 1;
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No Pet Records Available...' . "\n";
    }

    /* Generate Header File Encodings For Download */
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    /* Render  Excel Data For Download */
    echo $excelData;

    exit;
} else if ($_GET['module'] == 'Staffs') {
    /* Get Staffs Details Report */
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    /* Excel File Name */
    $fileName = 'Staffs.xls';

    /* Excel Column Name */
    $fields = array(
        '#',
        'Full Names',
        'Login Username',
    );

    /* Implode Excel Data */
    $excelData = implode("\t", array_values($fields)) . "\n";

    /* Fetch All Records From The Database */
    $query = $mysqli->query("SELECT * FROM login l
    INNER JOIN admin a ON a.admin_login_id  = l.login_id");
    if ($query->num_rows > 0) {
        /* Load All Fetched Rows */
        $cnt = 1;
        while ($row = $query->fetch_assoc()) {
            $lineData = array(
                $cnt,
                $row['admin_name'],
                $row['login_username'],
            );
            $cnt = $cnt + 1;
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No Staffs Records Available...' . "\n";
    }

    /* Generate Header File Encodings For Download */
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    /* Render  Excel Data For Download */
    echo $excelData;

    exit;
} else if ($_GET['module'] == 'Owners') {
    /* Pet Owners Details Report */
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    /* Excel File Name */
    $fileName = 'Pet Owners.xls';

    /* Excel Column Name */
    $fields = array(
        '#',
        'Full Names',
        'Email',
        'Contacts',
        'Address'
    );

    /* Implode Excel Data */
    $excelData = implode("\t", array_values($fields)) . "\n";

    /* Fetch All Records From The Database */
    $query = $mysqli->query("SELECT * FROM login l
    INNER JOIN pet_owner pa ON pa.pet_owner_login_id  = l.login_id");
    if ($query->num_rows > 0) {
        /* Load All Fetched Rows */
        $cnt = 1;
        while ($row = $query->fetch_assoc()) {
            $lineData = array(
                $cnt,
                $row['pet_owner_name'],
                $row['pet_owner_email'],
                $row['pet_owner_contacts'],
                $row['pet_owner_address']
            );
            $cnt = $cnt + 1;
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No Pet Owner Records Available...' . "\n";
    }

    /* Generate Header File Encodings For Download */
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    /* Render  Excel Data For Download */
    echo $excelData;

    exit;
} else if ($_GET['module'] == 'Adopters') {
    /* Pet Adopters Details Report */
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    /* Excel File Name */
    $fileName = 'Pet Adopters.xls';

    /* Excel Column Name */
    $fields = array(
        '#',
        'Full Names',
        'Email',
        'Contacts',
        'Address'
    );

    /* Implode Excel Data */
    $excelData = implode("\t", array_values($fields)) . "\n";

    /* Fetch All Records From The Database */
    $query = $mysqli->query("SELECT * FROM login l
    INNER JOIN pet_adopter pa ON pa.pet_adopter_login_id  = l.login_id");
    if ($query->num_rows > 0) {
        /* Load All Fetched Rows */
        $cnt = 1;
        while ($row = $query->fetch_assoc()) {
            $lineData = array(
                $cnt,
                $row['pet_adopter_name'],
                $row['pet_adopter_email'],
                $row['pet_adopter_phone_number'],
                $row['pet_adopter_address']
            );
            $cnt = $cnt + 1;
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No Pet Adopters Records Available...' . "\n";
    }

    /* Generate Header File Encodings For Download */
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    /* Render  Excel Data For Download */
    echo $excelData;

    exit;
} else if ($_GET['module'] == 'Adoptions') {
    /* Adoptions */
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    /* Excel File Name */
    $fileName = 'Pet Adoptions.xls';

    /* Excel Column Name */
    $fields = array(
        '#',
        'Adoption Ref Number',
        'Adoption Date',
        'Adopter Full Names',
        'Adopter Email',
        'Adopter Contacts',
        'Adopter Address',
        'Pet Type',
        'Pet Breed',
        'Pet Age',
        'Pet Health Status',
        'Pet Owner Full Names',
        'Pet Owner Email',
        'Pet Owner Contacts',
        'Pet Owner Address',
        'Adoption Payment Status',
        'Adoption Return Status'
    );

    /* Implode Excel Data */
    $excelData = implode("\t", array_values($fields)) . "\n";

    /* Fetch All Records From The Database */
    $query = $mysqli->query("SELECT * FROM pet_adoption pa 
    INNER JOIN pet p ON p.pet_id = pa.pet_adoption_pet_id 
    INNER JOIN pet_owner po ON po.pet_owner_id = p.pet_owner_id 
    INNER JOIN pet_adopter pad ON pad.pet_adopter_id = pa.pet_adoption_pet_adopter_id");
    if ($query->num_rows > 0) {
        /* Load All Fetched Rows */
        $cnt = 1;
        while ($row = $query->fetch_assoc()) {
            $lineData = array(
                $cnt,
                $row['pet_adoption_ref'],
                date('d M Y', strtotime($row['pet_adoption_date'])),
                $row['pet_adopter_name'],
                $row['pet_adopter_email'],
                $row['pet_adopter_phone_number'],
                $row['pet_adopter_address'],
                $row['pet_type'],
                $row['pet_breed'],
                $row['pet_age'],
                $row['pet_health_status'],
                $row['pet_owner_name'],
                $row['pet_owner_email'],
                $row['pet_owner_contacts'],
                $row['pet_owner_address'],
                $row['pet_adoption_payment_status'],
                $row['pet_adoption_return_status']
            );
            $cnt = $cnt + 1;
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No Pet Adopters Records Available...' . "\n";
    }

    /* Generate Header File Encodings For Download */
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    /* Render  Excel Data For Download */
    echo $excelData;

    exit;
} else if ($_GET['module'] == 'Payments') {
    /* Get Payment Reports */
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }

    /* Excel File Name */
    $fileName = 'Pet Adoptions Payments.xls';

    /* Excel Column Name */
    $fields = array(
        '#',
        'Payment Ref Number',
        'Payment Amount',
        'Payment Date',
        'Payment Means',
        'Adoption Ref Number',
        'Adoption Date',
        'Adopter Full Names',
        'Adopter Email',
        'Adopter Contacts',
        'Adopter Address',
        'Pet Type',
        'Pet Breed',
        'Pet Age',
        'Pet Health Status',
        'Pet Owner Full Names',
        'Pet Owner Email',
        'Pet Owner Contacts',
        'Pet Owner Address',
        'Adoption Payment Status',
        'Adoption Return Status'
    );

    /* Implode Excel Data */
    $excelData = implode("\t", array_values($fields)) . "\n";

    /* Fetch All Records From The Database */
    $query = $mysqli->query("SELECT * FROM payment pay 
    INNER JOIN pet_adoption pa ON pa.pet_adoption_id = pay.payment_pet_adoption_id 
    INNER JOIN pet p ON p.pet_id = pa.pet_adoption_pet_id 
    INNER JOIN pet_owner po ON po.pet_owner_id = p.pet_owner_id 
    INNER JOIN pet_adopter pad ON pad.pet_adopter_id = pa.pet_adoption_pet_adopter_id");
    if ($query->num_rows > 0) {
        /* Load All Fetched Rows */
        $cnt = 1;
        while ($row = $query->fetch_assoc()) {
            $lineData = array(
                $cnt,
                $row['payment_ref'],
                $row['payment_amount'],
                date('d M Y g:ia', strtotime($row['payment_date'])),
                $row['payment_means'],
                $row['pet_adoption_ref'],
                date('d M Y', strtotime($row['pet_adoption_date'])),
                $row['pet_adopter_name'],
                $row['pet_adopter_email'],
                $row['pet_adopter_phone_number'],
                $row['pet_adopter_address'],
                $row['pet_type'],
                $row['pet_breed'],
                $row['pet_age'],
                $row['pet_health_status'],
                $row['pet_owner_name'],
                $row['pet_owner_email'],
                $row['pet_owner_contacts'],
                $row['pet_owner_address'],
                $row['pet_adoption_payment_status'],
                $row['pet_adoption_return_status']
            );
            $cnt = $cnt + 1;
            array_walk($lineData, 'filterData');
            $excelData .= implode("\t", array_values($lineData)) . "\n";
        }
    } else {
        $excelData .= 'No Pet Adopters Records Available...' . "\n";
    }

    /* Generate Header File Encodings For Download */
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    /* Render  Excel Data For Download */
    echo $excelData;

    exit;
} else {
    /* End This User Session */
    $_SESSION['err'] = 'Kindly stop messing with my urls';
    header('Location: logout');
    exit;
}
