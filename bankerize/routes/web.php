<?php

use Illuminate\Support\Facades\Route;

Route::get('/proposal', function () {
    return [
        'cpf'=> "654564654654",
        "nome"=> "",
        "data_nascimento"=>"",
        "valor emprestimo"=>"",
        "chave_pix"=>"chave@chave.com"
    ];
});
