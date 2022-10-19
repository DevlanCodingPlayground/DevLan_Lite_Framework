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
        $err="failed.";
    }

}
//update pet

//delete pet