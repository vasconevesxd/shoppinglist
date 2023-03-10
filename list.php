<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Shopping List</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

</head>
<body>

<?php

  session_start();
  if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: signin.php');
    exit;
  }

  $conn = include("connectBD.php"); 

  // Verifica se a conexão foi bem-sucedida
  if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
  }

  // Validação do id da lista
  $list_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  if ($list_id <= 0) {
    die("ID da lista inválido");
  }

  // Obtém as informações da lista
  $sql = "SELECT * FROM list WHERE id = $list_id";
  $list = mysqli_query($conn, $sql);
  if (!$list || mysqli_num_rows($list) == 0) {
    die("Lista não encontrada");
  }
  $list = mysqli_fetch_assoc($list);
  $list_name = $list['name'];

  // Obtém o ID do produto se ele existir
  $product_id = 1;
  $values = isset($_GET['productid']) ? $_GET['productid'] : '';
  if (!empty($values)) {
    if (preg_match('/list-(.*?)-product/', $values , $match) == 1) {
      $list_id = intval($match[1]);
    }
    if (preg_match('/product-(.*?)-/', $values , $match) == 1) {
      $product_id = intval($match[1]);
    }
  }

  // Obtém a lista de produtos
  $sql = "SELECT list_product.list_id as list_id,  list_product.state  as list_state ,product.*, category.name as category_name FROM product
    JOIN list_product ON list_product.product_id = product.id
    JOIN category ON product.category_id = category.id
    WHERE list_product.list_id = $list_id";
  $products = mysqli_query($conn, $sql);

  // Obtém informações do usuário logado
  $email = $_SESSION['email'];
  $sql = "SELECT * FROM user WHERE email=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if (mysqli_num_rows($result) > 0) {
      // Output data of each row
      $user = mysqli_fetch_assoc($result);
      $name = $user["name"];

      $sql = "SELECT * FROM category";
      $categories = mysqli_query($conn, $sql);

      $templateName = $_GET['templateName'];
      if($templateName !== ''){
        $sql = "SELECT * FROM list WHERE name = '$templateName' ORDER BY id DESC LIMIT 1";
        $query_list = mysqli_query($conn, $sql);
        $list = mysqli_fetch_assoc($query_list);
        $list_id = intval($list["id"]);
      
        if ($_GET['ProductDelete']) {
          $products = intval($_GET['ProductDelete']);
          $sql = "DELETE FROM list_product WHERE list_id = ? AND product_id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ii", $list_id,$products);
          $stmt->execute();
        }
        if ($list) {
          $sql = "SELECT p.id, p.name FROM product AS p
          LEFT JOIN list_product AS lp
          ON p.id = lp.product_id
          WHERE lp.list_id = $list_id";
            $query_products = mysqli_query($conn, $sql);
        }
      }

      if (isset($_POST['delete_list'])) {
        $list_id = $_GET['id'];
      
        // apaga o registro na tabela list
        $sql = "DELETE FROM list WHERE id = $list_id";
        if (!mysqli_query($conn, $sql)) {
          echo "Erro ao eliminar o registro na tabela list: " . mysqli_error($conn);
        }
      
        // apaga os registros na tabela list_product
        $sql = "DELETE FROM list_product WHERE list_id = $list_id";
        if (!mysqli_query($conn, $sql)) {
          echo "Erro ao eliminar os registros na tabela list_product: " . mysqli_error($conn);
        } else {
          header("Location: index.php");
          exit;
        }
      }


      if(isset($_POST['productid'])) {
        $product_id = $_POST['id'];
        $sql = "SELECT state FROM list_product WHERE id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        $current_state = mysqli_fetch_assoc($result)['state'];
        $current_state = 1; // supondo que o estado atual do produto seja 1
        $new_state = ($current_state == 0) ? 1 : 0;
        $sql = "UPDATE list_product SET state = '$new_state' WHERE id = '$product_id'";
        mysqli_query($conn, $sql);
       }

      if (isset($_POST['update_state'])) {
        $list_id = mysqli_real_escape_string($conn, $_GET['id']);
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $new_state = ($state == 0) ? 1 : 0;
        $sql = "UPDATE list_product SET state = $new_state WHERE list_id = $list_id AND product_id = $product_id";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
          echo "Erro ao atualizar o estado do produto: " . mysqli_error($conn);
        }
      }
      
      
      if (isset($_POST['signout'])) {
          session_destroy();
          header('Location: ' . $url . '/signin.php');
          exit;
      }
  }

?>


<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/shoppinglist">Shopping List</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-nav" style="flex-direction:unset">
    <div class="nav-item text-nowrap">
      <span class="nav-link px-3" href="#">Hello, <?php echo $name; ?></span>
    </div>
    <div class="nav-item text-nowrap">
      <form method="post">
        <button class="nav-link px-3 bg-transparent border-0" name="signout" type="submit">Sign out</button>
      </form>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">

  <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo $list_name ?></h1>
        <button type="button" class="btn btn-danger btn-close" aria-label="Close" onClick="location.href='/shoppinglist'"></button>
      </div>

  <form method="post" >
        
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Product</th>
            <th scope="col">Category</th>
            <th scope='col'>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($products) > 0 ): ?>
            <?php foreach($products as $product_row): ?>
              <tr id="list-<?php echo $product_row["id"] ?>">
                <td><?php echo $product_row["name"] ?></td>
                <td><?php echo $product_row["category_name"] ?></td>
                <td>
                  <button name="productid" value="<?php echo 'list'.'-' . $product_row["list_id"] . '-' . 'product' . '-' . $product_row["id"] . '-' ?>" type="submit" onclick="checkProduct(this)" id="strike-button-<?php echo $product_row["id"] ?>" class="btn btn-secondary" data-product-id="<?php echo $product_row["id"] ?>" data-state="<?php echo $product_row["state"] ?>">Acquired</button></td>
                <td>
                  <button name="refresh" value="<?php echo 'list'.'-' . $product_row["list_id"] . '-' . 'product' . '-' . $product_row["id"] . '-' . 'state' . '-'.  $product_row["list_state"] ?>" type="button" onclick="getData(this)" id="strike-button-<?php echo $product_row["id"] ?>" class="btn btn-secondary">Refresh</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    <div class="d-flex justify-content-between mt-4">
        <input type="hidden" name="list_id" value="<?php echo $list_id ?>">
        <input class="btn btn-danger" onclick="DeleteConfirm()" type="submit" name="delete_list" value="Delete List">
      <button type="button" class="btn btn-primary" id="shareBtn">
        <i class="fas fa-share-alt"></i> Share
      </button>
    </div>

  </form>
  </main>
  </div>
</div>

<script>
  function getData(e){
    let value = e.value;
    var n = value.lastIndexOf('-');
    var result = value.substring(n + 1);
    let idButton = e.id;
    let indexString = idButton.lastIndexOf('-');
    let index =  idButton.substring(indexString + 1);
    if(parseInt(result)){
    let element = document.querySelector(`#list-${index} td:first-child`);
    element.classList.add("text-strike");
    }
  }
  
  function checkProduct(e){
  let idButton = e.id;
  let indexString = idButton.lastIndexOf('-');
  let index =  idButton.substring(indexString + 1);
  let element = document.querySelector(`#list-${index} td:first-child`);
  let productId = e.dataset.productId;
  let state = e.dataset.state;
  let newState = state == 0 ? 1 : 0;
  element.classList.toggle('text-strike');
  let url = `update_state.php?product_id=${productId}&state=${newState}`;
  fetch(url, { method: 'post' })
     .then(response => response.json())
     .then(data => {
       if (data.success) {
         alert('Estado atualizado com sucesso');
       } else {
         alert('Ocorreu um erro ao atualizar o estado');
       }
     })
     .catch(error => {
       console.error(error);
       alert('Ocorreu um erro ao atualizar o estado');
     });
  }

</script>


<script>
  document.getElementById("shareBtn").addEventListener("click", function() {
    let email = "";
    let subject = "Lista: <?php echo $list_name ?>";
    let body = "Lista:\n\n";
    <?php foreach($products as $product_row): ?>
      body += "<?php echo $product_row["name"] ?>\n";
    <?php endforeach; ?>
    window.location.href = "mailto:" + email + "?subject=" + subject + "&body=" + body;
  });
</script>


<footer class="bg-dark py-4 d-flex justify-content-between">
  <p class="text-center text-white">Copyright &copy; 2023 | All rights reserved</p>
  <button class="btn btn-secondary d-flex align-items-center">
  <span class="mr-2" onclick="About()">About </span><i class="fa fa-info-circle mr-2"></i> 
  </button>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</body>
</html>