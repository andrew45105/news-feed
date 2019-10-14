$(document).ready(function () {

    let form    = $('#save_post');
    let submit  = form.find('button[type="submit"]');
    let cancel  = form.find('button.cancel');
    let title   = $('#post_title');
    let body    = $('#post_body');
    let id      = $('#post_id');
    let isAdd   = true;

    // Добавление или сохранение новости
    form.on('submit',function(e){
        e.preventDefault();

        // если добавляем новость
        if (isAdd) {
            $.ajax({
                url: '/news/create',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    title: $.trim(title.val()),
                    body: $.trim(body.val()),
                },
                success: function (response) {
                    // формируем html нового поста
                    let post =
                        '<div class="post" data-id="' +
                        response.data.id + '"><h2 class="post-title">' +
                        response.data.title + '</h2><p class="post-body">' +
                        response.data.body +
                        '</p>' +
                        '<button class="btn btn-primary edit-post">Редактировать</button>\n' +
                        '<button class="btn btn-danger delete-post">Удалить</button>' +
                        '</div>';
                    // добавляем новый пост в DOM
                    $('#posts').prepend(post).hide().show(200);
                    // обновляем контент
                    updateContent();
                },
                error: function (err) {
                    alert(err.responseText);
                }
            })
        // если редактируем новость
        } else {
            $.ajax({
                url: '/news/edit/' + id.val(),
                type: 'POST',
                dataType: 'JSON',
                data: {
                    title: $.trim(title.val()),
                    body: $.trim(body.val()),
                },
                success: function (response) {
                    // ищем редактируемый пост
                    let editedPost = $('div.post[data-id="' + response.data.id + '"]');
                    // устанавливаем новые значения
                    editedPost.find('.post-title').html(response.data.title);
                    editedPost.find('.post-body').html(response.data.body);

                    // проматываем страницу к посту и подсвечиваем его
                    highLightElem(editedPost);
                    // очищаем форму
                    clearForm();
                },
                error: function (err) {
                    alert(err.responseText);
                }
            });
        }
    });


    // Редактирование новости
    $(document).on('click','.edit-post',function() {
        // проматываем страницу к форме и подсвечиваем ее
        highLightElem(form);
        // меняем надпись на кнопке
        submit.html('Сохранить');

        let post = $(this).parent();

        // устанавливаем данные редактируемой новости в форму
        id.val(post.data('id'));
        title.val(post.find('.post-title').html());
        body.val(post.find('.post-body').html());

        // меняем флаг на редактирование новости
        isAdd = false;
    });

    // Удаление новости
    $(document).on('click','.delete-post',function() {
        // подтверждаем удаление
        if (!confirm('Удалить новость?')) {
            return false;
        }

        let post = $(this).parent();
        let postId = post.data('id');

        $.ajax({
            url: '/news/' + postId,
            type: 'DELETE',
            dataType: 'JSON',
            success: function (response) {
                // очищаем форму
                clearForm();
                // скрываем и удаляем пост со страницы
                post.hide(200, function(){ post.remove();});
                // обновляем контент
                updateContent();
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    });

    // Нажатие кнопки пагинации
    $(document).on('click','.pg-button',function() {

        let button = $(this);
        let page = button.data('value');

        $.ajax({
            url: '/news/list/' + page,
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
                // формируем html из данных с сервера
                let posts = '';
                $.each(response.data.news, function (index, post) {
                    posts +=
                        '<div class="post" data-id="' +
                        post.id + '"><h2 class="post-title">' +
                        post.title + '</h2><p class="post-body">' +
                        post.body +
                        '</p>' +
                        '<button class="btn btn-primary edit-post">Редактировать</button>\n' +
                        '<button class="btn btn-danger delete-post">Удалить</button>' +
                        '</div>';
                });
                // обновляем содержимое страницы новостей
                $('#posts').html(posts);
                // меняем кнопку активной страницы
                $('.pg-button').removeClass('btn-primary').addClass('btn-secondary');
                button.removeClass('btn-secondary').addClass('btn-primary');
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    });

    // Очистка формы
    $(document).on('click','.cancel',function(e) {
        e.preventDefault();
        clearForm();
    });

    // Фокуссировка на элементе и его подсветка
    function highLightElem(elem) {
        // проматываем страницу к элементу
        $('html, body').animate({ scrollTop: elem.offset().top }, 'fast');
        // подсвечиваем элемент
        elem.addClass('highlighted');
        setTimeout(function () {
            elem.removeClass('highlighted');
        }, 1000);
    }

    // Очистка данных формы
    function clearForm() {
        // меняем флаг на добавление новости
        isAdd = true;
        // меняем надпись на кнопке
        submit.html('Добавить');
        // очищаем данные редактируемой новости в форме
        id.val('');
        title.val('');
        body.val('');
    }

    // Обновление контента после удаления|добавления новости
    function updateContent() {
        // получаем кол-во актуальных страниц с сервера
        $.ajax({
            url: '/news/pages',
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
                let count = response.data.count;
                let buttons = '';

                // формируем пагинацию
                for (let i = 1; i <= count; i++) {
                    let btnClass = i === 1 ? 'btn-primary' : 'btn-secondary';
                    buttons +=
                        '<button class="btn ' + btnClass + ' pg-button" data-value="' +
                        i +
                        '">' +
                        i +
                        '</button>&nbsp;';
                }
                // устанавливаем пагинацию
                $('#pagination').html(buttons);
                // переходим на первую страницу
                $('.pg-button').first().click();
                // если страниц меньше двух, убираем кнопки
                if ($('.pg-button').length < 2) {
                    $('#pagination').html('');
                }
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    }
});