*.Application files are either located in htdocs folder in Xampp or /var/www/html in a Linux environment.
It is mandatory to give full permisson to the root folder if this is running in a linux environment.

* Create The Database
To change the database credentials (username and password) go to .env file in intranet/.env

*to execute from the command line,go to the application folder and run  below the commands

 php bin/console doctrine:migrations:migrate
 php bin/console doctrine:fixtures:load



*. URL of the application is as http://localhost/Internet/public/
to execute from the command line,go to the application folder and run the command  symfony server:start,
after that the application can be accessed from the
following URL, http://127.0.0.1:8000

*.Items available for the users to add to cart are categorized into 'Children' and 'Fiction'
User can view the items that have been added to the cart and their quantity by clicking the cart icon.
When the cart icon is clicked it displays a popup with a list of currently added items and their quantity.
User can remove an item from the list if needed.

*.Invoice can be viewed in View Invoice page with the discounted values if available.

*.This application was built using Symphony 4.4 .
Doctrine ORM has been used to implement the model and twig template engine for templating purposes.




