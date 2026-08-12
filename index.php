<?php
  session_start();

  $config = require_once __DIR__ . '/config/config.php';

  $site = $config['site'];

  $errors = $_SESSION['errors'] ?? null;
  $success = $_SESSION['success'] ?? null;

  $old = $_SESSION['old'] ?? [];

  $name = $old['name'] ?? '';
  $email = $old['email'] ?? '';
  $message = $old['message'] ?? '';

  unset($_SESSION['errors']);
  unset($_SESSION['success']);
  unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?= htmlspecialchars($site['title'], ENT_QUOTES, 'UTF-8') ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link
      rel="stylesheet"
      type="text/css"
      href="./css/main.css"
    />

  </head>
  <body>
    <div >
     <h1><?= htmlspecialchars($site['domain'], ENT_QUOTES, 'UTF-8') ?></h1>
     <p><?= htmlspecialchars($site['description'], ENT_QUOTES, 'UTF-8') ?></p>

    <?php if ($errors): ?>

        <div class="popup error" id="popup">

            <strong>Error:</strong>

            <ul>
                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </li>

                <?php endforeach; ?>
            </ul>

        </div>

    <?php elseif ($success): ?>

        <div class="popup success" id="popup">
            <strong>Success:</strong>
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>

    <?php endif; ?>

    <form action="./actions/submit.php" method="POST">
        <input 
          type="text" 
          name="name" 
          placeholder="Your name..." 
          value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
          required />
        <input
          type="email"
          name="email"
          placeholder="Your email..."
          value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
          required
        >
        <textarea
            name="message"
            rows="6"
            cols="50"
            placeholder="Your message..."
            required
        ><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea>
        <button type="submit">Send</button>
    </form>
    </div>
    <script src="./js/index.js"></script>
  </body>
</html>
