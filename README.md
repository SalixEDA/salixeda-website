# salixeda-website
Official SalixEDA website source. Hosting documentation, downloads, and community resources.

Installation
Install apache server and php

sudo apt update
sudo apt install apache2 php libapache2-mod-php

После установки Apache обычно запускается автоматически. Проверьте его статус:
sudo systemctl status apache2

Если он не запущен, выполните:
sudo systemctl start apache2

Чтобы сервер запускался при старте системы:
sudo systemctl enable apache2

All files in directory /var/www/html/
    # Указываем путь к вашему сайту
    DocumentRoot /home/ваш_пользователь/my_sites

    <Directory /home/ваш_пользователь/my_sites>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

Allow rewrite module
# Если ничего не вывелось - включаем:
sudo a2enmod rewrite
sudo systemctl restart apache2  # или перезапустите ваш ручной процесс

# В конфиге Apache (/etc/apache2/envvars)
export APACHE_RUN_USER=webuser
export APACHE_RUN_GROUP=services





А как заставить apache поддерживать несколько доменов. Например, у меня на сервере одновременно живут site1.org и site2.ru.

Отличный вопрос! Это называется виртуальные хосты (VirtualHosts) в Apache. Есть два основных подхода:
Способ 1. Виртуальные хосты на основе имени (самый распространённый)

Оба сайта живут на одном IP, Apache различает их по доменному имени из запроса.
Структура папок (пример):
text

/home/usik/work/
├── site1.org/
│   └── index.php
└── site2.ru/
    └── index.php

Настройка виртуальных хостов:
1. Создайте конфигурационные файлы для каждого сайта:
bash

sudo nano /etc/apache2/sites-available/site1.org.conf

apache

<VirtualHost *:80>
    ServerName site1.org
    ServerAlias www.site1.org
    DocumentRoot /home/usik/work/site1.org

    <Directory /home/usik/work/site1.org>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/site1.org-error.log
    CustomLog ${APACHE_LOG_DIR}/site1.org-access.log combined
</VirtualHost>

bash

sudo nano /etc/apache2/sites-available/site2.ru.conf

apache

<VirtualHost *:80>
    ServerName site2.ru
    ServerAlias www.site2.ru
    DocumentRoot /home/usik/work/site2.ru

    <Directory /home/usik/work/site2.ru>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/site2.ru-error.log
    CustomLog ${APACHE_LOG_DIR}/site2.ru-access.log combined
</VirtualHost>

2. Включите сайты:
bash

sudo a2ensite site1.org.conf
sudo a2ensite site2.ru.conf

# Отключите сайт по умолчанию, если не нужен
sudo a2dissite 000-default.conf

# Перезапустите Apache
sudo systemctl reload apache2  # или sudo apachectl -X

3. Настройка локальных доменов (для тестирования)

Чтобы тестировать на локальной машине, добавьте записи в файл hosts:
bash

sudo nano /etc/hosts

Добавьте строки:
text

127.0.0.1 site1.org www.site1.org
127.0.0.1 site2.ru www.site2.ru

Теперь в браузере можно открывать http://site1.org и http://site2.ru

