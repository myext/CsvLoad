# CsvLoad
This package for Laravel 5.4+ allows you to easily and quickly insert data from a CSV file into a database.

The package works with the MySql and uses `INFORMATION_SCHEMA`

Before using the software, you need to create a model and a table in the database.

The fields of the csv file must match the fields in the DB table.


Installation.

1/ Install with Composer


composer require zvg/csvload

or 

add in composer.json 


"require": {
        .
        .
        
        "zvg/csvload": "*",
        
        .
        .
        
    }



2/ Add the service provider to config/app.php

'providers' => [

    '...',
    
    'Zvg\CsvLoad\CsvLoadServiceProvider::class',
    
];

3/ Publish files

php artisan vendor:publish --provider="Zvg\CsvLoad\CsvLoadServiceProvider"

Using.

Insert into your blade ( 'admin' or other ) form to load CSV file.
 
 @include('zvg::csv_form',['model' => 'your model'])
  
  You need insert full model name
 ( for exemple @include('zvg::csv_form',['model' => '\App\Adress'])).
 
 
