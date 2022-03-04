<?php

namespace App\Services;

class BaseService { 

    protected $serviceResponse = [
        'status' => 'success',
        'code' => 200,
        'message' => null ,
        'data' => [] ,
    ];

}