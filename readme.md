# salixeda-website
Official SalixEDA website source. Hosting documentation, downloads, and community resources.

## Website Engine and the Site Itself
There was a project called GetSimple. It's a file-based content management system.
Currently, the project has stalled, but I decided to implement the ideas embedded in it
on my own website.

### Main Engine Concept
PHP is chosen as the execution system. The key principles are:
- zero dependencies on external resources or libraries
- maximum static nature
- multilingualism "out of the box"
- ease of maintenance

### Organization Principle
The entry point for all page requests is index.php. It parses the target page URL and
attempts to find a suitable template in the pages directory. The template defines the
structure of a specific page. For example, it makes sense for all articles to look
structurally similar. This is exactly what the page template provides. Files containing
text content are stored separately.

## Installation
Install Apache server and PHP

```bash
sudo apt update
sudo apt install apache2 php libapache2-mod-php
```

After installation, Apache usually starts automatically. Check its status:
```bash
sudo systemctl status apache2
```

If it's not running, execute:
```bash
sudo systemctl start apache2
```

To start the server at system boot:
```bash
sudo systemctl enable apache2
```

```apache
# All files in directory /var/www/html/
# Specify the path to your site
DocumentRoot /home/your_user/my_sites

<Directory /home/your_user/my_sites>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Enable rewrite module
If nothing is displayed - enable it:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2  # or restart your manual process
```

In Apache config (/etc/apache2/envvars)
```apache
export APACHE_RUN_USER=webuser
export APACHE_RUN_GROUP=services
```
```

