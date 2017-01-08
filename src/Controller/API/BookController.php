<?php
namespace Controller\API;

use Library\Controller;
use Library\Request;
use Library\Response;
use Model\Book;

class BookController extends Controller{

    //GET books: curl http://host/api/books
    //GET books: curl http://host/api/books?format=xml
    //GET books: curl http://host/api/books?format=html
    //GET book: curl http://host/api/books/15
    //UPDATE book: curl -X PUT -d '{"title":"Edited book","price":500,"description":"edited with api","is_active":1,"authors":[1,2,3],"style_id":1}' http://host/api/books/6
    //DELETE book: curl -X DELETE mymvc/api/books/5
    //ADD new Book: curl -X POST -d json='{"title":"New book","price":555,"description":"This book was added with api","is_active":1,"authors":[5,7,9],"style_id":5}' http://host/api/books/

    public function indexAction(Request $request){
        $outputFormatter = $this->getOutputFormatter($request);

        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $books = $repo->getAll($hydrateArray = true);
        if(!$books){
            return new Response(404, 'Books not found', $outputFormatter);
        }

		return new Response(200, $books, $outputFormatter);
	}
	
	public function showAction(Request $request){
        $outputFormatter = $this->getOutputFormatter($request);

        $repo = $this->container->get('repository_manager')->getRepository('Book');

        if(!in_array($request->get('id'), $repo->getBooksIds())){
            return new Response(404, 'Book not found', $outputFormatter);
		}
        $id = $request->get('id');
		$book = $repo->getById($id, $hydrateArray = true);
        if(!$book['is_active']){
            return new Response(403, 'Book is not active', $outputFormatter);
        }
        $response = array('book' => $book);

        return new Response(200, $response, $outputFormatter);
	}

    public function addAction(Request $request){
        $outputFormatter = $this->getOutputFormatter($request);
        $repo = $this->container->get('repository_manager')->getRepository('Book');

        if(!$dataString = $request->post('json')){
            return new Response(400, 'Bad request', $outputFormatter);
        }

        $postData = json_decode($dataString, $assoc = true);
        if(empty($postData['title']) or
            empty($postData['price']) or
            empty($postData['authors']) or
            empty($postData['style_id'])
        ){
            return new Response(400, 'Bad request, send title,price,style_id and authors[]', $outputFormatter);
        }

        $description = isset($postData['description']) ? $postData['description'] : '';
        $is_active = isset($postData['is_active']) ? $postData['is_active'] : 0;

        $book = (new Book())->setTitle( $postData['title'])
            ->setDescription($description)
            ->setPrice($postData['price'])
            ->setStyleId($postData['style_id'])
            ->setAuthorIds($postData['authors'])
            ->setIsActive($is_active);

        $repo->insertBook($book);
        $newBookId = $repo->getLastInsertId();
        $repo->insertBookAuthor($newBookId,$book->getAuthorIds());

        $this->saveLog('New book added');

        return new Response(200, 'New book added', $outputFormatter);
    }

    public function deleteAction(Request $request){
        $outputFormatter = $this->getOutputFormatter($request);
        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $bookId = $request->get('id');

        if(!in_array($bookId, $repo->getBooksIds())){
            return new Response(404, 'Book not found', $outputFormatter);
        }

        if(!$repo->deleteById($bookId)){
            return new Response(500, 'Book not deleted', $outputFormatter);
        }

        $this->saveLog('Book with id: ' . $bookId . ' deleted');

        return new Response(200, 'Book successfully deleted', $outputFormatter);
    }

    public function updateAction(Request $request){
        $outputFormatter = $this->getOutputFormatter($request);
        $repo = $this->container->get('repository_manager')->getRepository('Book');
        $bookId = $request->get('id');

        if(!in_array($bookId, $repo->getBooksIds())){
            return new Response(404, 'Book not found', $outputFormatter);
        }

        $putObj = json_decode(file_get_contents('php://input'));
        $putData = array();

        foreach($putObj as $key => $value){
            $putData[$key] = $value;
        }

        if(empty($putData['title']) or
            empty($putData['price']) or
            empty($putData['authors']) or
            empty($putData['style_id'])
        ){
            return new Response(400, 'Bad request', $outputFormatter);
        }

        $description = isset($putData['description']) ? $putData['description'] : '';
        $is_active = isset($putData['is_active']) ? $putData['is_active'] : 0;

        $editedBook = (new Book())->setTitle( $putData['title'])
            ->setDescription($description)
            ->setId($bookId)
            ->setPrice($putData['price'])
            ->setStyleId($putData['style_id'])
            ->setAuthorIds($putData['authors'])
            ->setIsActive($is_active);

        $repo->updateBook($editedBook)
            ->deleteBookAuthor($editedBook->getId())
            ->insertBookAuthor($editedBook->getId(), $editedBook->getAuthorIds());

        $this->saveLog('Book id: ' . $bookId . ' changed', ['With API']);

        return new Response(200, 'Book updated', $outputFormatter);
    }
}