*.Application files are either located in htdocs folder in Xampp or /var/www/html in a Linux environment.
It is mandatory to give full permisson to the root folder if this is running in a linux environment.

* Create The Database
To change the database credentials (username and password) go to .env file in intranet/.env

*to execute from the command line,go to the application folder and run  below the commands

 composer install 
 
 php bin/console doctrine:migrations:migrate
 
 php bin/console doctrine:fixtures:load


*. URL of the application is as http://127.0.0.1:8000
to execute from the command line,go to the application folder and run the command  symfony server:start,
after that the application can be accessed from the
following URL, http://127.0.0.1:8000

*.This application was built using Symphony 4.4 .
Doctrine ORM has been used to implement the model and twig template engine for templating purposes.




