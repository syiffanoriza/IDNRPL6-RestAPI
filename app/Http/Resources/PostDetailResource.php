<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        // 27 MAR 2023
        return [
            'id' => $this->id,
            'title' => $this->title,
            // 11 APR 2023
            'image' => $this->image,
            'news_content' => $this->news_content,
            'created_at' => date_format($this->created_at, "Y/m/d H:i:s"),
            'author' => $this->author,
            'writer' => $this->whenLoaded('writer'),
            // 10 APR 2023
            'comments' => $this->whenLoaded('comments', function(){
                return collect($this->comments)->each(function($comment){
                    $comment->comentator;
                    return $comment;
                });
            }),
            'comment_total' => $this->whenLoaded('comments', function(){
                return $this->comments->count();
            })
        ];
    }
}
