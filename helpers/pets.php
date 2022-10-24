<?php
//add pet
if (isset($_POST['add_pet'])) {
    $pet_owner_id = mysqli_real_escape_string($mysqli, $_POST['pet_owner_id']);
    $pet_type = mysqli_real_escape_string($mysqli, $_POST['pet_type']);
    $pet_breed = mysqli_real_escape_string($mysqli, $_POST['pet_breed']);
    $pet_age = mysqli_real_escape_string($mysqli, $_POST['pet_age']);
    $pet_health_status = mysqli_real_escape_string($mysqli, $_POST['pet_health_status']);
    $pet_description = mysqli_real_escape_string($mysqli, $_POST['pet_description']);
    $pet_adoption_staus = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_status']);

    $insert_sql = "INSERT INTO pet (pet_owner_id,pet_type,pet_breed,pet_age, pet_health_status,pet_description,pet_adoption_status)
    VALUES ('{$pet_owner_id}','{$pet_type}','{$pet_breed}','{$pet_age}','{$pet_health_status}','{$pet_description}','{$pet_adoption_status}')";

    if (mysqli_query($mysqli, $insert_sql)) {
        $success = "pet registered successfully";
    } else {
        $err="Sorry.failed to register pet.";
    }

}
//update pet
if (isset($_POST['update_pet'])) {
    $pet_id = mysqli_real_escape_string($mysqli, $_POST['pet_id']);
    $pet_type = mysqli_real_escape_string($mysqli, $_POST['pet_type']);
    $pet_breed = mysqli_real_escape_string($mysqli, $_POST['pet_breed']);
    $pet_age = mysqli_real_escape_string($mysqli, $_POST['pet_age']);
    $pet_health_status = mysqli_real_escape_string($mysqli, $_POST['pet_health_status']);
    $pet_description = mysqli_real_escape_string($mysqli, $_POST['pet_description']);
    $pet_adoption_staus = mysqli_real_escape_string($mysqli, $_POST['pet_adoption_status']);

    $update_sql = "UPDATE pet SET pet_type='{$pet_type}', pet_breed='{$pet_breed}', pet_age='{$pet_age}', pet_health_status='{$pet_health_status}', pet_description='{$pet_description}', pet_adoption_status='{$pet_adoption_status}' WHERE pet_id='{$pet_id}' ";
    if (mysqli_query($mysqli, $update_sql)) {
        $success = "pet updated successfully";
    } else {
        $err="Sorry.failed to update pet.";
    }

}
//delete pet