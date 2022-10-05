<?php
/* Pet Owners */
$query = "SELECT COUNT(*)  FROM pet_owner ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($pet_owners);
$stmt->fetch();
$stmt->close();


/* Pet Adopters */
$query = "SELECT COUNT(*)  FROM pet_adopter ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($pet_adopters);
$stmt->fetch();
$stmt->close();

/* Registered Pets */
$query = "SELECT COUNT(*)  FROM pet ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($pets);
$stmt->fetch();
$stmt->close();


/* Successful Adoptions */
$query = "SELECT COUNT(*)  FROM pet_adoption ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($pet_adoptions);
$stmt->fetch();
$stmt->close();


/* Available Pets */
$query = "SELECT COUNT(*)  FROM pet WHERE pet_adoption_status = 'Pending' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($available_pets);
$stmt->fetch();
$stmt->close();


/* Total Amount */
$query = "SELECT SUM(payment_amount)  FROM payment ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($payment_amount);
$stmt->fetch();
$stmt->close();
?>