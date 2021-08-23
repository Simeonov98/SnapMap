# SnapMap
This is an web app whose only purpose is to accumulate images from the satelite maps based on .gpx files.
## Description
Meant to ease the offline globetrotters, this web app generates an image around a waypoint from a .gpx file from a reasonable height
to ensure clarity and detail whilst synchronising between different satelite map povidors.
## Getting Started
### Dependencies
* You will require PHP, Python, MySQL and Composer. I recommend you use XAMPP for PHP and MySQL.
  https://getcomposer.org/download/ - get Composer from here.
* You will ALSO need Symfony CLI. It is a convenient tool built to speed up Symfony development.
  https://symfony.com/download - get it from here.
###  Running the site
* Download the code and install the dependencies
* Open the source dir in a terminal and run
```
composer install
```
* The next step is to create the database from the schema in the code, for this run
```
php bin/console doctrine:database:create
```
* After you create the database you will need to create the tables.
You can use the next command to see the SQL query
    ```
    php bin/console doctrine:schema:update --dump-sql
    ```
    and after it this one or directly execute this one 
    ```
    php bin/console doctrine:schema:update --force
    ```
* The last step is to start the intetgrated Symfony server
  (to run the server in daemon mode use ``` symfony serve -d ```
    ```
    symfony serve
    ``` 
* To stop the server use ``` symfony server:stop``` 
## Author
[@Simeonov98](https://github.com/Simeonov98)
