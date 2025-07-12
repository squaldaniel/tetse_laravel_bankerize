<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiServices;
use App\Http\Requests\ValidProposal;

Route::get('/', function(){
    //verificar se está ok.
    return ["api"=>"funcionando"];
});

Route::post('/proposal', function (ValidProposal $request) {
    try {
        $result = ApiServices::createProposal($request);
    } catch (\Throwable $th) {
        throw $th;
    }
    // return $result;
    return $result;

});
