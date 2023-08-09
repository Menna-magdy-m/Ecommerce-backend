<?php

namespace App\Http\Controllers;


use App\Dtos\SearchQuery;
use App\Http\Requests\OfferRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Responses\SuccessResponse;
use App\Services\OfferService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    private $offer_service;

    /**
     * @param OfferService $offer_service
     */
    public function __construct(OfferService $offer_service)
    {
        $this->offer_service = $offer_service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return SuccessResponse
     */
    public function index(SearchRequest $request)
    {
        return $this->ok($this->offer_service->search(SearchQuery::fromJson($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OfferRequest $request
     * @return SuccessResponse
     */
    public function store(OfferRequest $request)
    {
        return $this->ok($this->offer_service->createOffer($request->all()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OfferRequest $request
     * @param int $id
     * @return SuccessResponse
     */
    public function update(OfferRequest $request, int $id)
    {
        return $this->ok($this->offer_service->updateOffer($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return SuccessResponse
     */
    public function destroy(int $id)
    {
        return $this->ok($this->offer_service->deleteOffer($id));
    }
}
