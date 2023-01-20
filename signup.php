<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">


</head>
<body class="text-center"  style="background-color: #eee;">


<section class="vh-100">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black border-0" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                <form action="signup.php" class="mx-1 mx-md-4" method="POST">
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="username" class="form-control" id="username" placeholder="Your Name">
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="email" name="email" class="form-control" id="email" placeholder="Your Email">
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                    <input type="password" name="password2" class="form-control" id="password2"  placeholder="Repeat your password" >
                    </div>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                  <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>

                  </div>
                  <footer>Already a member? <a href="signin.php">Sign In</a></footer>
                </form>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center">
                <img src="https://cdn-icons-png.flaticon.com/512/3176/3176195.png"
                  class="img-fluid" alt="Sample image">
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
    <?php 

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $conn = include("connectBD.php"); 
        $valid = true;
       
        if (empty($_POST["username"])) {
            $usernameErr = "Name is required";
            $valid = false;
        } else {
            $username =  $_POST['username'];
        }

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

        if (empty($_POST["password2"])) {
            $password2Err = "Password Confirmation is required";
            $valid = false;
        } else {
            $password2 =  $_POST['password2'];
        }
       
        
 
        if($valid){ 
            if($password === $password2){
                $stmt = $conn->prepare("INSERT INTO user (name,email,password) VALUES (?,?,?)");

                $stmt->bind_param("sss",$username, $email, $password);
                $stmt->execute();

                $stmt->close();
                header("Location: signin.php");
            }
        }

    }
   
    ?> 
</body>
</html>