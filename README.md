#SETUP INSTRUCTIONS:

##1. Set up a Vhost with SSL

####in /etc/apache2/sites-available make a your.domain.conf file

```apacheconf
<VirtualHost *:443>
    DocumentRoot /your/path/to/app/public
    ServerName your.domain
    ServerAlias www.your.domain

    <Directory /your/path/to/app/public>
        AllowOverride All
        Require all granted
        Order Allow,Deny
        Allow from All
    </Directory>

SSLEngine on
   SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt
   SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key

</VirtualHost>

<VirtualHost *:80>
    ServerName your.domain
    Redirect / https://your.domain/
</VirtualHost>

```

Facebook requires an https redirect, so you'll need a (self-signed) certificate.

##2. Set up your database

##3. Create an app on the Facebook Developer page

https://developers.facebook.com/ (go to My Apps and click 'Create App' and pick 'Build Connected Experiences').

In your app dashboard, set up the redirect url (https://your.domain/login/check.php and https://www.your.domain/login/check.php where "your domain" is the domain you set up in step 1.)

Keep this page open for your app id and secret in the next step.

##4. In /public make a config directory with the files dbConfig.php and fbConfig.php

####Inside dbConfig.php (use information from step 2):

define('DB_NAME', 'your_db_name'); 

define('DB_USER', 'user');

define('DB_PASSWORD', 'your_password');

####Inside fbConfig.php (use information from step 3):

define('FB_APP_SECRET', 'your_app_secret');

define('FB_APP_ID', 'your_app_id');

define('FB_APP_REDIRECT', 'https://'.$_SERVER['SERVER_NAME'].'/login/check.php');


##5. Run "composer update" 
