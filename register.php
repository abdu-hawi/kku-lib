<!DOCTYPE html>
<html>
<head>
    <title>Book recommendations system</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="design/css/register.css" />
    <style>
        .btn-reader-register{
            margin-top: 10px;
            width: 40%;
            height: 50px;
            background-color: #428836;
            border: 2px solid #2e6025;
            font-size: 18px;
            color: white;
            border-radius: 8px;
            transition-duration: 0.4s;
        }
        .btn-reader-register:hover{
            background-color: #296025;
            border: 2px solid #2e6025;
            border-radius: 8px;
        }
        .form-reader label input{
            color: #2e6025;
        }
    </style>
</head>
<body>
<div class="header">
    <p class="header-join">Join us and be one of our family</p>
    <div class="header-container">
        <div class="header-left">
            Book recommendations system
        </div>
        <div class="header-right">
            <a href="login.php">
                Sign in
            </a>
            |
            <a href="#">
                Sign up
            </a>
        </div>
    </div>
</div>

<div class="container">
    <div class="content">
        <div class="tab">
            <button class="tablinks" onclick="openUser(event, 'Company')" id="defaultOpen">Company</button>
            <button class="tablinks" onclick="openUser(event, 'User')">Reader</button>
        </div>
        <div class="search">
            <!-- company -->
            <div id="Company" class="tab-content">
                <form class="form-company" action="DB/company/saveCompany.php" method="post">
                    <div class="label">
                        <label>
                            Company name
                            <input name="publish" type="text">
                        </label>
                    </div>
                    <div class="label">
                        <label>
                            Username
                            <input name="username" type="text">
                        </label>
                    </div>
                    <div class="label">
                        <label>
                            Password
                            <input name="password" type="password">
                        </label>
                    </div>
                    <div class="label">
                        <label for="email">Email</label>
                        <input name="email" type="email" id="email">
                    </div>
                    <input type="submit" value="Register" class="btn-company-register">
                </form>
            </div>

            <!-- Reader -->
            <div id="User" class="tab-content">
                <form class="form-reader" method="post" action="DB/reader/saveReader.php">
                    <div class="label">
                        <label>
                            Reader name
                            <input name="name" type="text">
                        </label>
                    </div>
                    <div class="label">
                        <label>
                            Username
                            <input name="username" type="text">
                        </label>
                    </div>
                    <div class="label">
                        <label>
                            Password
                            <input name="password" type="password">
                        </label>
                    </div>
                    <div class="label">
                        <label for="email">Email</label>
                        <input name="email" type="email" id="email">
                    </div>
                    <div class="label">
                        <label for="age">Age</label>
                        <input name="age" type="number" id="age">
                    </div>
                    <div class="label">
                        <label for="gender">Gender</label>
                        <div class="gender">
                            <input type="radio" id="male" name="gender" value="male">
                            <label for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">Female</label>
                        </div>
                    </div>
                    <input type="submit" value="Register" class="btn-reader-register">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openUser(evt, userType) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(userType).style.display = "block";
        evt.currentTarget.className += " active";
    }
    document.getElementById("defaultOpen").click();
</script>

</body>
</html> 