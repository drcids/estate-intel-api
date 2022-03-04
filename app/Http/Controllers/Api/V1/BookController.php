<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Services\BookService;
use App\Http\Requests\Api\V1\BookRequest;

class BookController extends BaseApiController
{
    protected $bookService;

    public function __construct(BookService $bookService) {

        $this->bookService = $bookService;
    }

    public function getExternalBooks(BookRequest $request) {

        $validated = $request->safe()->all();

        $response = $this->bookService->retrieveExternalBook($validated);

        return $this->returnResponse($response['status'], $response['code'], $response['data'], $response['message'] );
        

    }

    public function create(BookRequest $request) {

        $validated = $request->safe()->all();

        $response = $this->bookService->create($validated);

        return $this->returnResponse($response['status'], $response['code'], $response['data'], $response['message'] );
        

    }

    public function read(BookRequest $request) {

        $validated = Arr::only($request->safe()->all(), ['name', 'country', 'publisher', 'release_date']);

        $response = $this->bookService->getAll($validated);

        return $this->returnResponse($response['status'], $response['code'], $response['data'], $response['message'] );
        

    }

    public function update(BookRequest $request) {

        $validated = $request->safe()->all();

        $response = $this->bookService->update($validated, $request->route('id'));

        return $this->returnResponse($response['status'], $response['code'], $response['data'], $response['message'] );
        
    }

    public function delete(BookRequest $request) {

        $response = $this->bookService->delete($request->route('id'));

        return $this->returnResponse($response['status'], $response['code'], $response['data'], $response['message'] );
        
    }

    public function show(BookRequest $request) {

        $response = $this->bookService->getById($request->route('id'));

        return $this->returnResponse($response['status'], $response['code'], $response['data'], $response['message'] );
        
    }

    
}
