<?php

/* Login */
if (isset($_POST['Login'])) {
    $login_username = mysqli_real_escape_string($mysqli, $_POST['login_username']);
    $login_password = md5(mysqli_real_escape_string($mysqli, $_POST['login_password']));

    /* Process Login */
    $stmt = $mysqli->prepare("SELECT login_username, login_password, login_rank, login_id FROM login 
    WHERE login_username = '{$login_username}' AND login_password = '{$login_password}'");
    $stmt->execute();
    $stmt->bind_result($login_username, $login_password, $login_rank, $login_id);
    $rs = $stmt->fetch();

    /* Session Variables */
    $_SESSION['login_id'] = $login_id;
    $_SESSION['login_rank'] = $login_rank;

    if (($rs && $login_rank == "administrator")) {
        /* Pass This Alert Via Session */
        $_SESSION['success'] = 'You Have Successfully Logged In';
        header('Location: dashboard');
        exit;
    } elseif ($rs && $login_rank == "Owner") {
        $_SESSION['success'] = 'Successfully logged in as pet owner';
        header('Location: pet_owner_dashboard');
        exit;
    } elseif ($rs && $login_rank == "Adopter") {
        $_SESSION['success'] = 'Successfully logged in as pet adopter';
        header('Location: adopter_dashboard');
        exit;
    } else {
        $err = "Access Denied Please Check Your Email Or Password";
    }
}

 /* Sign Up As Pet Adopter */
 if (isset($_POST['Register_Adopter'])) {
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
                /* Redirect To Login After Successful Sign Up */
                $_SESSION['success'] = 'Account created successfully';
                header('Location: ../');
                exit;
            }
        } else {
            $err = "Failed saving login information, please try again";
        }
    }
}

 /* Sign Up As Pet Owner */
 if (isset($_POST['Register_Pet_Owner'])) {
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
        }
        else{
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
                /* Redirect To Login After Successful Sign Up */
                $_SESSION['success'] = 'Account created successfully';
                header('Location: ../');
                exit;
            }
        } else {
            $err = "Failed saving login information, please try again";
        }
    }
}
/* Reset Password Step 1 */
if (isset($_POST['Reset_Password_Step_1'])) {
    $login_username = mysqli_real_escape_string($mysqli, $_POST['login_username']);
    /* Check If User Exists */
    $sql = "SELECT * FROM  login WHERE login_username = '{$login_username}'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        /* Redirect User To Confirm Password */
        $_SESSION['success'] = 'Password Reset Token Generated, Proceed To Confirm Password';
        $_SESSION['login_username'] = $login_username;
        header('Location: confirm_password');
        exit;
    } else {
        $err = "Username Does Not Exist";
    }
}

/* Reset Password Step 2 */
if (isset($_POST['Reset_Password_Step_2'])) {
    $login_username = mysqli_real_escape_string($mysqli, $_SESSION['login_username']);
    $new_password = (md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password =(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));

    /* Check If They Match */
    if ($new_password != $confirm_password) {
        $err = "Passwords Does Not Match";
    } else {
        $sql = "UPDATE login SET login_password = '{$confirm_password}' WHERE login_username = '{$login_username}'";

        if (mysqli_query($mysqli, $sql)) {
            /* Pass This Alert Via Session */
            $_SESSION['success'] = 'Your Password Has Been Reset Proceed To Login';
            header('Location: index');
            exit;
        } else {
            $err = "Failed!, Please Try Again";
        }
    }
}
