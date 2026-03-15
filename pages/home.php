<?php
$content = loadPageContent('home');

$galleryContent   = extractSection($content,'GALLERY');
$featuresContent  = extractSection($content,'FEATURES');
$sidebarContent   = extractSection($content,'SIDEBAR');
$mainNewsContent  = extractSection($content,'MAINNEWS');
$trailNewsContent = extractSection($content,'TRAILNEWS');
$articlesContent  = extractSection($content,'ARTICLES');


// Автоматическое формирование новостей
// Загружаем список записей
$entriesFile = 'content/news/entries.json';
$entries = file_exists($entriesFile) ? json_decode(file_get_contents($entriesFile), true) : [];
$entries = $entries['entries'] ?? [];

// Сортируем по дате (новые сначала)
usort($entries, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});

// Получаем 5 последних записей для правой колонки
$latestEntries = array_slice($entries, 0, 4);


//Автоматическое формирование списка последних статей
$articlesFile = 'content/articles/entries.json';
$articles = file_exists($articlesFile) ? json_decode(file_get_contents($articlesFile), true) : [];
$articles = $articles['entries'] ?? [];

// Сортируем по дате (новые сначала)
usort($articles, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});

// Получаем 4 последних записей для правой колонки
$latestArticles = array_slice($articles, 0, 4);


$gallerySettings = [
    'interval'=>5000,
    'transition'=>700,
    'autoPlay'=>true
];
?>

<section class="hero">

<div class="hero-container"
     data-interval="<?=$gallerySettings['interval']?>"
     data-transition="<?=$gallerySettings['transition']?>"
     data-autoplay="<?=$gallerySettings['autoPlay']?'true':'false'?>">

<div class="slides">

<?= $galleryContent ?>

</div>

<div class="hero-nav">
<button class="prev">‹</button>
<div class="dots"></div>
<button class="next">›</button>
</div>

</div>
</section>


<div class="home-content">

<main class="main-features">
<?= $featuresContent ?>
</main>

<aside class="home-sidebar">

  <div class="sidebar-data">
    <!-- Главное + новости -->
    <div class="news-block">
      <?= $mainNewsContent ?>

      <?php foreach ($latestEntries as $entry): ?>
        <div class="news-item">
          <div class="news-date"><?= date('d.m.Y', strtotime($entry['date'])) ?></div>
          <h4><a href="/news/<?= htmlspecialchars($entry['id']) ?>">
                <?= htmlspecialchars($entry['title'][$lang] ?? $entry['title']['ru'] ?? '') ?>
              </a>
          </h4>
          <p>
            <?= htmlspecialchars($entry['excerpt'][$lang] ?? $entry['excerpt']['ru'] ?? '') ?>
          </p>
        </div>
      <?php endforeach; ?>

      <?= $trailNewsContent ?>
    </div>
  </div>


  <div class="blog-block">
      <?= $articlesContent ?>

      <?php foreach ($latestArticles as $entry): ?>
        <div class="blog-item">
          <a href="/articles/<?= htmlspecialchars($entry['id']) ?>">
                <?= htmlspecialchars($entry['title'][$lang] ?? $entry['title']['ru'] ?? '') ?>
          </a>
        </div>
      <?php endforeach; ?>
  </div>

</aside>

</div>



<style>

/* HERO */

.hero{
background:#1a252f;
overflow:hidden;
}

.hero-container{
max-width:1400px;
margin:auto;
position:relative;
height:400px;
}

.slides{
position:relative;
height:100%;
}

/* slide */

.slide{
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
display:flex;
align-items:center;
opacity:0;
pointer-events:none;
}

.slide.active{
opacity:1;
pointer-events:auto;
}

/* layout */

.slide-layout{
display:flex;
align-items:center;
width:100%;
gap:40px;
padding:40px;
}

/* text column */

.slide-text{
flex:1;
color:white;
opacity:0;
transition:opacity .6s;
}

.slide.active .slide-text{
color:white;
opacity:1;
}

.slide-text h2{
font-size:40px;
margin-bottom:20px;
}

.slide-text p{
font-size:20px;
margin-bottom:30px;
line-height:1.4;
}

/* buttons */

.slide-buttons a{
display:inline-block;
margin-right:10px;
padding:10px 18px;
border-radius:4px;
text-decoration:none;
font-weight:bold;
}

.btn-download{
background:#3498db;
color:white;
}

.btn-more{
background:rgba(255,255,255,.2);
color:white;
}

/* image */

.slide-image{
flex:1;
display:flex;
justify-content:flex-end;
}

.slide-image img{
max-height:420px;
transform:translateX(80px);
opacity:0;
transition:transform .7s ease,opacity .7s;
}

.slide.active .slide-image img{
transform:translateX(0);
opacity:1;
}

/* nav */

.hero-nav{
position:absolute;
bottom:20px;
left:0;
right:0;
display:flex;
justify-content:center;
gap:20px;
}

.hero-nav button{
width:36px;
height:36px;
border-radius:50%;
border:none;
background:rgba(255,255,255,.2);
color:white;
font-size:20px;
cursor:pointer;
}

.dots{
display:flex;
gap:10px;
}

.dot{
width:10px;
height:10px;
border-radius:50%;
background:rgba(255,255,255,.3);
cursor:pointer;
}

.dot.active{
background:#3498db;
}

/* main layout */

.home-content {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    gap: 40px;
}

.main-features {
    flex: 3;
    min-width: 0;
}

.home-sidebar {
    flex: 1;
    min-width: 300px;
}

.zigzag {
    overflow: hidden;  /* чтобы блок не схлопывался */
    margin-bottom: 40px;
}
.zigzag-img {
    float: left;
    width: 400px;      /* нужный размер */
    margin-right: 30px;
    margin-bottom: 20px;
}
.zigzag.right .zigzag-img {
    float: right;
    margin-right: 0;
    margin-left: 30px;
}
.zigzag-text {
    overflow: hidden;  /* чтобы текст не обтекал лишнего */
}


/* NEWS */
.home-sidebar > div {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.home-sidebar h3 {
    color: #2c3e50;
    font-size: 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}

.block-icon {
    font-size: 20px;
}

/* Новости */
.news-item {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.news-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.news-date {
    color: #7f8c8d;
    font-size: 14px;
    margin-bottom: 5px;
}

.news-item h4 {
    margin: 0 0 8px 0;
}

.news-item h4 a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.2s;
}

.news-item h4 a:hover {
    color: #3498db;
}

.news-item p {
    color: #666;
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
}

.more-link a {
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
    display: block;
    text-align: center;
    padding: 10px;
    transition: all 0.2s;
}

.more-link a:hover {
    color: #2980b9;
    text-decoration: underline;
}


.main-news-item {
    background: #f4f8fc;
    border-left: 3px solid #3498db;
    padding: 12px 14px;
    margin-bottom: 25px;
}

.main-news-item h4 {
    margin: 0 0 6px 0;
}

.main-news-item h4 a {
    color: #2c3e50;
    text-decoration: none;
}

.main-news-item h4 a:hover {
    color: #3498db;
}

.main-news-item p {
    color: #555;
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
}





.news-header {
    margin-top: 25px;
}


.blog-block {

    margin-top: 25px;

    padding: 20px;

    border: 1px solid #e6e6e6;

    border-radius: 4px;

    background: #fafafa;

}

.blog-item {

    margin-bottom: 12px;

}

.blog-item:last-child {

    margin-bottom: 0;

}

.blog-item a {

    color: #2c3e50;

    text-decoration: none;

    font-size: 14px;

}

.blog-item a:hover {

    color: #3498db;

}

/*
.news-block{
background:#f7f7f7;
padding:20px;
border-radius:6px;
}

.news-item{
margin-bottom:18px;
padding-bottom:14px;
border-bottom:1px solid #ddd;
}

.news-date{
font-size:12px;
color:#888;
}

.news-title{
font-weight:bold;
margin:4px 0;
}

.news-title a{
text-decoration:none;
}

.news-text{
font-size:14px;
color:#444;
}
*/
</style>



<script>

document.addEventListener("DOMContentLoaded",function(){

const hero=document.querySelector(".hero-container");
if(!hero)return;

const slides=hero.querySelectorAll(".slide");
const dotsBox=hero.querySelector(".dots");
const prev=hero.querySelector(".prev");
const next=hero.querySelector(".next");

let index=0;
let timer=null;

const interval=parseInt(hero.dataset.interval)||5000;
const auto=hero.dataset.autoplay==="true";

/* dots */

slides.forEach((s,i)=>{
const d=document.createElement("span");
d.className="dot";
if(i===0)d.classList.add("active");
d.onclick=()=>go(i);
dotsBox.appendChild(d);
});

const dots=dotsBox.querySelectorAll(".dot");

/* init */

slides[0].classList.add("active");

/* change */

function go(i){

slides[index].classList.remove("active");
dots[index].classList.remove("active");

index=i;

slides[index].classList.add("active");
dots[index].classList.add("active");

reset();
}

function nextSlide(){
let i=index+1;
if(i>=slides.length)i=0;
go(i);
}

function prevSlide(){
let i=index-1;
if(i<0)i=slides.length-1;
go(i);
}

/* buttons */

next.onclick=nextSlide;
prev.onclick=prevSlide;

/* autoplay */

function reset(){
if(!auto)return;
clearInterval(timer);
timer=setInterval(nextSlide,interval);
}

if(auto)timer=setInterval(nextSlide,interval);

/* swipe */

let startX=0;

hero.addEventListener("touchstart",e=>{
startX=e.touches[0].clientX;
});

hero.addEventListener("touchend",e=>{
let dx=e.changedTouches[0].clientX-startX;

if(dx>50)prevSlide();
if(dx<-50)nextSlide();
});

});

</script>

