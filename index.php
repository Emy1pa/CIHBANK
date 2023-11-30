<?php
session_start();

@include 'database.php';

$error = array();

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['names']);
    $password = $_POST['password'];

    // Use placeholders for password comparison and fetch the hashed password from the database
   // ...

$query = "SELECT users.userId, roleofuser.rolename, roleofuser.userId, users.username, users.pw
FROM users 
INNER JOIN roleofuser ON users.userId = roleofuser.userId
WHERE users.username = ?";



$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ...


    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verify hashed password
            if (password_verify($password, $row['pw'])) {
                $_SESSION['user_type'] = $row['rolename'];
                $_SESSION['user_id'] = $row['userId'];
                $_SESSION['username'] = $row['username'];
                
                if ($_SESSION['user_type'] === 'admin') {
                    header("Location: Accounts.php");
                    exit;
                } elseif ($_SESSION['user_type'] === 'client') {
                    header("Location: clients.php");
                    exit;
                } else {
                    // Handle other user types if needed
                }
            } else {
                $error[] = 'Incorrect username or password!';
            }
            
        } else {
            $error[] = 'Incorrect username or password!';
        }
    } else {
        $error[] = 'Database query error: ' . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
 

    <title>CIH_BANQUE</title>
</head>
<body>
    



    <div class=" h-screen md:flex ">
		<div class="relative overflow-hidden md:flex w-1/2 bg-[url('home.jpg')] bg-no-repeat bg-center bg-cover i justify-around items-center hidden">


		</div>
		<div class="flex md:w-1/2 justify-center py-10 items-center bg-white">
        <?php
if (!empty($error)) {
    foreach ($error as $err) {
        echo '<p style="color: black;">' . $err . '</p>';
    }
}
?>
			<form action="" method="POST" class="bg-white">

				
				<span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">CIH BANQUE</span>
				<div class="flex items-center border-2 py-2 px-3 rounded-2xl mb-4">
					
					<input class="pl-2 outline-none border-none" type="text" name="names" id="username" placeholder="Your username here" />
				</div>
				<div class="flex items-center border-2 py-2 px-3 rounded-2xl">
					<input class="pl-2 outline-none border-none" type="password" name="password" id="password" placeholder="Your password here" />
                    <?php
              $sql = "SELECT  userId FROM users";
              $result = $conn->query($sql);


              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "   
                    <input type='hidden' name='userdata' value='" . $row["userId"] . "'>       
                    ";
                 }}
               
           ?>
				</div>
				<button type="submit" name="submit" class="block w-full bg-cyan-600 mt-4 py-2 rounded-2xl text-white font-semibold mb-2">Log in</button>

			</form>
		</div>
	</div>


  
</body>
</html>
