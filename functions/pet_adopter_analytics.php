<?php
$pet_adopter_login_id = mysqli_real_escape_string($mysqli, $_SESSION['login_id']);

$ret = "SELECT * FROM login l 
INNER JOIN pet_adopter pa ON pa.pet_adopter_login_id = l.login_id 
WHERE l.login_id = '{$pet_adopter_login_id}'";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($user = $res->fetch_object()) {

    $adopter_id = $user->pet_adopter_id;

    //1. Available pets
    $query = "SELECT COUNT(*)  FROM pet WHERE  pet_adoption_status !='Adopted' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($available_pets);
    $stmt->fetch();
    $stmt->close();

    //2. Adoptions
    $query = "SELECT COUNT(*) FROM pet_adoption WHERE pet_adoption_pet_adopter_id = '{$adopter_id }'";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($adopted_pets);
    $stmt->fetch();
    $stmt->close();

    //3.Expenditure
    $query = "SELECT SUM(payment_amount)  FROM payment p
    INNER JOIN pet_adoption pa ON pa.pet_adoption_id = p.payment_pet_adoption_id
    WHERE pa.pet_adoption_pet_adopter_id = '{$adopter_id}'";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($expenditure);
    $stmt->fetch();
    $stmt->close();
}
