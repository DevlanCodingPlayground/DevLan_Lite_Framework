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
    } elseif ($rs && $user_access_level == "owner") {
        $_SESSION['success'] = 'Successfully logged in as pet owner';
        header('Location: owner_dashboard');
        exit;
    } elseif ($rs && $user_access_level == "adopter") {
        $_SESSION['success'] = 'Successfully logged in as pet adopter';
        header('Location: adopter_dashboard');
        exit;
    } else {
        $err = "Access Denied Please Check Your Email Or Password";
    }
}

 /* Sign Up As Pet Adopter */

 /* Sign Up As Pet Owner */

 /* Reset Password Step 1 */

 /* Reset Password Step 2 */
 