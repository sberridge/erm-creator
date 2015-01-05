<?php
	include("shared/bootstrap.php");
	if(isset($currentUser)) {
		$projects = $currentUser->projects();
		include("includes/begin.php");
?>
	<a href="#" id="topmenu">
		<span></span>
	</a>
	<div class="topbar">
		<h3 id="projectTitle">No project open</h3>
		<p id="owner"></p>
		<div class="buttons">
			<a href="#" id="share" title="share" class="button"></a>
			<a href="#" id="download" class="button" title="download image"></a>
			<a href="#" id="help" class="button" title="help"></a>
			<a href="/logout.php" id="logout" class="button" title="logout"></a>
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
		<div id="projects">
			<h3>Projects</h3>
			<a href="#" id="newProject">New Project</a>
			<ul id="projectlist">
				<?php
					foreach($projects as $project) {
				?>
				<li><a href="#" class="project" data-id="<?=$project['id']?>"><?=$project['name']?></a></li>
				<?php
					}
				?>
			</ul>
		</div>
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
		<form id="shareForm">
			<h3>Share Project</h3>
			<a class="btn" href="#" target="_blank" id="createPublicLink">Public</a>
			<ul id="shareList">
				
			</ul>
			<fieldset>
				<label for="Email">Email</label>
				<input type="email" id="shareEmail">
			</fieldset>
			<fieldset>
				<label for="writeAccess">Can edit?</label>
				<input type="checkbox" id="writeAccess">
			</fieldset>
			<input type="submit" value="Share">
		</form>
		<div id="helpSection">
			<h3>Help</h3>
			<ul>
				<li>Hold space and drag to move around the canvas.</li>
				<li>Double click the canvas to add new tables.</li>
				<li>Double click a table to edit.</li>
				<li>Drag the heading of a table to move it around the canvas.</li>
				<li>Drag from the centre of a table to another table to add a relationship.</li>
				<li>Drag from the centre of a table to a related table to delete the relationship.</li>
				<li>Click and hold the centre of a table to delete it.</li>
			</ul>
		</div>
	</div>
	<!-- <div class="modal">
		<header>
			<h3>Add Entity</h3>
			<a href="#" class="close">&times;</a>
		</header>
		<div class="content">
			<form action="">
				<label for="">Name</label>
				<input type="text">
				<label for="fields">Fields</label>
				<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Type</th>
							<th>Length</th>
							<th>Key</th>
							<th>Nullable</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<td>
							<input type="text">
						</td>
						<td>
							<select name="" id="">
								<option value="varchar">varchar</option>
								<option value="text">text</option>
								<option value="date">date</option>
								<option value="int">int</option>
								<option value="float">float</option>
								<option value="boolean">boolean</option>
							</select>
						</td>
						<td>
							<input type="number">
						</td>
						<td>
							<select name="" id="">
								<option value=""></option>
								<option value="">index</option>
								<option value="">primary</option>
								<option value="">unique</option>
								<option value="">fulltext</option>
							</select>
						</td>
						<td>
							<input type="checkbox">
						</td>
						<td>
							<a href="#">+</a>
						</td>
					</tbody>
				</table>
			</form>
		</div>
	</div> -->
	<script>
		var userName = "<?=$currentUser->name?>";
		var currentUser = <?=$currentUser->id?>;
	</script>
	<script src="/js/script.js"></script>
<?php
		include("includes/end.php");
	} else {
		header('location:/login.php');
		exit;
	}
?>