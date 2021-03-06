<?php
include ("DB/session.php");
require_once ("Controller/index.php");
//echo "<pre>";
//print_r($books);
//echo "</pre>";
//die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Page Title</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Gentium+Basic&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="design/css/main.css">
    <link rel="stylesheet" href="design/css/home.css">
</head>
<body>

<div class="nav">
    <ul class="nav">
        <li><a href="javascript:void(0)">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Categories</a>
            <div class="dropdown-content">
                <?php
                foreach ($genres as $genre){
                    echo '<a href="genres.php?genres='.$genre["id"].'">'.$genre["name"].'</a>';
                }
                ?>
            </div>
        </li>
        <?php
        if (isset($_SESSION['userinfo']) && $_SESSION['userinfo'] != false){
            ?>
            <li class="dropdown login">
                <a href="javascript:void(0)" class="dropbtn"><?php echo $_SESSION['userinfo'][2] ?></a>
                <div class="dropdown-content">
                    <a class="active" href="logout.php">  Logout</a>
                </div>
            </li>
        <?php
        }else{
            echo '<li class="login"><a class="active" href="login.php">Login</a></li>
                  <li class="login"><a class="active" href="register.php">Register</a></li>
                    ';
        }
        ?>

        <div class="search-container">
            <input type="text" placeholder="Write book name to search ..." name="search" id="txt_search">
        </div>
    </ul>
    <ul id="searchResult">
    </ul>
</div>


<section style="width: 100%; margin: 0">
    <div class="book-show-container">
        <img class="book-show-img" id="img" src="design/image/shelves2.png">
        <div class="SpecialHeading" id="SpecialHeading">
            <h1 class="special-title special-heading-letter">
                Read With Us
            </h1>
        </div>
    </div>
</section>

<section>
    <div class="SpecialHeading topic">
        <h3 class="special-title special-heading-letter">read your book</h3>
    </div>
</section>

<section>
    <div class="desc-content">
<!--        --><?php //for ($b=0;$b<count($books);$b++){ ?>
        <?php $b=0; foreach ($books as $book){ ?>
        <div class="" id="hide<?php echo $b; ?>" style="width: 100%">
            <h2 class="book-panel-info-title title-book-with-author">
                <?php echo $book["title"]; ?>
            </h2>
            <span class="author">By: <?php
                if (gettype($book["authors"]) != "array")
                    echo $book["authors"];
                else{
                    $cnt = count($book["authors"]);
                    $i=1;
                    foreach ($book["authors"] as $author){
                        if ($cnt == $i) echo $author;
                        else{
                            echo $author.", ";
                            $i++;
                        }
                    }
                }
                ?>
            </span>
            <div class="book-panel-info-categories dash-left dash-dark">
                <span class="dash-dark">
                    <a class="genres" href="" rel="tag">
                        <?php
                        if (gettype($book["genres"]) != "array")
                        echo $book["genres"];
                        else{
                            $cnt = count($book["genres"]);
                            $i=1;
                            foreach ($book["genres"] as $genre){
                                if ($cnt == $i) echo $genre;
                                else{
                                    echo $genre.", ";
                                    $i++;
                                }
                            }
                        }
                        ?>
                    </a>
                </span>
            </div>
            <div>
                <?php
                $rate = round($book["rate"],1);
                echo "
                    <img src='design/image/".str_replace(".","_",$rate).".png' alt='$rate' style='height: 1.3em'>
                    <span>$rate</span>
                ";
                ?>

            </div>
            <hr class="genres">
            <div class="publish">
                Paperback, <span><?php echo $book["num_pages"]; ?></span> pages
                <div>
                    Published by <span><?php echo $book["publisher"]; ?></span>
                </div>
            </div>
            <div class="btn-read">
                <a href="book.php?id=<?php echo $book["id"]; ?>" target="_blank"><button>View book</button></a>
            </div>

            <div class="btn-more-book">
                <a href="books.php" target="_blank"><button>View more book</button></a>
            </div>

        </div>
        <?php $b++;} ?>
    </div>
    <div class="clip">
        <section>
            <?php $b=0;foreach ($books as $book){ ?>
            <div class="gallery" onclick="hide(<?php echo $b; ?>)">
                <a href="javascript:void(0)" onclick="this.preventDefault();">
                    <img src="<?php echo $book["image_url"]; ?>" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc"><?php echo $book["title"]; ?></div>
            </div>
            <?php $b++;} ?>
        </section>
    </div>
</section>
<?php
include("footer.php");
?>
<script src="design/js/jquery.min.js"></script>
<script>
    const img = document.getElementById("img");
    img.onload = function () {
        const h = img.height
        document.getElementById("SpecialHeading").style.marginTop = 50-h+"px";
        document.getElementById("SpecialHeading").style.paddingBottom = h/2+"px";
    }

    hide(0)
    function hide(e) {
        const ele = document.getElementById('hide' + e);
        for (var i = 0; i < 6; i++) {
            document.getElementById('hide' + i).style.display = 'none';
        }
        ele.style.display = 'block';
    }

    $("#txt_search").keyup(function() {
        var search = $(this).val();

        if (search !== "") {
            $.ajax({
                url: 'Controller/Search.php',
                type: 'post',
                data: {search:search},
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#searchResult").empty();
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var name = response[i]['title'];

                        $("#searchResult").append("<li value='"+id+"'>"+name+"</li>");

                    }

                    // binding click event to li
                    $("#searchResult li").bind("click",function(){
                        setText(this);
                    });

                },error:function (e, t) {
                    console.log(e.responseText)
                }
            });
        }else {
            $("#searchResult").empty();
        }
    })
    function setText(element) {
        var value = $(element).text();
        var id = $(element).val();
        $("#searchResult").empty();
        var redirectWindow = window.open('book.php?id='+id, '_self');
        redirectWindow.location;
    }
</script>
</body>
</html>