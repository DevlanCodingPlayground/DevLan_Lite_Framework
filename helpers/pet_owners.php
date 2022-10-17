<?php
//add pet owner
if (isset($_POST['Add_PetOwner'])) {
    $pet_owner_name = mysqli_real_escape_string($mysqli, $_POST['pet_owner_name']);
    $pet_owner_email = mysqli_real_escape_string($mysqli, $_POST['pet_owner_email']);
    $pet_owner_contacts = mysqli_real_escape_string($mysqli, $_POST['pet_owner_contacts']);
    $pet_owner_address = mysqli_real_escape_string($mysqli, $_POST['pet_owner_address']);

    /* Auth Variables */
    $login_username = mysqli_real_escape_string($mysqli, $_POST['login_username']);
    $login_password = md5(mysqli_real_escape_string($mysqli, $_POST['login_password']));
    $login_rank = mysqli_real_escape_string($mysqli, 'Owner');

    /* Prevent Double submissions */
    $sql = "SELECT * FROM  login   WHERE login_username = '{$login_username}'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (
            $login_username == $row['login_username']
        ) {
            $err = 'Login username already taken';
        } else {
            $err = "Login username already taken";
        }
    } else {
        /* Persist Auth Record */
        $auth_sql = "INSERT INTO login (login_username, login_password, login_rank) VALUES('{$login_username}', '{$login_password}', '{$login_rank}')";
        if (mysqli_query($mysqli, $auth_sql)) {
            /* Get Newly Inserted Login Id */
            $login_id = $mysqli->insert_id;

            /* Persist Owner Record */
            $owner_sql = "INSERT INTO pet_owner(pet_owner_login_id, pet_owner_name, pet_owner_email, pet_owner_contacts, pet_owner_address)
            VALUES('{$login_id}', '{$pet_owner_name}', '{$pet_owner_email}', '{$pet_owner_contacts}', '{$pet_owner_address}')";

            if (mysqli_query($mysqli, $owner_sql)) {
                $success = "Account has been created successfuly";
            }
        } else {
            $err = "Failed saving login information, please try again";
        }
    }
}
//update pet owner
if (isset($_POST["Update_PetOwner"])) {
    $pet_owner_name = mysqli_real_escape_string($mysqli, $_POST['pet_owner_name']);
    $pet_owner_email = mysqli_real_escape_string($mysqli, $_POST['pet_owner_email']);
    $pet_owner_contacts = mysqli_real_escape_string($mysqli, $_POST['pet_owner_contacts']);
    $pet_owner_address = mysqli_real_escape_string($mysqli, $_POST['pet_owner_address']);
    $pet_owner_id = mysqli_real_escape_string($mysqli, $_POST['pet_owner_id']);

    $update_sql = "UPDATE pet_owner SET pet_owner_name='{$pet_owner_name}', pet_owner_email='{$pet_owner_email}',pet_owner_contacts='{$pet_owner_contacts}', pet_owner_address='{$pet_owner_address}' WHERE pet_owner_id= '{$pet_owner_id}'";

    if (mysqli_query($mysqli, $update_sql)) {
        $success = "Account has been updated successfuly";
    } else {
        $err = "Failed saving login information, please try again";
    }
}
//delete pet owner
if (isset($_POST["Delete_PetOwner"])) {
    $login_id= mysqli_real_escape_string($mysqli, $_POST['login_id']);

   $delete_sql="DELETE FROM login WHERE login_id='{$login_id}'";

    if (mysqli_query($mysqli, $delete_sql)) {
        $success = "Account has been deleted successfuly";
    } else {
        $err = "Failed saving login information, please try again";
    }
}