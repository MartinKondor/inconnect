![Logo](https://github.com/MartinKondor/fsberry/blob/master/public/images/strawberry-logo.png)

FSBerry (FlyingStrawberry), an open source social network.

## Getting Started

### Goals

* Friend adding, removing and listing on profiles
* Images in posts
* Profile picture change
* Chat between users
* Organizations & teams
* Searching for users / organizations
* Improve UX

## Recommended way

You can use apache but I like to start the server by console PHP ```php -S localhost:8080```.

Also note that since this project is at the beggining all paths are go to a root ```public/index.php```, so the main path is: ```localhost:8080/public/index.php/``` and home is ```localhost:8080/public/index.php/home```.

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


