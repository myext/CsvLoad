# CsvLoad
This package for Laravel 5.4+ allows you to easily and quickly insert data from a CSV file into a database.

Before using the software, you need to create a model and a table in the database.

Installation.

1/ Install with Composer

composer require zvg/csvload

2/ Add the service provider to config/app.php

'providers' => [
    '...',
    'Zvg\CsvLoad\CsvLoadServiceProvider::class',
];

3/ Publish the config file

php artisan vendor:publish --provider="Zvg\CsvLoad\CsvLoadServiceProvider"

Using.

Insert into your blade ( 'admin' or other ) form to load CSV file.
 
 @include('svg::csv from',['model' => 'your model'])
  
  You need insert full model name
 ( for exemple @include('zvg::csv_form',['model' => '\App\Adress'])).
 
 
