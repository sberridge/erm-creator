<?php
class Fieldset {
	public $id;
	public $fields = array();
	public $attributes = array();

	function __construct($id,$attributes=array()) {
		$this->id = $id;
		$this->attributes = $attributes;
	}
	function set($prop,$val) {

	}
	function addField($id,$type,$name,$parameters=array(),$validateRules=array()) {
		$this->fields[$id] = new Field($id,$type,$name,$parameters,$validateRules);
	}
	function render($data = NULL,$validation=NULL,$formId=NULL) {
		$attributeString = "";
		foreach($this->attributes as $attribute=>$value) {
			$attributeString .= $attribute."='".htmlentities($value,ENT_QUOTES)."' ";
		}
		$output = "<fieldset ".$attributeString.">";
		foreach($this->fields as $field) {
			$dataKey = str_replace(" ", "_", $field->name);
			if(isset($data) && isset($data[$dataKey]) && $field->type !== "file") {
				if($field->type !== "radio") {
					
					$field->attributes['value'] = $data[$dataKey];
					if($field->type == "checkbox") {
						$field->attributes['checked'] = "checked";
					}
				} elseif(isset($field->attributes['value']) && $field->attributes['value'] == $data[$dataKey]) {
					$field->attributes['checked'] = "checked";
				}
			} elseif((preg_match("/([A-Za-z0-9 ]+)\[([A-Za-z0-9]+)\]/",$field->name,$fieldArray) || preg_match("/([A-Za-z0-9 ]+)\[\]/",$field->name,$fieldArray)) && isset($data) && isset($fieldArray) && $field->type !== "file") {
				if(count($fieldArray) == 3) {
					$name = str_replace(" ", "_",$fieldArray[1]);
					$key = $fieldArray[2];
					$field->attributes['value'] = $data[$name][$key];
				}
			}
			if(isset($validation) && (isset($validation["errors"][$field->name]) || isset($name) && isset($validation['errors'][$name]))) {
				$field->attributes['class'] = (isset($field->attributes['class']) ? $field->attributes['class']." error" : "error");
			}
			$output .= $field->render((isset($formId) ? $formId : NULL));
		}
		$output .= "</fieldset>";

		return $output;
	}
	function validate($data) {
		$errorMsgs = array();
		foreach($this->fields as $field) {
			$arrayfield = NULL;
			$fieldArray = NULL;

			$dataKey = str_replace(" ", "_", $field->name);
			if(isset($field->attributes['required']) && (!isset($data[$dataKey]) || $data[$dataKey] == "") && !preg_match("/([A-Za-z0-9 ]+)\[([A-Za-z0-9]+)\]/",$field->name,$fieldArray) && !preg_match("/([A-Za-z0-9 ]+)\[\]/",$field->name,$fieldArray) && $field->type !== "file") {
				$errorMsgs[$field->name] = "You must fill out the ".$field->name." field";				
			} elseif($field->type == "file" && isset($field->attributes['required']) && (!isset($_FILES[$dataKey]) || $_FILES[$dataKey]['name'] == "")) {
				if(isset($fieldArray) && count($fieldArray) == 3) {
					$arrayfield = str_replace(" ","_",$fieldArray[1]);
					$index = $fieldArray[2];
					if(!isset($_FILES[$arrayfield]["name"][$index]) || is_array($_FILES[$arrayfield]["name"][$index]) && $_FILES[$arrayfield]["name"][$index][0] == "" || $_FILES[$arrayfield]["name"][$index] == "") {
						$errorMsgs[$arrayfield] = "You must select a file for the ".$fieldArray[1]." fields";
						
					}
				} else {
					$errorMsgs[$field->name] = "You must select a file for the ".$field->name." field";
				}

			} elseif(isset($field->attributes['required']) && isset($fieldArray) && count($fieldArray) == 3) {	
				$arrayfield = str_replace(" ","_",$fieldArray[1]);
				$index = $fieldArray[2];
				if(!isset($data[$arrayfield][$index]) || $data[$arrayfield][$index] == "") {
					$errorMsgs[$arrayfield] = "You must fill out all the ".$fieldArray[1]." fields";
				}
			}
			if(!isset($errorMsgs[$field->name]) && !isset($errorMsgs[$arrayfield])) {
				if(isset($arrayfield) && isset($index)) {
					$fieldValue = ($field->type == "file" && isset($_FILES[$arrayfield]["name"][$index]) ? $_FILES[$arrayfield]["name"][$index] : (isset($data[$dataKey]) ? $data[$arrayfield][$index] : NULL));
				} else {
					$fieldValue = ($field->type == "file" && isset($_FILES[$dataKey]["name"]) ? $_FILES[$dataKey]["name"] : (isset($data[$dataKey]) ? $data[$dataKey] : NULL));
				}
				if($field->type == "radio") {
					$matched = false;
					foreach($this->fields as $valueCheck) {
						if(isset($valueCheck->attributes['value']) && $valueCheck->attributes['value'] == $fieldValue) {
							$matched = true;
							break;
						}
					}
					if(!$matched) {
						$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must select one of the given options for the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." list";
					}
				} elseif($field->type == "select" && !isset($field->attributes['dynamic'])) {
					if(is_array($fieldValue)) {
						foreach($fieldValue as $value) {
							if(!$field->validate($value)) {
								$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must select one of the given options from the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." drop down list";
							}
						}
					} else {
						if(!$field->validate($fieldValue)) {
							$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must select one of the given options from the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." drop down list";
						}
					}
				} elseif($field->type == "file" && $fieldValue !== "") {
					if(!$field->validate($fieldValue)) {
						$errorMsgs[$field->name] = "The uploaded file for the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." field must use one of the following extensions: ".implode(',',$field->validateRules['extensions']);
					}
				} elseif($field->type == "email") {
					if(!$field->validate($fieldValue)) {
						$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must enter a real email address in the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." field";
					}
				} elseif($field->type == "date" || $field->type == "datetime") {
					if(!$field->validate($fieldValue)) {
						$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must enter a date in the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." field";
					}
				} elseif($field->type == "time") {
					if(!$field->validate($fieldValue)) {
						$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must enter a time in the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." field";
					}
				} elseif($field->type == "number") {
					if(!$field->validate($fieldValue)) {
						$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must enter a number in the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." field";;
					}
				} elseif($field->type == "url") {
					if(!$field->validate($fieldValue)) {
						$errorMsgs[(isset($arrayfield) ? $arrayfield : $field->name)] = "You must enter a URL in the ".(isset($fieldArray) && count($fieldArray) == 3 ? $fieldArray[1] : $field->name)." field";
					}
				}
			}
		}
		if(count($errorMsgs) > 0) {
			return $errorMsgs;
		} else {
			return false;
		}
	}
}
?>