<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}

require_once("settings.php");

// Helper: trim, strip slashes, escape HTML
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Read and sanitise every field
$jobref       = clean_input($_POST["jobref"] ?? "");
$first_name   = clean_input($_POST["firstname"] ?? "");
$last_name    = clean_input($_POST["lastname"] ?? "");
$dob          = clean_input($_POST["dob"] ?? "");
$gender       = clean_input($_POST["gender"] ?? "");
$street       = clean_input($_POST["street"] ?? "");
$suburb       = clean_input($_POST["suburb"] ?? "");
$state        = clean_input($_POST["state"] ?? "");
$postcode     = clean_input($_POST["postcode"] ?? "");
$email        = clean_input($_POST["email"] ?? "");
$phone        = clean_input($_POST["phone"] ?? "");
$other_skills = clean_input($_POST["otherskills"] ?? "");

$allowed_skills = ["HTML", "CSS", "JavaScript", "Python"];
$skills_in = (isset($_POST["skills"]) && is_array($_POST["skills"])) ? $_POST["skills"] : [];
$skills_ok = [];
foreach ($skills_in as $s) {
    $s = clean_input($s);
    if (in_array($s, $allowed_skills, true)) {
        $skills_ok[] = $s;
    }
}
$skills = count($skills_ok) > 0 ? implode(",", array_unique($skills_ok)) : null;

// Connect to the database
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
$errors = [];

if (!$conn) {
    $errors[] = "Database connection failed. Please try again later.";
} else {
    // Validate
    if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobref)) {
        $errors[] = "Job reference must be exactly 5 letters or digits.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT job_ref FROM jobs WHERE job_ref = ?");
        mysqli_stmt_bind_param($stmt, "s", $jobref);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) === 0) {
            $errors[] = "Job reference does not exist.";
        }
        mysqli_stmt_close($stmt);
    }

    if (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
        $errors[] = "First name must be 1 to 20 letters only.";
    }
    if (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
        $errors[] = "Last name must be 1 to 20 letters only.";
    }

    $dob_sql = "";
    if (!preg_match("#^(\d{2})/(\d{2})/(\d{4})$#", $dob, $m) || !checkdate((int)$m[2], (int)$m[1], (int)$m[3])) {
        $errors[] = "Date of birth must be a real date in dd/mm/yyyy format.";
    } else {
        $dob_sql = $m[3] . "-" . $m[2] . "-" . $m[1];
        $age = (int)date("Y") - (int)$m[3];
        if ($age < 15 || $age > 80) {
            $errors[] = "Age must be between 15 and 80.";
        }
    }

    if (!in_array($gender, ["Male", "Female", "Other"], true)) {
        $errors[] = "Please select a gender.";
    }
    if ($street === "" || strlen($street) > 40) {
        $errors[] = "Street address is required (maximum 40 characters).";
    }
    if ($suburb === "" || strlen($suburb) > 40) {
        $errors[] = "Suburb/town is required (maximum 40 characters).";
    }

    $states = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
    if (!in_array($state, $states, true)) {
        $errors[] = "Please select a valid state.";
    }
    if (!preg_match("/^[0-9]{4}$/", $postcode)) {
        $errors[] = "Postcode must be exactly 4 digits.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $errors[] = "Please enter a valid email address.";
    }
    if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
        $errors[] = "Phone number must be 8 to 12 digits.";
    }
}

// Page start
if (file_exists("header.inc") && filesize("header.inc") > 0) {
    include("header.inc");
} else {
    echo "<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><title>Application</title></head><body>";
}
echo "<main>";

if (count($errors) > 0) {
    echo "<h1>Application could not be submitted</h1><ul>";
    foreach ($errors as $e) {
        echo "<li>" . $e . "</li>";
    }
    echo "</ul><p><a href=\"apply.php\">Back to the application form</a></p>";
} else {
    $create = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        job_ref     VARCHAR(5)   NOT NULL,
        first_name  VARCHAR(20)  NOT NULL,
        last_name   VARCHAR(20)  NOT NULL,
        dob         DATE         NOT NULL,
        gender      ENUM('Male','Female','Other') NOT NULL,
        street      VARCHAR(40)  NOT NULL,
        suburb      VARCHAR(40)  NOT NULL,
        state       ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
        postcode    CHAR(4)      NOT NULL,
        email       VARCHAR(100) NOT NULL,
        phone       VARCHAR(12)  NOT NULL,
        skills      SET('HTML','CSS','JavaScript','Python') NULL,
        other_skills TEXT NULL,
        status      ENUM('New','Current','Final') NOT NULL DEFAULT 'New'
    )";
    mysqli_query($conn, $create);

    // Insert the data into the database
    $sql = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street, suburb, state, postcode, email, phone, skills, other_skills)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssss",
        $jobref, $first_name, $last_name, $dob_sql, $gender, $street, $suburb,
        $state, $postcode, $email, $phone, $skills, $other_skills);

    if (mysqli_stmt_execute($stmt)) {
        $eoi_number = mysqli_insert_id($conn);
        echo "<h1>Application received</h1>";
        echo "<p>Thank you, " . $first_name . ". Your application has been submitted.</p>";
        echo "<p>Your EOI number is <strong>" . $eoi_number . "</strong>. Please keep it for your records.</p>";
        echo "<p><a href=\"index.php\">Back to home</a></p>";
    } else {
        echo "<h1>Something went wrong</h1><p>We could not save your application. Please try again.</p>";
        echo "<p><a href=\"apply.php\">Back to the application form</a></p>";
    }
    mysqli_stmt_close($stmt);
}

echo "</main>";
if (file_exists("footer.inc") && filesize("footer.inc") > 0) {
    include("footer.inc");
} else {
    echo "</body></html>";
}

if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>