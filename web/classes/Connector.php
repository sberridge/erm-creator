<?php
	class Connector extends Base {
		public $id;
		public $type;
		public $tables = array();
		public $table = "connector";

		function load($id) {
			$this->query("SELECT connector.id,connector.type,table_connector.table_id,table_connector.order FROM connector INNER JOIN table_connector ON connector.id = table_connector.connector_id WHERE connector.id = ?");
			$this->execute(array($id));
			if($this->rowCount() > 0) {
				while($result = $this->getResult()) {
					$this->id = $result['id'];
					$this->type = $result['type'];
					$this->tables[$result['order']] = $result['table_id'];
				}
				return true;
			} else {
				return false;
			}
		}
		function create($type,$table1,$table2) {
			$this->query("INSERT INTO connector(type) VALUES(?)");
			$this->execute(array($type));
			if($this->rowCount() > 0) {
				$id = $this->lastId();
				$table = new Table;
				$this->query("INSERT INTO table_connector(table_id,connector_id,`order`) VALUES(?,?,?)");
				if($table->load($table1)) {					
					$this->execute(array($table->id,$id,1));
				}
				if($table->load($table2)) {
					$this->execute(array($table->id,$id,2));
				}
				$this->load($id);
				return true;
			} else {
				return false;
			}
		}
		function update($prop,$value) {
			$this->query("UPDATE `connector` SET ".$prop." = ? WHERE id = ?");
			$this->execute(array($value,$this->id));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function delete() {
			$this->query("DELETE FROM `connector` WHERE id = ?");
			$this->execute(array($this->id));
			if($this->rowCount() > 0) {
				$this->query("DELETE FROM table_connector WHERE connector_id = ?");
				$this->execute(array($this->id));
				return true;
			} else {
				return false;
			}
		}
	}
?>