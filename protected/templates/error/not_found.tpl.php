<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>
<style type="text/css">
	div.access_denied {
		font-size: 22px;
		font-weight: bold;
		color: #610;
		text-align: center;
		padding: 20px 0px;
	}
	div.access_denied span {
	}
</style>
<div class="access_denied">
	<span>404 - Страница не найдена</span>
</div>

<?php
if ( isset($data['msg']) and IS_DEBUG )
	echo $data['msg'];
?>