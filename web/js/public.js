var erm_creation = function(project) {

	function parseTable(table) {
		var thisEntity = table;
		var entity = new Entity(thisEntity.id,thisEntity.x,thisEntity.y,thisEntity.name);
		var li = document.createElement('li');
		var a = document.createElement('a');
		a.href = "#";
		a.addEventListener('click',showTableHndl);
		
		a.innerHTML = thisEntity.name;
		a.setAttribute('data-id',thisEntity.id);
		tablelist.appendChild(li);
		li.appendChild(a);

		for(var j = 0, k = thisEntity.fields.length; j < k; j++) {
			entity.fields.push(new Field(thisEntity.fields[j].id,thisEntity.fields[j].name));
		}
		entitys[thisEntity.id] = entity;
	}
	function loadProject(project) {
		console.log(project);
		tablelist.innerHTML = "";
		entitys = [];
		response = project;
		for(var i = 0, l = response.tables.length; i < l; i++) {
			
			parseTable(response.tables[i]);
		}
		connectors = [];
		for(key in response.connectors) {
			var thisConnector = response.connectors[key];
			var connector = new Connector(thisConnector.id,thisConnector.table1,thisConnector.table2,thisConnector.type);
			connectors.push(connector);
		}
		sortTableList();

		
	}
	
	var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
    window.requestAnimationFrame = requestAnimationFrame;
    
    function clearSelection() {
	    if(document.selection && document.selection.empty) {
	        document.selection.empty();
	    } else if(window.getSelection) {
	        var sel = window.getSelection();
	        sel.removeAllRanges();
	    }
	}
	function setBoxStyle() {
		ctx.fillStyle = "#fff";
	}
	Number.prototype.toRads = function() {
		return this.valueOf() * (Math.PI/180);
	};

	function randColour() {
		return "rgb("+(Math.floor(Math.random()*200))+","+(Math.floor(Math.random()*200))+","+(Math.floor(Math.random()*200))+")";
	}
	var Connector = function(id,entity1,entity2,type) {
		this.id = id ? id : null;
		this.entity1 = entity1 ? entity1 : null;
		this.entity2 = entity2 ? entity2 : null;
		this.type = type ? type : null;
		this.position = "";
		this.colour = null;

		this.draw = function() {
			ctx.save();
			if(!this.colour) {
				this.colour = randColour();
			}
			ctx.strokeStyle = this.colour;
			this.position = "";
			var entity1 = entitys[this.entity1];
			var entity2 = entitys[this.entity2];
			if((entity1.y+entity1.headH+entity1.bodyH) < entity2.y) {
				//entity 1 is above entity 2

				this.position += "above";
			}
			if(entity2.y+entity2.headH+entity2.bodyH < entity1.y) {
				this.position += "below";
			}
			if(entity2.x > (entity1.x+entity1.w)) {
				//entity 1 is to the left of entity 2
				this.position += "left";
			}
			if(entity1.x > entity2.x+entity2.w) {
				//entity 1 is to the right of entity 2
				this.position += "right";
			}
			ctx.beginPath();
			
			var startX = 0;
			var startY = 0;
			var endX = 0;
			var endY = 0;
			if(this.position == "above") {
				startX = entity1.x+(entity1.w/2);
				startY = entity1.y+entity1.headH+entity1.bodyH;
				endX = entity2.x+(entity2.w/2);
				endY = entity2.y;						
			} else if(this.position == "below") {
				startX = entity1.x+(entity1.w/2);
				startY = entity1.y;
				endX = entity2.x+(entity2.w/2);
				endY = entity2.y+entity2.headH+entity2.bodyH						
			} else if(this.position == "aboveright") {
				if(Math.abs((entity2.x+entity2.w - entity1.x)) < 100 && Math.abs(entity2.y - (entity1.y+entity1.headH+entity1.bodyH)) > 30) {
					startX = entity1.x+(entity1.w/2);
					startY = entity1.y+entity1.headH+entity1.bodyH;
					endX = entity2.x+(entity2.w/2)
					endY = entity2.y
				} else {
					startX = entity1.x
					startY = (entity1.y+((entity1.headH+entity1.bodyH)/2));
					endX = entity2.x+entity2.w
					endY = (entity2.y+((entity2.headH+entity2.bodyH)/2))
				}
			} else if(this.position == "right") {
				startX = entity1.x
				startY = (entity1.y+((entity1.headH+entity1.bodyH)/2))
				endX = entity2.x+entity2.w
				endY = (entity2.y+((entity2.headH+entity2.bodyH)/2));
			} else if(this.position == "belowright") {
				if(Math.abs((entity2.x+entity2.w - entity1.x)) < 100 && Math.abs(entity1.y - (entity2.y+entity2.headH+entity2.bodyH)) > 30) {
					startX = entity1.x+(entity1.w/2)
					startY = entity1.y
					endX = entity2.x+(entity2.w/2)
					endY = entity2.y+entity2.headH+entity2.bodyH;
				} else {
					startX = entity1.x;
					startY = (entity1.y+((entity1.headH+entity1.bodyH)/2));
					endX = entity2.x+entity2.w
					endY = (entity2.y+((entity2.headH+entity2.bodyH)/2))
				}
			} else if(this.position == "belowleft") {
				if(Math.abs((entity1.x+entity1.w - entity2.x)) < 100 && Math.abs(entity1.y - (entity2.y+entity2.headH+entity2.bodyH)) > 30) {
					startX = entity1.x+(entity1.w/2)
					startY = entity1.y
					endX = entity2.x+(entity2.w/2)
					endY = entity2.y+entity2.headH+entity2.bodyH
				} else {
					startX = entity1.x+entity1.w
					startY = (entity1.y+((entity1.headH+entity1.bodyH)/2))
					endX = entity2.x
					endY = (entity2.y+((entity2.headH+entity2.bodyH)/2))
				}
			} else if(this.position == "left") {
				startX = entity1.x+entity1.w
				startY = (entity1.y+((entity1.headH+entity1.bodyH)/2))
				endX = entity2.x
				endY = (entity2.y+((entity2.headH+entity2.bodyH)/2));
			} else if(this.position == "aboveleft") {
				if(Math.abs((entity1.x+entity1.w - entity2.x)) < 100 && Math.abs(entity1.y - (entity2.y+entity2.headH+entity2.bodyH)) > 30) {
					startX = entity1.x+(entity1.w/2)
					startY = entity1.y+entity1.headH+entity1.bodyH
					endX = entity2.x+(entity2.w/2)
					endY = entity2.y
				} else {
					startX = entity1.x+entity1.w
					startY = (entity1.y+((entity1.headH+entity1.bodyH)/2))
					endX = entity2.x
					endY = (entity2.y+((entity2.headH+entity2.bodyH)/2))
				}
			}

			ctx.moveTo(startX,startY);
			ctx.lineTo(endX,endY);
			ctx.stroke();
			ctx.closePath();
			ctx.beginPath();
			if(this.type !== "one to one") {
				var xDif = endX-startX;
				var yDif = endY-startY;
				var angle = Math.atan2(yDif,xDif) * 180/Math.PI;
				var test = Math.sqrt((xDif*xDif)+(yDif*yDif));
				var xM = (xDif/test)*20;
				var yM = (yDif/test)*20;
				var aX = (startX+((xDif)/2));
				var aY = (startY+((yDif)/2));
				if(this.type == "many to one") {
					angle = angle - 180;
					aX = aX-xM;
					aY = aY-yM
				} else {
					aX = aX+xM;
					aY = aY+yM;
				}
				ctx.arc(aX,aY,20,angle.toRads()-(90).toRads(),(angle.toRads()-(90).toRads())-(180).toRads(),true);
				ctx.stroke();
			}
			ctx.restore();
		};
	};

	var Entity = function(id,x,y,name,fields) {
		this.id = id ? id : null;
		this.name = name ? name : "";
		this.fields = fields ? fields : [];
		this.x = x ? parseInt(x) : 0;
		this.y = y ? parseInt(y) : 0;
		this.w = 0;
		this.headH = 0;
		this.bodyH = 0;
		this.textHeight = 0;
		this.headerTextW = 0;
		this.textSize = 12;
		this.headSize = 16;
		this.dimensionsSet = false;
		this.setDimensions = function() {
			text.style.fontSize = this.textSize+"pt";
			text.style.display = "block";
			header.style.fontSize = this.headSize+"pt";
			header.style.display = "block";
			header.innerHTML = this.name;
			var maxWidth = header.offsetWidth;
			this.headerTextW = header.offsetWidth;
			this.bodyH = 10;
			this.textHeight = 0;
			for(var i = 0, l = this.fields.length; i < l; i++) {
				text.innerHTML = this.fields[i].name;
				if(text.offsetWidth > maxWidth) {
					maxWidth = text.offsetWidth;
				}
				this.textHeight = text.offsetHeight;
				
				this.bodyH += text.offsetHeight+5;
			}
			
			this.headH = header.offsetHeight + 10;
			this.w = maxWidth+10;
		};
		this.draw = function() {
			ctx.save();
			ctx.textBaseline = "top";
			if(!this.dimensionsSet) {
				this.setDimensions();
				this.dimensionsSet = true;
			}
			
			//ctx.font = "16pt Arial";
			
			//header
			ctx.font = this.headSize+"pt Arial";
			ctx.strokeRect(this.x,this.y,this.w,this.headH);
			
			ctx.save();
			setBoxStyle();
			ctx.fillRect(this.x,this.y,this.w,this.headH);
			ctx.restore();
			ctx.fillText(this.name,this.x+((this.w/2)-(this.headerTextW/2)),this.y+5);
			//end header
			
			//body
			ctx.font = this.textSize+"pt Arial";
			ctx.strokeRect(this.x,(this.y+this.headH),this.w,this.bodyH);
			ctx.save();
			setBoxStyle();
			ctx.fillRect(this.x,(this.y+this.headH),this.w,this.bodyH);
			ctx.restore();
			var textY = this.y+this.headH+5;
			for(var i = 0, l = this.fields.length; i < l; i++) {
				if(this.fields[i].id !== null) {
					ctx.fillText(this.fields[i].name,this.x+((this.w/2)-(ctx.measureText(this.fields[i].name).width/2)),textY);
				}
				textY += this.textHeight+5;
			}
			//end body
			text.style.display = "none";
			header.style.display = "none";
			ctx.restore();
		};
	};

	var Field = function(id,name) {
		this.id = id ? id : null;
		this.name = name ? name : null;
	};
	var kTime = null;
	var panTime = null;
	function kHndl() {
		if(kcodeEntered) {
			if(kTime == null) {
				var date = new Date();
				kTime = date.getTime();
			}
			var date = new Date();
			var now = date.getTime();
			var diff = (now - kTime)/1000;
			if(diff <= 3) {
				if(diff == 0 || diff == 0.5 || diff == 1 || diff == 1.5 || diff == 2 || diff == 2.5 || diff == 3) {
					if(document.body.style.webkitTransform == "" || document.body.style.webkitTransform == "rotate(0deg)") {
						document.body.style.webkitTransform = "rotate(360deg)";	
					} else {
						document.body.style.webkitTransform = "rotate(0deg)";	
					}
										
				}
				ctx.scale(Math.random(),Math.random());
				/*ctx.fillStyle = randColour();
				ctx.fillRect(Math.floor(Math.random()*canvas.width)-offsetCoords[0],Math.floor(Math.random()*canvas.height)-offsetCoords[1],Math.floor(Math.random()*500)+50,Math.floor(Math.random()*500)+50);*/
			} else {
				kTime = null;
				kcodeEntered = false;	
			}
		}
	}
	

	function panToTable() {
		if(panning) {
			if(panTime == null) {
				var date = new Date();
				panTime = date.getTime();
			}
			var date = new Date();
			var now = date.getTime();
			var diff = (now - panTime)/1000;
			
			var percent = (diff/3)*100;
			var currentX = offsetCoords[0];
			var targetX = panTarget[0];
			var xDistance = currentX-targetX;
			var currentXPos = (percent/100)*xDistance;
			var currentY = offsetCoords[1];
			var targetY = panTarget[1];
			var yDistance = currentY - targetY;
			var currentYPos = (percent/100)*yDistance;
			offsetCoords[0] = panStartCoords[0]-currentXPos;
			offsetCoords[1] = panStartCoords[1]-currentYPos;
			if(xDistance == 0 && yDistance == 0) {
				offsetCoords = [panTarget[0],panTarget[1]];
				panLastCoords = offsetCoords;
				panTime = null;
				panning = false;
			}
			
		}
	}
	function loop() {
		ctx.clearRect(0,0,canvas.width,canvas.height);
		ctx.save();
		
		kHndl();
		panToTable();
		ctx.translate(offsetCoords[0],offsetCoords[1]);
		for(var i = 0, l = connectors.length; i < l; i++) {
			connectors[i].draw();
		}
		for(key in entitys) {

			entitys[key].draw();
		}
		
		ctx.restore();
		if(window.requestAnimationFrame) {
			window.requestAnimationFrame(loop);
		}
	}
	if(window.requestAnimationFrame) {
		window.requestAnimationFrame(loop);	
	} else {
		setInterval(loop,10);
	}
	
	function showForm(form) {
		menuBtn.style.right = "0px";
		menuBtn.getElementsByTagName('span')[0].className = "";
		help.style.display = "none";
		form.style.display = "block";
	}
	
	function sortTableList() {
		var children = [];
		for(var i = 0, l = tablelist.children.length; i < l; i++) {
			children.push(tablelist.children[i]);
		}
		children.sort(function(a,b) {
			var av = a.getElementsByTagName('a')[0].innerHTML;
			var bv = b.getElementsByTagName('a')[0].innerHTML;
			return (av < bv ? -1 : (av > bv ? 1 : 0));
		});
		tablelist.innerHTML = "";
		for(var i = 0, l = children.length; i < l; i++) {
			tablelist.appendChild(children[i]);
		}
	}
	function mouseDownHndl(e) {
		mouseDown = true;
		var x = e.layerX ? e.layerX : e.offsetX;
		var y = e.layerY ? e.layerY : e.offsetY;
		if(spaceDown) {

			panStartCoords = [x  - panLastCoords[0],y - panLastCoords[1]];
		}
		mousePosition = [x-offsetCoords[0],y-offsetCoords[1]];
		e.preventDefault();
		return false;
	}
	function mouseUpHndl(e) {
		mouseDown = false;
		var x = e.layerX ? e.layerX : e.offsetX;
		var y = e.layerY ? e.layerY : e.offsetY;
		if(spaceDown) {
			panLastCoords = [x - panStartCoords[0],y - panStartCoords[1]];
		}
	}
	function mouseMoveHndl(e) {
		var x = e.layerX ? e.layerX : e.offsetX;
		var y = e.layerY ? e.layerY : e.offsetY;
		if(mouseDown && spaceDown) {

			var xDif = x - panStartCoords[0];
			var yDif = y - panStartCoords[1];
			offsetCoords[0] = xDif;
			offsetCoords[1] = yDif;
		}
		e.preventDefault();
	}
	function clickHndl(e) {
		var x = e.layerX ? e.layerX : e.offsetX;
		var y = e.layerY ? e.layerY : e.offsetY;
	}
	function keyDownHndl(e) {
		var keyCode = e.keyCode;

		if(keyCode == "32") {
			canvas.className = " pan";
			//canvas.style.cursor = "url('/images/hand-icon.png'),auto";
			spaceDown = true;
			if(panning) {
				panning = false;
				panLastCoords = offsetCoords;
			}
		}
	}
	function kcodeHndl() {
		kcodeEntered = true;
		kcheck = [];
	}
	function keyUpHndl(e) {
		var keyCode = e.keyCode;
		if(keyCode == 32) {
			canvas.className = canvas.className.replace(" pan","");
			//canvas.style.cursor = "auto";
			panLastCoords = [offsetCoords[0],offsetCoords[1]];
			spaceDown = true;
		} else {
			var i = kcheck.push(keyCode)-1;
			(kcode[i] != keyCode ? kcheck = [] : (kcheck.length == kcode.length ? kcodeHndl() : null));
		}
	}
	function menuHndl(e) {
		menuBtn.style.right = (canvas.style.right == "220px" ? "0px" : "220px");
		menuBtn.getElementsByTagName('span')[0].className = (menuBtn.getElementsByTagName('span')[0].className == "rotate" ? "" : "rotate");
		canvas.style.right = (canvas.style.right == "220px" ? "0px" : "220px");
		e.preventDefault();
	}
	function topMenuHndl(e) {
		canvas.style.top = (canvas.style.top == "150px" ? "0px" : "150px");
		menuBtn.style.top = (parseInt(canvas.style.top)+10)+"px";
		topMenuBtn.style.top = canvas.style.top;
		topMenuBtn.getElementsByTagName('span')[0].className = (parseInt(canvas.style.top) > 0  ? "toprotate" : "");
		sidebar.style.top = canvas.style.top;
		formbar.style.top = canvas.style.top;
		e.preventDefault();
	}
	function showTableHndl(e) {
		var target = e.target;
		var id = target.getAttribute('data-id');
		
		var offsetX = 0-entitys[id].x+((canvas.width/2)-(entitys[id].w/2));
		var offsetY = 0-entitys[id].y+((canvas.height/2)-((entitys[id].headH+entitys[id].bodyH)/2));
		panTarget = [offsetX,offsetY];
		panStartCoords = offsetCoords;
		panning = true;
		panTime = null;
		e.preventDefault();
	}
	function downloadHndl(e) {
		var fL = 0;
		var fR = 0;
		var fT = 0;
		var fB = 0;
		for(key in entitys) {
			if(entitys[key].x < fL) {
				fL = entitys[key].x
			}
			if((entitys[key].x+entitys[key].w) > fR) {
				fR = entitys[key].x+entitys[key].w;
			}
			if(entitys[key].y < fT) {
				fT = entitys[key].y;
			}
			if((entitys[key].y+entitys[key].headH+entitys[key].bodyH) > fB) {
				fB = (entitys[key].y+entitys[key].headH+entitys[key].bodyH);
			}
		}
		offsetCoords[0] = -(fL-10);
		offsetCoords[1] = -(fT-10);
		canvas.width = (fR - fL)+20;
		canvas.height = (fB - fT)+20;
		setTimeout(function() {
			var data = canvas.toDataURL();
			window.location.href = data;
		},200);
		
		e.preventDefault();
	}
	function helpHndl(e) {
		canvas.style.right = "-220px";
		showForm(helpSection);
		e.preventDefault();
	}
	var kcode = [38,38,40,40,37,39,37,39,66,65];
	var kcheck = [];
	var kcodeEntered = false;
	
	var text = document.getElementById('text');
	var header = document.getElementById('header');
	var canvas = document.getElementById('canvas');
	var tableList = document.getElementById('tablelist');
	var mouseDown = false;
	var draggedentity;
	var dragStart;
	var creatingConnection = false;
	var deleting = false;
	var panTarget;
	var panning = false;
	var connectionentity;
	var spaceDown = true;
	var offsetCoords = [0,0];
	var panStartCoords = [];
	var currentUser = currentUser;
	var userName = userName;
	var panLastCoords = [0,0];
	var mousePosition = [0,0];
	var downloadBtn = document.getElementById('download');
	var helpBtn = document.getElementById('help');
	var ctx = canvas.getContext('2d');
	var entitys = {};
	var connectors = [];
	var menuBtn = document.getElementById('menu');
	var topMenuBtn = document.getElementById('topmenu');	
	var help = document.getElementById('helpSection');
	var addFormClose = document.getElementById('closeEntityForm');
	var sidebar = document.getElementsByClassName('sidebar')[0];
	var formbar = document.getElementsByClassName('formbar')[0];
	loadProject(project);
	



	canvas.setAttribute('height',window.innerHeight+"px");
	canvas.setAttribute('width',window.innerWidth+"px");
	
	
	menuBtn.addEventListener('click',menuHndl);
	topMenuBtn.addEventListener('click',topMenuHndl);
	
	addFormClose.addEventListener('click',function(e) {
		canvas.style.right = "0px";
		

		e.preventDefault();
	});
	
	

	helpBtn.addEventListener('click',helpHndl);
	canvas.addEventListener('mousedown',mouseDownHndl);
	canvas.addEventListener('mousemove',mouseMoveHndl);
	canvas.addEventListener('mouseup',mouseUpHndl);
	canvas.addEventListener('click',clickHndl);
	canvas.style.right = "220px";
	canvas.style.top = (canvas.style.top == "150px" ? "0px" : "150px");
	menuBtn.style.top = (parseInt(canvas.style.top)+10)+"px";
	topMenuBtn.style.top = canvas.style.top;
	topMenuBtn.getElementsByTagName('span')[0].className = (parseInt(canvas.style.top) > 0  ? "toprotate" : "");
	sidebar.style.top = canvas.style.top;
	formbar.style.top = canvas.style.top;
	menuBtn.style.right = "220px";
	menuBtn.getElementsByTagName('span')[0].className = "rotate";
	document.addEventListener('keydown',keyDownHndl);
	document.addEventListener('keyup',keyUpHndl);
	function centerElement(element) {
		element.style.left = (window.innerWidth/2)-(element.offsetWidth/2)+"px";
	}
	centerElement(topMenuBtn);
	window.onresize = function() {
		centerElement(topMenuBtn);
		canvas.setAttribute('height',window.innerHeight+"px");
		canvas.setAttribute('width',window.innerWidth+"px");
		centerElement(users);
	};
};
erm_creation(project);