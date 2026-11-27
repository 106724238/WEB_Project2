<?php
// 1. Block direct access: if the form was not submitted, go back to the form
if (!isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}

require_once("settings.php");

// 2. Function to clean the input
function clean_input($data) {
    $data = trim($data);            // remove spaces at the start and end
    $data = stripslashes($data);    // remove backslashes
    $data = htmlspecialchars($data); // change < > & into safe HTML
    return $data;
}

// 3. Get the data from the form
$jobref       = clean_input($_POST["jobref"]);
$first_name   = clean_input($_POST["firstname"]);
$last_name    = clean_input($_POST["lastname"]);
$dob          = clean_input($_POST["dob"]);
$gender       = isset($_POST["gender"]) ? clean_input($_POST["gender"]) : "";
$street       = clean_input($_POST["street"]);
$suburb       = clean_input($_POST["suburb"]);
$state        = clean_input($_POST["state"]);
$postcode     = clean_input($_POST["postcode"]);
$email        = clean_input($_POST["email"]);
$phone        = clean_input($_POST["phone"]);
$other_skills = clean_input($_POST["otherskills"]);

// skills are checkboxes, use array
$skills = "";
if (isset($_POST["skills"])) {
    $skills = implode(",", $_POST["skills"]); 
}

// 4. Connect to the database (details in settings.php)
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// 5. Check every field and collect the errors
$errors = array();

if (!$conn) {
    $errors[] = "Database connection failed.";
}

if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobref)) {
    $errors[] = "Job reference must be exactly 5 letters or digits.";
} else if ($conn) {
    // the job reference must exist in the jobs table
    $jobref_safe = mysqli_real_escape_string($conn, $jobref);
    $result = mysqli_query($conn, "SELECT job_ref FROM jobs WHERE job_ref = '$jobref_safe'");
    if (!$result || mysqli_num_rows($result) == 0) {
        $errors[] = "Job reference does not exist.";
    }
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors[] = "First name must be 1 to 20 letters only.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors[] = "Last name must be 1 to 20 letters only.";
}

// date of birth: dd/mm/yyyy
$dob_sql = "";
if (!preg_match("#^(\d{2})/(\d{2})/(\d{4})$#", $dob, $m) || !checkdate((int)$m[2], (int)$m[1], (int)$m[3])) {
    $errors[] = "Date of birth must be a real date in dd/mm/yyyy format.";
} else {
    // $m[1] = day, $m[2] = month, $m[3] = year. MySQL needs yyyy-mm-dd
    $dob_sql = $m[3] . "-" . $m[2] . "-" . $m[1];
    $age = date("Y") - (int)$m[3];
    if ($age < 15 || $age > 80) {
        $errors[] = "Age must be between 15 and 80.";
    }
}

if ($gender != "Male" && $gender != "Female" && $gender != "Other") {
    $errors[] = "Please select a gender.";
}

if (strlen($street) < 1 || strlen($street) > 40) {
    $errors[] = "Street address is required (maximum 40 characters).";
}

if (strlen($suburb) < 1 || strlen($suburb) > 40) {
    $errors[] = "Suburb/town is required (maximum 40 characters).";
}

$states = array("VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT");
if (!in_array($state, $states)) {
    $errors[] = "Please select a valid state.";
}

if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8 to 12 digits.";
}

// 6. Show the page
include("header.inc");
echo "<link rel=\"stylesheet\" href=\"styles/eoi.css\">";
echo "<main class=\"eoi-result\"><div class=\"eoi-card\">";

if (count($errors) > 0) {
    // show error messages
    echo "<div class=\"eoi-icon eoi-icon-error\">!</div>";
    echo "<h1>Application could not be submitted</h1>";
    echo "<p class=\"eoi-sub\">Please fix the following and try again:</p>";
    echo "<ul class=\"eoi-errors\">";
    foreach ($errors as $e) {
        echo "<li>" . $e . "</li>";
    }
    echo "</ul>";
    echo "<div class=\"eoi-actions\"><a class=\"eoi-btn\" href=\"apply.php\">Back to the application form</a></div>";
} else {
    // 7. Create the eoi table if it does not exist
    $create = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber    INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        job_ref      VARCHAR(5)   NOT NULL,
        first_name   VARCHAR(20)  NOT NULL,
        last_name    VARCHAR(20)  NOT NULL,
        dob          DATE         NOT NULL,
        gender       ENUM('Male','Female','Other') NOT NULL,
        street       VARCHAR(40)  NOT NULL,
        suburb       VARCHAR(40)  NOT NULL,
        state        ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
        postcode     CHAR(4)      NOT NULL,
        email        VARCHAR(100) NOT NULL,
        phone        VARCHAR(12)  NOT NULL,
        skills       SET('HTML','CSS','JavaScript','Python') NULL,
        other_skills TEXT NULL,
        status       ENUM('New','Current','Final') NOT NULL DEFAULT 'New'
    )";
    mysqli_query($conn, $create);

    // 8. Make the data safe for the SQL query
    $jobref       = mysqli_real_escape_string($conn, $jobref);
    $first_name   = mysqli_real_escape_string($conn, $first_name);
    $last_name    = mysqli_real_escape_string($conn, $last_name);
    $street       = mysqli_real_escape_string($conn, $street);
    $suburb       = mysqli_real_escape_string($conn, $suburb);
    $email        = mysqli_real_escape_string($conn, $email);
    $skills       = mysqli_real_escape_string($conn, $skills);
    $other_skills = mysqli_real_escape_string($conn, $other_skills);

    // 9. Insert the record into the eoi table
    $sql = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street, suburb, state, postcode, email, phone, skills, other_skills)
            VALUES ('$jobref', '$first_name', '$last_name', '$dob_sql', '$gender', '$street', '$suburb', '$state', '$postcode', '$email', '$phone', '$skills', '$other_skills')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // get the EOInumber that MySQL created
        $eoi_number = mysqli_insert_id($conn);

        echo "<div class=\"eoi-icon eoi-icon-ok\">&#10003;</div>";
        echo "<h1>Application received</h1>";
        echo "<p class=\"eoi-sub\">Thank you, " . $first_name . ". Your application has been submitted.</p>";
        echo "<div class=\"eoi-number\"><span>Your EOI number</span><strong>" . $eoi_number . "</strong></div>";
        echo "<ul class=\"eoi-details\">";
        echo "<li><span>Position</span><strong>" . $jobref . "</strong></li>";
        echo "<li><span>Applicant</span><strong>" . $first_name . " " . $last_name . "</strong></li>";
        echo "<li><span>Email</span><strong>" . $email . "</strong></li>";
        echo "<li><span>Status</span><strong>New</strong></li>";
        echo "</ul>";
        echo "<p class=\"eoi-note\">Please keep your EOI number for your records.</p>";
        echo "<div class=\"eoi-actions\"><a class=\"eoi-btn\" href=\"index.php\">Back to home</a> <a class=\"eoi-btn eoi-btn-light\" href=\"jobs.php\">View more jobs</a></div>";
    } else {
        echo "<div class=\"eoi-icon eoi-icon-error\">!</div>";
        echo "<h1>Something went wrong</h1>";
        echo "<p class=\"eoi-sub\">We could not save your application. Please try again.</p>";
        echo "<div class=\"eoi-actions\"><a class=\"eoi-btn\" href=\"apply.php\">Back to the application form</a></div>";
    }
}

echo "</div></main>";
include("footer.inc");

if ($conn) {
    mysqli_close($conn);
}
?>