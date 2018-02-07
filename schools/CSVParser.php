<?php
class CSVParser implements Iterator{

	private $handle;
	private $rowProcessingFunction;
	private $current = null;

	/**
	 * Create an iterator which will parse the specified handle
	 *
	 * @param $rowProcessingFunction a function which get a CSV row as array in input and produce a corresponding object.
	 */
	public function __construct($handle, $rowProcessingFunction){
		$this->handle=$handle;
		$this->rowProcessingFunction=$rowProcessingFunction;
		//$this->readNext();
	}

	/**
	  * read the next row and set it as current
	  */
	private function readNext(){
		if (feof($this->handle))
			$this->current=null;
		else{
			$processor=$this->rowProcessingFunction;
			$row=fgetcsv($this->handle, 1000, ",");
			$this->current=$processor($row);
		}	
	}

	public function current ( ){
		return $this->current;
	}

	public function key ( ){
		return $this->current->codice;
	}

	public function next(){
		$this->readNext();
		return $this->current;
	}

	public function rewind (){
		try{
			fseek($this->handle, 0);
		}catch(Exception $e){
			//empty, just ignore if stream does not suppor fseek
		}
		$this->readNext();
	}

	public function valid (){
		return $this->current!==null;
	}
}
?>