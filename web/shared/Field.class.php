<?php
	class Field {
		public $id;
		public $type;
		public $label;
		
		public $name;
		public $options;
		public $attributes;
		public $validateRules;

		function __construct($id,$type,$name,$attributes=array(),$validateRules=array()) {
			$this->id = $id;
			$this->type = $type;
			$this->name = $name;
			$this->attributes = $attributes;
			$this->validateRules = $validateRules;
		}
		function set($prop,$val) {
			if(isset($this->$prop)) {
				$this->$prop = $val;
			}
		}
		function render($formId=NULL) {
			if(isset($this->label)) {
				$output = "<label for='".(isset($formId) ? $formId."-" : "").$this->id."'>".$this->label."</label>";
			} else {
				$output = "";
			}
			$attributeString = "";
			foreach($this->attributes as $attribute=>$value) {
				$attributeString .= $attribute."='".htmlentities($value,ENT_QUOTES)."' ";
			}
			if($this->type == "textarea") {
				$output .= "<textarea name='".$this->name."' id='".(isset($formId) ? $formId."-" : "").$this->id."' ".$attributeString.">".(isset($this->attributes['value']) ? $this->attributes['value'] : "")."</textarea>";
			} elseif($this->type == "select") {
				$output .= "<select  name='".$this->name."' id='".(isset($formId) ? $formId."-" : "").$this->id."' ".$attributeString.">";
				foreach($this->options as $value=>$option) {
					if(preg_match("/^\{selected\}(.*)$/",$option,$matches)) {
						$option = $matches[1];
						$output .= "<option selected value='".$value."' >".$option."</option>";
					} else {
						$output .= "<option value='".$value."' >".$option."</option>";
					}
				}
				$output .= "</select>";
			} else {
				$output .= "<input type='".$this->type."' name='".$this->name."' id='".(isset($formId) ? $formId."-" : "").$this->id."' ".$attributeString."/>";
			}
			return $output;
		}
		function validate($value) {
			if($value !== "") {
				if($this->type == "email") {
					if(filter_var($value,FILTER_VALIDATE_EMAIL)) {
						$emailParts = explode('@',$value);
						getmxrr($emailParts[1],$mxr);
						if(count($mxr) > 0) {
							return true;
						} else {
							return false;
						}
					} else {
						return false;
					}
				} elseif($this->type == "select") {
					$matched = false;
					foreach($this->options as $thisvalue=>$option) {
						if($value == $thisvalue) {
							$matched = true;
							break;
						}
					}
					return $matched;
				} elseif($this->type == "file") {
					if(isset($this->validateRules['extensions'])) {
						if(is_array($value)) {
							$mismatch = false;
							foreach($value as $val) {
								$fileArr = explode(".",$val);
								$ext = strtolower(end($fileArr));
								if(!in_array($ext,$this->validateRules['extensions'])) {
									$mismatch = true;
									break;
								}
							}
							if($mismatch) {
								return false;
							} else {
								return true;
							}
						} else {
							$fileArr = explode(".",$value);
							$ext = strtolower(end($fileArr));
							if(!in_array($ext,$this->validateRules['extensions'])) {
								return false;
							} else {
								return true;
							}
						}
					} else {
						return true;
					}
				} elseif($this->type == "date" || $this->type == "time" || $this->type == "datetime") {
					if(!strtotime($value)) {
						return false;
					} else {
						return true;
					}
				} elseif($this->type == "number") {
					
					return (bool)(preg_match("/^[0-9.]+$/",$value));
				} elseif($this->type == "url") {
					if(preg_match("/^https?:\/\/(?:www)?",$value)) {
						
					}
				}
			} else {
				return true;
			}

		}
	}
?>