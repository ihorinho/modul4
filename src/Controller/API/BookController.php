<?php
namespace Controller\API;

use Library\Controller;
use Library\Request;
use Library\Response;
use Model\Book;

class BookController extends Controller{

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

        return new Response(200, $book, $outputFormatter);
	}

    public function addAction(Request $request){
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

        $title = $putData['title'];
        $price = $putData['price'];
        $authors = $putData['authors'];
        $description = isset($putData['description']) ? $putData['description'] : '';
        $style_id = $putData['style_id'];
        $is_active = isset($putData['is_active']) ? $putData['is_active'] : 0;

        $editedBook = (new Book())->setTitle($title)
            ->setDescription($description)
            ->setId($bookId)
            ->setPrice($price)
            ->setStyleId($style_id)
            ->setAuthorIds($authors)
            ->setIsActive($is_active);

        $repo->updateBook($editedBook)
            ->deleteBookAuthor($editedBook->getId())
            ->insertBookAuthor($editedBook->getId(), $editedBook->getAuthorIds());

        $this->saveLog('Book id: ' . $bookId . ' changed', ['With API']);

        return new Response(200, 'Book updated', $outputFormatter);
    }
}