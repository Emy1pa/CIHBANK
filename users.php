<?php
@include "DataBase.php";



if (isset($_POST['deleteuser']) && isset($_POST['userId'])) {
    $id = $_POST['userId'];


   
    
    $deletetransaction = "DELETE FROM transaction WHERE accountId IN (SELECT accountId FROM account WHERE userId = $id)";
    $conn->query($deletetransaction);
    


    $deleteaaccount = "DELETE FROM account WHERE userId = $id";
    $conn->query($deleteaaccount);
    // Delete associated records in the 'agency' table
    $deleterole = "DELETE FROM roleofuser WHERE userId = $id";
    $conn->query($deleterole);

    $deleteadress = "DELETE FROM adress WHERE userId = $id";
    $conn->query($deleteadress);

    // Delete the record from the 'bank' table
    $deleteuser = "DELETE FROM users WHERE userId = $id";
    $conn->query($deleteuser);
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
    <style>
        header {
            filter: drop-shadow(4px 4px 5px rgba(255, 255, 255));
            border: 1px white solid;
        }
    </style>
</head>

<body>
<section class="  relative overflow-x-auto shadow-md sm:rounded-lg top-10 w-[80%] ml-auto mr-5 ">
  
  <?php
include('sidenav.php');
?>


        <div class="flex justify-evenly items-center mb-[50px]">
            <h1 class="text-[50px] h-[10%]  text-center text-black">USERS</h1>
    <button type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"><a href="registre.php">Ajouter un Client </a></button>

        </div>
        <?php
        // Check if the 'submit' and 'bankid' are set, indicating that the form is submitted
        if (isset($_POST['users']) && isset($_POST['agencyId'])) {
            $agencyid = $conn->real_escape_string($_POST['agencyId']);


            // Fetch bank details based on the bankid
            $agency_sql = "SELECT * FROM agency WHERE agencyid = '$agencyid'";
            $agency_result = $conn->query($agency_sql);

            if ($agency_result->num_rows > 0) {
                $agency_row = $agency_result->fetch_assoc();
                echo "<div class ='flex w-[100%]  justify-center h-[60px] border-[2px] border-white border-solid items-center text-black'>";

                echo "<p class='border-[2px] border-white border-solid w-[50%] h-[100%] flex items-center  justify-center'>Agence : {$agency_row["agencyname"]}</p>";
                echo "</div>";
            }

            // Fetch data based on the selected bankid for 'agency'
            $sql = "SELECT users.userid, users.firstName, users.familyName, users.username, agency.agencyid
            FROM users
            INNER JOIN adress ON users.userid = adress.userid
            INNER JOIN agency ON adress.agencyid = agency.agencyid
            WHERE agency.agencyid = '$agencyid'";




            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table class="leading-9 h-[90%] w-[100%] text-center text-black">';
                echo '<thead>
                        <tr>
                            <th class="border-[2px] border-black border-solid w-[15%]">User Name</th>
                            <th class="border-[2px] border-black border-solid w-[15%]">First Name</th>
                            <th class="border-[2px] border-black border-solid w-[15%]">Family Name</th>
                            <th class="border-[2px] border-black border-solid w-[15%]">Accounts</th>
                            <th class="border-[2px] border-black border-solid w-[15%]">Editing</th>
                            <th class="border-[2px] border-black border-solid w-[15%]">Deleting</th>
                          
                        </tr>
                    </thead>';
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <td class='border-[2px] border-black border-solid w-[15%]'>" . $row["username"] . " </td>
                            <td class='border-[2px] border-black border-solid w-[15%]'> " . $row["firstName"] . "</td>
                            <td class='border-[2px] border-black border-solid w-[15%]'>" . $row["familyName"] . "</td>
                           
                            
                       
                                <td class='border-[2px] border-black border-solid '>
                            <form action='agences.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                <input type='hidden' name='userid' value='" . $row["userid"] . "'>
                                <input type='submit'  name='submit' value='Accounts'>
                            </form>
                        </td> 
                        
                        <td class='border-[2px] border-black border-solid '>
                            <form action='registre.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["userid"] . "'>
                            <input type='hidden' name='userid' value='" . $row["userid"] . "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
    
                        
                            </td>
                           <td class='border-[2px] border-black border-solid '>
                           <form action='users.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                           <input type='hidden' name='userid' value='" . $row["userid"] . "'>
                           <input type='submit'  name='deleteuser' value='Delete'>
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
            $sqlATM = "SELECT * FROM users";
            $result2 = $conn->query($sqlATM);

            if ($result2->num_rows > 0) {
                echo '<table class="leading-9  w-[100%] text-center h-[7vh] items-start text-black">';
                echo '<thead>
                <tr>
                <th class="border-[2px] border-black border-solid w-[15%]">User Name</th>
                <th class="border-[2px] border-black border-solid w-[15%]">First Name</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Family Name</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Accounts</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Editing</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Deleting</th>
              
            </tr>
                    </thead>';
                while ($row = $result2->fetch_assoc()) {

                    echo "<tr>
                    <td class='border-[2px] border-black border-solid '>" . $row["username"] . " </td>
                    <td class='border-[2px] border-black border-solid '> " . $row["firstName"] . "</td>
                    <td class='border-[2px] border-black border-solid '>" . $row["familyName"] . "</td>


                            <td class='border-[2px] border-black border-solid '>
                            <form action='Accounts.php' method='post' class='height-[80px] cursor-pointer w-[100%] hover:bg-black bg-white hover:text-white text-black '>

                                <input type='hidden' name='userid' value='" . $row["userId"] . "'>
                                <input type='submit' name='submit'  value='Accounts'>
                                </form>

                                </td>
                            <td class='border-[2px] border-black border-solid '>
                            <form action='registre.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["userId"] . "'>
                            <input type='hidden' name='userid' value='" . $row["userId"] . "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>

                            </td>
                            <td class='border-[2px] border-black border-solid '>
                            <form action='users.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='userId' value='" . $row["userId"] . "'>
                            <input type='submit'  name='deleteuser' value='Delete'>
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