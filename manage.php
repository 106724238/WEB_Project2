<?php
session_start();
// Bảo vệ trang: Nếu chưa login thì đá về
if (!isset($_SESSION['adminlogin'])) {
    header("Not Logged In");
    exit();
}

require_once("settings.php");
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage EOIs</title>
</head>
<body>
    <h1>HR Manager Dashboard</h1>
    <p>Welcome, admin! <a href="logout.php">Logout</a></p>
    <hr>

    <h3>Search & Filter</h3>
    <form action="manage.php" method="post">
        <label>Job Reference:</label> <input type="text" name="job_ref"><br><br>
        <label>First Name:</label> <input type="text" name="fname">
        <label>Last Name:</label> <input type="text" name="lname"><br><br>
        
        <label>Sort By:</label>
        <select name="sort_by">
            <option value="EOInumber">EOI Number</option>
            <option value="JobReference">Job Reference</option>
            <option value="FirstName">First Name</option>
        </select>
        <input type="submit" name="search" value="Search">
        <input type="submit" name="list_all" value="List All EOIs">
    </form>
    <hr>

    <h3>Update EOI Status</h3>
    <form action="manage.php" method="post">
        <label>EOI Number:</label> <input type="number" name="eoi_num" required>
        <label>New Status:</label>
        <select name="new_status">
            <option value="New">New</option>
            <option value="Current">Current</option>
            <option value="Final">Final</option>
        </select>
        <input type="submit" name="update_status" value="Update Status">
    </form>
    <hr>

    <h3>Delete EOIs by Job Reference</h3>
    <form action="manage.php" method="post">
        <label>Job Reference:</label> <input type="text" name="delete_job_ref" required>
        <input type="submit" name="delete_eoi" value="Delete All EOIs for this Job">
    </form>
    <hr>

    <h3>Results</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>EOI Number</th>
            <th>Job Ref</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
        <?php
        $sql = "";

        // Search Engine
        if (isset($_POST['list_all'])) {
            $sql = "SELECT * FROM eoi";
        } elseif (isset($_POST['search'])) {
            $job_ref = mysqli_real_escape_string($conn, $_POST['job_ref']);
            $fname = mysqli_real_escape_string($conn, $_POST['fname']);
            $lname = mysqli_real_escape_string($conn, $_POST['lname']);
            // bỏ qua kt đặc biệt
            $conditions = array();
            if (!empty($job_ref)) $conditions[] = "JobReference = '$job_ref'";
            if (!empty($fname)) $conditions[] = "FirstName LIKE '%$fname%'";
            if (!empty($lname)) $conditions[] = "LastName LIKE '%$lname%'";
            
            $sql = "SELECT * FROM eoi";
            if (count($conditions) > 0) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }
            // . là để nối chuỗi, implode là nối mảng thành chuỗi
        }

        // Sorting
        if (!empty($sql) && isset($_POST['sort_by'])) {
            $sort_by = mysqli_real_escape_string($conn, $_POST['sort_by']);
            $sql .= " ORDER BY $sort_by ASC";
            // từ A đến Z
        }

        // Table Display
        if (!empty($sql)) {
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['EOInumber'] . "</td>";
                echo "<td>" . $row['JobReference'] . "</td>";
                echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['Status'] . "</td>";
                echo "</tr>";
            }
        }

        // Status
        if (isset($_POST['update_status'])) {
            $eoi_num = $_POST['eoi_num'];
            $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
            
            $update_sql = "UPDATE eoi SET Status = '$new_status' WHERE EOInumber = $eoi_num";
            mysqli_query($conn, $update_sql);
            echo "<p>Updated EOI $eoi_num to $new_status!</p>";
        }

        // Job Delete
        if (isset($_POST['delete_eoi'])) {
            $delete_ref = mysqli_real_escape_string($conn, $_POST['delete_job_ref']);
            
            $delete_sql = "DELETE FROM eoi WHERE JobReference = '$delete_ref'";
            mysqli_query($conn, $delete_sql);
            echo "<p>Deleted all EOI for Job Reference: $delete_ref</p>";
        }
        ?>
    </table>
</body>
</html>
<?php mysqli_close($conn); ?>