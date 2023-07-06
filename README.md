# Movies Quote App

This movie quote website is built using Laravel, a popular PHP framework. Users can add movies/quotes, and engage in discussions by leaving comments. Additionally, they can like to express emotion. Users have the ability to update their email passwords to ensure the security and privacy of their accounts.

### Table of Contents

-   [Tech Stack](#tech-stack)
-   [Database](#Database)
-   [Resources](#resources)
-   [Getting Started](#getting-started)
-   [Environment Variables](#environment-variables)

### Tech Stack

-   [Laravel@10.x](https://laravel.com/docs/10.x) - back-end framework
-   [Pusher](https://pusher.com/) - Powering realtime experiences for mobile and web

-   [MySQL](https://www.mysql.com/) - for database

### Resources

-   [Assignment](https://redberry.gitbook.io/assignment-iv-movie-quotes-1/)
-   [figma](https://www.figma.com/file/5uMXCg3itJwpzh9cVIK3hA/Movie-Quotes-Bootcamp-assignment?type=design&node-id=0-1&mode=design)
-   [Git semantic commits](https://redberry.gitbook.io/resources/other/git-is-semantikuri-komitebi)

### Database

To view the database structure, go to the following link: [drawsql](https://drawsql.app/teams/my-team-704/diagrams/epic-movie)

![App Screenshot](/readme/drawsql.jpg)

### Getting Started

1\. Clone the repository to your local machine

```sh
https://github.com/RedberryInternship/esaia-gaprindashvili-epic-movie-quotes-back.git
```

2\. Run composer install to install the dependencies

```sh
composer install
```

3\. Configure your database credentials in the .env file

4\. Run php artisan migrate command to create the database tables

```sh
php artisan migrate --seed
```

5\. And start the local development server

```sh
php artisan serve
```

### Environment Variables

To run this project, you will need to add the following environment variables to your .env file

**MYSQL:**

> DB_CONNECTION=mysql

> DB_HOST=127.0.0.1

> DB_PORT=3306

> DB_DATABASE=**\***

> DB_USERNAME=**\***

> DB_PASSWORD=**\***

> GOOGLE_CLIENT_ID=\*

> GOOGLE_CLIENT_SECRET=\*

> GOOGLE_REDIRECT_URI=\*

> FRONT_APP_URL=\*

> SANCTUM_STATEFUL_DOMAINS=\*

> SESSION_DOMAIN=\*

_Also you need set up the Pusher configuration_
