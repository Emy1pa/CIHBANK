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

    <title>Clients Page</title>
   
</head>

<body>

<?php
include('sidenavclient.php');
?>
<section class="  relative overflow-x-auto shadow-md sm:rounded-lg top-10 w-[80%] ml-auto mr-5 ">
        <div class=' texto w-[40vw] h-[20vh] flex flex-row justify-center items-center gap-[10px]'>
            <h1 class="text-[30px] font-bold">Votre Comptes :  </h1>
            <p class="text-[30px] font-bold"> <?php echo $userData1; ?> </p>
        </div>
        <section class="h-[20vh] flex items-center w-[100%] ">
            <div class="relative overflow-x-auto w-[100%] shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Accounts Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Balance
                            </th>
                            <th scope="col" class="px-6 py-3">
                                RIB
                            </th>
                           
                            <th scope="col" class="px-6 py-3">
                                Transactions
                            </th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php



                        $sql = "SELECT * FROM account WHERE userid = '$userData'";
                        $result = $conn->query($sql);


                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {

                                $id = $row["accountId"];
                                echo '
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ' . $row['accountId'] . '
                </th>
                <td class="px-6 py-4">
                ' . $row['balance'] . '
                MAD </td>
                <td class="px-6 py-4">
                ' . $row['RIB'] . '
                </td>
               
                <td class="px-6 py-4">
                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"> <a href="transactionsclient.php">Afficher</a></button>

                   
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
    <script src="main.js">

</script>
</body>

</html>