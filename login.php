<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="process_login.php" method="post">
        <label>Username: </label>
        <input type="text" name="username" required><br><br>
        
        <label>Password: </label>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>

    <?php 
    if(isset($_GET['error'])) {
        echo "Wrong username or password!</p>";
    } 
    ?>
</body>
</html>