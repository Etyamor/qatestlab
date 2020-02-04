<?php
class Book{
 
    private $conn;
    private $table_name = "books";

    public $id;
    public $name;
    public $authors_id;
    public $publication_id;
 
    public function __construct($db){
        $this->conn = $db;
    }

	function read(){
	    $query = "SELECT books.id, books.name, books.publication_id, publications.name AS publication_name, GROUP_CONCAT(authors.name) AS authors_name
	    FROM ". $this->table_name ."
	    LEFT JOIN publications ON books.publication_id = publications.id
	    LEFT JOIN m2m_books_authors ON books.id = m2m_books_authors.book_id
	    LEFT JOIN authors ON m2m_books_authors.author_id = authors.id
	    GROUP BY books.id";
	    $stmt = $this->conn->prepare($query);
	    $stmt->execute();
	    return $stmt;
	}

	function create(){
	    $query_book = "INSERT INTO " . $this->table_name . " SET name=:name, publication_id=:publication_id";

	    $stmt = $this->conn->prepare($query_book);
	 
	    $this->name=htmlspecialchars(strip_tags($this->name));
	    $this->publication_id=htmlspecialchars(strip_tags($this->publication_id));

	    $stmt->bindParam(":name", $this->name);
	    $stmt->bindParam(":publication_id", $this->publication_id);

	    if($stmt->execute()){
	    	$lastId = $this->conn->lastInsertId();
	    	foreach ($this->authors_id as $id) {
		        $query_book_author = "INSERT INTO m2m_books_authors SET book_id=:book_id, author_id=:author_id";

		        $stmt = $this->conn->prepare($query_book_author);

		        $this->name=htmlspecialchars(strip_tags($this->name));
		    	$this->publication_id=htmlspecialchars(strip_tags($this->publication_id));

		    	$stmt->bindParam(":book_id", $lastId);
		    	$stmt->bindParam(":author_id", $id);

		    	$stmt->execute();
	    	}
	    	return true;
	    }
	    return false;     
	}

	function delete(){
		$query = "DELETE FROM m2m_books_authors WHERE book_id = ?";
 		$stmt = $this->conn->prepare($query);
 		$this->id=htmlspecialchars(strip_tags($this->id));
    	$stmt->bindParam(1, $this->id);
	 
	    if($stmt->execute()){
	 		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		    $stmt = $this->conn->prepare($query);
		    $this->id=htmlspecialchars(strip_tags($this->id));
		    $stmt->bindParam(1, $this->id);
		    if($stmt->execute()){
		    	return true;
		    }
	    }
	 
	    return false;	     
	}

	function update(){
	    $query = "UPDATE
	                " . $this->table_name . "
	            SET
	                name = :name,
	                publication_id = :publication_id
	            WHERE
	                id = :id";
	 
	    $stmt = $this->conn->prepare($query);
	 
	    $this->name=htmlspecialchars(strip_tags($this->name));
	    $this->publication_id=htmlspecialchars(strip_tags($this->publication_id));
	    $this->id=htmlspecialchars(strip_tags($this->id));
	 
	    $stmt->bindParam(':name', $this->name);
	    $stmt->bindParam(':publication_id', $this->publication_id);
	    $stmt->bindParam(':id', $this->id);
	 
	    if($stmt->execute()){
	    	if($this->authors_id) {

	    		$query_book_author = "DELETE FROM m2m_books_authors WHERE book_id=:id";
 				$stmt = $this->conn->prepare($query_book_author);

 				$stmt->bindParam(':id', $this->id);

 				if($stmt->execute()){
    				foreach ($this->authors_id as $author_id) {
	 					$query_book_author = "INSERT INTO m2m_books_authors SET book_id=:id, author_id=:author_id";
	    				$stmt = $this->conn->prepare($query_book_author);

						$stmt->bindParam(':id', $this->id);
						$stmt->bindParam(':author_id', $author_id);

						$stmt->execute();
    				}
    				return true;
 				}

	    	}
	    }
	 
	    return false;
	}

}