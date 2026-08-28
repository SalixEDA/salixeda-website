# Install Apache server and PHP

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

By default all files in directory /var/www/html/
We need create own structure

# Configure Directory Permissions

Now you need to "put everything in its place" and set proper permissions.

```bash
# 1. Website structure (managed by webmaster, readable by Apache)
# Create subdirectories for websites
mkdir -p /srv/web/salixeda.org

# Set owner: webmaster, group: webmaster
chown -R webmaster:webmaster /srv/web

# Permissions: owner (webmaster) can do everything (7), group (webmaster) can read and enter (5), others nothing (0).
# But! Apache must be able to read files. Apache usually runs as user www-data.
# Add www-data to the webmaster group so it gets access (r-x).
usermod -aG webmaster www-data  # Give Apache access to websites
```

Create Apache Configs for Site
```apache
<Directory /srv/web/salixeda.org>
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

# Creating Service Users

Log in to the server as root and create users without interactive login capabilities (no password and with shell /usr/sbin/nologin).

Create a user for website management
```bash
useradd -r -s /usr/sbin/nologin -m -d /srv/web webmaster
```

Create a user for the global library service
```bash
useradd -r -s /usr/sbin/nologin -m -d /srv/global-lib glib
```

Create a user for the private cloud service
```bash
useradd -r -s /usr/sbin/nologin -m -d /srv/private-cloud pcloud
```

Create a user for the AI interface
```bash
useradd -r -s /usr/sbin/nologin -m -d /srv/ai aiagent
```

Parameter breakdown:

    -r: Creates a system user (UID from the system range).

    -s /usr/sbin/nologin: Disables interactive login. Secure.

    -m -d /path/...: Creates a home directory and immediately assigns the user as its owner. This fits perfectly with your /srv structure.


Copy the global library executable
```bash
scp global-library-server root@your_ip:/srv/global-lib/
```

Change file permissions
```bash
chown glib:glib /srv/global-lib/global-library-server
chmod 750 /srv/global-lib/global-library-server
```

Create the service file
```bash
nano /etc/systemd/system/global-lib.service
```

```global-lib.service
[Unit]
Description=Global Components Library Service
After=network.target

[Service]
Type=simple
User=glib
Group=glib
WorkingDirectory=/srv/global-lib
ExecStart=/srv/global-lib/global-library-server
Restart=on-failure
RestartSec=10
NoNewPrivileges=yes
PrivateTmp=yes

[Install]
WantedBy=multi-user.target
```

Start the service
```bash
sudo systemctl daemon-reload
sudo systemctl enable global-lib
sudo systemctl start global-lib
```

# Private Cloud Setup

Copy the private cloud executable
```bash
scp private-cloud-server root@Your_ip:/srv/private-cloud/
```

Change file permissions
```bash
chown pcloud:pcloud private-cloud-server
chmod 750 private-cloud-server
```

Create the service file
```bash
nano /etc/systemd/system/private-cloud.service
```

```private-cloud.service
[Unit]
Description=Global Components Library Service
After=network.target

[Service]
Type=simple
User=pcloud
Group=pcloud
WorkingDirectory=/srv/private-cloud
ExecStart=/srv/private-cloud/private-cloud-server
Restart=on-failure
RestartSec=10
NoNewPrivileges=yes
PrivateTmp=yes

[Install]
WantedBy=multi-user.target
```

Start the service
```bash
sudo systemctl daemon-reload
sudo systemctl enable private-cloud
sudo systemctl start private-cloud
```

# Log Limiting
```bash
sudo mkdir -p /etc/systemd/journald.conf.d/
sudo nano /etc/systemd/journald.conf.d/00-limit.conf
```

```00-limit.conf
[Journal]
# Maximum total log size
SystemMaxUse=500M

# Minimum free space the system should preserve
SystemKeepFree=1G

# Maximum size of a single log file
SystemMaxFileSize=100M

# Maximum number of log files
SystemMaxFiles=100

# How long to keep logs (1 month)
MaxFileSec=1month
```

Restart the journal
```bash
sudo systemctl restart systemd-journald
```

# Ollama Installation and Configuration
```bash
curl -fsSL https://ollama.com/install.sh | sh
```

Verify the installation
```bash
ollama --version
```

Ensure it's running
```bash
systemctl status ollama
```

Pull models
```bash
ollama pull bge-m3
ollama pull qwen3:4b
```



# SSL Certificate Setup

Install the certificate utility
```bash
apt install certbot python3-certbot-apache
```

Obtain certificates for site
```bash
certbot --apache -d salixeda.org -d www.salixeda.org
```

Edit the certified Apache configuration
```bash
nano /etc/apache2/sites-available/salixdev.ru-le-ssl.conf
```
