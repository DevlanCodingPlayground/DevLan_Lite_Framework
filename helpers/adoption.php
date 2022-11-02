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

//pay

//delete