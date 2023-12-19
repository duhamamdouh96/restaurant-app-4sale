<h2 align="center">Resturant app</h2>

## Clone the repository

 - Open your favorite terminal
 - Clone the repository in the work directory through the following command:
   
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

After the ports have been turned off, Please run the following command to install composer through docker:

    $ docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    
Run the following command:

    $ alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    
And then run the following command: 

    $ sail up -d

Run the following command:

    $ sail artisan key:generate

Run the following command:

    $ sail artisan migrate --seed
    
Open Postman or any app like it and go through the Postman collection: 
        https://documenter.getpostman.com/view/1879651/2s9Ykodgxa


You may also try to run the tests:

    $ sail artisan test 

That's it and you're good to go!
