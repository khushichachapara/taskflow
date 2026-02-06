<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TaskFlow</title>

    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: #f4f6f8;
            color: #333;
        }

        .hero {
            height: calc(100vh - 64px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-content {
            max-width: 520px;
            background: #ffffff;
            padding: 48px 56px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            text-align: center;
        }

        .hero-content h1 {
            margin: 0;
            font-size: 34px;
            font-weight: 600;
            color: #1f2937;
        }

        .hero-content .subtitle {
            margin-top: 12px;
            font-size: 15px;
            color: #6b7280;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: #459efe;
            margin: 22px auto;
            border-radius: 2px;
        }

        .description {
            font-size: 15px;
            line-height: 1.6;
            color: #4b5563;
        }
    </style>    
</head>
<body>

<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="hero">
    <div class="hero-content">
        <h1>TaskFlow</h1>
        <div class="subtitle">Task Management System</div>

        <div class="divider"></div>

        <p class="description">
            TaskFlow helps you organize tasks, track progress,
            and maintain a clear workflow using a structured and
            reliable system designed for everyday productivity.
        </p>
    </div>
</div>

</body>
</html>
