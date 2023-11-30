<?php
session_start();
@include "DataBase.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'client' || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit();
}

$userData = array();

if (isset($_SESSION['user_id'])) {
    $userData = $_SESSION['user_id'];
}
$userData1 = array();

if (isset($_SESSION['username'])) {
    $userData1 = $_SESSION['username'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

   
</head>

<body>


    
<?php
include('sidenavclient.php');
?>
<section class="  relative overflow-x-auto shadow-md sm:rounded-lg top-10 w-[80%] ml-auto mr-5 ">
        <div class=' texto w-[40vw] h-[20vh] flex flex-row justify-center items-center gap-[10px]'>
            <h1 class="text-[30px] font-bold">Votre Transactions : </h1>
        </div>
        <section class="h-[20vh] flex items-center w-[100%] ">
            <div class="relative overflow-x-auto w-[100%] shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                TRansactions Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Operations Type
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Amount
                            </th>




                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = "SELECT * FROM transaction WHERE accountId IN (SELECT accountId FROM account WHERE userId = $userData)";

                        $result = $conn->query($sql);


                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {


                                $id = $row["transactionId"];
                                echo '
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ' . $row['transactionId'] . '
                </th>
                <td class="px-6 py-4">
                ' . $row['type'] . '
                MAD </td>
                <td class="px-6 py-4">
                ' . $row['amount'] . '
                </td>
               
               
                
            </tr>';
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </section>
    </section>
</body>

</html>