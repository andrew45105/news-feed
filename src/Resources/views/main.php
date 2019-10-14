<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <title><?=$title;?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>

    <h1 style="text-align: center"><?=$title;?></h1>

    <div class="container">
        <div class="row">

            <form id="save_post">
                <input type="hidden" id="post_id">
                <div class="form-group">
                    <label for="post_title" class="col-form-label">Заголовок</label>
                    <div class="">
                        <input type="text" class="form-control" id="post_title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="post_body" class="col-form-label">Текст</label>
                    <div class="">
                        <textarea rows="5" class="form-control" id="post_body"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Добавить</button>
                <button class="btn btn-primary cancel">Отмена</button>
            </form>

            <div id="posts">

                <?php foreach ($news as $post) { ?>

                    <div class="post" data-id="<?=$post['id'];?>">
                        <h2 class="post-title"><?=$post['title'];?></h2>
                        <p class="post-body"><?=$post['body'];?></p>
                        <button class="btn btn-primary edit-post">Редактировать</button>
                        <button class="btn btn-danger delete-post">Удалить</button>
                    </div>

                <?php } ?>

            </div>

            <div id="pagination">

                <?php if ($pages > 1) { ?>
                    <?php for ($i = 1; $i <= $pages; $i++) { ?>

                        <?php $btnClass = $i == 1 ? 'btn-primary' : 'btn-secondary'; ?>
                        <button class="btn <?=$btnClass;?> pg-button" data-value="<?=$i;?>">
                            <?=$i;?>
                        </button>

                    <?php } ?>
                <?php } ?>

            </div>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/main.js"></script>
</body>

</html>