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
                    header("Location: banques.php");
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
 

    <title>CIH_BANQUE LOGIN</title>
</head>
<body>
    



    <div class="h-screen md:flex ">
		<div class="relative overflow-hidden md:flex w-1/2 bg-[url('home.jpg')] bg-no-repeat bg-center bg-cover i justify-around items-center hidden">


		</div>
		<div class="flex md:w-1/2 justify-center py-10 items-center bg-white">
        <?php
if (!empty($error)) {
    foreach ($error as $err) {
        echo '<p style="color: red;">' . $err . '</p>';
    }
}
?>
			<form action="" method="POST" class="bg-white">

				<!-- <img src="logo.png" class="h-8 me-3 sm:h-8" alt="bank Logo" /> -->
				<span class=" self-center text-xl font-semibold whitespace-nowrap dark:text-white">CIH BANK</span>
				<div class="flex items-center border-2 py-2 px-3 rounded-2xl mb-4">
					<!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
					</svg> -->
					<input class="pl-2 outline-none border-none" type="text" name="names" id="username" placeholder="Username" />
				</div>
				<div class="flex items-center border-2 py-2 px-3 rounded-2xl">
					<!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
					</svg> -->
					<input class="pl-2 outline-none border-none" type="password" name="password" id="password" placeholder="Password" />
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
				<button type="submit" name="submit" class="block w-full bg-cyan-600 mt-4 py-2 rounded-2xl text-white font-semibold mb-2">Login</button>

			</form>
		</div>
	</div>


  
</body>
</html>
