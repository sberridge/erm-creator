<?php
	include("shared/bootstrap.php");
	include("includes/begin.php");
?>
	<a href="#" id="topmenu">
		<span></span>
	</a>
	<div class="topbar">
		<h3 id="projectTitle">Project title: this is a demo</h3>
		<p id="owner">Project owner: this is a demo</p>
		<div class="buttons">
			<a href="#" id="download" class="button" title="download image"></a>
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
		<form id="entityForm">
			<h3>Add Entity</h3>
			<fieldset>
				<label for="name">Name</label>
				<input type="text" id="name">
			</fieldset>
			<fieldset class="fields">
				<label for="fields">Fields</label>
				<input type="text" class="field">
				<a class="addField">+</a>
			</fieldset>
			<input type="hidden" id="xy">
			<input type="submit" value="Add entity">
		</form>
		<form id="editEntityForm">
			<h3>Edit Entity</h3>
			<fieldset>
				<label for="name">Name</label>
				<input type="text" id="editName">
			</fieldset>
			<fieldset class="fields">
				<label for="fields">Fields</label>
				<a class="addField">+</a>
			</fieldset>
			<input type="hidden" id="entity">
			<input type="submit" value="Edit Entity">
		</form>
		<div id="helpSection">
			<h3>Help</h3>
			<ul>
				<li>Hold down space and drag to move around the canvas.</li>
				<li>Double click the canvas to add new tables.</li>
				<li>Double click a table to edit.</li>
				<li>Drag the heading of a table to move it around the canvas.</li>
				<li>Drag from the centre of a table to another table to add a relationship.</li>
				<li>Drag from the centre of a table to a related table to delete the relationship.</li>
				<li>Click and hold the centre of a table to delete it (full version only).</li>
			</ul>
		</div>
	</div>
	<script src="/js/demo.js"></script>
<?php
	include("includes/end.php");
?>