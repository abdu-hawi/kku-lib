<?php
require_once ("Controller/index.php");
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
                    echo '<a href="genre/'.$genre["id"].'">'.$genre["name"].'</a>';
                }
                ?>
<!--                <a href="#">history, historical fiction, biography</a>-->
<!--                <a href="#">fiction</a>-->
<!--                <a href="#">fantasy, paranormal</a>-->
<!--                <a href="#">mystery, thriller, crime</a>-->
<!--                <a href="#">poetry</a>-->
<!--                <a href="#">romance</a>-->
<!--                <a href="#">non-fiction</a>-->
<!--                <a href="#">children</a>-->
<!--                <a href="#">young-adult</a>-->
<!--                <a href="#">comics, graphic</a>-->
            </div>
        </li>
        <li class="login"><a class="active" href="#about">Login</a></li>
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
        <?php for ($b=0;$b<count($books);$b++){ ?>
        <div class="" id="hide<?php echo $b; ?>" style="width: 100%">
            <h2 class="book-panel-info-title title-book-with-author">
                <?php echo $books[$b]["title"]; ?>
            </h2>
            <span class="author">By: <?php echo $books[$b]["authors"]; ?></span>
            <div class="book-panel-info-categories dash-left dash-dark">
                <span class="dash-dark">
                    <a class="genres" href="" rel="tag"><?php echo $books[$b]["genres"]; ?></a>
                </span>
            </div>
            <div>
                <?php
                $rate = round($books[$b]["rate"]);
                echo "
                    <img src='design/image/$rate.png' alt='$rate' style='height: 1.3em'>
                    <span>$rate</span>
                ";
                ?>

            </div>
            <hr class="genres">
            <div class="publish">
                Paperback, <span><?php echo $books[$b]["num_pages"]; ?></span> pages
                <div>
                    Published by <span><?php echo $books[$b]["publisher"]; ?></span>
                </div>
            </div>
            <div class="btn-read">
                <a href="book.php?id=<?php echo $books[$b]["id"]; ?>" target="_blank"><button>View book</button></a>
            </div>

            <div class="btn-more-book">
                <a href="" target="_blank"><button>View more book</button></a>
            </div>

        </div>
        <?php } ?>
    </div>
    <div class="clip">
        <section>
            <?php for ($b=0;$b<count($books);$b++){ ?>
            <div class="gallery" onclick="hide(<?php echo $b; ?>)">
                <a href="javascript:void(0)" onclick="this.preventDefault();">
                    <img src="<?php echo $books[$b]["image_url"]; ?>" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc"><?php echo $books[$b]["title"]; ?></div>
            </div>
            <?php } ?>
        </section>
    </div>
</section>

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