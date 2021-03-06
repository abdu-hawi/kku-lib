<!DOCTYPE html>
<html>
<head>
    <title>Book recommendations system</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="design/css/register.css" />
    <style>
        .btn-reader-login{
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
        .btn-reader-login:hover{
            background-color: #2e6025;
            border: 2px solid #2e6025;
            border-radius: 8px;
        }
        .form-reader.label label{
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
            <a class="login" href="#">
                Sign in
            </a>
            |
            <a class="login" href="register.php">
                Sign up
            </a>
        </div>
    </div>
</div>

<div class="container">
    <div class="content">
        <div class="tab">
            <button class="tablinks" onclick="openCity(event, 'Company')" id="defaultOpen">Company</button>
            <button class="tablinks" onclick="openCity(event, 'User')">Reader</button>
        </div>
        <div class="search">
            <!-- company -->
            <div id="Company" class="tab-content">
                <form class="form-company" action="DB/company/checkLogin.php" method="post">
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
                    <input type="submit" value="Login" class="btn-company-register">
                </form>
            </div>

            <!-- Reader -->
            <div id="User" class="tab-content">
                <form class="form-reader" method="post" action="DB/reader/checkLogin.php">
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
                    <input type="submit" value="Login" class="btn-reader-login">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    document.getElementById("defaultOpen").click();
</script>

</body>
</html>