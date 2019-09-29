<?php 


require('AlboCittaDellaSaluteEntry.php');

class AlboCittaDellaSaluteParser implements Iterator{
	private $rows;
	private $index;

	/**
	 * Parse the entries of the Albo from the rows of the table in the Albo Pretorio page.
	 */
	public function __construct($page) 
	{
	
		$this->rows=$page->getElementsByTagName('atto');
		$this->index=0;
	}

	public function current()
	{
		return new AlboCittaDellaSaluteEntry($this->rows[$this->index]);
	}
	
	
	public function key (){
		return $this->index;
	}
	
	public function next(){
		++$this->index;
	}
	
	public function rewind(){
		$this->index=0;
	}
	
	public function valid(){
		return $this->rows->length>0 && $this->index<($this->rows->length);
	}
}
?>