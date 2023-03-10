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

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: signin.php');
    exit;
}
$current_url = 'http://' . $_SERVER['HTTP_HOST'];
$parsed_url = parse_url($current_url . $_SERVER['REQUEST_URI']);
$current_dir = dirname($parsed_url['path']);
$url = $current_url . $current_dir;
$conn = include "connectBD.php";

$email = $_SESSION['email'];

$sql = "SELECT * FROM user WHERE email='$email'";
$result = mysqli_query($conn, $sql);

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
      $listIdCon = intval($list["id"]);

      if ($_GET['Categories']) {
          if ($_GET['Search']) {

              $categoryID = intval($_GET['Categories']);
              $searchValue = $_GET['Search'];
              $formatQuery = '%' . $searchValue . '%';
              $sql = "SELECT * FROM product WHERE category_id = $categoryID AND name LIKE '$formatQuery'";
              $products = mysqli_query($conn, $sql);
              $productCount = -1;
              if(mysqli_num_rows($products)){
                $productCount = 1;
              }
              

              if (mysqli_num_rows($query_list) <= 0) {
                  $stmt = $conn->prepare("INSERT INTO list (name,user_id) VALUES (?,?)");
                  $stmt->bind_param("si", $templateName, $user["id"]);
                  $stmt->execute();
              }

          }
      }

      if($_GET['AddList'] === "") {
        if ($_GET['Product']) {
            $productsSelected = $_GET['Product'];

            foreach ($productsSelected as $value) {
                $convert = intval($value);

                $stmt = $conn->prepare("INSERT INTO list_product (list_id,product_id) VALUES (?,?)");
                $stmt->bind_param("ii", $listIdCon, $convert);
                $stmt->execute();
            }
            if ($list) {

              $sql = "SELECT p.id, p.name FROM product AS p
              LEFT JOIN list_product AS lp
              ON p.id = lp.product_id
              WHERE lp.list_id = $listIdCon";
                $query_products = mysqli_query($conn, $sql);

                $queryproductCount = -1;
                if(mysqli_num_rows($query_products)){
                  $queryproductCount = 1;
                }
          
            }
        }
      }
   
      if ($_GET['ProductDelete']) {
        $products = intval($_GET['ProductDelete']);
        $sql = "DELETE FROM list_product WHERE list_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $listIdCon,$products);
        $stmt->execute();
      }
      if ($list) {

        $sql = "SELECT p.id, p.name FROM product AS p
        LEFT JOIN list_product AS lp
        ON p.id = lp.product_id
        WHERE lp.list_id = $listIdCon";
          $query_products = mysqli_query($conn, $sql);
          $queryproductCount = -1;
          if(mysqli_num_rows($query_products)){
            $queryproductCount = 1;
          }
    
      }
    }

    if ($_GET['ListDelete'] !== NULL) {
      $sql = "DELETE FROM list WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $listIdCon);
      $stmt->execute();
      $sql = "DELETE FROM list_product WHERE list_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $listIdCon);
      $stmt->execute();
      header('Location: index.php');
      exit;
    }

    if ($_GET['SaveList'] !== NULL) {
      header('Location: index.php');
      exit;

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
        <h1 class="h2">Create a List</h1>
      </div>
      <form action="createList.php" method="get">
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">List Name</label>
          <input type="text" value="<?php echo $templateName; ?>" name="templateName" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Categories</label>
              <select class="form-select" name="Categories" aria-label="Default select example">
                <?php if (mysqli_num_rows($categories) > 0): ?>
                  <?php foreach ($categories as $key => $row): ?>
                    <?php if (intval($_GET['Categories']) == $row["id"]): ?>
                      <option value="<?php echo $row["id"] ?>" selected><?php echo $row["name"] ?></option>
                    <?php endif;?>
                    <?php if (intval($_GET['Categories']) != $row["id"]): ?>
                      <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>
                    <?php endif;?>
                  <?php endforeach;?>
                <?php endif;?>
              </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Search Product</label>
          <div class="d-flex">
            <input type="text" placeholder="Search" class="form-control" name="Search">
            <button type="submit" class="btn btn-primary">Search</button>
            <button class="btn btn-success" name="AddList" type="submit">Add</button>
          </div>
          <?php if ($productCount > 0): ?>
                <div class="list-group" style="position: relative; width: 95%;height: 300px; overflow-y: scroll;">
                  <?php foreach ($products as $key => $row): ?>
                  <li class="list-group-item">
                    <input class="form-check-input me-1" name="Product[]" type="checkbox" value="<?php echo $row["id"] ?>" id="<?php echo $row["id"] ?>">
                    <label class="form-check-label" for="firstCheckbox"><?php echo $row["name"] ?></label>
                  </li>
                  <?php endforeach;?>
                </div>
            <?php endif;?>



        </div>
        <?php if ($queryproductCount > 0): ?>
                <div class="list-group" >

                  <?php foreach ($query_products as $key => $row): ?>
                  <li class="list-group-item d-flex">
                    <label class="form-check-label" for="firstCheckbox"><?php echo $row["name"] ?></label>
                    <span style="margin: 0 0 0 auto;">
                      <button class="btn btn-danger" name="ProductDelete" type="submit" value="<?php echo $row["id"] ?>" id="<?php echo $row["id"] ?>">Remove</button>
                    </span>
                  </li>
                  <?php endforeach;?>
                </div>
              <?php endif;?>
              <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-danger" name="ListDelete" type="submit">Clear List</button>
                <button class="btn btn-success" name="SaveList" type="submit">Save</button>
              </div>
      </form>


    </main>
  </div>
</div>

<script>
  function About() {
    confirm("Desenvolvimento de Aplica????es Web (DAW) - ISTEC Lisboa\nJaneiro 2023\n\nTrabalho realizado por Daniel Oliveira & Vasco Neves");
  }
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