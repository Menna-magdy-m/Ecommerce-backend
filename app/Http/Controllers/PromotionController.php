<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\SearchRequest;
use App\Services\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    private $promotionService;

    /**
     * @param $promotionService
     */
    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index(SearchRequest $request)
    {
        return $this->ok($this->promotionService->search(SearchQuery::fromJson($request->all())));
    }

    public function store(Request $request)
    {
        return $this->ok($this->promotionService->createPromotion($request->all()));
    }
}
