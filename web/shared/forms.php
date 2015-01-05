<?php
	include('CustomForm.class.php');
	include('Fieldset.class.php');
	include('Field.class.php');

	function loginForm() {
		$form = new CustomForm("/formhandlers/login.php","POST");
		$form->addFieldset("email");
		$form->fieldsets["email"]->addField("email","email","email",array("required"=>""));
		$form->fieldsets["email"]->fields['email']->label = "Email";
		
		$form->addFieldset("password");
		$form->fieldsets["password"]->addField("password","password","password",array("required"=>""));
		$form->fieldsets['password']->fields['password']->label = "Password";
		
		$form->addFieldset("submit");
		$form->fieldsets["submit"]->addField("submit","submit","submit",array("value"=>"Login"));
		return $form;
	}
	function registerForm() {
		$form = new CustomForm("/formhandlers/register.php","POST");
		$form->addFieldset("name");
		$form->fieldsets['name']->addField("name","text","name",array("required"=>""));
		$form->fieldsets['name']->fields['name']->label = "Name";
		$form->addFieldset("email");
		$form->fieldsets["email"]->addField("email","email","email",array("required"=>""));
		$form->fieldsets["email"]->fields['email']->label = "Email";
		
		$form->addFieldset("password");
		$form->fieldsets["password"]->addField("password","password","password",array("required"=>""));
		$form->fieldsets['password']->fields['password']->label = "Password";
		
		$form->addFieldset("submit");
		$form->fieldsets["submit"]->addField("submit","submit","submit",array("value"=>"Register"));
		return $form;
	}
?>