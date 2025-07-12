<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;

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
    public static function createProposal()
    {
        $response = Http::get(env('URL_AUTORIZE'));
        $users = $response->json();
        return $users;
    }
    public static function notify()
    {

    }
}
