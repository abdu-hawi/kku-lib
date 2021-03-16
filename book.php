<?php
include ("DB/session.php");
$id =  $_GET["id"];
require_once ("DB/book.php");
require_once ("Controller/geners.php");
$book = get_book_by_id($id);
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Gentium+Basic&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Gentium+Basic&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="design/css/main.css">
    <link rel="stylesheet" href="design/css/book.css">
</head>
<body>

<div class="nav">
    <ul class="nav">
        <li><a href="../library">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Categories</a>
            <div class="dropdown-content">
                <?php
                foreach ($genres as $genre){
                    echo '<a href="genre/'.$genre["id"].'">'.$genre["name"].'</a>';
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
    <div class="desc-content">

        <div class="" style="width: 100%">
            <h2 class="book-panel-info-title title-book-with-author">
                <?php echo $book["title"]; ?>
            </h2>

            <span class="author">By:
                <?php
                if (array_search("authors",$book)){
                    for ($n=0;$n<count($book["authors"]);$n++){
                        if ($n != count($book["authors"])-1)
                            echo $book["authors"][$n]."&nbsp&nbsp-&nbsp&nbsp";
                        else echo $book["authors"][$n];
                    }
                }

                ?>
            </span>
            <div class="book-panel-info-categories dash-left dash-dark">
                <span class="dash-dark">
                    <?php
//                    if (array_search("genres",$book)){
                        for ($n=0;$n<count($book["genres"]);$n++){
                            if ($n != count($book["genres"])-1)
                                echo '<a class="genres" href="" rel="tag">'.$book["genres"][$n]."</a> ,";
                            else echo '<a class="genres" href="" rel="tag">'.$book["genres"][$n]."</a>";
                        }
//                    }
                    ?>
<!--                    <a class="genres" href="" rel="tag">history</a>-->
<!--                    ,-->
<!--                    <a class="genres" href="" rel="tag">historical fiction</a>-->
<!--                    ,-->
<!--                    <a class="genres" href="" rel="tag">biography</a>-->
                </span>
            </div>
            <div>
                <img src="design/image/<?php echo round($book["rate"]); ?>.png"
                     alt="<?php echo round($book["rate"]); ?>" style="height: 1.3em">
                <span><?php echo round($book["rate"]); ?></span>&nbsp&nbsp&nbsp
                <span>review: <strong><?php echo $book["count"]; ?></strong></span>
            </div>
            <hr class="genres">
            <div class="publish">
                Paperback, <span><?php echo $book["num_pages"]; ?></span> pages
                <div>
                    Published by: <span><?php echo $book["publisher"]; ?></span>
                </div>
            </div>

            <div class="description info-box-panel info-box-panel-description special-first-letter entry-content">
                <div class="ElementHeading">
                    <h2 class="element-title">
                        Description
                    </h2>
                </div>
                <p>
                    <?php echo $book["description"]; ?>
                </p>
            </div>

            <div class="description info-box-panel info-box-panel-description special-first-letter entry-content">
                <div class="ElementHeading">
                    <h2 class="element-title">
                        Reviews
                    </h2>
                </div>
                <div class="reviews">
                    <?php
                    for ($r=0;$r<count($book["reviews"]);$r++){
                        ?>
                        <div style="width: 100%">
                            <span class="author"><?php echo $book["reviews"][$r]["username"] ?></span>
                            <img src="design/image/<?php echo round($book["reviews"][$r]["rating"]) ?>.png"
                                 alt="<?php echo round($book["reviews"][$r]["rating"]) ?>" style="height: 1em; margin-left: 4rem">
                            <span><?php echo round($book["reviews"][$r]["rating"]) ?></span>
                        </div>
                        <div>
                            <?php echo $book["reviews"][$r]["review_text"] ?>
                        </div>
                    <?php
                        if ($r != count($book["reviews"])-1)
                            echo "<hr>";
                    }
                    ?>
                </div>
            </div>
            <?php
//            print_r($book["reviews"]);
            ?>
        </div>

    </div>
    <div class="clip">
        <section>
            <div class="gallery">
                <a href="javascript:void(0)" onclick="this.preventDefault();">
                    <img src="<?php echo $book["image_url"]; ?>" alt="<?php echo $book["title"]; ?>" >
                </a>
            </div>
        </section>
    </div>
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
