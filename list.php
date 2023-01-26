<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                  
              if (mysqli_num_rows($query_list) <= 0) {
                $stmt = $conn->prepare("INSERT INTO list (name,user_id) VALUES (?,?)");
                $stmt->bind_param("si", $templateName, $user["id"]);
                $stmt->execute();
              }

            }
          }

          if ($_GET['Product']) {
              $productsSelected = $_GET['Product'];

              foreach ($productsSelected as $value) {
                  $convert = intval($value);

                  $stmt = $conn->prepare("INSERT INTO list_product (list_id,product_id) VALUES (?,?)");
                  $stmt->bind_param("ii", $listIdCon, $convert);
                  $stmt->execute();
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
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="index.php">Shopping List</a>
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

        <div class="list-group mt-5">
            <table>
                <tr>
                    <th scope="col">List</th>
                    <th scope="col">Product</th>
                    <th scope="col">Date Regis:</th>
                    <th scope="col">Modify</th>
                    <th scope="col">Delete</th>
                </tr>
                <tbody>
                    <?php
                    $name >= 1;
                    while ($row = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td scope="row"><?php echo $name ?></td>
                            <td><?php echo $row['list Name'] ?></td>
                            <td><?php echo $row['product'] ?></td>
                            <td class="timeregis"><?php echo $row['time'] ?></td>
                            <td class="modify"><a name="edit" id="" class="bfix" href="" role="button">
                                    Modify
                                </a></td>
                            <td class="delete"><a name="id" id="" class="bdelete" href="" role="button">
                                    Delete
                                </a></td>
                        </tr>
                    <?php
                        $idpro++;
                    } ?>
                </tbody>
            </table>
            <br>
            <a name="" id="" class="Addlist" style="float:right" href="addlist.php" role="button">Add list</a>
        </div>

      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</body>
</html>