<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiServices;

Route::get('/', function(){
    //verificar se está ok.
    return ["api"=>"funcionando"];
});

Route::post('/proposal', function (Request $request) {
    $token = csrf_token();
    try {
        $result = ApiServices::createProposal();
    } catch (\Throwable $th) {
        throw $th;
    }
    return $result;
    // return [
    //     'cpf'=> "654564654654",
    //     "nome"=> "",
    //     "data_nascimento"=>"",
    //     "valor emprestimo"=>"",
    //     "chave_pix"=>"chave@chave.com"
    // ];
});
