<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


</head>
<body class="text-center"  style="background-color: #eee;">
<?php 

session_start();
       
if (isset($_POST['submit'])) { 
    $conn = include("connectBD.php"); 
    $valid = true;

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $valid = false;
    } else {
        $email =  $_POST['email'];
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $valid = false;
    } else {
        $password =  $_POST['password'];
    }



    if($valid){
        $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $sql);
    
        if (mysqli_num_rows($result) == 1) {
            // login is successful
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email;
            header('Location: index.php');
            exit;
        } else {
            // login is unsuccessful
            $error = "Invalid email or password";
        }
    }

}

?> 

<section class="vh-100">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black border-0" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign in</p>
                <form action="" method="post" class="mx-1 mx-md-4">
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="email" name="email" class="form-control" id="email" placeholder="Your Email">
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="password" id="password"  class="form-control" placeholder="Password" />
                    </div>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                  <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Sign In">
                   
                  </div>
                  <footer>I am not a member? <a href="signup.php">Sign Up</a></footer>
                </form>
                <?php if (isset($error)) { ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php } ?>
              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center">
                <img src="https://img.freepik.com/vetores-gratis/calculo-de-despesas-planejamento-de-lista-de-desejos-lista-de-compras-resumo-de-compras-cesta-de-supermercado-na-internet-elemento-de-design-criativo-da-lista-de-desejos-do-comprador_335657-3556.jpg?w=740&t=st=1674593591~exp=1674594191~hmac=c6003e756d62f5049b98e5c77e991ba2856163ce5663de4ff1b4dc0a029388a8"
                  class="img-fluid" alt="Fonte: img.freepik.com" width=70% height=70%>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

</body>
</html>