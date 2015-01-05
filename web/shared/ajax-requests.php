<?php
	include(dirname(dirname(__FILE__))."/shared/bootstrap.php");
	if(isset($_POST['action']) && isset($_POST['project']) && isset($currentUser)) {
		$project = $_POST['project'];
		$action = $_POST['action'];
		//TODO: auth
		$project = new Project;
		$userProjects = $currentUser->projects();
		
		if($project->load($_POST['project']) && array_key_exists($project->id, $userProjects)) {
			
			$canEdit = $userProjects[$project->id]['write_access'];

			$owner = ($currentUser->id == $project->ownerId);
			if($action == "newTable" && isset($_POST['table']) && $canEdit) {
				$table = json_decode($_POST['table']);
				$name = $table->name;
				$x = $table->x;
				$y = $table->y;
				$tableObj = new Table;
				if($tableObj->create($project->id,$name,$x,$y)) {
					$table->id = $tableObj->id;
					foreach($table->fields as $key=>$field) {
						$name = $field->name;
						$id = $table->id;
						$fieldObj = new Column;
						if($fieldObj->create($name,$id)) {
							$table->fields[$key]->id = $fieldObj->id;
						}
					}
					echo json_encode(array("success"=>true,"table"=>$table));
				} else {
					echo json_encode(array("success"=>false));
				}
				
			} elseif($action == "newConnection" && isset($_POST['connector']) && $canEdit) {
				$connector = json_decode($_POST['connector']);
				$table1 = $connector->entity1;
				$table2 = $connector->entity2;
				$type = $connector->type;
				$connectorObj = new Connector;
				if($connectorObj->create($type,$table1,$table2)) {
					$connector->id = $connectorObj->id;
					echo json_encode(array("success"=>true,"connector"=>$connector));
				} else {
					echo json_encode(array("success"=>false));
				}
			} elseif($action == "loadProject") {
				
				$tables = $project->tables();
				$returnObj = array();
				$returnObj['id'] = $project->id;
				$returnObj["tables"] = array();
				$returnObj["connectors"] = array();
				foreach($tables as $table) {
					$thisTable = array("id"=>$table->id,"x"=>$table->x,"y"=>$table->y,"name"=>$table->name);
					$thisTable["fields"] = array();
					$fields = $table->fields();
					foreach($fields as $field) {
						$thisTable["fields"][] = array("id"=>$field->id,"name"=>$field->name);
					}
					$returnObj["tables"][] = $thisTable;
					$connectors = $table->connectors();
					foreach($connectors as $connector) {
						$thisConnector = array("id"=>$connector->id,"table1"=>$connector->tables[1],"table2"=>$connector->tables[2],"type"=>$connector->type);
						$returnObj["connectors"][$connector->id] = $thisConnector;
					}
				}
				$users = $project->users();
				$returnObj['users'] = $users;
				$returnObj['name'] = $project->name;
				echo json_encode(array("success"=>true,"project"=>$returnObj));
			} elseif($action == "deleteConnection" && isset($_POST['connector']) && $canEdit) {
				$connector = new Connector;
				if($connector->load($_POST['connector'])) {
					$table = $connector->tables[1];
					$tableObj = new Table;
					$tableObj->load($table);
					$connectorProject = $tableObj->project;
					if($connectorProject == $project->id) {
						if($connector->delete()) {
							echo json_encode(array("success"=>true));
						} else {
							echo json_encode(array("success"=>false));
						}
					} else {
						echo json_encode(array("success"=>false));
					}


				} else {
					echo json_encode(array("success"=>false));
				}
			} elseif($action == "updateTable" && isset($_POST['table']) && $canEdit) {
				$table = json_decode($_POST['table']);
				$tableObj = new Table;
				if($tableObj->load($table->id) && $tableObj->project == $project->id) {
					if($tableObj->name !== "") {
						$tableObj->update("name",$table->name);
						$tableObj->update("x",$table->x);
						$tableObj->update("y",$table->y);
						$tableFields = $tableObj->fields();
						$fieldObj = new Column;
						foreach($table->fields as $key=>$field) {
							if(isset($tableFields[$field->id])) {
								$fieldObj->load($field->id);
								if($field->name !== "") {
									$fieldObj->update("name",$field->name);
								} elseif($fieldObj->delete()) {
									$table->fields[$key]->id = null;
								}
							} elseif($field->id == NULL && $field->name !== "") {
								if($fieldObj->create($field->name,$table->id)) {
									$table->fields[$key]->id = $fieldObj->id;
								}
							}
						}
						echo json_encode(array("success"=>true,"table"=>$table));
					} else {
						echo json_encode(array("success"=>false));
					}
				}
			} elseif($action == "deleteTable" && isset($_POST['table']) && $canEdit) {
				$table = json_decode($_POST['table']);
				$tableObj = new Table;
				if($tableObj->load($table->id) && $tableObj->project == $project->id) {
					$connectors = $tableObj->connectors();
					$fields = $tableObj->fields();
					if($tableObj->delete()) {
						$deletedConnections = array();
						$deletedFields = array();
						foreach($connectors as $connector) {
							$connector->delete();
							$deletedConnections[] = $connector->id;
						}
						foreach($fields as $field) {
							$field->delete();
							$deletedFields[] = $field->id;
						}
						echo json_encode(array("success"=>true,"id"=>$tableObj->id,"connectors"=>$deletedConnections,"fields"=>$deletedFields));
					}
				}
			} elseif($action == "shareProject" && isset($_POST['email']) && isset($_POST['writeAccess']) && $owner) {
				$user = new User;
				$check = $user->get("WHERE email = ?",array($_POST['email']));
				$users = $project->users();
				foreach($users as $user) {
					if($user['email'] == strtolower($_POST['email'])) {
						echo json_encode(array("success"=>false));
						return false;
					}
				}
				if(count($check) == 1) {
					$writeAccess = ($_POST['writeAccess'] == "true" ? 1 : 0);
					$project->addUser($check[0]['id'],$writeAccess);
					echo json_encode(array("success"=>true,"user"=>array("id"=>$check[0]['id'],"name"=>$check[0]['name'],"email"=>$check[0]['email'])));
				} else {
					echo json_encode(array("success"=>false));
				}
			} elseif($action == "renameProject" && isset($_POST['name']) && $owner) {
				if($project->update("name",$_POST['name'])) {
					echo json_encode(array("success"=>true,"name"=>$_POST['name']));
				} else {
					echo json_encode(array("success"=>false));
				}
			} elseif($action == "sharePublic" && $owner) {
				if($project->publicId !== null) {
					$link = "/public/".$project->publicId;
				} else {
					$publicId = $project->id.time();
					$project->update("public_id",$publicId);
					$link = "/public/".$publicId;
				}
				echo json_encode(array("success"=>true,"link"=>$link));
			}
		} else {
			echo json_encode(array("success"=>false));
		}
	} elseif(isset($_POST['action']) && $_POST['action'] == "newProject" && isset($currentUser) && isset($_POST['name'])) {
		$project = new Project;
		if($_POST['name'] !== "") {
			$project->create($_POST['name'],$currentUser->id);
			$project->addUser($currentUser->id,1);
			echo json_encode(array("success"=>true,"project"=>array("id"=>$project->id,"name"=>$_POST['name'])));
		} else {
			echo json_encode(array("success"=>false));
		}
		
	}
?>