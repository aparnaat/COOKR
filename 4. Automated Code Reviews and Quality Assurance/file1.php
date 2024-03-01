$hashedPassword = password_hash($userInputPassword, PASSWORD_BCRYPT);
$result = password_verify($userInputPassword, $hashedPassword);