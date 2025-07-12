<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalModel extends Model
{
    public $table = 'proposals';
    public $fillable = [
        'cpf',
        'nome',
        'data_nasc',
        'valor_emprest',
        'key_pix',
        'accepted'
    ];
}
