<?php

namespace App\Http\Resources;

use App\Models\Rating;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'cover'=>$this->cover,
            'rating'=>$this->rate,
        ];
    }
  /*  public function rating()
    {
        if (!$this->hasMany(Rating::class)){
            return null;
        }
        $ratings =  $this->hasMany(Rating::class);
        if ($ratings->count() != 0){
            $sum = 0;
            foreach($ratings->select('rate')->get() as $rating){
                return $sum = $sum + $rating->rating;
            }
            $sum = $sum/$ratings->count();
            return $sum;
        }
        // return 0;
    }*/

}
