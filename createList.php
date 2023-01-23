<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
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
    $row = mysqli_fetch_assoc($result);
    $name = $row["name"];

    $sql = "SELECT * FROM category";
    $categories = mysqli_query($conn, $sql);

    if($_GET['Categories']){
      if($_GET['Search']){     
        $categoryID = intval($_GET['Categories']);
        $searchValue= $_GET['Search'];
        $formatQuery = '%'.$searchValue.'%';
        $sql = "SELECT * FROM product WHERE id = $categoryID AND name LIKE '$formatQuery'";
        $products = mysqli_query($conn, $sql);
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
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Shopping List</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-nav" style="flex-direction:unset">
    <div class="nav-item text-nowrap">
      <span class="nav-link px-3" href="#"><?php echo $name; ?></span>
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
        <h1 class="h2">Create a Lists</h1>
      </div>
      <form>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Template Name</label>
          <input type="text" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Categories</label>
              <select class="form-select" name="Categories" aria-label="Default select example" onchange="this.form.submit();">
                <?php if (mysqli_num_rows($categories) > 0 ): ?>
                  <?php foreach($categories as $key=>$row): ?>
                    <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Search Product</label>
          <div class="d-flex">
            <input type="text" placeholder="Search" class="form-control" name="Search" onchange="this.form.submit();">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
          <?php if (mysqli_num_rows($products) > 0 ): ?>
                <div class="list-group">
                  <?php foreach($products as $key=>$row): ?>
                      <button type="button" id="<?php echo $row["id"] ?>" class="list-group-item list-group-item-action"><?php echo $row["name"] ?></button>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
        </div>
      </form>

    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</body>
</html>