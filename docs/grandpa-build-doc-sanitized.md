All WordPress is in the Database .  PHP supports and is static, is executing to query the database and builds the pages 
### Server Migration Notes (GoDaddy to Google Cloud)

#### **1. Update OS + install web stack
```bash
sudo apt update && sudo apt upgrade -y

sudo apt install -y nginx

# PHP 8.1 (default on this Ubuntu) + FPM + common extensions
sudo apt install -y php php-fpm php-cli php-mysql \
  php-xml php-mbstring php-curl php-zip php-intl php-bcmath php-gd

# Verify versions
php -v
php-fpm8.1 -v || php-fpm -v

# Enable services
sudo systemctl enable --now nginx
sudo systemctl enable --now php8.1-fpm
```

Install an editor (one-time)
```bash
sudo apt install -y vim
```

---

#### **2. Configure NGINX for PHP:**

Edit the NGINX site configuration file:
```bash
sudo vi /etc/nginx/sites-available/default
```

Add the following PHP location block and update the index directive:
```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    root /var/www/html;
    index index.php index.html index.htm;

    server_name _;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}

```

Validate + reload::
```bash
sudo nginx -t
sudo systemctl reload nginx
```

Create a PHP test page:
```bash
sudo mkdir -p /var/www/html
sudo rm -f /var/www/html/index.nginx-debian.html

sudo tee /var/www/html/index.php > /dev/null <<'PHP'
<?php
phpinfo();
PHP
```

Permissions:
```bash
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
```

Verify locally + externally
Local checks on the VM:
```bash
sudo ss -ltnp | grep ':80'
curl -I http://localhost
curl -s http://localhost/index.php | head
```

External test:
```bash
http://34.44.140.79/
```
Remove phpinfo once confirmed:
```php
sudo rm -f /var/www/html/index.php
```

---
On godaddy login as yourself and delegate access to Rob.  Go to all products and services - web hosting (its all under # nscfoodsafety.com) - manage - click cPanel admin - File Manager - public html - select folder northernsinc.org - select all - compress
#### **4. Migrate Website Files:**

**Tar and Copy the Backup:**
On your local machine:
```bash
scp northernsinc_backup.tar 34.44.140.79:
```

**Extract the Backup:**
```bash
cd ~
sudo mkdir /var/www/html/northernsinc.org
sudo mv northernsinc_backup.tar /var/www/html/northernsinc.org
cd /var/www/html/northernsinc.org
sudo tar -xvf northernsinc_backup.tar

sudo rm -rf northernsinc_backup.tar
```

---

#### **5. Set Correct Permissions:**

Ensure the correct ownership and permissions for the site:
```bash
sudo chown -R www-data:www-data /var/www/html/northernsinc.org
sudo find /var/www/html/northernsinc.org -type d -exec chmod 755 {} \;
sudo find /var/www/html/northernsinc.org -type f -exec chmod 644 {} \;
```

---

#### **6. Install and Configure MariaDB:**

Install MariaDB:
```bash
sudo apt install -y mariadb-server
sudo systemctl enable --now mariadb
sudo mysql_secure_installation

Root password set to <DB_ROOT_PASSWORD>
```

Check the status:
```bash
sudo systemctl status mariadb
```

Log in to MariaDB:
```bash
sudo mysql -u root -p
```

Create the database:
```sql
CREATE DATABASE northernsinc_db;
```

---

#### **7. Import/Configure the Database:**

Copy the SQL file to the server (export via phpMyadmin in cPanel under databases):
```bash
scp omz_d7c_mywebsitetransfer_com_1717598884.sql 34.72.16.244:
```

Import the database:
```bash
cd ~
sudo mysql -u root -p northernsinc_db < omz_d7c_mywebsitetransfer_com_1717598884.sql
```

Create northinc user for database:
```sql
sudo mysql -u root -p

USE northernsinc_db;

CREATE USER 'northernsinc_db_user'@'localhost' IDENTIFIED BY '<DB_APP_PASSWORD>';
GRANT ALL PRIVILEGES ON northernsinc_db.* TO 'northernsinc_db_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Create https://34.60.229.112/wp-admin WordPress user in DB (Eventually set automatic updates for wordpress, constant security holes):
```sql
USE northernsinc_db;

INSERT INTO wp_users 
(user_login, user_pass, user_email, user_registered, user_status, display_name) 
VALUES ('<WP_ADMIN_USERNAME>', MD5('<WP_ADMIN_PASSWORD>'), '<WP_ADMIN_EMAIL>', NOW(), '0', 'Dylan Admin');
```

Get the WordPress user ID (output is 77 = my ID):
```sql
SELECT ID FROM wp_users WHERE user_login = '<WP_ADMIN_USERNAME>';
```

Assign administrator role to your ID:
```sql
INSERT INTO wp_usermeta (user_id, meta_key, meta_value) 
VALUES (77, 'wp_capabilities', 'a:1:{s:13:"administrator";b:1;}');
```

Set the dashboard access level:
```sql
INSERT INTO wp_usermeta (user_id, meta_key, meta_value) 
VALUES (77, 'wp_user_level', '10');
```

Update URL's in the database (**TEST ENV ONLY**, WAS https://northernsinc.org ):
```sql
sudo mysql -u root -p

USE northernsinc_db;

SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');
UPDATE wp_options SET option_value = 'https://test.northernsinc.org' WHERE option_name IN ('siteurl', 'home');
```
VERIFY:
```sql
SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');
```


---

#### **8. Final Configuration Steps:**
Update the wp-config to use the newly created credentials / database:
```bash
vi /var/www/html/northernsinc.org/wp-config.php

define('DB_NAME', "northernsinc_db");
define('DB_USER', "northernsinc_db_user");
define('DB_PASSWORD', "<DB_APP_PASSWORD>");
```

ADD Update the wp-config to use a different URL (**TEST ENV ONLY**):
```bash
define('WP_HOME', 'https://test.northernsinc.org');
define('WP_SITEURL', 'https://test.northernsinc.org');
```

Create a new NGINX site configuration:
```bash
sudo vi /etc/nginx/sites-available/northernsinc.org
```

Plugin Compatibility Fixes (PHP 8+)
Disable incompatible plugins:
```bash
sudo mv wp-content/plugins/add-to-any-subscribe \
        wp-content/plugins/add-to-any-subscribe.DISABLED

sudo mv wp-content/plugins/really-simple-ssl \
        wp-content/plugins/really-simple-ssl.DISABLED
```

Reload:
```bash
sudo systemctl reload nginx
```

Verify DNS:
```bash
getent hosts test.northernsinc.org
```

HTTPS (Let’s Encrypt)
```bash
sudo apt install -y certbot python3-certbot-nginx
```

Request Cert
```bash
sudo certbot --nginx -d test.northernsinc.org
```


Final NGINX config
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name test.northernsinc.org;
    return 301 https://$host$request_uri;
}

server {
    server_name test.northernsinc.org;

    root /var/www/html/northernsinc.org;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~* ^/(?:\.ht|wp-config\.php|readme\.html|license\.txt) {
        deny all;
    }

    listen [::]:443 ssl http2 ipv6only=on;
    listen 443 ssl http2;
    ssl_certificate /etc/letsencrypt/live/test.northernsinc.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/test.northernsinc.org/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;
}

```

Restart NGINX:
```bash
sudo nginx -t
sudo systemctl reload nginx
```


---

#### **9. Login to WordPress and update redirectes/pages:**
https://test.northernsinc.org/wp-admin (this is a long term solution)

<WP_ADMIN_USERNAME>
<WP_ADMIN_PASSWORD>

Fix all the Max Buttons (For Ex to:  /about/ ) 
Fix Appearance - Menus - Home - to / 
Fix the Appearance - Themes - Active: Catch Flames - Customize - Header Image - Featured Header Image Link URL - to /

Search code for URL's to update (**TEST ENV ONLY**):
```bash
cd /var/www/html/northernsinc.org

grep -r "northernsinc.org" . | grep -v error_log
vi sitemap.xml - update the url 
vi robots.txt - update the url 
```

CHECK Database for HARD CODED(**TEST ENV ONLY**) it is down to images only, ignore:
```sql
sudo mysql -u root -p

SELECT * FROM wp_options WHERE option_value LIKE '%northernsinc.org%';
SELECT * FROM wp_postmeta WHERE meta_value LIKE '%northernsinc.org%';
SELECT * FROM wp_posts WHERE post_content LIKE '%northernsinc.org%';
SELECT * FROM wp_posts WHERE guid LIKE '%northernsinc.org%';
SELECT * FROM wp_comments WHERE comment_content LIKE '%northernsinc.org%';
SELECT * FROM wp_users WHERE user_url LIKE '%northernsinc.org%';
SELECT * FROM wp_links WHERE link_url LIKE '%northernsinc.org%';
```

#### GO LIVE:

Update URL's in the database 
```sql
sudo mysql -u root -p

USE northernsinc_db;

SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');
UPDATE wp_options SET option_value = 'https://northernsinc.org' WHERE option_name IN ('siteurl', 'home');
```

VERIFY:
```sql
SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home');
```

Update URLS in wp-config:
```bash
sudo vi /var/www/html/northernsinc.org/wp-config.php

define('WP_HOME', 'https://northernsinc.org');
define('WP_SITEURL', 'https://northernsinc.org');
```

Restart
```bash
sudo systemctl restart php8.1-fpm
sudo systemctl reload nginx
```

Create prod config
```bash
sudo vi /etc/nginx/sites-available/northernsinc.org.prod
```

Paste this (same as test, but for prod domain):
```bash
server {
    listen 80;
    listen [::]:80;
    server_name northernsinc.org www.northernsinc.org;
    return 301 https://$host$request_uri;
}

server {
    server_name northernsinc.org www.northernsinc.org;

    root /var/www/html/northernsinc.org;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~* ^/(?:\.ht|wp-config\.php|readme\.html|license\.txt) {
        deny all;
    }

    # certbot will add the listen 443 + ssl_certificate lines
}

```

Enable it (and keep test site enabled too for now):
```bash
sudo ln -sf /etc/nginx/sites-available/northernsinc.org.prod /etc/nginx/sites-enabled/northernsinc.org.prod
sudo nginx -t
sudo systemctl reload nginx

```

Switch DNS in GoDaddy. (pre upgrade screenshot)
![[Pasted image 20251218175701.png]]

Verify:
```bash
getent hosts northernsinc.org
getent hosts www.northernsinc.org

```

Request Certbot Cert:
```bash
sudo certbot --nginx -d northernsinc.org -d www.northernsinc.org
```

Autorenew for certbot:
```bash
sudo certbot renew --dry-run
```

Disable duplicate site:
```bash
sudo rm -f /etc/nginx/sites-enabled/northernsinc.org
```

Reload:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

Final Config:
```bash
# HTTP -> HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name northernsinc.org www.northernsinc.org;

    return 301 https://$host$request_uri;
}

# HTTPS site
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name northernsinc.org www.northernsinc.org;

    root /var/www/html/northernsinc.org;
    index index.php index.html;

    ssl_certificate /etc/letsencrypt/live/northernsinc.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/northernsinc.org/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~* ^/(?:\.ht|wp-config\.php|readme\.html|license\.txt) {
        deny all;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|webp|svg|woff|woff2|eot|ttf)$ {
        expires 30d;
        log_not_found off;
        access_log off;
    }
}

```

Update new member form as it is case sensitive:
```bash
cd /var/www/html/northernsinc.org/wp-content/uploads/2020/05
sudo mv Northerns-Inc-Membership-application-2020.pdf Northerns-Inc-Membership-Application-2020.pdf
```

## 🌐 Set a Static External IP in Google Cloud (GCP)

### ✅ Why
Compute Engine VMs default to an **ephemeral external IP**, which can change after a stop/start or restart. A **static external IP** keeps DNS + HTTPS stable.

---
## 1) Reserve a Static IP

In Google Cloud Console go to:

**VPC network → IP addresses**

Click: **Reserve external static IP address**

Set:
- **Type:** External  
- **IP version:** IPv4  
- **Network Service Tier:** Premium  
- **Region:** Same region as the VM  

Click: **Reserve**

---
## 2) Attach the Static IP to the VM

Go to:

**Compute Engine → VM instances**

1. Click the target VM
2. Click **Edit**
3. Under **Network interfaces → External IPv4 address**
   - Change from **Ephemeral** → **Static**
   - Select the reserved static IP
4. Click **Save**

---
## 3) Verify

On the VM:
```bash
curl -s ifconfig.me; echo

```



---
#### **10. Useful Locations and Commands:**

# Northerns Inc – Operations & Maintenance Reference

## 🔧 Stack Overview
- OS: Ubuntu (Google Cloud VM)
- Web Server: NGINX
- PHP: PHP 8.1 (PHP-FPM)
- Database: MariaDB
- App: WordPress
- HTTPS: Let’s Encrypt (certbot)
- Networking: Static External IP (Premium tier)

---

## 📁 Key Paths (Most Used)

### Website
- Site root  
/var/www/html/northernsinc.org
### NGINX
- Site config (available)  
/etc/nginx/sites-available/northernsinc.org.prod

- Enabled sites (symlinks only)  
/etc/nginx/sites-enabled/

- Global config  
/etc/nginx/nginx.conf
### PHP (PHP 8.1)
- PHP-FPM config  
/etc/php/8.1/fpm/php.ini

- PHP-FPM pool config  
/etc/php/8.1/fpm/pool.d/www.conf

- PHP-FPM socket  
/run/php/php8.1-fpm.sock
### Logs
- NGINX access log  
/var/log/nginx/access.log

- NGINX error log (primary troubleshooting log)  
/var/log/nginx/error.log

---

###### 🌐 NGINX Commands
```bash
Check enabled sites
ls -l /etc/nginx/sites-enabled/

Test configuration (always run first)
sudo nginx -t

Reload NGINX (safe – no downtime)
sudo systemctl reload nginx

Restart NGINX (only if reload fails)
sudo systemctl restart nginx

Check service status
sudo systemctl status nginx --no-pager

Tail logs live
sudo tail -f /var/log/nginx/error.log
```

🐘 PHP / PHP-FPM Commands (PHP 8.1)
```bash
Check PHP version
php -v

Restart PHP-FPM
sudo systemctl restart php8.1-fpm

Check PHP-FPM status
sudo systemctl status php8.1-fpm --no-pager

Verify socket exists
ls -l /run/php/php8.1-fpm.sock

List loaded PHP modules
php -m
```

🗄️ MariaDB / Database Commands
```bash
Restart MariaDB
sudo systemctl restart mariadb

Check MariaDB status
sudo systemctl status mariadb --no-pager

Login as root
sudo mysql

Login as application DB user
mysql -u northernsinc_db_user -p northernsinc_db

Verify tables
USE northernsinc_db;
SHOW TABLES;

Quick DB health check
mysql -u northernsinc_db_user -p -e "SHOW TABLES;" northernsinc_db
```

🔐 HTTPS / Certificates (Let’s Encrypt)
```bash
List installed certificates
sudo certbot certificates

Test auto-renewal
sudo certbot renew --dry-run

Manual renewal (rare)
sudo certbot renew
```

🌍 DNS / IP Verification
```bash
Check current public IP
curl -s ifconfig.me; echo

Verify DNS resolution
getent hosts northernsinc.org
getent hosts www.northernsinc.org
```

🛠️ Common Maintenance Tasks
```bash
Flush WordPress permalinks
WP Admin → Settings → Permalinks → Save

Clear PHP cache
sudo systemctl restart php8.1-fpm

Quick site health check
curl -I https://northernsinc.org
```

⚠️ Important Notes
```bash
Server runs PHP 8.1 (not 7.4 or 8.3)

Never edit files directly in sites-enabled/

Always run nginx -t before reload

Static IP must remain attached (DNS + HTTPS depend on it)

Most WordPress 500 errors = plugin incompatibility with PHP 8

✅ Recommended Next Docs
🔄 Backup & Restore Procedures

🧯 Troubleshooting by Symptom (500, 404, SSL)

📅 Monthly Maintenance Checklist

🔐 Security Hardening Notes

```

---
