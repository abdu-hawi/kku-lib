<?php
include ("DB/session.php");
require_once ("DB/db.php");
require_once ("Controller/geners.php");
require_once ("DB/book.php");

$books = get_book_by_genres($_GET["genres"]);

//echo "<pre>";
//print_r($books);
//echo "</pre>";
//exit();
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
    <link rel="stylesheet" href="design/css/genres.css">
    <style>
        div.clearfix {
            padding: 5px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .img2 {
            float: left;
        }
    </style>
</head>
<body>

<div class="nav">
    <ul class="nav">
        <li><a href="/library">Home</a></li>
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


<section>

    <?php
    foreach ($books as $book){
    ?>
        <p style="clear:left"></p>

        <div class="clearfix">
            <div class="gallery" id = "gallery">
                <img class="img2" src="<?php echo $book["image_url"]; ?>" alt="Cinque Terre" width="600" height="400">
            </div>
            <div class="desc-content">
                <h2 class="book-panel-info-title title-book-with-author">
                    <?php echo $book["title"]; ?>
                </h2>
                <span class="author">By:
                    <?php
                    for($i=0;$i<count($book["authors"]);$i++){
                        if ($i+1 != count($book["authors"]) )
                            echo $book["authors"][$i]." <span style=\"color:#000\">-</span> ";
                        else
                            echo $book["authors"][$i];
                    }
                    ?>
                </span>
                <div class="book-panel-info-categories dash-left dash-dark">
                <span class="dash-dark">
                    <?php
                    $i = 1;
                    $cnt = count($book["genres"]);
                    foreach ($book["genres"] as $id=>$name){
                        if ($i != $cnt)
                            echo '<a class="genres" href="genres.php?genres='.$id.'">'.$name.'</a> - ';
                        else
                            echo '<a class="genres" href="genres.php?genres='.$id.'">'.$name.'</a>';
                        $i++;
                    }
                    ?>
                </span>
                </div>
                <div>
                    <img src="design/image/<?php echo $book["rate"]; ?>.png" alt="<?php echo $book["rate"]; ?>" style="height: 1.3em">
                    <span><?php echo $book["rate"]; ?></span>
                </div>
                <hr class="genres">
                <table class="publish" width="100%">
                    <tr>
                        <td colspan="2">Paperback, <span><?php echo $book["num_pages"]; ?></span> pages</td>
                    </tr>
                    <tr>
                        <td>
                        <span>
                            Published by <span><?php echo $book["publisher"]; ?></span>
                        </span>
                        </td>
                        <td class="btn-read">
                            <a href="" target="_blank"><button>View book</button></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php
    }
    ?>

    <!--
    <p style="clear:left"></p>

    <div class="clearfix">
        <div class="gallery" id = "gallery">
            <img class="img2" src="https://images.gr-assets.com/books/1310220028m/5333265.jpg" alt="Cinque Terre" width="600" height="400">
        </div>
        <div class="desc-content">
            <h2 class="book-panel-info-title title-book-with-author">
                W.C. Fields: A Life on Film
            </h2>
            <span class="author">By: Some Name</span>
            <div class="book-panel-info-categories dash-left dash-dark">
                <span class="dash-dark">
                    <a class="genres" href="" rel="tag">history</a>
                    ,
                    <a class="genres" href="" rel="tag">historical fiction</a>
                    ,
                    <a class="genres" href="" rel="tag">biography</a>
                </span>
            </div>
            <div>
                <img src="design/image/4.png" alt="4" style="height: 1.3em">
                <span>4</span>
            </div>
            <hr class="genres">
            <table class="publish" width="100%">
                <tr>
                    <td colspan="2">Paperback, <span>290</span> pages</td>
                </tr>
                <tr>
                    <td>
                        <span>
                            Published by <span>St. Martin's Press</span>
                        </span>
                    </td>
                    <td class="btn-read">
                        <a href="" target="_blank"><button>View book</button></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
-->
</section>

<script src="design/js/jquery.min.js"></script>
<script>
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
