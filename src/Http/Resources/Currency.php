<?php

namespace Shopper\Framework\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Currency extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'exchange_rate' => $this->exchange_rate,
        ];
    }
}
