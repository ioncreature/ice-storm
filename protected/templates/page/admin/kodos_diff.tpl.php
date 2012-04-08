<?php
/**
 * @author Marenin Alex
 *         March 2012
 */
?>


<ul>
<?php foreach(  $data['diff'] as $key => $d ): ?>

	<li>
		<h4>User Name</h4>
		<ul>
			<li>
				<?php foreach(  $d as $field => $value ): ?>
					<?= $field ?> => <?= $value ?>
					<a href="" field="<?= $field ?>" value="<?= $value ?>">Принять из кодоса</a>
					<a href="" >Исправить в кодоса</a>
				<?php endforeach; ?>
			</li>
		</ul>
	</li>

<?php endforeach; ?>
</ul>
	
<script type="text/javascript">

	$( 'a[field][value]' ).click( function(){
		var a = $( this ),
			value = a.attr( 'value' ),
			field = a.attr( 'field' );

		$.ajax({
			type: 'POST',
			url: 'ololo',
			data: {value: value, field: field},
			dataType: 'json'
		})
		.then( function( json ){
			if ( json.status )
				alert( 'Поле сохранилось, сейчас я его скрою' );
			else
				alert( 'Произошла ошибка!' );
		});


	});

</script>