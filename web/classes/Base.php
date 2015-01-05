<?php
	class Base {
			private $dbHandle;
			private $query;
			private $result;
			
			function __CONSTRUCT() {
					global $dbHandle;
					if(!isset($this->dbHandle)) {
							$this->dbHandle = $dbHandle;
					}
			}
			
			function query($query) {
					$this->query = $this->dbHandle->prepare($query);
			}
			
			function execute($params=NULL) {
					if(isset($params)) {
							$this->result = $this->query->execute($params);
					} else {
							$this->result = $this->query->execute();
					}
			}
			
			function getResult() {
					return $this->query->fetch(PDO::FETCH_ASSOC);
			}
			function rowCount() {
					return $this->query->rowCount();
			}
			function lastId() {
					return $this->dbHandle->lastInsertId();
			}
			function get($query=null,$params=array()) {
			$queryString = "SELECT * FROM ".$this->table." ";
			$queryString .= $query;
			$this->query($queryString);
			$this->execute($params);
			$results = array();
			while($result = $this->getResult()) {
				$results[] = $result;
			}
			return $results;
		}
	}
?>