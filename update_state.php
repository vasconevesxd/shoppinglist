<?php

$conn = include("connectBD.php"); 

// verifica se o botão Acquire foi clicado
if (isset($_POST['acquire'])) {
  // recupera o ID do produto a ser atualizado
  $id = mysqli_real_escape_string($conn, $_POST['id']);

  // consulta o estado atual do produto
  $sql = "SELECT state FROM list_product WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $state = mysqli_fetch_assoc($result)['state'];

  // inverte o estado (0 para 1 ou 1 para 0)
  $new_state = ($state == 0) ? 1 : 0;

  // atualiza o estado no banco de dados
  $sql = "UPDATE list_product SET state = '$new_state' WHERE id = '$id'";
  mysqli_query($conn, $sql);

  // redireciona de volta para a página anterior
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}

?>
