<?php
include ("DB/session.php");
$id =  $_GET["id"];
require_once ("DB/book.php");
require_once ("Controller/geners.php");
$book = get_book_by_id($id);
require_once ("Sort/cosin.php");
$booksRecomminder = get_recomminder($id,true,false);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
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
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Categories</a>
            <div class="dropdown-content">
                <?php
                foreach($genres as $genre){
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
    <div class="desc-content">

        <div class="" style="width: 100%">
            <h2 class="book-panel-info-title title-book-with-author">
                <?php echo $book["title"]; ?>
            </h2>

            <span class="author">By:
                <?php
				$n = 1;
				foreach($book["authors"] as $author){
					if ($n != count($book["authors"]))
						echo $author."&nbsp&nbsp-&nbsp&nbsp";
					else echo $author;
					$n++;
				}
                // if (array_search("authors",$book)){
                    // for ($n=0;$n<count($book["authors"]);$n++){
                        // if ($n != count($book["authors"])-1)
                            // echo $book["authors"][$n]."&nbsp&nbsp-&nbsp&nbsp";
                        // else echo $book["authors"][$n];
                    // }
                // }
                ?>
            </span>
            <div class="book-panel-info-categories dash-left dash-dark">
                <span class="dash-dark">
                    <?php
                   if (array_search("genres",$book)){
                        for ($n=0;$n<count($book["genres"]);$n++){
                            if ($n != count($book["genres"])-1)
                                echo '<a class="genres" href="" rel="tag">'.$book["genres"][$n]."</a> ,";
                            else echo '<a class="genres" href="" rel="tag">'.$book["genres"][$n]."</a>";
                        }
                   }
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

            <div class="description info-box-panel info-box-panel-description entry-content">
                <div class="ElementHeading">
                    <h2 class="element-title">
                        We are choice for you
                    </h2>
                </div>
                <div class="clip">
                    <section class="inline">
                        <?php
                        $j=1;
                        foreach ($booksRecomminder as $br){
                            if ($j>6) break;
                            ?>
                            <div class="gallery1">
                                <a  href="book.php?id=<?php echo $br["id"]; ?>" target="_blank">
                                    <img src="<?php echo $br["image_url"]; ?>" alt="Cinque Terre" width="" height="">
                                </a>
                                <div class="desc"><?php echo $br["title"]; ?></div>
                            </div>
                            <?php
                            $j++;
                        }
                        ?>
                    </section>
                </div>
            </div>






            <div class="description info-box-panel info-box-panel-description entry-content">
                <div class="ElementHeading">
                    <h2 class="element-title">
                        Write your review
                    </h2>
                </div>
                <div style="display: inline-block;width: 100%;">
                    <div class="rating">
                        <span class="ion-android-star-outline s5" onclick="setRating(5)"></span>
                        <span class="ion-android-star-outline s4" onclick="setRating(4)"></span>
                        <span class="ion-android-star-outline s3" onclick="setRating(3)"></span>
                        <span class="ion-android-star-outline s2" onclick="setRating(2)"></span>
                        <span class="ion-android-star-outline s1" onclick="setRating(1)"></span>
                    </div>
                    <br>

                    <div style="width: 100%;margin-top: 3em;display: block ruby;">
                        <form id="form">
                            <input type="hidden" name="rate" value="" id="rate" style="width: 100%"/>
                            <input type="hidden" name="book_id" value="<?php echo $book["id"]; ?>" style="width: 100%" id="book_id"/>
                            <input type="hidden" name="user_id" id="user_id" value="<?php if ($_SESSION['userinfo'] != false) echo $_SESSION['userinfo'][0] ?>"/>
                            <label for="review_text">Write your review: </label>
                            <textarea name="review_text" contenteditable="true" class="text-area"
                                      id="review_text" rows="8" cols="50" style="width: 100%"></textarea>
                        </form>
                    </div>
                    <div class="btn-read" style="width: 100%;margin-top: 0.3em;display: block ruby;">
                        <button onclick="formRating()">Submit</button>
                    </div>
                </div>
                
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
<?php
include("footer.php");
?>
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

    function setRating(e){
        for (var i = 1 ; i<6 ; i++){
            var r = $(".s"+i);
            r.removeClass("ion-android-star-outline");
            r.removeClass("ion-android-star");
            if (i<=e){
                r.addClass("ion-android-star");
            }else{
                r.addClass("ion-android-star-outline");
            }
        }
        $("#rate").val(e)
    }

    function formRating(){
        if ($("#user_id").val().trim().length < 1){
            alert("Please login to make review")
            return
        }
        if ($("#rate").val().trim().length < 1){
            alert("Please make rate")
            return
        }
        if ($("#book_id").val().trim().length < 1){
            alert("Something wrong, please refresh page")
            return
        }
        if ($("#review_text").val().trim().length < 1){
            alert("Please write your review")
            return
        }
        // var form= $("#form");
        $.ajax({
            type: "POST",
            url: "Controller/review.php",
            data: $("#form").serialize(),
            dataType: "json",
            success: function (data) {
                if (data.result) {
                    alert("Your review adding success")
                    setRating(0)
                    $("#review_text").val("")
                }else{
                    alert("Your review not adding")
                }

            }
        });
    }
</script>
</body>
</html>
