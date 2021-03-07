<!DOCTYPE html>
<html>
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
        <li><a href="#home">Home</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Categories</a>
            <div class="dropdown-content">
                <a href="#">history, historical fiction, biography</a>
                <a href="#">fiction</a>
                <a href="#">fantasy, paranormal</a>
                <a href="#">mystery, thriller, crime</a>
                <a href="#">poetry</a>
                <a href="#">romance</a>
                <a href="#">non-fiction</a>
                <a href="#">children</a>
                <a href="#">young-adult</a>
                <a href="#">comics, graphic</a>
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
        <div class="" id="hide0" style="width: 100%">
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
                <img src="design/image/s4.png" alt="4.5" style="height: 1.3em">
                <span>4.5</span>
            </div>
            <hr class="genres">
<!--            <div style="height: 5px; width: 100%; border-bottom: 3px #fa7a6a dotted"></div>-->
            <div class="publish">
                Paperback, <span>265</span> pages
                <div>
                    Published by <span>St. Martin's Press</span>
                </div>
            </div>
            <div class="btn-read">
                <a href="" target="_blank"><button>View book</button></a>
            </div>

            <div class="btn-more-book">
                <a href="" target="_blank"><button>View more book</button></a>
            </div>

        </div>
        <div id="hide1" style="width: 100%">
            show text 1
        </div>
        <div id="hide2" style="width: 100%">
            show text 2
        </div>
    </div>
    <div class="clip">
        <section>
            <div class="gallery" onclick="hide(0)">
                <a href="javascript:void(0)" onclick="this.preventDefault();">
                    <img src="https://images.gr-assets.com/books/1310220028m/5333265.jpg" alt="Cinque Terre" width="600" height="400">
                </a href="">
                <div class="desc">W.C. Fields: A Life on Film</div>
            </div>
            <div class="gallery" onclick="hide(1)">
                <a href="javascript:void(0)">
                    <img src="https://s.gr-assets.com/assets/nophoto/book/111x148-bcc042a9c91a29c1d680899eff700a03.png" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc">Good Harbor</div>
            </div>
            <div class="gallery" onclick="hide(2)">
                <a href="javascript:void(0)">
                    <img src="https://images.gr-assets.com/books/1304100136m/7327624.jpg" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc">The Unschooled Wizard (Sun Wolf and Starhawk, #1-2)e</div>
            </div>
            <div class="gallery">
                <a target="_blank" href="">
                    <img src="https://s.gr-assets.com/assets/nophoto/book/111x148-bcc042a9c91a29c1d680899eff700a03.png" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc">Best Friends Forever</div>
            </div>
            <div class="gallery">
                <a target="_blank" href="">
                    <img src="https://s.gr-assets.com/assets/nophoto/book/111x148-bcc042a9c91a29c1d680899eff700a03.png" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc">Runic Astrology: Starcraft and Timekeeping in the Northern Tradition</div>
            </div>
            <div class="gallery" id = "gallery">
                <a target="_blank" href="">
                    <img src="https://images.gr-assets.com/books/1316637798m/6066812.jpg" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc">Playmaker: A Venom Series Novella</div>
            </div>
        </section>
    </div>
</section>

<!--<script src="design/js/jquery.min.js"></script>-->
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
        for (var i = 0; i < 3; i++) {
            document.getElementById('hide' + i).style.display = 'none';
        }
        ele.style.display = 'block';
    }
</script>
</body>
</html>