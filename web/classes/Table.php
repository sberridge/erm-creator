<?php
	class Table extends Base {
		public $id;
		public $name;
		public $project;
		public $x;
		public $y;
		public $table = "table";

		function __construct() {
			parent::__construct();
		}
		function load($id) {
			$this->query("SELECT id,name,project_id,x,y FROM `table` WHERE id = ?");
			$this->execute(array($id));
			if($this->rowCount() > 0) {
				$result = $this->getResult();
				$this->id = $result['id'];
				$this->name = $result['name'];
				$this->project = $result['project_id'];
				$this->x = $result['x'];
				$this->y = $result['y'];
				return true;
			} else {
				return false;
			}
		}
		function create($project,$name,$x,$y) {
			$this->query("INSERT INTO `table`(project_id,name,x,y) VALUES(?,?,?,?)");
			$this->execute(array($project,$name,$x,$y));
			if($this->rowCount() > 0) {
				$id = $this->lastId();
				$this->load($id);
				return true;
			} else {
				return false;
			}
		}
		function update($prop,$value) {
			$this->query("UPDATE `table` SET ".$prop." = ? WHERE id = ?");
			$this->execute(array($value,$this->id));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function fields() {
			$this->query("SELECT id FROM `field` WHERE table_id = ?");
			$this->execute(array($this->id));
			$fields = array();
			while($result = $this->getResult()) {
				$field = new Column;
				$field->load($result['id']);
				$fields[$field->id] = $field;
			}
			return $fields;
		}
		function delete() {
			$this->query("DELETE FROM `table` WHERE id = ?");
			$this->execute(array($this->id));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function connectors() {
			$this->query("SELECT id FROM connector INNER JOIN table_connector ON connector.id = table_connector.connector_id WHERE table_connector.table_id = ?");
			$this->execute(array($this->id));
			$connectors = array();
			while($result = $this->getResult()) {
				$connector = new Connector;
				$connector->load($result['id']);
				$connectors[] = $connector;
			}
			return $connectors;
		}
	}
?>