<?php

require_once 'config.php';

$data = filter_var($_REQUEST['page'], FILTER_SANITIZE_NUMBER_INT);
$page = $data > 0 ? $data : 1;
$saved = isset($_REQUEST['saved']) ? 1 : 0;
$topicExist = 0;

if (isset($_REQUEST['author']) && isset($_REQUEST['title']) && isset($_REQUEST['text'])) {
	if (checkTopicName($_REQUEST['title'])) {
		$topicExist = 1;
	} else {
		$result = add_topic($_REQUEST['author'], $_REQUEST['title'], $_REQUEST['text']);
		if ($result) {
			header('Location: index.php?saved=1', true, 303);
		}
	}
}

?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Форум</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Наш форум</h1>
			
			
			<div class="note">
				Наш супер крутой форум посвящен phasellus gravida fermentum pellentesque. Aenean non neque mollis nisl dapibus eleifend. Sed interdum dui nec dictum elementum. Proin eget semper dolor, ut commodo nibh. 
				Quisque vitae pharetra ligula. Sed dictum, sem sed pellentesque aliquam, tellus sapien dapibus magna, eu suscipit lacus augue sed velit. Ut vehicula sagittis nulla, et aliquet elit. Quisque tincidunt sem nibh, finibus dictum nisl vulputate quis. In vitae nisl et lacus pulvinar ornare id ac libero. Morbi pharetra fringilla erat ut lacinia. 
			</div>	
			<h2>Темы форума</h2>

			<?php prtint_topics(getTopicsFromDB(), $page);
			pagination(count(getTopicsFromDB()), $page);?>

			
			<h2>Создать тему</h2>
			
			<?php if($saved):?>
			<div class="info alert alert-info">
				Тема успешно создана!
			</div>
			<? endif;?>

			<?php if($topicExist):?>
			<div class="info alert alert-danger">
				Тема с таким названием уже существует!
			</div>
			<? endif;?>

			
			<div id="form">
				<form action="" method="POST">
					<p><input name="author" class="form-control" placeholder="Ваше имя"></p>
					<p><input  name="title" class="form-control" placeholder="Название темы"></p>
					
					<p><textarea name="text" class="form-control" placeholder="Описание темы"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>


			