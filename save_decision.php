<?php
// error_reporting(E_ALL^E_WARNING^E_NOTICE);
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if ( !isset($_SESSION['u']) || empty($_SESSION['u']) ) {
  header('Location: index.php');
  exit();
}

// db connection
try {
  $db = new PDO('mysql:host=localhost;dbname=crowdica', 'root', '');
} catch (PDOException $e) {
  print "Database error!: " . $e->getMessage() . "<br/>";
  die();
}

$error = '';
if ( isset( $_POST['id'] ) && $_POST['id'] && isset( $_POST['label_1'] ) && $_POST['label_1'] ) {
  try {
    // db entry
    $db->prepare("INSERT INTO decisions (user_id, image_id, volume, label_1, comment)  VALUES (?, ?, ?, ?, ?)")
      ->execute([$_SESSION['u'], $_POST['id'], $_POST['volume'], $_POST['label_1'], $_POST['comment']]);
  }
  catch (PDOException $e) {
    $error = $e->getMessage();
  }
}
else {
  $error = 'Invalid parameters!';
}

$data = [
  'error' => $error
];
header('Content-Type: application/json');
echo json_encode($data);
exit();