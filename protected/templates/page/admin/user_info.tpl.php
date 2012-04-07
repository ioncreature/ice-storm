<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

?>

<ul>
 	<li>Login - <?= $data['login'] ?></li>
 	<li>Name - <?= $data['full_name'] ?></li>
</ul>

<?php if ( $data['is_student'] )
	Template::show( 'page/student_show', array( 'student' => $data['student_model']	) );
?>


<a href="<?= WEBURL . 'admin/users/'. $data[''] ?>">Редактировать</a>
