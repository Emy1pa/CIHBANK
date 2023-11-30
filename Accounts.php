<?php
@include "DataBase.php";


// Handle Delete action
if (isset($_POST['deleteaccount']) && isset($_POST['delete'])) {
    $id = $_POST['delete'];

    // Delete associated records in the 'agency' table
    $deletetransaction = "DELETE FROM transaction WHERE accountid = $id";
    if ($conn->query($deletetransaction) !== TRUE) {
        echo "Error deleting address: " . $conn->error;
    }

    // Delete the record from the 'agency' table
    $deleteAccounts = "DELETE FROM account WHERE accountid = $id";
    if ($conn->query($deleteAccounts) !== TRUE) {
        echo "Error deleting agency: " . $conn->error;
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and stylesheets go here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gestionaire Bancaire</title>
   
</head>

<body>
    <section class="  relative overflow-x-auto shadow-md sm:rounded-lg top-10 w-[80%] ml-auto mr-5 ">
  
    <?php
include('sidenav.php');
?>


        <div class="flex justify-evenly items-center mb-[50px]">
            <h1 class="text-[50px] h-[10%]  text-center text-black">ACCOUNTS</h1>
            <button type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
            <a href="addaccounts.php">Ajouter un Account </a></button>
        </div>
        <?php
        // Check if the 'submit' and 'bankid' are set, indicating that the form is submitted
        if (isset($_POST['submit']) && isset($_POST['userid'])) {
            $userid = $conn->real_escape_string($_POST['userid']);

            // Fetch bank details based on the bankid
            $user_sql = "SELECT * FROM users WHERE userid = '$userid'";
            $user_result = $conn->query($user_sql);

            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                echo "<div class ='flex w-[100%]  justify-center h-[60px] border-[2px] border-black border-solid items-center text-black'>";
                echo "<p class='border-[2px] border-black border-solid w-[85%] h-[100%] flex items-center  justify-center'>Username : {$user_row["username"]}</p>";
                echo "<p class='border-[2px] border-black border-solid w-[85%] h-[100%] flex items-center  justify-center'>first Name : {$user_row["firstName"]}</p>";
                echo "<p class='border-[2px] border-black border-solid w-[85%] h-[100%] flex items-center  justify-center'>family Name : {$user_row["familyName"]}</p>";
                echo "</div>";
            }

            // Fetch data based on the selected bankid for 'agency'
            $sql = "SELECT * FROM `account` WHERE userid = '$userid'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table class="leading-9 h-[90%]  w-[100%] text-center text-black">';
                echo '<thead>
                        <tr>
                            <th class="border-[2px] border-black border-solid w-[15%] ">ID</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">RIB</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Balance</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Edit</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Delete</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Transaction</th>
                        </tr>
                    </thead>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td class='border-[2px] border-black border-solid '>" . $row["accountId"] . " </td>
                            <td class='border-[2px] border-black border-solid '>" . $row["RIB"] . "  MAD</td>
                            <td class='border-[2px] border-black border-solid '> " . $row["balance"] . " </td>

                            <td class='border-[2px] border-black border-solid '>
                            <button action='transactions.php' method='post' class='text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 '>

                                <input type='hidden' name='accountid' value='" . $row["accountId"] . "'>
                                <input type='submit' name='submit'  value='Transactions'>
                                </button>
                                </td>
                               

                            <td class='border-[2px] border-black border-solid '>
                            <form action='addaccounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["accountId"] . "'>
                            <input type='hidden' name='accountid' value='" . $row["accountId"] . "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
                            </td>
                            <td class='border-[2px] border-black border-solid '>
                            <form action='accounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                <input type='hidden' name='delete' value='" . $row["accountId"] . "'>
                                <input type='submit'  name='deleteaccount' value='Delete'>
                            </form>
                        </td>
                        </tr>";
                }
                echo '</table>';
            } else {
                echo "<p class='text-center'>0 results</p>";
            }
        } else {
            // Handle the case when 'submit' and 'bankid' are not set (initial page load)
            // Fetch data for 'compts' table
            $sqlall = "SELECT * FROM `account`";
            $result2 = $conn->query($sqlall);

            if ($result2->num_rows > 0) {
                echo '<table class="leading-9  w-[100%] text-center h-[7vh] items-start text-black">';
                echo '<thead>
                        <tr>
                        <th class="border-[2px] border-black border-solid w-[15%] ">ID</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">RIB</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Balance</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Transaction</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Update</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Delete</th>
                        </tr>
                    </thead>';
                while ($row = $result2->fetch_assoc()) {

                    echo "<tr>
                    <td class='border-[2px] border-black border-solid '>" . $row["accountId"] . " </td>
                    <td class='border-[2px] border-black border-solid '> " . $row["RIB"] . "</td>
                    <td class='border-[2px] border-black border-solid '> " . $row["balance"] . "  MAD</td>


                    <td class='border-[2px] border-black border-solid '>
                    <form action='transactions.php' method='post' class='height-[80px] cursor-pointer w-[100%] hover:bg-black bg-white hover:text-white text-black '>

                        <input type='hidden' name='accountid' value='" . $row["accountId"] . "'>
                        <input type='submit' name='submit'  value='Transactions'>
                        </form>
                        </td>
                               

                            <td class='border-[2px] border-black border-solid '>
                            <form action='addaccounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["accountId"] . "'>
                            <input type='hidden' name='accountid' value='" . $row["accountId"] . "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
                            </td>
                            <td class='border-[2px] border-black border-solid '>
                            <form action='accounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                <input type='hidden' name='delete' value='" . $row["accountId"] . "'>
                                <input type='submit'  name='deleteaccount' value='Delete'>
                            </form>
                        </td>
                        </tr>";
                }
                echo '</table>';
            } else {
                echo "<p class='text-center'>0 results</p>";
            }
        }
        $conn->close();
        ?>
    </section>

    
    <script src="main.js">

    </script>

</body>

</html>