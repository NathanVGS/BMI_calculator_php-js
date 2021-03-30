<?php
session_start();
require_once '../classes/DB.php';

$user = $_SESSION['logged-in-user'];
//$dateFormat = 'd/m/Y';
$dateFormat = 'Y/m/d';

$db = new \Custom\DB();
$connection = $db->connect();

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $connection->prepare('SELECT id FROM user WHERE email = :email');
$statement->bindValue('email', $user);
$statement->execute();
$dbUser = $statement->fetch(PDO::FETCH_ASSOC);
$id = (int)$dbUser['id'];

//reset height or weight for user TODO let user delete/update weight for specific dates?
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])){
    $reset = $_POST['reset'];

    if($reset === 'height'){
        $resetHeight = $connection->prepare('UPDATE user SET height = null WHERE id = ?');
        $resetHeight->execute([$id]);
    }

    if($reset === 'weight'){
        $resetHeight = $connection->prepare('DELETE FROM weight WHERE userId = ?');
        $resetHeight->execute([$id]);
    }

    return;
}

//set user weight per date //TODO let user choose if he wants to replace weight for a day he already has stored a value
if($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['height'])){

    $date = new DateTimeImmutable($_POST['date']);
    $date = $date->format($dateFormat);
    $weight = (float)$_POST['weight'];

    $checkDate = $connection->prepare('SELECT * FROM weight WHERE date = ? AND userId = ?');
    $checkDate->execute([$date, $id]);

    if(!$checkDate->fetch(PDO::FETCH_ASSOC)){
        $insertWeight = $connection->prepare('INSERT INTO weight (userId, weight, date) VALUES (?, ?, ?)');
        $insertWeight->execute([$id, $weight, $date]);
    } //TODO else: update data for date?

    header('Content-Type: application/json');
    echo json_encode(['date' => $date, 'weight' => $weight, 'user' => $user, 'db' => $id]);
    return;
}

//set user height (invariable height)
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['height'])){
    $height = (int)$_POST['height'];

    $setHeight = $connection->prepare('UPDATE user SET height = ? WHERE id = ?');
    $setHeight->execute([$height, $id]);

    header('Content-Type: application/json');
    echo json_encode(['height' => $height, 'user' => $user, 'db' => $id]);
    return;
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $statement = $connection->prepare('SELECT weight,date FROM weight WHERE userId = ? ORDER BY date');
    $statement->execute([$id]);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([$result][0]);
    return;
}

