<?php
$petowner_login_id = mysqli_real_escape_string($mysqli, $_SESSION['login_id']);

$ret = "SELECT * FROM login l INNER JOIN pet_adopter pa ON pa.pet_adopter_login_id  = l.login_id
WHERE l.login_id = '{$petowner_login_id}'";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($user = $res->fetch_object()) {

    $petowner_id = $user->pet_owner_id;

    //1. Total pets

    //2. Adopted Pets

    //3. Available Pets

    //4. Revenue
}
