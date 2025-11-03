<?php
session_start();
include 'php/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mustache@4.2.0/mustache.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <title>cryptomania</title>
</head>

<body>
    <?php navbar(); ?>

    <div class="news-container">
        <h1 class="news-h1">News</h1>

        <!-- Mustache Template -->
        <script id="news-template" type="x-tmpl-mustache">
            <div class="news-list">
                {{#news}}
                <div class="news-card">
                    <img src="{{img}}" alt="{{title}}" class="news-img">
                    <h2 class="news-title">{{title}}</h2>
                    <p class="news-text">{{text}}</p>
                    <p class="news-source">Source: {{source}}</p>
                    <a href="{{url}}" target="_blank" class="news-link">Read more</a>
                </div>
                {{/news}}
            </div>
        </script>

        <!-- Render target -->
        <div id="newsContainer"></div>

        <script src="js/news.js"></script>
</body>

</html>