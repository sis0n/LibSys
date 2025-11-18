<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>405 Method Not Allowed</title>
    <style>
        /* CSS to match the style of the image: Orange background, centered, and the hanging sign look */
        body {  
            background-color: #ff6a00; /* Bright orange background */
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff; /* White text for general info */
            overflow: hidden; /* Hide scrollbars if the content is centered */
        }

        .sign-container {
            /* This div simulates the pulley/rope mechanism */
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px; /* Space for the pulley/rope */
        }

        .pulley {
            /* The top hanging point/pulley */
            width: 15px;
            height: 15px;
            background-color: #fff;
            border: 2px solid #000;
            border-radius: 50%;
            position: absolute;
            top: 0;
            z-index: 2;
        }

        .rope {
            /* The rope/line the sign hangs from */
            width: 2px;
            height: 50px; /* Length of the rope */
            background-color: #000;
            position: absolute;
            top: 15px; /* Below the pulley */
            z-index: 1;
        }

        .error-sign {
            /* The actual white sign */
            background-color: #fff;
            padding: 20px 40px;
            border: 2px solid #000;
            box-shadow: 10px 10px 0px rgba(0, 0, 0, 0.2); /* Slight shadow effect */
            position: relative;
            z-index: 3;
            margin-top: -5px; /* Adjust to better align with the rope */
        }

        .error-code {
            /* The '403' text style */
            font-size: 10em; /* Very large text */
            font-weight: bold;
            color: #000;
            margin: 0;
            /* To simulate the outline look in the image */
            -webkit-text-stroke: 4px #000;
            color: transparent; /* Makes the fill transparent, showing only the stroke */
        }
        
        /* Additional text below the sign */
        .message-box {
            margin-top: 30px;
            text-align: center;
        }
        
        .message-box h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .message-box p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        
        .message-box a {
            color: #000; /* Link color */
            background-color: #fff; /* White button look */
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            /* 🎯 Ito ang kailangan para maging button: */
            display: inline-block; 
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .message-box a:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="sign-container">
        <div class="pulley"></div>
        <div class="rope"></div>
        
        <div class="error-sign">
            <h1 class="error-code">405</h1>
        </div>
    </div>
    
    <div class="message-box">
        <h1>Not Found</h1>
        <p>The request method is not supported for the requested resource.</p>
        <a href= "../auth/login.php">Go back to login</a>
    </div>
</body>
</html>

 <!-- <a href="<?= BASE_URL ?>/login">Go back to login</a> -->