<?php

class BookPage {
	var $items;
	var $itemsPerPage;
	var $hasNextPage;

	public function __construct($itemsPerPage){
		$this->items = array();
		$this->itemsPerPage = (int) $itemsPerPage;
		$this->hasNextPage = 0;
	}

	public function pushItems($items){
		if(!is_array($items))
			$items = array($items);

		$this->items = array_merge($this->items, $items);
		if(count($this->items) > $this->itemsPerPage) $this->hasNextPage = 1;
		else $this->hasNextPage = 0;
	}

	public function getItems(){
		return $this->items;
	}

	public function setItemsPerPage($newNumber){
		$this->itemsPerPage = $newNumber;
		if(count($this->items) > $this->itemsPerPage) $this->hasNextPage = 1;
		else $this->hasNextPage = 0;
	}

	public function getItemsPerPage(){
		return $this->itemsPerPage;
	}

	public function hasNextPage(){
		return $this->hasNextPage;
	}
}

?>