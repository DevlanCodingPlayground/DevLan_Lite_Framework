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
              $success="Pet Adopter is sucessfully registered!";
            }
        } else {
            $err = "Failed saving login information, please try again";
        }
    }
}
//update pet adopter

//delete pet adopter