<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    protected $msg = 'Something went wrong!';
    public function __construct($msg='Something went wrong!', $collection=[])
    {
        parent::__construct($collection);
        $this->msg = $msg;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'success' =>false,
            'data' =>null,
            'message' => $this->msg
        ];
    }


}
