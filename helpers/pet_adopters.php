<?php
//add pet adopter
if (isset($_POST['Register_Pet_Adopter'])) {
    $pet_adopter_name = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_name']);
    $pet_adopter_email = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_email']);
    $pet_adopter_phone_number = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_phone_number']);
    $pet_adopter_address = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_address']);

    /* Auth Variables */
    $login_username = mysqli_real_escape_string($mysqli, $_POST['login_username']);
    $login_password = md5(mysqli_real_escape_string($mysqli, $_POST['login_password']));
    $login_rank = mysqli_real_escape_string($mysqli, 'Adopter');

    /* Prevent Double submissions */
    $sql = "SELECT * FROM  login   WHERE login_username = '{$login_username}'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (
            $login_username == $row['login_username']
        ) {
            $err = 'Login username already taken';
        }
    } else {
        /* Persist Auth Record */
        $auth_sql = "INSERT INTO login (login_username, login_password, login_rank) VALUES('{$login_username}', '{$login_password}', '{$login_rank}')";
        if (mysqli_query($mysqli, $auth_sql)) {
            /* Get Newly Inserted Login Id */
            $login_id = $mysqli->insert_id;

            /* Persist Adopter Record */
            $adopter_sql = "INSERT INTO pet_adopter(pet_adopter_login_id, pet_adopter_name, pet_adopter_email, pet_adopter_phone_number, pet_adopter_address)
            VALUES('{$login_id}', '{$pet_adopter_name}', '{$pet_adopter_email}', '{$pet_adopter_phone_number}', '{$pet_adopter_address}')";

            if (mysqli_query($mysqli, $adopter_sql)) {
                $success = "Pet Adopter is sucessfully registered!";
            }
        } else {
            $err = "Failed saving login information, please try again";
        }
    }
}
//update pet adopter
if (isset($_POST['update_pet_adopter'])) {
    $pet_adopter_name = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_name']);
    $pet_adopter_email = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_email']);
    $pet_adopter_phone_number = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_phone_number']);
    $pet_adopter_address = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_address']);
    $pet_adopter_id = mysqli_real_escape_string($mysqli, $_POST['pet_adopter_id']);

    $update_sql = "UPDATE pet_adopter SET pet_adopter_name='{$pet_adopter_name}',pet_adopter_email='{$pet_adopter_email}',pet_adopter_phone_number='{$pet_adopter_phone_number}',
    pet_adopter_address='{$pet_adopter_address}' WHERE pet_adopter_id='{$pet_adopter_id}'";

    if (mysqli_query($mysqli, $update_sql)) {
        $success = "Pet Adopter is sucessfully updated!";
    } else {
        $err = "Failed saving login information, please try again";
    }
}

//delete pet adopter
if (isset($_POST['delete_pet_adopter'])) {
    $login_id = mysqli_real_escape_string($mysqli, $_POST['login_id']);
    $delete_sql = "DELETE FROM login WHERE login_id = '{$login_id}'";

    if (mysqli_query($mysqli, $delete_sql)) {
        $success = "Pet Adopter is sucessfully deleted!";
    } else {
        $err = "Failed saving login information, please try again";
    }
}

//change passwrd

if (isset($_POST['Update_Pet_adopter_Password'])) {
    $login_id = mysqli_real_escape_string($mysqli, $_SESSION['login_id']);
    $new_password = md5(mysqli_real_escape_string($mysqli, $_POST['new_password']));
    $confirm_password = md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password']));
    $login_username = mysqli_real_escape_string($mysqli, $_POST['login_username']);

    //Check If passwords match
    if ($new_password != $confirm_password) {
        $err = "Password does not match";
    } else {
        $sql = "UPDATE login SET login_username = '{$login_username}', login_password = '{$confirm_password}' WHERE login_id = '{$login_id}'";
        if (mysqli_query($mysqli, $sql)) {
            $success = "Password updated";
        } else {
            $err = "Please try again later";
        }
    }
}
