<?php
// update adoption 
if(isset($_POST['update_pet_adoption'])){
    $pet_adoption_id = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_id']);
    $pet_adoption_date = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_date']);

    $update_sql = "UPDATE pet_adoption SET pet_adoption_date= '{$pet_adoption_date}' WHERE pet_adoption_id = '{$pet_adoption_id}'";
    if (mysqli_query($mysqli, $update_sql)) {
        $success = "Pet adoption updated.";
    } else {
        $err = "Sorry.failed to update adoption record.";
    }

}
//return pet
if(isset($_POST['return_pet'])){
    $pet_adoption_id = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_id']);
    $pet_id = mysqli_real_escape_string($mysqli, $_POST['pet_id']);

    $update_sql = "UPDATE pet_adoption SET pet_adoption_return_status= 'Returned' WHERE pet_adoption_id = '{$pet_adoption_id}'";
    $update_pet_sql = "UPDATE pet SET pet_adoption_status= 'Available' WHERE pet_id = '{$pet_id}'";


    if (mysqli_query($mysqli, $update_sql) && mysqli_query($mysqli, $update_pet_sql) ) {
        $success = "Pet returned";
    } else {
        $err = "Sorry.failed to update adoption record.";
    }

}
/* Add Payment */
if (isset($_POST['Add_Payment'])) {
    $payment_pet_adoption_id = mysqli_real_escape_string($mysqli, $_POST['payment_pet_adoption_id']);
    $payment_ref = mysqli_real_escape_string($mysqli, $paycode);
    $payment_amount = mysqli_real_escape_string($mysqli, '500');
    $payment_means  = mysqli_real_escape_string($mysqli, $_POST['payment_means']);

    /* Rave Payment Variables */
    $pet_adopter_name = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_name']);
    $pet_adopter_email = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_email']);

    if ($payment_means == 'Cash') {
        /* Persist */
        $payment_sql = "INSERT INTO payment (payment_pet_adoption_id, payment_ref, payment_amount, payment_means) 
        VALUES('{$payment_pet_adoption_id}', '{$payment_ref}', '{$payment_amount}', '{$payment_means}')";
        $adoption_sql = "UPDATE pet_adoption SET pet_adoption_payment_status = 'Paid' WHERE pet_adoption_id = '{$payment_pet_adoption_id}'";

        if (mysqli_query($mysqli, $payment_sql) && mysqli_query($mysqli, $adoption_sql)) {
            $success = "Cash Payment Ref: $paycode Posted";
        } else {
            $err = "Failed, please try again";
        }
    } else if ($payment_means == 'Credit / Debit Card') {
        /* Handle Credit/Debit Card - To Avoid Messy Codebases Just Include The File Here */
        include('../api/flutterwave/process_payment.php');
    } else {
        /* Handle Mobile Payments - To Avoid Messy Codebases Just Include The File Here */
        $err = "Payment method is not supported";
    }
}
//delete
if(isset($_POST['delete_adoption'])){
    $pet_adoption_id = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_id']);
    $pet_id = mysqli_real_escape_string($mysqli, $_POST['pet_id']);

    $delete_sql = "DELETE FROM pet_adoption WHERE pet_adoption_id = '{$pet_adoption_id}'";
    $update_pet_sql = "UPDATE pet SET pet_adoption_status= 'Available' WHERE pet_id = '{$pet_id}'";


    if (mysqli_query($mysqli, $delete_sql) && mysqli_query($mysqli, $update_pet_sql) ) {
        $success = "Pet adoption record deleted";
    } else {
        $err = "Sorry.failed to update adoption record.";
    }

}
