# Pokerhands
 Pokerhand Test Project

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

PHP Version 7.3.17 or greater  
Laravel Framework 7.9.2  
PHPUnit 8.0.0

 
### Installing

Go to folder where you want to install the project

```
git clone https://github.com/jonaber/pokerhands.git
```

Run Composer update to update the dependencies

```
composer update
```


Copy the .env.example to .env

Generate an APP_KEY by doing the following:  

```
php artisan key:generate
```
Rename the APP_NAME in the .env file to PokerHands

Create database Laravel on your local host (the same setting as in the .env file)

Run migration and seeder scripts

```
php artisan migrate:refresh
```
```
php artisan db:seed
```


## Running the tests

Go to the project folder

Run PHPunit to do the PHPUnit tests for PokerHandValidationTest.php

Example:
```
vendor\bin\phpunit tests\unit\PokerHandValidationTest.php
```

## Playing the game on the website

Run the website on your server    
In the welcome page, click on Register to create a new user  
Once the user is created, you are redirected to the home page   
A page is shown to upload a file containing a list of Poker hands for 2 players in the format of:

```
8C TS KC 9H 4S 7D 2S 5D 3S AC
```

Once the file is selected you can upload the file by pressing Upload button.  
The file takes may take some time to upload.  
After the file is uploaded you are notified and you can start to play, by pressing the PLAY button.  
Once the play button is calculated (this may take some time), you are redirected to the results page  
where you can see the user who won together.
with other statistics on the players.



