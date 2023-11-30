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

    $operation = mysqli_real_escape_string($conn, $_POST['operation-type']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    $accountid = $_POST['accountId'];;


    // Insert new bank into the 'bank' table
    $insertQuery = "INSERT INTO transaction (type, amount,accountId)
     VALUES 
     ('$operation', '$amount', '$accountid')";

    $conn->query($insertQuery);

    header('location: transactions.php');
}
if (isset($_POST['transactionid']) && $_POST['editing'] === 'Edit') {
    // Retrieve agency details for editing
    $id = $_POST["transactionid"];
    $transactioninfo = "SELECT * FROM transaction WHERE transactionId = $id";
    $stk_trns_info = $conn->query($transactioninfo);
    $rows = mysqli_fetch_assoc($stk_trns_info);

    // Populate variables with retrieved data
    $type = $rows["type"];
    $amount = $rows["amount"];
}


if (isset($_POST['edited'])) {

    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $type = mysqli_real_escape_string($conn, $_POST['operation-type']);
    $id = $_POST['transactionid'];
    $updateQuery = "UPDATE transaction SET type='$type', amount='$amount' WHERE transactionId=$id";
    $conn->query($updateQuery);
    header('location: transactions.php');
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

<section class=" bg-orange-600 p-[60px]">

        <div class="min-h-[85vh] w-[90%] m-auto gap-[15px] flex flex-col md:flex-row md:justify-evenly items-center  ">

            <form action="addtransactions.php" method="post" class="flex flex-col gap-[19px] h-[70%] md:h-[80%] w-[80%] md:w-[30%] mb-[15px] p-[10px] bg-gray-300/20 items-center justify-center rounded-[20px]">

                <h1 class="md:text-[45px] text-[35px] text-gray-900 font-bold">Transactions</h1>

                <input type="text" name="amount" placeholder=" Operation Amount" value="<?php echo isset($amount) ? $amount : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] p-[10px] w-[85%] rounded">

                <div class="w-[85%]">
                    <select name="operation-type" id="" class="outline-none h-[40px] p-[5px] w-[50%] rounded">
                        <option value="Debit" <?php if (isset($_POST['transactionId']) && $_POST['editing'] === 'Edit') { echo ($type === 'Debit') ? 'selected' : ''; }?>>Debit</option>
                        <option value="Credit" <?php if (isset($_POST['transactionId']) && $_POST['editing'] === 'Edit') { echo ($type === 'Credit') ? 'selected' : ''; }?>>Credit</option>
                    </select>
                </div>

                <!-- Hidden input for agencyid -->

                <?php
                if (!isset($_POST['transactionid'])) {
                    echo '<select name="accountId"  class="outline-none h-[40px] p-[5px] w-[50%] rounded">';

                    // Query to get all banks
                    $accountQuery = "SELECT accountId, RIB FROM account";
                    $accountResult = mysqli_query($conn, $accountQuery);

                    // Check if there are banks
                    if (mysqli_num_rows($accountResult) > 0) {
                        while ($accountRow = mysqli_fetch_assoc($accountResult)) {
                            // Set the selected attribute if the bank is associated with the agency
                            $selected = ($accountRow['accountId'] == $selecteduserid) ? 'selected' : '';
                            echo '<option value="' . $accountRow['accountId'] . '" ' . $selected . '>' . $accountRow['RIB'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No banks found</option>';
                    }
                } else {
                    echo 'You can change operation type or amount';
                }
                ?>
                </select>
                <input type="hidden" name="transactionid" value="<?php echo isset($id) ? $id : ''; ?>">

                <?php
                if (isset($_POST['transactionid'])) {
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