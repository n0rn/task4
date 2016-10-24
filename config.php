    <?php

    define('ITEMS_PER_PAGE', 4);

    function db_connect() {
        $dsn = 'mysql:host=localhost; dbname=post45';
        $user = 'root';
        $password = '';

            $db = new PDO($dsn, $user, $password);
            $db->exec('SET CHARACTER SET utf8');

        return $db;
    }

    function pagination($items, $page, $topic_id = false) {
        $numOfPages = ceil($items / ITEMS_PER_PAGE);
        $flag = ($topic_id == false) ? '' : "&topic=$topic_id";

        if ($page > $numOfPages) { $page = 1; }?>

        <div>
        <nav>
            <ul class="pagination">
                <?php if ($page == 1) : ?>
                    <li class="disabled">
                        <a href="?page=1<?=$flag?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <? else : ?>
                    <li>
                        <a href="?page=<?=$page-1?><?=$flag?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <? endif; ?>

                <?php for ($i = 1; $i <= $numOfPages; $i++) : ?>
                    <?php if ($i != $page) : ?>
                        <li><a href="?page=<?=$i;?><?=$flag?>"><?=$i;?></a></li>
                    <? else: ?>
                        <li class="active"><a href="?page=<?=$i;?><?=$flag?>"><?=$i;?></a></li>
                    <? endif; ?>
                <? endfor; ?>

                <?php if ($page == $numOfPages) : ?>
                    <li class="disabled">
                        <a href="?page=<?=$page?><?=$flag?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <? else: ?>
                    <li>
                        <a href="?page=<?=$page+1?><?=$flag?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <? endif; ?>
            </ul>
        </nav>
        <div>
    <?php
    }

    function getTopicsFromDB() {
        $db = db_connect();
        $sth = $db->query('SELECT * FROM topics');
        return $topics = $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTopicFromDB($topic_id) {
        $db = db_connect();
        $sth = $db->prepare("SELECT * FROM post WHERE topic_id = ?");
        $sth -> execute(array($topic_id));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function getPostsByTopicFromDB($topic_id) {
        $db = db_connect();
        $sth = $db->prepare("SELECT * FROM post WHERE topic_id = ?");
        $sth -> execute(array($topic_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    function numOfPostsByTopic($topic_id) {
        $db = db_connect();
        $sth = $db->prepare("SELECT * FROM post WHERE topic_id = ?");
        $sth -> execute(array($topic_id));
        return $sth->rowCount();
    }

    function print_topics($topics, $page) {

    $count_topics = count($topics);

    if($count_topics == 0) {
    echo 'No topics!';
    return;
    }

    $topics_rev = array_reverse($topics);

    for ($i = ($page -1)* ITEMS_PER_PAGE ; $i < $page * ITEMS_PER_PAGE; $i++) :
    if (!empty($topics_rev[$i])) : ?>
    <div class="well">
    <p class="topic">
    <a href="topic.php?topic=<?=$topics_rev[$i]['id']?>">
    <?=$topics_rev[$i]['title'];?></a>
    </p>
    <p>
    <span class="subheader">Создана:</span>
    <?=$topics_rev[$i]['created_on'];?>
    <span class="subheader">Автор:</span>
    <?=$topics_rev[$i]['author'];?>
    <br>
    <span class="subheader">Количество ответов:</span>
    <?=numOfPostsByTopic($topics_rev[$i]['id']);?>
    </p>
    </div>
    <? endif; endfor;
    }

    function print_posts($posts, $page) {

    $count_posts = count($posts);

    if($count_posts == 0) {
    echo 'No posts!';
    return;
    }

    $posts_rev = array_reverse($posts);

    for ($i = ($page -1) * ITEMS_PER_PAGE ; $i < $page * ITEMS_PER_PAGE; $i++) :
    if (!empty($posts_rev[$i])) : ?>
    <div class="note">
    <p>
    <span class="date"><?=$posts_rev[$i]['created_on'];?></span>
    <span class="name"><?=$posts_rev[$i]['p_author'];?></span>
    </p>
    <p><?=$posts_rev[$i]['p_text'];?></p>
    </div>
    <? endif;
    endfor;
    }

    function checkTopicName ($topic_title) {
    $topic_title = htmlspecialchars(trim($topic_title));
    $db = db_connect();
    $sth = $db->query("SELECT * FROM post WHERE title = '$topic_title'");
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return (count($result)) ? 1 : 0;
    }

    function add_topic ($topic_author, $topic_title, $topic_text) {
    $topic_author = htmlspecialchars(trim($topic_author));
    $topic_title = htmlspecialchars(trim($topic_title));
    $topic_text = htmlspecialchars(trim($topic_text));

    $db = db_connect();
    $result = $db->query("INSERT INTO topics (author, title, text) 
    VALUES ('$topic_author', '$topic_title', '$topic_text')");
    return ($result) ? true : false;
    }

    function add_post ($topic_id, $post_author, $post_text) {
    $topic_id = htmlspecialchars(trim($topic_id));
    $post_author = htmlspecialchars(trim($post_author));
    $post_text = htmlspecialchars(trim($post_text));

    $db = db_connect();
    $result = $db->query("INSERT INTO posts (p_id, p_author, p_text) 
    VALUES ('$topic_id', '$post_author', '$post_text')");
    return ($result) ? true : false;
    }