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

    <div class="exchange-container">
        <h1 class="exchange-page-title">All Exchanges</h1>

        <!-- Mustache Template -->
        <script id="exchange-template" type="x-tmpl-mustache">
            <div class="exchange-list">
                {{#exchanges}}
                <div class="exchange-card">
                    <div class="exchange-rank">Rank: {{rank}}</div>
                    <div class="exchange-name">{{name}}</div>
                    <div class="exchange-volume">Volume (24h): ${{volumeUsd}}</div>
                    <div class="exchange-link">
                        <a href="{{exchangeUrl}}" target="_blank">Visit Website</a>
                    </div>
                </div>
                {{/exchanges}}
            </div>
        </script>

        <!-- Render target -->
        <div id="exchangeContainer"></div>
    </div>

    <script src="js/exchanges.js"></script>
</body>

</html>