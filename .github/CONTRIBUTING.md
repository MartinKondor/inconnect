# Contributing to InConnect
Thank you for your interest in contributing to InConnect! We believe that only 
through community involvement can InConnect be the best it can be. There are a whole
host of ways to contribute, and every single one is appreciated. The major 
sections of this document are linked below:

- [Feature Enhancements](#feature-enhancements)
- [Bug Reports](#bug-reports)
- [Pull Requests](#pull-requests)
- [Steps](#steps)

All contributions to InConnect should be in keeping with our 
[Code of Conduct](https://github.com/in-connect/inconnect/blob/master/.github/CODE_OF_CONDUCT.md).

## Feature Enhancements
If you feel like you have a suggestion for a change to the way that InConnect works
as a social network, please open an issue in this repository.

## Bug Reports
While it's never great to find a bug, they are a reality of software and 
software development. We can't fix or improve on the things that we don't know
about, so report as many bugs as you can by opening an issue. If you're not sure whether something 
is a bug, file it anyway.

**If you are concerned that your bug publicly presents a security risk to the
users of InConnect, please contact 
[martinkondor@gmail.com](mailto://martinkondor@gmail.com).**

## Pull Requests
Pull Requests are the primary method for making changes to InConnect. GitHub has 
[fantastic documentation](https://help.github.com/articles/about-pull-requests/)
on using the pull request feature. InConnect uses the 'fork-and-pull' model of 
development. It is as described 
[here](https://help.github.com/articles/about-collaborative-development-models/)
and involves people pushing changes to their own fork and creating pull requests
to bring those changes into the main InConnect repository.

Please make all pull requests against the `master` branch.

Every pull request for InConnect is reviewed by another person. You'll get a 
reviewer from the core team assigned at random, but feel free to ask for a 
specific person if you've dealt with them in a certain area before. 

Once the reviewer approves your pull request it will be tested by our continuous
integration provider before being merged.

## Steps
These are the steps on how to set up your computer for running an InConnect
server and making changes on it.

1. Star (very important ;)) and Fork this repository.
2. Clone your forked repository.
3. If you does'nt have, install an Apache server, MySQL server, PHP, and Composer:

   The easiest way of doing this is by downloading and installing [XAMP](https://www.apachefriends.org/hu/index.html),
   and [Composer](https://getcomposer.org/).
4. Start the server, set up the database:

   In the terminal go to the main directory and set up the dependencies
   ```composer install```
   
   Create a file in the main directory called ```.env``` and place this in it, with your database credentials:
   ```
    APP_ENV=dev
    APP_SECRET=<Put a random string here>
    DATABASE_URL=mysql://<username>:<password>@<host>:<port>/<database>
   ```
   Create the database
   ```php bin/console doctrine:database:create```

   Create schema
   ```php bin/console doctrine:schema:create```

   Hit ```y```.

   Load fixtures to make two default user what you can use for testing:
   
   ```php bin/console doctrine:fixtures:load -q -append```

5. Go to ```localhost/inconnect/public/index.php/``` with your browser to see if everything is up and running.
    
    Log in to one of these users:
    
    ```
    Email: test@aaliyah.test
    Password: test
    -
    Email: test@john.test
    Password: test
    ```
    
    Or Sign up and log in to your own user.
    
    Examples (Not final design):
    
    The homepage with the two default user and a created post:
    
    ![Example](https://github.com/in-connect/inconnect/blob/master/src/DataFixtures/example1.png)

    A test profile page:
    
    ![Example](https://github.com/in-connect/inconnect/blob/master/src/DataFixtures/example2.png)

6. Change the code as you wish, like add a new feature, remove a bug, change the design and so on...
7. Write tests for your changes and try to test everything what can be affected by your changes.
8. Make sure that all the tests passing, run tests with ```php bin/phpunit```
9. Create a pull request at this repository, wait for a feedback.
10. If the community accepted your changes then congrats! You are now officially contributed on InConnect!
But anyway, thank you for making InConnect better! 

NOTE: If you use apache server on Windows, then you can
set a virtual host on your computer to make the development process faster.
Follow these steps:

1. Paste this into your ```apache/conf/extra/httpd-vhosts.conf``` file:
```
<VirtualHost *:80>
    ServerName InConnect.local
    DocumentRoot "E:/Programs/xamp/htdocs/inconnect/public"
    DirectoryIndex index.php
    <Directory "E:/Programs/xamp/htdocs/inconnect/public/">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride None
        Order allow,deny
        allow from all
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ /index.php [QSA,L]
        </IfModule>
    </Directory>
</VirtualHost>
```

2. Open the file ```Windows/System32/drivers/etc/hosts``` as administrator on your computer and append this to it:
```
    127.0.0.1 inconnect.local
```

3. From now you can access the site in the browser with simply typing in the above url.
(and you will don't need to write index.php before a path every time.)