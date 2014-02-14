<?
class error
{
	private $errors = "";
	
	function addError($error)
	{
		global $errors;
		$errors .= $error;
	}
	function dispErrors()
	{
		global $errors;
		return $errors;	
	}
}

	$err = new error;
	$err->addError("testerr");
	echo $err->dispErrors();
?>