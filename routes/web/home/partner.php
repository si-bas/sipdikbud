<?php

Route::prefix('home/partner')->group(function () {
    Route::get('/institute', 'PartnerController@institute')->name('home.partner.institute');  
});