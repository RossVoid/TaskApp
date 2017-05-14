# Task Rest Example

Rest Service test project for a friend using Slim PHP to interact with the MySQL Database.

Instructions get get installed

1. Create database and run /sql/exampletasks.sql on created database.
2. Install [Slim PHP Dependencies](https://www.slimframework.com/) using composer install in command line.
3. Update Database information in /src/config/db.php

Endpoints:
```
Users
GET,POST [domain/folder]/api/users
GET [domain/folder]/api/user/{id}
DELETE [domain/folder]/api/user/delete/{id}

Posts
GET,POST [domain/folder]/api/tasks
GET [domain/folder]/api/task/{id}
DELETE [domain/folder]/api/task/delete/{id}

```
