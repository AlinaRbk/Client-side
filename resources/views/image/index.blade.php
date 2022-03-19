<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Client-side</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    </head>
    <body class="antialiased">
        <div class= "container">
        <button id="show-create-image-modal" data-bs-toggle="modal" data-bs-target="#createImageModal" >Create Image</button>
            <table id="images" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Alt</th>
                        <th>Url</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>        
            </table>
            <div class="button-container">
            </div>

            <table class="template d-none">
                <tr>
                    <td class="col-image-id"></td>
                    <td class="col-image-title"></td>
                    <td class="col-image-alt"></td>
                    <td class="col-image-url"></td>
                    <td class="col-image-description"></td>
                    <td>
                        <button class="btn btn-danger delete-image" type="submit" data-imageid="">DELETE</button>
                        <button type="button" class="btn btn-primary show-image" data-bs-toggle="modal"
                            data-bs-target="#showImageModal" data-imageid="">Show</button>
                        <button type="button" class="btn btn-secondary edit-image" data-bs-toggle="modal"
                            data-bs-target="#editImageModal" data-imageid="">Edit</button>
                    </td>
                </tr>
            </table>


        </div>

        <!-- Modal -->

        <div class="modal fade" id="createImageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create Modal</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="ajaxForm">
                    <div class="form-group">
                        <label for="Image_title">Image Title</label>
                        <input id="image_title" class="form-control create-input" type="text" name="image_title" />
                        <span class="invalid-feedback input_image_title">
                        </span>
                      </div>
                    <div class="form-group">
                        <label for="image_alt">Image Alt</label>
                        <input id="image_alt" class="form-control create-input" type="text" name="image_alt" />
                        <span class="invalid-feedback input_image_alt">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="image_url">Image Url</label>
                        <input id="image_url" class="form-control create-input" type="text" name="image_url" />
                        <span class="invalid-feedback input_image_url">
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="image_description">Image Description</label>
                        <input id="image_description" class="form-control create-input" type="text" name="image_description" />
                        <span class="invalid-feedback input_image_description">
                        </span>  
                    </div>
                    </div> 
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="create-image" type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div>
            </div>
          </div>    


        <script>
            $(document).ready(function(){
                console.log('jquery veikia');
            });

            function createRowFromHtml(imageId, imageTitle, imageAlt, imageUrl, imageDescription) {
                $(".template tr").removeAttr("class");
                $(".template tr").addClass("image" + imageId);
                $(".template .delete-image").attr('data-imageid', imageId);
                $(".template .show-image").attr('data-imageid', imageId);
                $(".template .edit-image").attr('data-imageid', imageId);
                $(".template .col-image-id").html(imageId);
                $(".template .col-image-title").html(imageTitle);
                $(".template .col-image-alt").html(imageAlt);
                $(".template .col-image-url").html(imageUrl);
                $(".template .col-image-description").html(imageDescription);

                return $(".template tbody").html();
            }

            $(document).on('click', '.button-container button',function() {

                let page= $(this).attr('data-page');
                console.log(page);
                $.ajax({
                    type: 'GET',
                    url: page,
                   
                    success: function(data) {
                        $('#images tbody').html('');
                        $('.button-container').html('');

                        $.each(data.data, function(key, image) {
                    
                    let html;
                    html = createRowFromHtml(image.id, image.title, image.alt, image.url, image.description);
                    $('#images tbody').append(html);
                });

                       $.each(data.links, function(key, link) {
                            
                        let button;
                            if (link.url != null) {
                                if(link.active == true) {
                                    button = "<button class='btn btn-primary active type='button' data-page='"+link.url +"'>" + link.label+" </button>";
                                } else {
                                    button = "<button class='btn btn-primary' type='button' data-page='"+link.url +"'>" + link.label+" </button>";
                                }
                            }
                            $('.button-container').append(button);
                       });
                        console.log(data)
                    }
                });
            });


            $.ajax({
                    type: 'GET',
                    url: 'http://127.0.0.1:8000/api/images',
                   
                    success: function(data) {
                        $.each(data.data, function(key, image) {
                    
                        let html;
                        html = createRowFromHtml(image.id, image.title, image.alt, image.url, image.description);
                        $('#images tbody').append(html);
                        });
                       console.log(data.links)
                       $.each(data.links, function(key, link) {
                            let button;
                    
                        
                                    button = "<button class='btn btn-primary' type='button' data-page='"+link.url +"'>" + link.label+" </button>";
                            $('.button-container').append(button);
                        });
                    }
            });

            $(document).on('click', '#create-image', function() {
                let image_title = $('#image_title').val();
                let image_alt = $('#image_alt').val();
                let image_url = $('#image_url').val();
                let image_description = $('#image_description').val();
                $.ajax({
                        type: 'POST',
                        url: 'http://127.0.0.1:8000/api/images',
                        data: {image_title:image_title, image_alt:image_alt,image_url:image_url, image_description:image_description },
                        success: function(data) {
                            console.log(data)
                        }
                });

                $.ajax({
                    type: 'GET',
                    url: 'http://127.0.0.1:8000/api/images',
                    data: {csrf:csrf},
                    success: function(data) {
                        $('#cimages tbody').html('');
                        $('.button-container').html('');
                        $.each(data.data, function(key, image) {
                    
                            let html;
                            html = createRowFromHtml(image.id, image.title, image.alt, image.url, image.description);
                            $('#images tbody').append(html);
                        });
                       console.log(data.links)
                       $.each(data.links, function(key, link) {
                            let button;
                            button = "<button class='btn btn-primary' type='button' data-page='"+link.url +"'>" + link.label+" </button>";
                            $('.button-container').append(button);
                        });
                    }
                });
            })

        </script>
        
    </body>
</html>
