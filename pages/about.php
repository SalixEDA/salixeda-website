<?php
// pages/about.php
$pageName = 'about';

// Используем шаблон
include 'pages/shorts.php';

?>

<style>
.about-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: 8px;
    padding: 1rem;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.about-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.about-card img {
    border-radius: 8px;
}
@media (max-width: 700px) {
    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>
