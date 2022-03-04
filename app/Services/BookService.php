<?php

namespace App\Services;

use App\Services\BaseService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

use App\Repositories\BookRepository;

class BookService extends BaseService { 

    private $repository;

    public function __construct(BookRepository $repository) {

        $this->repository = $repository;

    }

    public function retrieveExternalBook($data) {

        $response = Http::get('https://www.anapioficeandfire.com/api/books', [
            'name' => isset($data['name']) ? $data['name'] : '',
        ]);
        if($response->successful() && count($response->json()) > 0){

            $this->serviceResponse['status'] = 'success';
            $this->serviceResponse['code'] = 200;
            $this->serviceResponse['data'] = $this->suppressResponseFields($response->json());


        }else{

            $this->serviceResponse['status'] = 'not found';
            $this->serviceResponse['code'] = 404;
        }

        return $this->serviceResponse;

    }

    private function suppressResponseFields($array) {

        for ($i=0; $i < count($array); $i++) { 
            
            $book =  $this->repository->fill($array[$i]);
            $book->number_of_pages = $array[$i]['numberOfPages'];
            $book->release_date = Carbon::parse($array[$i]['released'])->format('Y-m-d');

            $array[$i] = $book;
        }

        return $array;
    }

    public function create($data) {

        $values = [
            'name' => $data['name'],
            'isbn' => $data['isbn'],
            'authors' => $data['authors'],
            'country' => $data['country'],
            'number_of_pages' => $data['number_of_pages'],
            'publisher' => $data['publisher'],
            'release_date' => Carbon::parse($data['release_date'])->format('Y-m-d')
        ];

        $book = $this->repository->add($values);
        if($book && $book->id){

            $this->serviceResponse['status'] = 'success';
            $this->serviceResponse['data']['book'] = $values;
            $this->serviceResponse['code'] = 201;

        }else{

            $this->serviceResponse['status'] = 'failed';
            $this->serviceResponse['code'] = 400;

        }
        
        return $this->serviceResponse;
    }

    public function getAll($data) {

        $release_date = null;
        if(isset($data['release_date'])){
            $release_date = Carbon::createFromFormat('Y', $data['release_date'])->format('Y');
            unset($data['release_date']);
        }

        $books = $this->repository->allByValue($data, $release_date);

        $this->serviceResponse['data'] = $books;
        $this->serviceResponse['status'] = 'success';
        $this->serviceResponse['code'] = 200;
        
        return $this->serviceResponse;
    }

    public function update($data, $id) {

        if(isset($data['release_date'])){
            $data['release_date'] = Carbon::parse($data['release_date'])->format('Y-m-d');
        }

        $book = $this->repository->find($id);
        if($book && $book->id){

            $name = $book->name;
            if($this->repository->update($book, $data)){
            
                $this->serviceResponse['status'] = 'success';
                $this->serviceResponse['message'] = 'The book '.$name.' was updated successfully';
                $this->serviceResponse['data'] = $book;
                $this->serviceResponse['code'] = 200;

            }else{

                $this->serviceResponse['status'] = 'failed';
                $this->serviceResponse['message'] = 'The book was not updated';
                $this->serviceResponse['code'] = 400;

            }

        }else{

            $this->serviceResponse['status'] = 'failed';
            $this->serviceResponse['message'] = 'The book does not exist';
            $this->serviceResponse['code'] = 404;

        }
        
        return $this->serviceResponse;
    }

    public function delete($id) {

        $book = $this->repository->find($id);
        if($book && $book->id){

            $name = $book->name;
            if($this->repository->destroy($id)){
            
                $this->serviceResponse['status'] = 'success';
                $this->serviceResponse['message'] = 'The book "'.$name.'" was deleted successfully';
                $this->serviceResponse['code'] = 200;

            }else{

                $this->serviceResponse['status'] = 'failed';
                $this->serviceResponse['message'] = 'The book was not deleted';
                $this->serviceResponse['code'] = 400;

            }

        }else{

            $this->serviceResponse['status'] = 'failed';
            $this->serviceResponse['message'] = 'The book does not exist';
            $this->serviceResponse['code'] = 404;

        }
        
        return $this->serviceResponse;

    }

    public function getById($id) {

        $book = $this->repository->find($id);
        if($book && $book->id){

            $this->serviceResponse['status'] = 'success';
            $this->serviceResponse['data'] = $book;
            $this->serviceResponse['code'] = 200;

        }else{

            $this->serviceResponse['status'] = 'not found';
            $this->serviceResponse['message'] = 'The book does not exist';
            $this->serviceResponse['code'] = 404;

        }
        
        return $this->serviceResponse;

    }
}