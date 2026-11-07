<!-- AI used to generate logo of this page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply</title>
    <link rel="stylesheet" href="apply.css">
</head>
<body id="trang">
    <div class="header">
        <div class="ok">
            <img id="logo" src="images/logo.jpg" alt="EdTech logo" width="65">
            <nav>
                <div class="menu"><a href="index.html"><strong>MENU</strong></a></div>
                <div class="menu"><a href="jobs.html"><strong>JOBS</strong></a></div>
                <div class="menu"><a href="apply.html"><strong>APPLY</strong></a></div>
                <div class="menu"><a href="about.html"><strong>ABOUT</strong></a></div>
            </nav>
        </div>
    </div>
    <div id="diff">
       <h1>Job Application Form</h1>

       <form action="https://mercury.swin.edu.au/it000000/formtest.php" method="post">

              <label for="jobref">Job Reference Number:</label>
              <input type="text" id="jobref" name="jobref"
                     pattern="[A-Za-z0-9]{5}"
                     title="Enter exactly 5 alphanumeric characters"
                     placeholder="Enter exactly 5 alphanumeric characters"
                     required>
              <br><br>

              <label for="firstname">First Name:</label>
              <input type="text" id="firstname" name="firstname"
                     maxlength="20" pattern="[A-Za-z]+"
                     title="Enter up to 20 alphabetic characters"
                     placeholder="Enter up to 20 alphabetic characters"
                     required>
              <br><br>

              <label for="lastname">Last Name:</label>
              <input type="text" id="lastname" name="lastname"
                     maxlength="20" pattern="[A-Za-z]+"
                     title="Enter up to 20 alphabetic characters"
                     placeholder="Enter up to 20 alphabetic characters"
                     required>
              <br><br>

              <label for="dob">Date of Birth (dd/mm/yyyy):</label>
              <input type="text" id="dob" name="dob"
                     placeholder="dd/mm/yyyy"
                     pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/[0-9]{4}"
                     title="Enter the date in dd/mm/yyyy format"
                     required>
              <br><br>

              <fieldset>
              <legend>Gender:</legend>

              <input type="radio" id="male" name="gender"
                     value="Male" required>
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
              <input type="text" id="street" name="street"
                     maxlength="40" required>
              <br><br>

              <label for="suburb">Suburb/Town:</label>
              <input type="text" id="suburb" name="suburb"
                     maxlength="40" required>
              <br><br>

              <label for="state">State:</label>
              <select id="state" name="state" required>
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
                     pattern="[0-9]{4}" maxlength="4"
                     title="Enter exactly 4 digits"
                     placeholder="Enter exactly 4 digits"
                     required>
              <br><br>

              <label for="email">Email:</label>
              <input type="email" id="email" name="email" required>
              <br><br>

              <label for="phone">Phone Number:</label>
              <input type="tel" id="phone" name="phone"
                     pattern="[0-9]{8,12}" maxlength="12"
                     title="Enter 8 to 12 digits"
                     placeholder="Enter 8 to 12 digits"
                     required>
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