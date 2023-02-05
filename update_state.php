<?php
  include_once 'connectBD.php';

  $productId = $_POST['id'];
  $state = $_POST['state'];

  $query = "UPDATE list_product SET state = $state WHERE id = $productId";
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false]);
  }
?>
