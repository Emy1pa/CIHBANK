<?php
@include 'DataBase.php';

if (isset($_POST['submit'])) {
    $ville = mysqli_real_escape_string($conn, $_POST['ville']);
    $quartier = mysqli_real_escape_string($conn, $_POST['quartier']);
    $rue = mysqli_real_escape_string($conn, $_POST['rue']);
    $codepostal = mysqli_real_escape_string($conn, $_POST['codepostal']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Add this line to capture email
    $user = $_POST['user'];
    $agency = $_POST['agency'];;


    // Insert data into the address table
    $insertAddress = "INSERT INTO adress (ville, quartier, rue, codepostal, tel, email, userid,agencyid) 
                      VALUES ('$ville', '$quartier', '$rue', '$codepostal', '$phone', '$email', '$user', '$agency')";
    mysqli_query($conn, $insertAddress);

    // Rest of your code...

    // For example, you can redirect to another page after successful insertion
    header('location: users.php');
    exit();
}




if (isset($_POST['user_ids']) && $_POST['edited'] === 'Edit') {
    $id = mysqli_real_escape_string($conn, $_POST['userid']);

    // Fetch user data based on ID
    $userinfo = "SELECT * FROM users WHERE userid = $id";
    $result = $conn->query($userinfo);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Fetch address data based on user ID
        $addressInfo = "SELECT * FROM adress WHERE userid = $id";
        $addressResult = $conn->query($addressInfo);

        if ($addressResult->num_rows > 0) {
            $addressRow = $addressResult->fetch_assoc();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    

    <title>CIH_BANQUE LOGIN</title>
</head>

<body>
<section class=" bg-blue-300 p-[60px]">
        <div class="min-h-[85vh] w-[90%] m-auto gap-[15px] flex flex-col md:flex-row md:justify-evenly items-center  ">
            <div class="md:w-[50%] w-[85%] flex flex-col gap-[25px] mt-[15px]">
                <h1 class="text-gray-900 text-[45px] md:text-[60px]">CIH BANQUE</h1>
                <h3 class="text-gray-900 text-[25px] md:text-[30px]"> Your Gateway to Financial Harmony</h3>
                <p class="text-gray-900 text-[15px] md:text-[18px]">
                    CIH Banque is your gateway to financial success, offering personalized solutions <br>
                    expert guidance to navigate your unique financial journey. With a commitment <br>
                    to trust and innovation, we stand as a reliable partner, empowering you to achieve <br>
                    your financial goals seamlessly. Join us for a transformative experience, where your<br>
                    prosperity is our priority.
                </p>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="flex flex-col gap-[19px] h-[70%] md:h-[80%] w-[80%] md:w-[30%] mb-[15px] p-[10px] bg-gray-300/20 items-center justify-center rounded-[20px]">
                <h3 class="text-3xl mb-2.5 uppercase font-medium text-gray-900">ADD USER</h3>

                <input type="text" name="ville" required placeholder="City" value="<?php echo isset($addressRow['ville']) ? $addressRow['ville'] : ''; ?>"  class="outline-none h-[3rem] p-[5px] w-[85%] rounded">
                <input type="text" name="quartier" required placeholder="Neighborhood" value="<?php echo isset($addressRow['quartier']) ? $addressRow['quartier'] : ''; ?>"  class="outline-none h-[3rem] p-[5px] w-[85%] rounded">
                <input type="text" name="rue" required placeholder="Street" value="<?php echo isset($addressRow['rue']) ? $addressRow['rue'] : ''; ?>"   class="outline-none h-[3rem] p-[5px] w-[85%] rounded">
                <input type="text" name="codepostal" required placeholder="Code Postal" value="<?php echo isset($addressRow['codepostal']) ? $addressRow['codepostal'] : ''; ?>" class="outline-none h-[3rem] p-[5px] w-[85%] rounded">
                <input type="email" name="email" required placeholder="E-mail" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>"  class="outline-none h-[3rem] p-[5px] w-[85%] rounded">
                <input type="tel" name="phone" required placeholder="Phone Number" value="<?php echo isset($row['tel']) ? $row['tel'] : ''; ?>"   class="outline-none h-[3rem] p-[5px] w-[85%] rounded">
                <div class="w-[85%] flex gap-[50px]">
                    <select name="user" id="" class="outline-none h-[40px] p-[5px] w-[50%] rounded">
                        <?php
                        // Query to get all users
                        $userQuery = "SELECT userId, firstName, familyName FROM users";
                        $userResult = mysqli_query($conn, $userQuery);

                        // Check if there are users
                        if (mysqli_num_rows($userResult) > 0) {
                            while ($userRow = mysqli_fetch_assoc($userResult)) {
                                echo '<option value="' . $userRow['userId'] . '">' . $userRow['firstName'] . ' ' . $userRow['familyName'] . '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>No users found</option>';
                        }
                        ?>
                    </select>
                    <select name="agency" id="" class="outline-none h-[40px] p-[5px] w-[50%] rounded">
                        <?php
                        // Query to get all users
                        $agencyQuery = "SELECT agencyid, agencyname FROM agency";
                        $agencyResult = mysqli_query($conn, $agencyQuery);

                        // Check if there are users
                        if (mysqli_num_rows($agencyResult) > 0) {
                            while ($agencyRow = mysqli_fetch_assoc($agencyResult)) {
                                echo '<option value="' . $agencyRow['agencyid'] . '">' . $agencyRow['agencyname'] . ' </option>';
                            }
                        } else {
                            echo '<option value="" disabled>No users found</option>';
                        }
                        ?>
                    </select>
                </div>

                <input type="submit" name="submit" value="ADD USER" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">
            </form>


        </div>
    </section>



</body>

</html>