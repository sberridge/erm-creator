(function() {
	function Modal(elem) {
		var elem = elem;
		elem.style.display = 'none';
		var head = elem.children[0];
		var dragging = false;
		var pos = [0,0];
		var close = elem.getElementsByClassName('close');
		close[0].addEventListener('click',function(e) {
			e.preventDefault();
			elem.modal.close();
		});
		head.addEventListener('mousedown',function(e) {
			dragging = true;
			pos = [e.pageX,e.pageY];
		});
		document.body.addEventListener('mousemove',function(e) {
			if(dragging) {
				var xDif = e.pageX - pos[0];
				var yDif = e.pageY - pos[1];
				elem.style.top = (parseInt(elem.style.top) + yDif)+'px';
				elem.style.left = (parseInt(elem.style.left) + xDif)+'px';
				pos = [e.pageX,e.pageY];
			}
			
		});
		document.body.addEventListener('mouseup',function(e) {
			if(dragging) {
				dragging = false;
			}
		});
		this.open = function() {
			if(Modal.currentModal) {
				Modal.currentModal.modal.close();
			}
			Modal.currentModal = elem;
			elem.style.display = 'block';
			elem.style.left = ((window.innerWidth/2) - (elem.offsetWidth/2))+'px';
			elem.style.top = (10 + document.body.scrollTop)+'px';
		}
		this.close = function() {
			Modal.currentModal = null;
			elem.style.display = 'none';
		}
	}
	document.addEventListener('DOMContentLoaded',function() {

		var modals = document.getElementsByClassName('modal');
		for(var i = 0, l = modals.length; i < l; i++) {
			modals[i].modal = new Modal(modals[i]);
			//modals[i].modal.open();
		}
		function modalTrigger(e) {
			e.preventDefault();
			var target = e.target;
			while(!target.getAttribute('data-modal')) {
				target = target.parentNode;
			}
			var modal = document.getElementById(target.getAttribute('data-modal'));
			modal.modal.open();
		}
		var modalTriggers = document.querySelectorAll('[data-modal]');
		for(var i = 0, l = modalTriggers.length; i < l; i++) {
			modalTriggers[i].addEventListener('click',modalTrigger);
		}
	});
	
})();