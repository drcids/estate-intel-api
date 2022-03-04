<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository {

    public function add(array $values) {

        return Book::create($values);

    }

    public function fill(array $values) {

        $book = new Book;
        $book->fill($values);

        return $book;
    }

    public function allByValue(array $values, $date = null) {

        return isset($date) ? Book::where($values)->where('release_date', 'LIKE', "%{$date}%")->get() : 
            Book::where($values)->get();
    }

    public function find($id) { 

        return Book::where('id', $id)->first();

    }

    public function update(Book $book, array $values){

        return $book->update($values);

    }

    public function destroy($id) { 

        return Book::destroy($id);

    }
}