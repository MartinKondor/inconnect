![Logo](https://github.com/MartinKondor/fsberry/blob/master/public/images/strawberry-logo.png)

FSBerry (FlyingStrawberry), an open source social network.

## Goals

The main mission of this project (FlyingStrawberry), is to create and maintain a social network which is avaible for everyone, free to use, as secure as possible and built by a cummunity. The community means both the developers and the users who can recommend different functionalities and/or contribute on FlyingStrawberry. 

On the other hand a social network must have some functions what could make it more useful. Here is the list (not complete) of these yet not implemented functions:

* Friend adding, removing and listing on profiles
* Images in posts
* Profile picture change & user settings
* Chat between users
* Organizations & teams
* Searching for users / organizations
* Improve UX
* Translations for the website
* Mobile app

## Getting started

Clearly in a few steps:

* Prepare your computer for development
* Fork this repository
* Clone your fork
* Change your local files (make it better)
* Test if everything is working fine (the change doesnt break anything)
* Create a pull request on this repository

### Prerequisites

We use Symfony framework, and MySQL database.

Create a .env file in the main directory:

```
APP_ENV=dev
APP_SECRET=<Put a random string here>
DATABASE_URL=mysql://<username>:<password>@<host>:<port>/<database>
```

## Built With

* [Symfony](https://symfony.com/) - PHP framework used
* [MySQL](https://www.mysql.com/) - Database used

## License

Copyright (c) 2018 Martin Kondor.

The content of this repository bound by the [BSD-3-Clause](./LICENSE) license.


