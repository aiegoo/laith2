<html>  
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <div class="container mt-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Fetch Youtube URL Details
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <input type="url" class="form-control" placeholder="Please Add Youtube URL" id="youtubeUrl" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" placeholder="Youtube API Key" id="apiKey" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" id="fetchYoutubeUrlData"  class="btn btn-primary d-block" >Fetch Youtube Video Data</button>
                                        <button class="btn btn-primary d-none" type="button" disabled id="loaderBtn">
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Fetching...
                                        </button>
                                        <button class="btn btn-primary d-none" type="button" disabled id="redirectToListPage">
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Redirecting to list page...
                                        </button>
                                    </div>
				    <div class="col-lg-3">
                                        <a href="view-urls.php" class="btn btn-primary d-block">View Fetched Videos List</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(){
                $("#fetchYoutubeUrlData").click(function(){
                    if($('#fetchYoutubeUrlData').hasClass('d-block')){
                        $('#fetchYoutubeUrlData').addClass('d-none');
                        $('#fetchYoutubeUrlData').removeClass('d-block');
                    }
                    if($('#loaderBtn').hasClass('d-none')){
                        $('#loaderBtn').addClass('d-block');
                        $('#loaderBtn').removeClass('d-none');
                    }

                    var videoId = youtube_parser($('#youtubeUrl').val());
                    if(videoId == ''){
                        if($('#fetchYoutubeUrlData').hasClass('d-none')){
                            $('#fetchYoutubeUrlData').addClass('d-block');
                            $('#fetchYoutubeUrlData').removeClass('d-none');
                        }
                        if($('#loaderBtn').hasClass('d-block')){
                            $('#loaderBtn').addClass('d-none');
                            $('#loaderBtn').removeClass('d-block');
                        }
                        alert('Something went wrong');;
                    }else{
                        setTimeout(function(){
                            $.ajax({
                                url: 'action.php',
                                type: "POST",
                                data: {
                                    youtubeId : videoId,
                                    apiKey : $('#apiKey').val(),
                                    url : $('#youtubeUrl').val()
                                },
                                success: function(data, textStatus, jqXHR) {
                                        if($('#loaderBtn').hasClass('d-block')){
                                            $('#loaderBtn').addClass('d-none');
                                            $('#loaderBtn').removeClass('d-block');
                                        }
                                        if($('#redirectToListPage').hasClass('d-none')){
                                            $('#redirectToListPage').addClass('d-block');
                                            $('#redirectToListPage').removeClass('d-none');
                                        }
                                        window.location.href = "http://159.65.8.38/laith/view-video.php?id="+data;
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    if($('#fetchYoutubeUrlData').hasClass('d-none')){
                                        $('#fetchYoutubeUrlData').addClass('d-block');
                                        $('#fetchYoutubeUrlData').removeClass('d-none');
                                    }
                                    if($('#loaderBtn').hasClass('d-block')){
                                        $('#loaderBtn').addClass('d-none');
                                        $('#loaderBtn').removeClass('d-block');
                                    }
                                    alert('Something went wrong');
                                }
                            });
                        }, 3000);
                    }
                });
            });

            function youtube_parser(url){
                var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
                var match = url.match(regExp);
                if (match && match[7].length==11){
                    var b = match[7];
                    return b;
                }else{
                    return '';
                }
            }
        </script>
    </body>
</html>
