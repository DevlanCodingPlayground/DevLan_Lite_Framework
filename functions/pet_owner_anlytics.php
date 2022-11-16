<?php
$pet_owner_login_id = mysqli_real_escape_string($mysqli, $_SESSION['login_id']);

$ret = "SELECT * FROM login l 
INNER JOIN pet_owner po ON po.pet_owner_login_id = l.login_id 
WHERE l.login_id = '{$pet_owner_login_id}'";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($user = $res->fetch_object()) {

    $owner_id = $user->pet_owner_id;

    //1. Total pets
    $query = "SELECT COUNT(*)  FROM pet WHERE pet_owner_id ='{$owner_id}' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($pets);
    $stmt->fetch();
    $stmt->close();

    //2. Adopted Pets
    $query = "SELECT COUNT(*)  FROM pet WHERE pet_owner_id ='{$owner_id}'
    AND pet_adoption_status ='Adopted' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($adopted_pets);
    $stmt->fetch();
    $stmt->close();

    //3. Available Pets
    $query = "SELECT COUNT(*)  FROM pet WHERE pet_owner_id ='{$owner_id}'
    AND pet_adoption_status !='Adopted' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($available_pets);
    $stmt->fetch();
    $stmt->close();

    //4. Revenue
    $query = "SELECT SUM(payment_amount)  FROM payment p
    INNER JOIN pet_adoption pa ON pa.pet_adoption_id = p.payment_pet_adoption_id
    INNER JOIN pet pe ON pe.pet_id = pa.pet_adoption_pet_id
    WHERE pe.pet_owner_id = '{$owner_id}'";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($payment_amount);
    $stmt->fetch();
    $stmt->close();
}
