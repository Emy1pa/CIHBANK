<?php


@include 'database.php';
// if (isset($_POST["accountid"])) {
//     $id = $_POST["accountid"];
//     $bnkinfo = "SELECT * FROM account where accountid = $id";
//     $stk_bnk_info = $conn->query("$bnkinfo");
//     $rows = mysqli_fetch_assoc($stk_bnk_info);
//     $agencyname = $rows["RIB"];
//     $longtitud = $rows["balance"];
    
// }


if (isset($_POST['submit'])) {
    // Sanitize user inputs

    $RIB = mysqli_real_escape_string($conn, $_POST['RIB']);
    $balance = mysqli_real_escape_string($conn, $_POST['balance']);
    
    $userid = $_POST['userId'];;


    // Insert new bank into the 'bank' table
    $insertQuery = "INSERT INTO account (RIB, balance,userId)
     VALUES 
     ('$RIB', '$balance', '$userid')";

    $conn->query($insertQuery);

    header('location: accounts.php');
}
if (isset($_POST['accountid']) && $_POST['editing'] === 'Edit') {
    // Retrieve agency details for editing
    $id = $_POST["accountid"];
    $accountinfo = "SELECT * FROM account WHERE accountid = $id";
    $stk_cmt_info = $conn->query($accountinfo);
    $rows = mysqli_fetch_assoc($stk_cmt_info);

    // Populate variables with retrieved data
    $RIB = $rows["RIB"];
    $balance = $rows["balance"];
    
  
}


if (isset($_POST['edited'])) {

    $RIB = mysqli_real_escape_string($conn, $_POST['RIB']);
    $balance = mysqli_real_escape_string($conn, $_POST['balance']);
    $id = $_POST['accountid'];
    $updateQuery = "UPDATE account SET RIB='$RIB', balance='$balance' WHERE accountid=$id";
    $conn->query($updateQuery);
    header('location: accounts.php');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
  
    <title>ADD AGENCY</title>
</head>

<body>

    <section class=" bg ">

        <div class="min-h-[85vh] w-[90%] m-auto gap-[15px] flex flex-col md:flex-row md:justify-evenly items-center  ">

            <form action="addaccounts.php" method="post" class="flex flex-col gap-[19px] h-[70%] md:h-[80%] w-[80%] md:w-[30%] mb-[15px] p-[10px] bg-orange-700 items-center justify-center rounded-[20px]">

                <h1 class="md:text-[45px] text-[35px] text-gray-900 font-bold">Accounts</h1>

                <input type="text" name="RIB" placeholder="RIB : 2784xxxxxxxx"  value="<?php echo isset($RIB) ? $RIB : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] p-[10px] w-[85%] rounded">

                <input type="text" name="balance" placeholder="Balance of client" value="<?php echo isset($balance) ? $balance : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] w-[85%] p-[10px] rounded">


                <!-- Hidden input for agencyid -->
                <input type="hidden" name="accountid" value="<?php echo isset($id) ? $id : ''; ?>">

                <?php
                    if (!isset($_POST['accountid'])) {         
                        echo '<select name="userId"  class="outline-none h-[40px] p-[5px] w-[50%] rounded">'    ;   
                    
                    // Query to get all banks
                    $userQuery = "SELECT userId, firstName,familyName FROM users";
                    $userResult = mysqli_query($conn, $userQuery);

                    // Check if there are banks
                    if (mysqli_num_rows($userResult) > 0) {
                        while ($userRow = mysqli_fetch_assoc($userResult)) {
                            // Set the selected attribute if the bank is associated with the agency
                            $selected = ($userRow['userId'] == $selecteduserid) ? 'selected' : '';
                            echo '<option value="' . $userRow['userId'] . '" ' . $selected . '>' . $userRow['firstName'] . '' . $userRow['familyName'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No banks found</option>';
                    }
                }else{
                    echo 'You can change account RIB OR balance';
                }
                    ?>
                </select>

                <?php
                if (isset($_POST['accountid'])) {
                    echo '<input type="submit" name="edited" value="Edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
                } else {
                    echo '<input type="submit" name="submit" value="Add Account" class="bg-cyan-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
                };
                ?>


                <a href="accounts.php" class="bg-cyan-500 w-[85%] text-center hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">accounts Details</a>

            </form>

        </div>

    </section>



</body>

</html>