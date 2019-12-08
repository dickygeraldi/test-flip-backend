# Test Flip Backend Service


This application is a disbursement backend service using Slightly-big Flip as third party. This service is created as test before join with Flip.id.

# What Service inside it?

  - Get Product Bank Data
  - Get Data all Invoice
  - Disbursement Inquiry
  - Disbursement request to Flip
  - Check detail invoice
### Technology

This service is creaed using:

* [PHP] - Backend service using PHP 7.0.33 
* [MySql] - Database management system, MySql version is 15.1 Distrib 10.1.37-MariaDB
* [XAMPP] - Free and open-source cross-platform web server, using XAMPP version 7.3 for linux
* [Composer] - Management depedency for PHP, using composer version 1.9.1

### How to Use

Before go to next step, change the environment variable in folder config/env.php. Set to your environment variable. 

| KEY | VALUE |
| ------ | ------ |
| DB_NAME | It's a fixed value ('testFlip') |
| DB_USER | Change it into your user database |
| DB_PASS | Change it to your password |
| DB_HOST | Change it to yout host |

This service is must install all technology use first. Secondly, create a database schema using MySql and migrate data.

```sh
CREATE DATABASE testFlip
$ php migration.php
```

After done with all, just start this service using command line.
```sh
$ php -S localhost:5000
```
#### Postman Collection

If you want to run your service, please install Postman first for easy to use. This is the collection to run backend service. Collection Import [Collection](https://www.getpostman.com/collections/992c5456ea59e8cb0953)

License
----

GNU General Public License v3.0


**Author**
Dicky Geraldi, Software Engineer Freelance


 
   [php]: <https://php.net>
   [MySql]: <https://www.mysql.com/>
   [XAMPP]: <https://www.apachefriends.org/index.html>
   [Composer]: <https://getcomposer.org/>