<?php
require_once('../controllers/connection.php');
require_once(ROOT_DIR . "/src/controllers/register.php");
require_once(ROOT_DIR . "/src/controllers/utility.php");

$utility = new utility();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../ref/css/styles.css">
    <link rel="stylesheet" href="../../ref/css/tailwind.min.css">
    <link rel="stylesheet" href="../../ref/css/extended.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">

    <title>Signup</title>
</head>

<body class=" bg-purple-50 font-rubik">

    <div class="min-h-screen hero bg-base-200">
        <div class="flex-col hero-content lg:flex-row-reverse">
            <div class="text-center lg:ml-12 lg:text-left">
                <h1 class="text-5xl font-bold">Signup now!</h1>
                <p class="py-6" id="signup-quote">Loading our unique Signup quotes to cheer you up. So make sure to
                    Signup and checkout our goodies :)</p>
            </div>
            <div class="w-full max-w-sm shadow-2xl card shrink-0 bg-base-100">
                <form class="card-body" action="../controllers/register.php" method="post">
                    <div class="form-control">
                        <label for="username" class="label">
                            <span class="label-text">Username</span>
                        </label>
                        <input name="username" type="text" placeholder="Username" class="input input-bordered"
                            required />
                        <span><?php $utility->showError('username_err') ?></span>
                    </div>
                    <div class="form-control">
                        <label for="fullname" class="label">
                            <span class="label-text">Full Name</span>
                        </label>
                        <input name="fullname" type="text" placeholder="Full Name" class="input input-bordered"
                            required />
                        <span><?php $utility->showError('full_name_err') ?></span>
                    </div>
                    <div class="form-control">
                        <label for="email" class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <input name="email" type="email" placeholder="Email" class="input input-bordered" required />
                        <span><?php $utility->showError('email_err') ?></span>
                    </div>
                    <div class="form-control">
                        <label for="password" class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <input name="password" type="password" placeholder="Password" class="input input-bordered"
                            required />
                        <span><?php $utility->showError('password_err') ?></span>
                    </div>
                    <div class="form-control">
                        <label for="confirm_password" class="label">
                            <span class="label-text">Confirm Password</span>
                        </label>
                        <input name="confirm_password" type="password" placeholder="Confirm Password"
                            class="input input-bordered" required />
                        <span><?php $utility->showError('confirm_password_err') ?></span>
                    </div>
                    <p class="p-1 text-xs">Already have an account?<a class="m-1 hover:underline"
                            href="../pages/login_page.php">Login here.</a></p>
                    <div class="mt-6 form-control">
                        <button
                            class="text-white bg-black border-black btn btn-primary hover:bg-white hover:text-black hover:border-black">Signup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="../ref/js/sweetalert.js"></script>
    <script>
        // Array of signup quotes
        var signupQuotes = [
            "Ready to join the preloved party? Sign up now and become a VIP thrift shopper!",
            "Why did the t-shirt sign up? To be worn and cherished again! Follow its lead – sign up for a second chance at greatness.",
            "Sign up today and be the first to know about exclusive deals and hidden treasures. It's like having a secret map to the land of awesome bargains!",
            "What's the secret to a happy wardrobe? A joyful signup experience! Jump into the world of preloved fashion – sign up and let the good times roll.",
            "Why should you sign up? Because preloved goodies are like a box of chocolates – you never know what you're gonna get, but it's always delightful!",
            "Sign up and be the trendsetter of eco-friendly chic. Your fashion journey starts here – don't miss the sustainable style train!",
            "Knock, knock. Who's there? A world of preloved possibilities! Open the door by signing up and let the fashion adventure begin."
        ];

        // Function to display a random signup quote
        function displayRandomSignupQuote() {
            var randomNumber = Math.floor(Math.random() * signupQuotes.length);
            document.getElementById('signup-quote').innerText = signupQuotes[randomNumber];
        }

        // Call the function on page load
        window.onload = displayRandomSignupQuote;
    </script>

</body>

</html>