<?php

namespace App\Http\Resources;
use Illuminate\Http\Response;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthLoginRegisterResource extends JsonResource
{
    public static $wrap = null;

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(Response::HTTP_CREATED);
    }

    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email

            ],
            "token" => $this->token
        ];
    }
}
