
<?php
    // include the database connection
    require('dbconnect.php');

    $urlId = $_GET['id'];

    $getYoutubeByVideoId = $pdo->query("SELECT * FROM youtubeurl where url_key = '".$urlId."'");
    $rows = $getYoutubeByVideoId->fetchAll();

?>
<html>  
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Video Details
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered  table-responsive text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Youtube Page</th>
                                        <th scope="col">Youtube ID</th>
                                        <th scope="col">Published At</th>
                                        <th scope="col">Channel ID</th>
                                        <th scope="col">Video Title</th>
                                        <th scope="col">Channel Title</th>
                                        <th scope="col">Category ID</th>
                                        <th scope="col">Language</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row"><a href="<?php echo $rows[0]['url']; ?>" target="_blank">View</a></th>
                                        <td><?php echo $rows[0]['youtube_id']; ?></td>
                                        <td><?php echo $rows[0]['published_at']; ?></td>
                                        <td><?php echo $rows[0]['channel_id']; ?></td>
                                        <td><?php echo $rows[0]['title']; ?></td>
                                        <td><?php echo $rows[0]['channel_title']; ?></td>
                                        <td><?php echo $rows[0]['category_id']; ?></td>
                                        <td><?php echo $rows[0]['default_language']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-5">
                        <div class="card-header">
                            Play Video
                        </div>
                        <div class="card-body">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $rows[0]['youtube_id']; ?>" allowfullscreen></iframe>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    </body>
</html>
