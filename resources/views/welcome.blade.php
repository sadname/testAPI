<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">



</head>
<body>
    <div class="container">
        <div class="row mt-3 articles">

        </div>
        <div class="row mt-3 full-article d-none">
            <div class="card">
                <div class="card-header">
                    Full Post
                </div>
                <div class="card-body">
                    <h5 class="card-title article-title"></h5>
                    <p class="card-text article-content"></p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-4">
            <form action="">
                <div class="mb-3">
                    <label for="title" class="form-label">Article title</label>
                    <input type="text" class="form-control" id="title">
                    <div class="alert alert-danger mt-2 d-none" id="title-error" role="alert">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Article content</label>
                    <textarea class="form-control" id="content" rows="3"></textarea>
                    <div class="alert alert-danger mt-2 d-none" id="content-error" role="alert">
                    </div>
                </div>
                <button type="button" class="btn btn-success" onclick="storeArticle()">Add</button>
            </form>
        </div>
    </div>


  
<div class="modal" id="delete" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="delete-id">
            <div class="modal-body">
                <p>Вы действительно хотите удалить пост - <span id="delete-title"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="deleteArticle()">Continue</button>
            </div>
        </div>
    </div>
</div>


    <script>
    
       function loadArticles() {
        $('.articles').html('');
        $.ajax({
            url: "/api/articles",
            type: "GET",
            dataType: "json",
            success(data) {
                for (let index in data) {
                    $('.articles').append(`
                    <div class="card" style="width: 18rem; margin-right: 10px; margin-bottom: 10px;">
                        <div class="card-body">
                            <h5 class="card-title">${data[index].title}</h5>
                            <p class="card-text">${data[index].content.slice(0, 20)}...</p>
                            <a href="#" class="btn btn-primary" onclick="fullArticle(${data[index].id})">Show</a>

                            <button type="button" onclick="setFieldsForModalDelete('${data[index].title}', ${data[index].id})" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete">
                                Delete
                            </button>
                        </div>
                    </div>
                `)
                }
            }
        });
    }

    loadArticles();


        function fullArticle(id) {
            $.ajax({
                url: "/api/articles/" + id,
                type: "GET",
                 dataType: "json",
                 success(data) {
                    $('.article-title').text(data.title);
                    $('.article-content').text(data.content);
                    $('.full-article').removeClass('d-none');
                }
            })
        }

        function storeArticle() {
            const title = $('#title'), 
            content = $('#content');
        $('#title-error').addClass('d-none');
        $('#content-error').addClass('d-none');
            $.ajax({
                url: "/api/articles",
             type: "POST",
                dataType: "json",
                 data: {
                    title: title.val(),
                     content: content.val()
                },
                error(err){
                    const data = err.responseJSON;
                    for (let key in err.responseJSON.errors){
                        let error_text = err.responseJSON.errors[[key][0]];
                        $(`#${key}-error`).removeClass('d-none').text(error_text);
                    }
                },
                 success(data) {
                    title.val('');
                    content.val('');
                    $('.articles').append(`
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">${data.article.title}</h5> 
                        <p class="card-text">${data.article.content.slice(0, 20)}...</p>
                        <a href="#" class="btn btn-primary" onclick="fullArticle(${data.article.id})">Show</a>
                        <button type="button" onclick="setFieldsForModalDelete('${data.article.title}', ${data.article.id})" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete">
                                Delete
                        </button>
                    </div>
                </div>
                `)
                    console.log(data)
                }
            })
        }

        function setFieldsForModalDelete(title,id){
            $('#delete-id').val(id);
            $('#delete-title').text(title);
        }

        function deleteArticle(){

            const id = $('#delete-id').val();
            $.ajax({
                url:"/api/articles/"+id,
                type:"DELETE",
                dataType:"json",
                success(data){
                    $('#delete').modal('hide');
                     loadArticles();
                }
            })
        }

    </script>
</body>
</html>
