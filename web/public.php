<?php
	include("shared/bootstrap.php");
	$project = new Project;
	$project->load($_GET['id']);
	if(!$project) {
		header('location:/login.php');
	}
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

	include("includes/begin.php");
?>
	<script>
		var project = <?=json_encode($returnObj)?>;
	</script>
	<a href="#" id="topmenu">
		<span></span>
	</a>
	<div class="topbar">
		<h3 id="projectTitle"><?=$project->name?></h3>
		<div class="buttons">
			<a href="#" id="help" class="button" title="help"></a>
		</div>

	</div>
	<canvas id="canvas" height="100px" width="100px"></canvas>
	<a href="#" id="menu">
		<span></span>
	</a>
	<div id="users">
	</div>
	<p id="text"></p>
	<p id="header"></p>
	
	<div class="sidebar">
		
		<div id="tables">
			<h3>Tables</h3>
			<ul id="tablelist">
				
			</ul>
		</div>
	</div>
	<div class="formbar">

		<a id="closeEntityForm">x</a>
		<div id="helpSection">
			<h3>Help</h3>
			<ul>
				<li>Click and drag to move around the canvas.</li>
				<li>Click a table in the right menu to move to it.</li>
			</ul>
		</div>
	</div>
	<script src="/js/public.js"></script>
<?php
	include("includes/end.php");
?>