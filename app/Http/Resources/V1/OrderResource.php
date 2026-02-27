<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => (int) $this->user_id,
            'estado' => $this->estado,
            'fecha_pedido' => optional($this->fecha_pedido)?->toISOString(),
            'direccion_envio' => $this->direccion_envio,
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
        ];
    }
}
