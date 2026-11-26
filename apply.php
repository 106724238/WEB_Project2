<!-- AI used to generate logo of this page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply</title>
    <link rel="stylesheet" href="styles/apply.css">
</head>
<body id="trang">
    <div class="header">
        <div class="ok">
            <img id="logo" src="images/logo.jpg" alt="EdTech logo" width="65">
            <nav>
                <div class="menu"><a href="index.php"><strong>MENU</strong></a></div>
                <div class="menu"><a href="jobs.php"><strong>JOBS</strong></a></div>
                <div class="menu"><a href="apply.php"><strong>APPLY</strong></a></div>
                <div class="menu"><a href="about.php"><strong>ABOUT</strong></a></div>
            </nav>
        </div>
    </div>

    <div id="diff">
        <h1>Job Application Form</h1>

        <form action="process_eoi.php" method="post" novalidate>

            <label for="jobref">Job Reference Number:</label>
            <input type="text" id="jobref" name="jobref"
                   placeholder="Enter exactly 5 alphanumeric characters">
            <br><br>

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname"
                   placeholder="Enter up to 20 alphabetic characters">
            <br><br>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname"
                   placeholder="Enter up to 20 alphabetic characters">
            <br><br>

            <label for="dob">Date of Birth (dd/mm/yyyy):</label>
            <input type="text" id="dob" name="dob"
                   placeholder="dd/mm/yyyy">
            <br><br>

            <fieldset>
                <legend>Gender:</legend>

                <input type="radio" id="male" name="gender"
                       value="Male">
                <label for="male">Male</label>

                <input type="radio" id="female" name="gender"
                       value="Female">
                <label for="female">Female</label>

                <input type="radio" id="other" name="gender"
                       value="Other">
                <label for="other">Other</label>
            </fieldset>
            <br>

            <label for="street">Street Address:</label>
            <input type="text" id="street" name="street">
            <br><br>

            <label for="suburb">Suburb/Town:</label>
            <input type="text" id="suburb" name="suburb">
            <br><br>

            <label for="state">State:</label>
            <select id="state" name="state">
                <option value="">Please select</option>
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="NT">NT</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
            </select>
            <br><br>

            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode"
                   placeholder="Enter exactly 4 digits">
            <br><br>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email">
            <br><br>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone"
                   placeholder="Enter 8 to 12 digits">
            <br><br>

            <fieldset>
                <legend>Skills:</legend>

                <input type="checkbox" id="html" name="skills[]"
                       value="HTML">
                <label for="html">HTML</label>

                <input type="checkbox" id="css" name="skills[]"
                       value="CSS">
                <label for="css">CSS</label>

                <input type="checkbox" id="javascript" name="skills[]"
                       value="JavaScript">
                <label for="javascript">JavaScript</label>

                <input type="checkbox" id="python" name="skills[]"
                       value="Python">
                <label for="python">Python</label>
            </fieldset>
            <br>

            <label for="otherskills">Other Skills:</label>
            <br>
            <textarea id="otherskills" name="otherskills"
                      rows="5" cols="40"></textarea>
            <br><br>

            <div class="form-actions">
                <input type="submit" value="Apply">
                <input type="reset" value="Reset">
            </div>

        </form>
    </div>

    <footer>
        <p>&copy; 2026 EdTech – Educational Technology Company</p>
    </footer>
</body>
</html>
