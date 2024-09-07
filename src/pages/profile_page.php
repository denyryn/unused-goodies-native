<?php
require_once("../controllers/connection.php");
require_once(ROOT_DIR . "/src/controllers/auth.php");
require_once(ROOT_DIR . "/src/controllers/utility.php");

$utility = new utility();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="stylesheet" href="../../ref/css/styles.css">
    <link rel="stylesheet" href="../../ref/css/extended.css">
    <link rel="icon" type="image/x-icon" href="../assets/ico/favicon.ico">

    <title>Identicon</title>
</head>

<body class="bg-white font-rubik">

    <div class="flex flex-row-reverse justify-between mb-[5vh] p-2 md:p-4">
        <div class="flex flex-col items-end ">
            <div class="flex flex-row-reverse items-end justify-normal ">
                <a href="#edit" id="editDiv" onclick="showSaveDiv()">
                    <div
                        class="btn flex items-center justify-center p-2.5 w-12 h-12 m-2 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-300">
                        <img class="" src="<?php $utility->svgAssets("edit-button") ?>" alt="edit">
                    </div>
                </a>
                <a href="#saved" id="saveDiv" onclick="saveChanges()">
                    <div onclick="submitForm()"
                        class="btn p-2.5 flex items-center justify-center w-12 h-12 m-2 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-300">
                        <img id="submitFormButton" class="h-7" src="<?php $utility->svgAssets("save-button") ?>"
                            alt="save">
                    </div>
                </a>
                <a href="./main_page.php">
                    <div id="home"
                        class="btn p-2.5 flex items-center justify-center w-12 h-12 m-2 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-300">
                        <img class="h-7" src="<?php $utility->svgAssets("home-button") ?>" alt="home">
                    </div>
                </a>
            </div>
        </div>
        <a href="../controllers/logout.php">
            <div id="logout"
                class="btn p-2.5 flex items-center justify-center w-12 h-12 m-2 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-300">
                <img class="h-7" src="<?php $utility->svgAssets("logout-button") ?>" alt="logout">
            </div>
        </a>
    </div>

    <?php
    $pdo_statement = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");

    $user_id = $_SESSION['user_id'];

    $pdo_statement->bindParam(':user_id', $user_id);
    $pdo_statement->execute();
    $result = $pdo_statement->fetchAll();

    foreach ($result as $profile) {
        ?>

        <div class="flex items-center justify-center mb-20 ml-2 mr-2 h-fit">
            <div
                class="shadow-md transition-shadow duration-150  hover:shadow-2xl bg-blue-200 rounded-xl h-fit max-w-3xl w-fit p-4 flex flex-col bg-[url(../assets/svg/identicon.svg)] md:h-fit md:w-auto">
                <div class="flex flex-col items-center ml-5 mr-5 md:mb-4">
                    <h3 class="text-2xl font-bold tracking-wide text-gray-900 ">
                        PROFILE
                    </h3>
                </div>
                <div
                    class="flex flex-col-reverse items-center justify-between mb-0 ml-4 mr-4 md:flex-row md:mb-0 md:mr-2 md:ml-2 md:pl-2">
                    <div class="items-start mb-2 text-sm font-medium leading-loose md:text-base md:mr-16 md:leading-loose">
                        <table id="infoTable" class="">
                            <tr>
                                <td class="pr-2">Name</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['full_name']; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">Gender</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo ($profile['gender'] === 'M') ? 'Male' : 'Female'; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">Birthdate</td>
                                <td class="pr-2"> : </td>
                                <td>
                                    <?php
                                    $birthdate_before = $profile['birthdate'];
                                    $timestamp = strtotime($birthdate_before);
                                    $birthdate_after = date("j F Y", $timestamp);
                                    echo $birthdate_after;
                                    ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="pr-2">Hobby</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['hobby'] ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">Email</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['email']; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">Phone</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['phone_no']; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">State</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['state']; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">City</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['city']; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">Postal Code</td>
                                <td class="pr-2"> : </td>
                                <td><?php echo $profile['postal_code']; ?></td>
                            </tr>
                            <tr>
                                <td class="pr-2">Main Address &nbsp&nbsp</td>
                                <td class="pr-2"> : </td>
                                <td class="break-words whitespace-pre-line"><?php echo $profile['main_address']; ?></td>
                            </tr>
                        </table>

                        <form id="editForm" action="../controllers/save_edit_profile.php" method="post"
                            enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?php echo $profile['user_id']; ?>" />
                            <table class="border-separate border-spacing-y-4" border="1" style="border-spacing: 0 10px;"">
                            <tr> 
                                <td class="
                                pr-2 ">Name</td>
                                                                                                                                                                        <td class="
                            pr-2 "> : </td>
                                                                                                                                                                    <td> <input
                                                                                                                                                                            class="
                            w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm
                            focus:ring-blue-500 focus:border-blue-500 block p-2.5" type="text" id="fullName"
                                name="full_name" value="<?php echo $profile['full_name']; ?>">
                                </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Gender</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <select
                                            class="cursor-pointer w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            name="gender" id="gender">
                                            <option class="bg-transparent" value="M" <?php echo ($profile['gender'] === 'M') ? 'selected' : ''; ?>>Male</option>
                                            <option class="bg-transparent" value="F" <?php echo ($profile['gender'] === 'F') ? 'selected' : ''; ?>>Female</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Birthdate</td>
                                    <td class="pr-2"> : </td>
                                    <td> <input
                                            class="cursor-pointer w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="date" id="birthdate" name="birthdate"
                                            value="<?php echo $birthdate_before; ?>"> </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Hobby</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="text" id="hobby" name="hobby" value="<?php echo $profile['hobby']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Email</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="email" id="email" name="email"
                                            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                            value="<?php echo $profile['email']; ?>" required
                                            oninput="this.setCustomValidity('')"
                                            oninvalid="this.setCustomValidity('Please enter a valid email address')">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Phone</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="text" id="phone" name="phone" value="<?php echo $profile['phone_no']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">State</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="text" id="state" name="state" value="<?php echo $profile['state']; ?>">
                                    </td>
                                </tr>
                                <tr>

                                    <td class="pr-2">City</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="text" id="city" name="city" value="<?php echo $profile['city']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Postal Code</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                                            type="text" id="postal_code" name="postal_code"
                                            value="<?php echo $profile['postal_code']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">Main Address</td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <textarea
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-sm border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                            name="main_address" id="main_address" cols="50"
                                            rows="5"><?php echo $profile['main_address']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-2">
                                        <label for="profile_photo" class="inline-block mt-2 mb-2 text-neutral-700">
                                            Profile Photo
                                        </label>
                                    </td>
                                    <td class="pr-2"> : </td>
                                    <td>
                                        <input
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 "
                                            id="photoInput" type="file" name="profile_photo" accept="image/*">
                                    </td>
                                </tr>
                            </table>

                            <button class="hidden" id="submitButton" type="submit"></button>
                        </form>

                    </div>
                    <div class="flex items-end m-2 md:mr-2 md:ml-2 md:items-center ">
                        <div class="flex flex-col">
                            <img class="object-cover mb-2 rounded-full w-36 max-h-36 md:h-max md:max-h-52 md:rounded-xl md:w-40"
                                src="<?php $utility->profilePhoto($profile['profile_photo']); ?>" alt="moon" id="imgBase"">
                        <p class=" mt-2 font-semibold text-center "><?php echo $profile['username']; ?></p>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>

                                                                                                                                            <?php
    }
    ?>

<script>
    var table = document.getElementById('infoTable');
    var form = document.getElementById('editForm');
    var form1 = document.getElementById('editForm1');
    var saveDiv = document.getElementById('saveDiv');
    var editDiv = document.getElementById('editDiv');
    var imgBase = document.getElementById('imgBase');
    var imgEditForm = document.getElementById('imgEditForm');

    saveDiv.style.display = 'none';
    form.style.display = 'none';

    function toggleForm() {
        // Toggle the display of table and form
        if (table.style.display === 'none') {
            table.style.display = 'table';
            form.style.display = 'none';
            form1.style.display = 'none';

        } else {
            table.style.display = 'none';
            form.style.display = 'block';
            form1.style.display = 'block';
        }
    }


    function showSaveDiv() {
        // Show the save div and hide the edit div
        saveDiv.style.display = 'flex';
        editDiv.style.display = 'none';

        // Show the form
        toggleForm();
    }

    function submitForm() {
        // Trigger the form submission
        document.getElementById('editForm').submit();
    }

    function saveChanges() {

        // Toggle back to the table view
        table.style.display = 'table';
        form.style.display = 'none';

        // Show the edit div again
        saveDiv.style.display = 'none';
        editDiv.style.display = 'flex';
    }

    document.getElementById('profileImage').addEventListener('click', function () {
        document.getElementById('photoInput').click();
    });

    function editProfile() {
        // Trigger the click event of the file input when the image is clicked.
        document.getElementById('photoInput').click();
    }

    function submitForm() {
        // Automatically submit the form when a file is selected.
        document.getElementById('submitButton').click();
    }

</script>


</body>
</html>