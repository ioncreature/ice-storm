<?php
/*
	Модуль редактирования учебных тем внутри курса
	Marenin Alex
	September 2011
*/


//
// ВЫВОД
//
Template::add_js( '/js/json2.js' );
Template::add_js( '/js/underscore.js' );
Template::add_js( '/js/backbone.js' );
Template::top();
?>
<h2>Бэкбон МВЦ</h2>

<style type="text/css">
#error, #success{
	display: none;
}
</style>

<!-- Блок меню -->
<div id="menu">
    <ul>
        <li><a href="#!/">Start</a></li>
        <li><a href="#!/success">Success</a></li>
        <li><a href="#!/error">Error</a></li>
    </ul>
</div>

<!-- PAGE BLOCKS -->
<div id="start" class="block">
	<div class="userplace">
	    <label for="username">Имя пользователя: </label>
	    <input type="text" id="username" />
	</div>
	<div class="buttonplace">
	    <input type="button" value="Проверить" />
	</div>
</div>
<div id="error" class="block">
	Ошибка такой пользователь не найден.
</div>
<div id="success" class="block">
	Пользователь найден.
</div>


<script type="text/javascript">
var Controller = Backbone.Router.extend({
	routes: {
		"": "start", // Пустой hash-тэг
		"!/": "start", // Начальная страница
		"!/success": "success", // Блок удачи
		"!/error": "error" // Блок ошибки
	},

	start: function () {
		$(".block").hide(); // Прячем все блоки
		$("#start").show(); // Показываем нужный
	},

	success: function () {
		$(".block").hide();
		$("#success").show();
	},

	error: function () {
		$(".block").hide();
		$("#error").show();
	}
});
var controller = new Controller(); // Создаём контроллер
Backbone.history.start();  // Запускаем HTML5 History push


var Start = Backbone.View.extend({
	el: $("#start"), // DOM элемент widget'а
	events: {
		"click input:button": "check" // Обработчик клика на кнопке "Проверить"
	},
	check: function () {
		if (this.el.find("input:text").val() == "test") // Проверка текста
			controller.navigate("success", true); // переход на страницу success
		else
			controller.navigate("error", true); // переход на страницу error
	}
});
var start = new Start();



</script>


<?= Template::bottom() ?>