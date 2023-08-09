<?php

namespace App\Dtos;

class SearchQuery
{

    public static function fromJson(array $json): SearchQuery
    {
        $keyword = isset($json['keyword']) ? $json['keyword'] : null;
        $fields = isset($json['fields']) ? $json['fields'] : [];
        $offset = isset($json['offset']) ? $json['offset'] : 0;
        $limit = isset($json['limit']) ? $json['limit'] : 10;
        $sort = isset($json['sort']) ? $json['sort'] : null;
        return new SearchQuery($keyword, $fields, $offset, $limit, $sort);
    }


    /**
     *
     */
    public ?string $keyword=null;

    /**
     *
     */
    public array $fields;

    /**
     *
     */
    public int $offset;

    /**
     *
     */
    public int $limit;

    /**
     *
     */
    public  $sort;


    public function __construct($keyword, array $fields = [], int $offset = 0, int $limit = 10,  $sort = null)
    {
        $this->keyword = $keyword;
        $this->fields = $fields;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->sort = $sort;
    }
}
