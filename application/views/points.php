<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="80">pointsid</td><td width="200">adminsid</td><td width="200">fansid</td><td>rulesid</td><td width="80">points</td><td width="120">pointstime</td></tr>';
foreach ($points as $item)
{
	$message .= '<tr>';
	$message .= '<td>'.$item['pointsid'].'</td>';
	$message .= '<td>'.$item['adminsid'].'</td>';
	$message .= '<td>'.$item['fansid'].'</td>';
	$message .= '<td>'.$item['rulesid'].'</td>';
	$message .= '<td>'.$item['points'].'</td>';
	$message .= '<td>'.$item['pointstime'].'</td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
