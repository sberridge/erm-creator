<?php
	class User extends Base {
		public $id;
		public $name;
		public $email;
		public $table = "user";
		function __construct() {
			parent::__construct();
		}
		function load($id) {
			$this->query("SELECT * FROM user WHERE id = ?");
			$this->execute(array($id));
			if($this->rowCount() > 0) {
				$result = $this->getResult();
				$this->id = $result['id'];
				$this->email = $result['email'];
				$this->name = $result['name'];
				return true;
			} else {
				return false;
			}
		}
		function create($email,$password,$name) {
			$password = hash_pwd($password);
			$this->query("INSERT INTO user(name,email,password) VALUES(?,?,?)");
			$this->execute(array($name,$email,$password));
			if($this->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}
		function projects() {
			$this->query("SELECT project.id,project.name,user_project.write_access FROM project INNER JOIN user_project ON project.id = user_project.project_id INNER JOIN user ON user_project.user_id = user.id WHERE user.id = ?");
			$this->execute(array($this->id));
			$projects = array();
			while($project = $this->getResult()) {
				$projects[$project['id']] = $project;
			}
			return $projects;
		}
		function authenticate($email,$password) {
			$user = $this->get("WHERE email = ? LIMIT 1",array($email));
			if(count($user) > 0) {
				$hash = $user[0]['password'];
				if(auth($password,$hash)) {
					$id = $user[0]['id'];
					$this->load($id);
					return true;
				} else {
					return false;
				}
			}
		}
	}
?>