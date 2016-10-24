<?php

require_once 'config.php';

$data = filter_var($_REQUEST['topic'], FILTER_SANITIZE_NUMBER_INT);
$topic_id = $data > 0 ? $data : 1;
$data1 = filter_var($_REQUEST['page'], FILTER_SANITIZE_NUMBER_INT);
$page = $data1 > 0 ? $data1 : 1;
$saved = isset($_REQUEST['saved']) ? 1 : 0;

$topic = getTopicFromDB($topic_id);
$count = numOfPostsByTopic($topic_id);

if (isset($_REQUEST['p_author']) && isset($_REQUEST['p_text'])) {
	$result = add_post($topic_id ,$_REQUEST['p_author'], $_REQUEST['p_text']);
	if ($result) {
		$address = 'topic.php?page=' . $page . '&topic=' . $topic_id . '&saved=1';
		header("Location: $address", true, 303);
	}
}
?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Тема №1</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Тема №:<?=$topic['id']?></h1>
			<div class="well well-lg">
				<p>
					<span class="subheader">Создана:</span>
				<?=$topic['created_on']?>
					<span class="subheader">Автор:</span>
				<?=$topic['author']?>
				<br>
					<span class="subheader">Количество ответов:</span>
				<?=$count?>
					<a href="index.php">Перейти на список тем.</a>
				</p>
				<p class="title"><?=$topic['title']?></p>

				<div class="desc">
					<p><?=$topic['text']?></p>
				</div>
			</div>
			
			<h2>Ответы</h2>
			
			<?php print_posts(getPostsbyTopicFromDB($topic_id), $page);
			pagination(count(getPostsByTopicFromDB($topic_id)), $page, $topic_id);?>

			<?php if($saved):?>
			<div class="info alert alert-info">
				Запись успешно сохранена!
			</div>
			<? endif;?>

			<div id="form">
				<form action="#form" method="POST">
					<p><input class="form-control" placeholder="Ваше имя"></p>
					
					<p><textarea class="form-control" placeholder="Ваше сообщение"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>


			