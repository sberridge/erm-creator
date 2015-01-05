<?php
	class Column extends Base {
		public $id;
		public $name;
		public $table = "field";
		public $data_type_id;
		public $length;
		public $index_id;
		public $nullable;

		function __construct() {
			parent::__construct();
		}
		function create($name,$table) {
			$this->query("INSERT INTO `field`(name,table_id) VALUES(?,?)");
			$this->execute(array($name,$table));
			if($this->rowCount() > 0) {
				$id = $this->lastId();
				$this->load($id);
				return true;
			} else {
				return false;
			}
		}
		function load($id) {
			$this->query("SELECT * FROM `field` WHERE id = ?");
			$this->execute(array($id));
			if($this->rowCount() > 0) {
				$result = $this->getResult();
				$this->id = $result['id'];
				$this->name = $result['name'];
				$this->table = $result['table_id'];
				return true;
			} else {
				return false;
			}
		}
		function update($prop,$value) {
			$this->query("UPDATE `field` SET ".$prop." = ? WHERE id = ?");
			$this->execute(array($value,$this->id));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function delete() {
			$this->query("DELETE FROM `field` WHERE id = ?");
			$this->execute(array($this->id));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
	}
?>