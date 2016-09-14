<?php

class BookPage {
	private var $items;
	private var $itemsPerPage;
	private var $hasNextPage;

	public function __construct($itemsPerPage){
		$this->items = array();
		$this->itemsPerPage = $itemsPerPage;
		$this->hasNextPage = false;
	}

	public function pushItems($items){
		if(!is_array($items))
			$items = array($items);

		$this->items = array_merge($this->items, $items);
		$this->hasNextPage = (count($this->items) > $this->itemsPerPage);
	}

	public function getItems(){
		return $this->items;
	}

	public function setItemsPerPage($newNumber){
		$this->itemsPerPage = $newNumber;
		$this->hasNextPage = (count($this->items) > $this->itemsPerPage);
	}

	public function getItemsPerPage(){
		return $this->itemsPerPage;
	}

	public function hasNextPage(){
		return $this->hasNextPage;
	}
}

?>