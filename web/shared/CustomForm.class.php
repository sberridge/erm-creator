<?php
	class CustomForm {
		public $id;
		public $action;
		public $method;
		public $fieldsets = array();
		public $attributes = array();

		function __construct($action,$method,$attributes=array()) {
			$this->action = $action;
			$this->method = $method;
			$this->attributes = $attributes;
		}
		function addFieldset($id,$attributes=array()) {
			$this->fieldsets[$id] = new Fieldset($id,$attributes);
		}
		function render($postData = NULL,$validation=NULL) {
			$attributeString = "";
			foreach($this->attributes as $attribute=>$value) {
				$attributeString .= $attribute."='".htmlentities($value,ENT_QUOTES)."' ";
			}
			$form = "<form ".(isset($this->id) ? "id='".$this->id."'" : "")." method='".$this->method."' action='".$this->action."' ".$attributeString.">";
			foreach($this->fieldsets as $fieldset) {
				$form.=$fieldset->render((isset($postData) ? $postData : NULL),(isset($validation) ? $validation : NULL),(isset($this->id) ? $this->id : NULL));
			}
			$form .= "</form>";
			echo $form;
		}
		function validate() {
			$data = $GLOBALS["_".strtoupper($this->method)];
			$errorMsgs = array();
			foreach($this->fieldsets as $fieldset) {
				$validate = $fieldset->validate($data);
				if($validate !== false) {

					
					$errorMsgs = array_merge($errorMsgs,$validate);
				}				
			}
			if(count($errorMsgs) > 0) {
				return array("errors"=>$errorMsgs);
			} else {
				return true;
			}
		}
	}
?>