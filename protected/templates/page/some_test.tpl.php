<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>
Hello! <?= isset($data['some_key']) ? $data['some_key'] : ' And fuckoff!' ?>
</br>
<a href="<?= WEBURL .'some_test/redirect' ?>">Редирект тест</a>