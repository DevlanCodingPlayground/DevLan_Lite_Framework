<?php
if (isset($_POST['delete_payment'])) {
    $pet_adoption_id = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_id']);
    $payment_id = mysqli_real_escape_string($mysqli, $_POST['payment_id']);

    $update_sql = "UPDATE pet_adoption SET pet_adoption_payment_status= 'Pending' WHERE pet_adoption_id = '{$pet_adoption_id}'";
    $delete_sql = "DELETE FROM payment WHERE payment_id='{$payment_id}'";


    if (mysqli_query($mysqli, $update_sql) && mysqli_query($mysqli, $delete_sql)) {
        $success = "Payment deleted";
    } else {
        $err = "Sorry.failed to update adoption record.";
    }
}