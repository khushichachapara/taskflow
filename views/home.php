<!DOCTYPE html>
<html>
<head>
  <title>TaskFlow</title>
  <style>
    body {
      font-family: Arial;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background: white;
      padding: 40px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    h1 {
      margin-bottom: 20px;
    }

    a {
      display: block;
      margin: 10px 0;
      padding: 10px;
      text-decoration: none;
      background: #4f46e5;
      color: white;
      border-radius: 5px;
    }

    a:hover {
      background: #3730a3;
    }
  </style>
</head>
<body>

<div class="card">
  <h1>TaskFlow</h1>

  <?php if (isset($_SESSION['user'])): ?>
      <a href="/tasks">View Tasks</a>
      <a href="/tasks/create">Create Task</a>
      <a href="/logout">Logout</a>
  <?php else: ?>
      <a href="/login">Login</a>
  <?php endif; ?>
</div>

</body>
</html>
