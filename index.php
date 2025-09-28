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
    <?php navbar() ?>

    <div class="welcomeMessage">
        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        <?php endif; ?>
    </div>

    <div class="container">

        <table class="table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>logo</th>
                    <th>Crypto</th>
                    <th>Price</th>
                    <th >details:</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
        <div class="preloader">
            <img src="img/load.gif" alt="loading">
        </div>
    </div>
    <script id="table" type="x-tmpl-mustache">
        {{#assets}}
            <tr>
                <td>{{row}}</td>
                <td>
                    <img src="{{logo}}" alt="{{symbol}} logo" width="32" height="32">    
                    {{symbol}}
                </td>
                <td>{{name}}</td>
                <td>{{price}}</td>
                <td><button class="details-btn" data-index="{{row}}">View</button></td>
            </tr>
        {{/assets}}
    </script>
    <script defer src="js/main.js"></script>

    <div id="details" style="margin-top:20px;">

        <!-- Modal background -->
        <div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">

            <!-- Modal content -->
            <div id="modalContent" style="background:#fff; padding:20px; border-radius:10px; max-width:600px; width:90%;">
                <button id="closeModal" style="float:right;">X</button>
                <div id="modalDetails">
                    
                </div>
                <canvas id="historyChart" width="500" height="300"></canvas>
            </div>
        </div>


    </div>
</body>

</html>