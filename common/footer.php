<?php
// common/footer.php
?>

<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>CircuitCAD</h3>
            <p>
                <?= $lang == 'ru' 
                    ? 'Профессиональная САПР для схемотехники и печатных плат.' 
                    : 'Professional CAD for circuit design and PCBs.' 
                ?>
            </p>
            <p>
                <?= $lang == 'ru' 
                    ? 'Некоммерческий проект с открытым исходным кодом.' 
                    : 'Non-commercial open-source project.' 
                ?>
            </p>
        </div>
        
        <div class="footer-section">
            <h3><?= $lang == 'ru' ? 'Контакты' : 'Contact' ?></h3>
            <p>
                <a href="mailto:support@circuitcad.local" class="footer-link">
                    support@circuitcad.local
                </a>
            </p>
        </div>
        
        <div class="footer-section">
            <h3><?= $lang == 'ru' ? 'Ссылки' : 'Links' ?></h3>
            <ul class="footer-links">
                <li><a href="/about" class="footer-link"><?= $lang == 'ru' ? 'О проекте' : 'About' ?></a></li>
                <li><a href="/help/docs" class="footer-link"><?= $lang == 'ru' ? 'Документация' : 'Documentation' ?></a></li>
                <li><a href="/download" class="footer-link"><?= $lang == 'ru' ? 'Скачать' : 'Download' ?></a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> CircuitCAD. 
        <?= $lang == 'ru' ? 'Все права защищены.' : 'All rights reserved.' ?></p>
        <p class="footer-version">
            <?= $lang == 'ru' 
                ? 'Разрабатывается более 25 лет. Версия 2.5' 
                : 'In development for over 25 years. Version 2.5' 
            ?>
        </p>
    </div>
</footer>

<style>
/* Стили подвала */
.site-footer {
    background: #2c3e50;
    color: #ecf0f1;
    padding: 40px 0 20px;
    margin-top: 50px;
    font-family: Arial, sans-serif;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto 40px;
    padding: 0 40px;
}

.footer-section {
    flex: 1;
    min-width: 250px;
    padding: 0 20px;
}

.footer-section h3 {
    color: #3498db;
    margin-bottom: 20px;
    font-size: 18px;
}

.footer-section p {
    line-height: 1.6;
    margin-bottom: 15px;
    color: #bdc3c7;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-link {
    color: #3498db;
    text-decoration: none;
    transition: color 0.2s;
}

.footer-link:hover {
    color: #2980b9;
    text-decoration: underline;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #4a6572;
    color: #95a5a6;
    font-size: 14px;
}

.footer-version {
    font-style: italic;
    margin-top: 10px;
    color: #7f8c8d;
}
</style>