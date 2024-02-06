<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


/*
Route::get('/counties/filterbydistrict/{district}', 'ApiController@getcounties');
 
Route::get('/subcounties/filterbycounty/{county}', 'ApiController@getsubcounties');
 
Route::get('/parishes/filterbysubcounty/{subcounty}', 'ApiController@getparishes');

Route::get('/villages/filterbyparish/{parish}', 'ApiController@getvillages');
Route::post('/districts/get', 'ApiController@districts');
*/

 

