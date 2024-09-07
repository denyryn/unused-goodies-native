<?php
require_once('../controllers/connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/css/app.css">
  <link rel="icon" type="image/x-icon" href="../../assets/ico/favicon.ico">

  <title>Main Page</title>
</head>

<body class="bg-white font-rubik">
  <?php
  include("./navbar.php");
  ?>
  <section id="home">
    <div class="min-h-screen hero " style="background-image: url(../../assets/img/old_camera.jpg);">
      <div class="hero-overlay bg-opacity-60"></div>
      <div class="text-center hero-content text-neutral-content">
        <div class="max-w-md">
          <h1 class="mb-5 text-5xl font-bold">
            Hello <?php echo isUserLoggedIn() ? $_SESSION['username'] : "there"; ?>
          </h1>
          <p class="mb-5">Welcome to our website, Unused Goodies. We sells many of things that worth your penny. Lets
            start by Logging you in, or Register a new account if you dont have one.</p>
          <a href="<?php echo isUserLoggedIn() ? "./product_page.php" : "./login_page.php"; ?>"
            class="text-white btn btn-outline">Get Started</a>
        </div>
      </div>
    </div>
  </section>

  <section id="about">
    <div>

    </div>
  </section>

  <?php
  include("footer.php");
  ?>
</body>

</html>