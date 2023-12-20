<h2 align="center">Resturant app</h2>

## Clone the repository

 - Open your favorite terminal
 - Clone the repository in your working directory through the following command:
   
       $ git clone https://github.com/duhamamdouh96/restaurant-app-4sale.git

   *You can use SSH instead or any other way you like*

## Environment
- Please copy .env.example file that you'll find inside the repository directory
- Create .env file in the repository directory and paste the contents from the above step
- Please find the ports that I'm using in the docker-compose.yml file and turn the ports off from your side

    - *mysql port => 3306*
    - *app port => 8000*
    - *mailsearch port => 7700*
    - *redis port => 6379*
    - *mailpit port => 1025, 8025*

*Please download and install docker on your machine "https://www.docker.com/get-started/"*

After the ports have been turned off, Please run the following command to install composer through docker:

    $ docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    
Run the following command to make an alias for sail:

    $ alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    
Then run the following command to pull the images and create the app containers: 

    $ sail up -d

Run the following command to generate the application key inside .env file:

    $ sail artisan key:generate

Run the following command to create and seed the database tables:

    $ sail artisan migrate --seed
    
Open Postman or any app like it and go through the Postman collection: 
        https://documenter.getpostman.com/view/1879651/2s9Ykodgxa


You may also try to run the tests:

    $ sail artisan test 

*I assumed that we have waiters "users" in the application and we have an API to get all the available waiters to assign each to order,
Also, the application should have a listing for reservations API per customer, waiter, and admin*

Also here's a command for the waiting list:

    $ sail artisan reserve-table-from-waiting-list

 *and I assumed that we configured a cron entry in the server "Laravel task scheduling" to run every hour - or any specific time - to check if a table is available then push it to a queue and reserve it for the next record on the waiting list*

That's it and you're good to go!
