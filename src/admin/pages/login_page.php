<?php
session_start();
require_once("../controllers/connection.php");
require_once(ROOT_DIR . "/src/admin/controllers/login.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="stylesheet" href="/public/css/extended.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
    <title>Login</title>
</head>

<body class=" bg-purple-50">

    <div class="min-h-screen hero bg-base-200">
        <div class="flex-col hero-content lg:flex-row-reverse">
            <div class="text-center lg:text-left">
                <h1 class="text-5xl font-bold">Login now!</h1>
                <p class="py-6">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi
                    exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
            </div>
            <div class="w-full max-w-sm shadow-2xl card shrink-0 bg-base-100">
                <form class="card-body" action="../controllers/login.php" method="post">
                    <div class="form-control">
                        <label for="username" class="label">
                            <span class="label-text">Username or Email</span>
                        </label>
                        <input id="username" name="username" type="text" value="<?php echo $username; ?>"
                            placeholder="email or username" class="input input-bordered" required />
                        <span><?php echo $username_err; ?></span>
                    </div>
                    <div class="form-control">
                        <label for="password" class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <input id="password" name="password" type="password" placeholder="password"
                            class="input input-bordered" required />
                        <span><?php echo $password_err; ?></span>
                        <label class="label">
                            <a href="#" class="label-text-alt link link-hover">Forgot password?</a>
                        </label>
                    </div>
                    <div class="mt-6 form-control">
                        <button
                            class="bg-black border-black btn btn-primary hover:bg-white hover:text-black hover:border-black">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>