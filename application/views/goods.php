<?php
$message = '<ul class="ul_float_a">';
$message .= '<li>';
$message .= form_open('fpms_admin/goods/add');
$message .= form_dropdown('goodstypesid', $goodstypesid);
$message .= form_input('goodsname', '', 'placeholder="goodsname"');
$message .= form_input('goodsdescription', '', 'placeholder="goodsdescription"');
$message .= form_input('goodspoints', '', 'placeholder="goodspoints"');
$message .= form_input('goodscash', '', 'placeholder="goodscash"');
$message .= form_input('goodsdiscount', '', 'placeholder="goodsdiscount"');
$message .= form_input('goodsamount', '', 'placeholder="goodsamount"');
$message .= form_input('goodsltime', '', 'placeholder="goodsltime"');
$message .= form_input('goodsrtime', '', 'placeholder="goodsrtime"');
$message .= form_submit('submit', '添加');
$message .= form_close();
$message .= '</li>';
foreach ($goods as $item)
{
	$message .= '<li>';
	$message .= form_open('fpms_admin/goods/edit');
	$message .= form_hidden('goodsid', $item['goodsid']);
	$message .= form_dropdown('goodstypesid', $goodstypesid, $item['goodstypesid']);
	$message .= form_input('goodsname', $item['goodsname']);
	$message .= form_input('goodsdescription', $item['goodsdescription']);
	$message .= form_input('goodspoints', $item['goodspoints']);
	$message .= form_input('goodscash', $item['goodscash']);
	$message .= form_input('goodsdiscount', $item['goodsdiscount']);
	$message .= form_input('goodsamount', $item['goodsamount']);
	$message .= form_input('goodsltime', $item['goodsltime']);
	$message .= form_input('goodsrtime', $item['goodsrtime']);
	$message .= form_submit('submit', '修改'.$item['goodsid']);
	$message .= form_close();
	$message .= '</li>';
}
$message .= '</ul>';
echo $message;
?>
