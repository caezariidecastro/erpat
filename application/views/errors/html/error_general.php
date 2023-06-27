<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ERPat - Forbidden Access</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #d50000;
            font-size: xx-large;
        }

        p {
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #cd0000;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: medium;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?= $heading; ?></h1>
        <p><?= $message; ?></p>
        <button class="button btn-primary" onclick="goBack()">Go Back</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>