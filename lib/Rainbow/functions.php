<?php

function json_encode_pretty($input) {
	$tab = "  ";
	$return_value = "";
	$indent_level = 0;
	$in_string = false;
	$json = json_encode($input);
	$len = strlen($json);

	for ($c = 0; $c < $len; $c++)
	{
		$char = $json[$c];
		switch ($char)
		{
			case '{':
			case '[':
				if (!$in_string) {
					$return_value .= $char . "\n" . str_repeat($tab, $indent_level + 1);
					$indent_level++;
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case '}':
			case ']':
				if (!$in_string) {
					$indent_level--;
					$return_value .= "\n" . str_repeat($tab, $indent_level) . $char;
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case ',':
				if (!$in_string) {
					$return_value .= ",\n" . str_repeat($tab, $indent_level);
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case ':':
				if (!$in_string) {
					$return_value .= ": ";
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case '"':
				$in_string = !$in_string;
			default:
				$return_value .= $char;
				break;
		}
	}
	return $return_value;
}

?>