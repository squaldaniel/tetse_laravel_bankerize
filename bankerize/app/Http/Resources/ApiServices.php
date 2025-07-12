<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use App\Models\ProposalModel;
use Illuminate\Support\Facades\Log;

class ApiServices extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
    /**
     * funcção responsavel por criar as propostas
     * @param void
     * @return json
     */
    public static function createProposal(Request $request)
    {
        $dadosValidos = (object) $request->validated();
        $dadosValidos->cpf = preg_replace('/[^0-9]/', '', $dadosValidos->cpf);
        try {
            // checa proposta do CPF
            $exists = self::checkProposal($dadosValidos);
            if($exists == 0){
                // cadastra caso não exista com esse CPF
                $proposal = ProposalModel::create((array) $dadosValidos);
            } else {
                // atualiza proposta, caso exista com esse CPF
                $proposal = ProposalModel::where('cpf', $dadosValidos->cpf)
                            ->update((array) $dadosValidos);

            }
        } catch (\Throwable $th) {
            log::error('Erro ao criar proposta: ' . $th->getMessage(), [
                'cpf' => $dadosValidos->cpf,
                'nome' => $dadosValidos->nome,
                'data_nasc' => $dadosValidos->data_nasc,
                'valor_emprest' => $dadosValidos->valor_emprest,
                'key_pix' => $dadosValidos->key_pix
            ]);
            return response()->json([
                'Message' => 'Tente novamente em poucos instantes'
            ], 503);
        }

        $response = Http::get(env('URL_AUTORIZE'));
        if($response->failed()){
            return response()->json([
                'Message' => 'proposta recebida, mas, ainda não aceita.'
            ], 304);
        };
        if($response->successful()){
            ProposalModel::where('cpf', $dadosValidos->cpf)
                ->update([
                    'accepted'=> true
                ]);
            return response()->json([
                'Message' => 'proposta aceita.'
            ], 202);
        }
    }
    public static function notify()
    {

    }
    /**
     * Verifica se a proposta com este cpf já foi cadastrada, caso já tenha sido,
     * atualiza os dados
     * @param object $validData
     */
    public static function checkProposal($validData)
    {
        $exists = ProposalModel::where('cpf', $validData->cpf)->count();
        return $exists;
    }
}
