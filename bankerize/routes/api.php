<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiServices;
use App\Http\Requests\ValidProposal;
use Illuminate\Support\Facades\Log;

Route::get('/', function(){
    //verificar se está ok.
    return ["api"=>"funcionando"];
});

Route::post('/proposal', function (ValidProposal $request) {
    try {
        $result = ApiServices::createProposal($request);
    } catch (\Throwable $th) {
        log::error(error: 'erro ao acessar o endpont: ' . $th->getMessage());
        return response()->json([
            'Message' => 'tente novamente em poucos instantes'
        ], 500);
    }
    return $result;
});
