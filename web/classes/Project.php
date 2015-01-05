<?php
	class Project extends Base {
		public $id;
		public $name;
		public $tables;
		public $table = "project";
		public $ownerId;
		public $publicId;

		function __construct() {
			parent::__construct();
		}
		function load($id) {
			$this->query("SELECT id,name,owner_id,public_id FROM `project` WHERE id = ? OR public_id = ?");
			$this->execute(array($id,$id));
			if($this->rowCount() > 0) {
				$result = $this->getResult();
				$this->id = $result['id'];
				$this->name = $result['name'];
				$this->ownerId = $result['owner_id'];
				$this->publicId = $result['public_id'];
				return true;
			} else {
				return false;
			}
		}
		function create($name,$userId) {
			$this->query("INSERT INTO `project`(name,owner_id) VALUES(?,?)");
			$this->execute(array($name,$userId));
			if($this->rowCount() > 0) {
				$id = $this->lastId();
				$this->load($id);
				return true;
			} else {
				return false;
			}
		}
		function addUser($id,$writeAccess) {
			$this->query("INSERT INTO user_project(user_id,project_id,write_access) VALUES(?,?,?)");
			$this->execute(array($id,$this->id,$writeAccess));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function update($prop,$value) {
			$this->query("UPDATE `project` SET ".$prop." = ? WHERE id = ?");
			$this->execute(array($value,$this->id));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function users() {
			$this->query("SELECT user.id, user.name, user.email, user_project.write_access FROM user INNER JOIN user_project ON user.id = user_project.user_id INNER JOIN project ON user_project.project_id = project.id WHERE project.id = ?");

			$this->execute(array($this->id));
			$users = array();
			while($result = $this->getResult()) {
				$users[] = array("id"=>$result['id'],"name"=>$result['name'],"email"=>$result['email'],"owner"=>($result['id'] == $this->ownerId ? true : false),"writeAccess"=>$result['write_access']);
			}

			return $users;
		}
		function tables() {
			$this->query("SELECT id FROM `table` WHERE project_id = ?");
			$this->execute(array($this->id));
			$tables = array();
			while($result = $this->getResult()) {
				$table = new Table;
				$table->load($result['id']);
				$tables[] = $table;
			}
			return $tables;
		}
	}
?>