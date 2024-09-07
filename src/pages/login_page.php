<?php
require_once('../controllers/connection.php');
require_once(ROOT_DIR . "/src/controllers/login.php");
require_once(ROOT_DIR . "/src/controllers/utility.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">

    <title>Login</title>
</head>

<body class=" bg-purple-50 font-rubik">

    <div class="min-h-screen hero bg-base-200">
        <div class="flex-col hero-content lg:flex-row-reverse">
            <div class="text-center lg:ml-12 lg:text-left">
                <h1 class="text-5xl font-bold">Login now!</h1>
                <p class="py-6" id="login-quote">Loading our unique Login quotes to cheer you up. So make sure to Login
                    and checkout our goodies :)</p>
            </div>
            <div class="w-full max-w-sm shadow-2xl card shrink-0 bg-base-100">
                <form class="card-body" action="../controllers/login.php" method="post">
                    <div class="form-control">
                        <label for="username_or_email" class="label ">
                            <span class="label-text">Username or Email</span>
                        </label>
                        <input id="username_or_email" name="username_or_email" type="text"
                            placeholder="email or username" class="input input-bordered" required />
                        <span class="err-label-xs"><?php $utility->showError('username_or_email_err') ?></span>
                    </div>
                    <div class="form-control">
                        <label for="password" class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <input id="password" name="password" type="password" placeholder="password"
                            class="input input-bordered" required />
                        <span class="err-label-xs"><?php $utility->showError('password_err') ?></span>

                        <span class="p-1">
                            <a href="#" class="label-text-alt link link-hover">Forgot password?</a>
                        </span>

                        <p class="p-1 text-xs">Doesnt have an account? &nbsp<a class="hover:underline"
                                href="../pages/register_page.php">Signup here</a>.</p>
                    </div>
                    <div class="mt-1 form-control">
                        <button
                            class="text-white bg-black border-black btn btn-primary hover:bg-white hover:text-black hover:border-black">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Array of quotes
        var quotes = [
            "Unlock the treasure trove of preloved wonders – log in now and let the bargain hunt begin! Your next favorite item is just a click away.",
            "Join the preloved party! Logging in is like getting an exclusive invite to the coolest thrift store in town. Don't miss out on the fun – sign in and shop till you drop!",
            "Why did the user log in? To discover amazing preloved finds and unleash their inner bargain ninja! Be a savvy shopper – login and conquer the deals.",
            "Logging in is the key to a world of secondhand splendor. Your next 'must-have' is waiting – don't keep it waiting too long!",
            "What's the secret to a happy day? Logging in and scoring preloved treasures, of course! Get ready for smiles and great deals – click that login button now!",
            "Why did the fashionista log in? To stay fabulous on a budget! Join the style squad – login and strut into savings!",
            "Knock, knock. Who's there? A world of preloved wonders! Open the door by logging in and let the shopping adventure begin."
        ];

        // Function to display a random quote
        function displayRandomQuote() {
            var randomNumber = Math.floor(Math.random() * quotes.length);
            document.getElementById('login-quote').innerText = quotes[randomNumber];
        }

        // Call the function on page load
        window.onload = displayRandomQuote;
    </script>

</body>

</html>