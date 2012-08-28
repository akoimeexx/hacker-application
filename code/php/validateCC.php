<?php

function validateCCN($cc_number)
{
	switch($cc_number)
	{
		case ((substr($cc_number, 0, 2) > 50) and (substr($cc_number, 0, 2) < 56) and (strlen($cc_number) == 16)):
		/* It's a Mastercard, validate thusly */
			return checkLuhn($cc_number);
		case ((substr($cc_number, 0, 1) == 4) and ((strlen($cc_number) == 13) or (strlen($cc_number) == 16))):
		/* It's a Visa, validate thusly */
			return checkLuhn($cc_number);
		case ((substr($cc_number, 0, 4) == 6011) and (strlen($cc_number) == 16)):
		/* It's a Discover, validate thusly */
			return checkLuhn($cc_number);
		case (((substr($cc_number, 0, 2) == 34) or (substr($cc_number, 0, 2) == 37)) and (strlen($cc_number) == 15)):
		/* It's an AmEx, validate thusly */
			return checkLuhn($cc_number);
		case ($cc_number == '6767676767676767'):
		/* It's a number check */
			return checkLuhn($cc_number);
	}
	return false;
}

function checkLuhn($input)
{
	$odd = true;
	$total = 0;

	foreach(array_reverse(str_split($input)) as $num)
	{
		$total += array_sum(str_split(($odd = !$odd) ? $num*2 : $num));
	}
	return (($total % 10 == 0) && ($total != 0));
}
/*
echo 'Should return 1 (Mastercard): ' . validateCCN('5412345678901232') . "<br />\n\n";

echo 'Should return 1 (Visa): ' . validateCCN('4123456789012349') . "<br />\n\n";

echo 'Should return 1 (Discover): ' . validateCCN('6011123456789019') . "<br />\n\n";

echo 'Should return 1 (AmEx): ' . validateCCN('341234567890127') . "<br />\n\n";

echo 'Should return 1 (67-check): ' . validateCCN('6767676767676767') . "<br />\n\n";

echo 'Should return Null (invalid): ' . validateCCN('1234567890123456') . "<br />\n\n";
*/
?>
