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
$current_url = 'http://'.$_SERVER['HTTP_HOST'];

$url = $current_url.$_SERVER['REQUEST_URI'];
$conn = include("connectBD.php"); 

$email = $_SESSION['email'];


$sql = "SELECT * FROM user WHERE email='$email'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  // Output data of each row
  $row = mysqli_fetch_assoc($result);
  $name = $row["name"];

  $sql = "SELECT list.id,list.name, list.image FROM list LEFT JOIN user ON list.user_id = user.id  WHERE user.email = '$email'";
  $list = mysqli_query($conn, $sql);

  if (isset($_POST['signout'])) {

    session_destroy();

    header('Location: '.$url.'/signin.php');
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
        <h1 class="h2">My Lists</h1>
      </div>  
    </main>
  </div>
</div>


<div class="container mt-1">
  <div class="row d-flex justify-content-between mb-3">
    <div class="row mb-4 d-flex flex-wrap">
      <div class="col-md-3">
        <div class="card me-4" style="width: 240px;">
          <div class="card-body d-flex align-items-center">
            <a href="./createList.php">
              <img src="./img/plus.png" alt="Click to create a list" style="width:180px; height:180px;">
            </a>
          </div>
        </div>
      </div>
      <?php if (mysqli_num_rows($list) > 0 ): ?>
        <?php foreach($list as $row): ?>
          <div class="col-md-3">
            <a href="<?php echo $url?>list.php?id=<?php echo $row['id'] ?>" class="text-decoration-none" style="color:black">
              <div class="card me-4" style="width: 240px;">
                <img class="card-body d-flex align-items-center text-center" src="<?php echo $row["image"] ?>" alt="Card image cap" style="width:220px; height:220px;">
                <div class="card-body text-center">
                  <h5 class="card-title"><?php echo $row["name"] ?></h5>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
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