<?php
    // invlude memcached class and connect to memcached server
    require('memcached.php');

    // include the database connection
    require('dbconnect.php');


    function generateUniqueVideoId() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }


    // define variables and set to empty values
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["url"] != "" && $_POST["apiKey"] != "" && $_POST['youtubeId']){
            // check if the video was already fetched before
            $getYoutubeByVideoId = $pdo->query("SELECT * FROM youtubeurl where youtube_id = '".$youtubeId."'");
            $rows = $getYoutubeByVideoId->fetchAll();
            if(count($rows) > 0){
                echo $rows[0]['url_key'];
            }else{
                $videoUrl = $_POST["url"];
                $urlKey = generateUniqueVideoId();
                $youtubeId = $_POST['youtubeId'];
                $apiKey = $_POST["apiKey"];
                $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id=$youtubeId&key=$apiKey";
                $cURLConnection = curl_init();
    
                curl_setopt($cURLConnection, CURLOPT_URL, $url);
                curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    
                $youtubeUrlDetails = curl_exec($cURLConnection);
                curl_close($cURLConnection);
    
                $jsonArrayResponse = json_decode($youtubeUrlDetails,true);
    
                $newRecord = $pdo->prepare("INSERT INTO youtubeurl (url,url_key,youtube_id,serve,published_at,channel_id,title,channel_title,category_id,default_language,view_count,like_count,dislike_count,comment_count) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $insertedRecord = $newRecord->execute([
                    $videoUrl,
                    $urlKey,
                    $youtubeId,
                    date('Y-m-d H:i:s'),
                    (isset($jsonArrayResponse['items'][0]['snippet']['publishedAt']) && trim($jsonArrayResponse['items'][0]['snippet']['publishedAt'])) ? trim($jsonArrayResponse['items'][0]['snippet']['publishedAt'] ) : '',
                    (isset($jsonArrayResponse['items'][0]['snippet']['channelId']) && trim($jsonArrayResponse['items'][0]['snippet']['channelId'])) ? trim($jsonArrayResponse['items'][0]['snippet']['channelId'] ) : '',
                    (isset($jsonArrayResponse['items'][0]['snippet']['title']) && trim($jsonArrayResponse['items'][0]['snippet']['title'])) ? trim($jsonArrayResponse['items'][0]['snippet']['title'] ) : '',
                    (isset($jsonArrayResponse['items'][0]['snippet']['channelTitle-']) && trim($jsonArrayResponse['items'][0]['snippet']['channelTitle-'])) ? trim($jsonArrayResponse['items'][0]['snippet']['channelTitle-'] ) : '',
                    (isset($jsonArrayResponse['items'][0]['snippet']['categoryId']) && trim($jsonArrayResponse['items'][0]['snippet']['categoryId'])) ? trim($jsonArrayResponse['items'][0]['snippet']['categoryId'] ) : '',
                    (isset($jsonArrayResponse['items'][0]['snippet']['defaultLanguage']) && trim($jsonArrayResponse['items'][0]['snippet']['defaultLanguage'])) ? trim($jsonArrayResponse['items'][0]['snippet']['defaultLanguage'] ) : '',
		    (isset($jsonArrayResponse['items'][0]['statistics']['viewCount']) && trim($jsonArrayResponse['items'][0]['statistics']['viewCount'])) ? trim($jsonArrayResponse['items'][0]['statistics']['viewCount'] ) : '',
		    (isset($jsonArrayResponse['items'][0]['statistics']['likeCount']) && trim($jsonArrayResponse['items'][0]['statistics']['likeCount'])) ? trim($jsonArrayResponse['items'][0]['statistics']['likeCount'] ) : '',
	            (isset($jsonArrayResponse['items'][0]['statistics']['dislikeCount']) && trim($jsonArrayResponse['items'][0]['statistics']['dislikeCount'])) ? trim($jsonArrayResponse['items'][0]['statistics']['dislikeCount'] ) : '',
		    (isset($jsonArrayResponse['items'][0]['statistics']['commentCount']) && trim($jsonArrayResponse['items'][0]['statistics']['commentCount'])) ? trim($jsonArrayResponse['items'][0]['statistics']['commentCount'] ) : '',
                ]);
    
                if ($insertedRecord) {
                    // clear generatedUrl cache if exist
                    $memcache->delete('generatedURL');
    
                    // fetch all records and cache them to save fetching from database each time user enter url listing page
                    $stmt = $pdo->query('SELECT * FROM youtubeurl ORDER BY id DESC');
                    $rows = $stmt->fetchAll();
                    $memcache->set('generatedURL', $rows, false, 1800);
    
                    echo $urlKey;
                }else {
                    echo "error";
                }
            }
        }else{
	        echo "error";
        }
    }
?>

