<?php
echo anchor($menusurl, '更新菜单');
echo '<hr><br>[原菜单]';
echo '<pre>';
if ($menus != FALSE)
{
	var_dump($menus['menu']);
}
echo '</pre>';
?>
