# Contributing to FSBerry
Thank you for your interest in contributing to FSBerry! We believe that only 
through community involvement can FlyingStrawberry be the best it can be. There are a whole
host of ways to contribute, and every single one is appreciated. The major 
sections of this document are linked below:

- [Feature Enhancements](#feature-enhancements)
- [Bug Reports](#bug-reports)
- [Pull Requests](#pull-requests)
- [Steps](#steps)

All contributions to FSBerry should be in keeping with our 
[Code of Conduct](https://github.com/flyingstrawberry/fsberry/blob/master/.github/CODE_OF_CONDUCT.md).

## Feature Enhancements
If you feel like you have a suggestion for a change to the way that FSBerry works
as a social network, please open an issue in this repository.

## Bug Reports
While it's never great to find a bug, they are a reality of software and 
software development. We can't fix or improve on the things that we don't know
about, so report as many bugs as you can by opening an issue. If you're not sure whether something 
is a bug, file it anyway.

**If you are concerned that your bug publicly presents a security risk to the
users of FSBerry, please contact 
[martinkondor@gmail.com](mailto://martinkondor@gmail.com).**

## Pull Requests
Pull Requests are the primary method for making changes to FSBerry. GitHub has 
[fantastic documentation](https://help.github.com/articles/about-pull-requests/)
on using the pull request feature. FSBerry uses the 'fork-and-pull' model of 
development. It is as described 
[here](https://help.github.com/articles/about-collaborative-development-models/)
and involves people pushing changes to their own fork and creating pull requests
to bring those changes into the main FSBerry repository.

Please make all pull requests against the `master` branch.

Every pull request for FSBerry is reviewed by another person. You'll get a 
reviewer from the core team assigned at random, but feel free to ask for a 
specific person if you've dealt with them in a certain area before. 

Once the reviewer approves your pull request it will be tested by our continuous
integration provider before being merged.

## Steps
These are the steps on how to set up your computer for running an FSBerry
server and making changes on it.

1. Star (very important ;)) & Fork this repository.
2. Clone your forked repository.
3. If you doesnt have, install an Apache server, MySQL server, PHP, and Composer:

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
5. Go to ```localhost/fsberry/public/index.php/``` with your browser to see if everything is up and running.
