<?php
  session_start();
  $config = require __DIR__ . '/config/config.php';

  // check ip statistics && CUT SHORTLY
  require __DIR__ . '/actions/ip_statistics.php';
  $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  if(checkBlacklist($ip, $config)){
    var_dump("YOUR IP WAS BLACKLISTED. TOO MANY ATTEMPTS");
    die;
  }

  // initiate resend messages worker:
  require __DIR__ . '/actions/resend_messages.php';
  check_worker_state($config);
  
  $state = require __DIR__ . '/actions/state.php';
  $lang = require __DIR__ . '/actions/language.php';

  $local_project_name = $config['site']['local_url'];
  $domain = $_SERVER['HTTP_HOST'] === 'localhost' ? "localhost/$local_project_name" : $_SERVER['HTTP_HOST'];

  require __DIR__ . '/actions/available_hosts.php';

  if(!checkWhitelist($domain, $config)){
    var_dump("YOUR DOMAIN $domain IS NOT WHITELISTED. I ONLY SELL MY DOMAINS");
    die;
  }

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($domain, ENT_QUOTES, 'UTF-8') ?>
    </title>

    <link
        rel="stylesheet"
        type="text/css"
        href="./css/main.css"
    >
</head>

<body>

    <div class="language-picker">

        <div class="language-picker">

            <?php foreach ($supportedLanguages as $language): ?>

                <form action="./actions/language.php" method="POST">

                    <input
                        type="hidden"
                        name="language"
                        value="<?= htmlspecialchars($language, ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <button
                        type="submit"
                        class="<?= $language === $currentLanguage ? 'active' : '' ?>"
                    >
                        <?= strtoupper(htmlspecialchars($language, ENT_QUOTES, 'UTF-8')) ?>
                    </button>

                </form>

            <?php endforeach; ?>

        </div>

    </div>

    <main class="page">

        <section class="card">

            <div class="domain-icon">
                ✦
            </div>

            <div class="domain-label">
                <?= htmlspecialchars($lang['domain_for_sale'], ENT_QUOTES, 'UTF-8') ?>
            </div>

            <h1>
                <?= htmlspecialchars($domain, ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="description">
                 <?php if ($state === State::NORMAL): ?>
                    <?= htmlspecialchars($lang['description'], ENT_QUOTES, 'UTF-8') ?>
                <?php elseif ($state === State::LIMITED): ?>
                    <?= htmlspecialchars($lang['limited_description'], ENT_QUOTES, 'UTF-8') ?>
                <?php endif; ?>
            </p>


            <?php if ($errors): ?>

                <div class="popup error" id="popup">

                    <div class="popup-title">
                        <?= htmlspecialchars($lang['error'], ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <ul>
                        <?php foreach ($errors as $error): ?>

                            <li>
                                <?= htmlspecialchars(
                                    $error,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </li>

                        <?php endforeach; ?>
                    </ul>

                </div>

            <?php elseif ($success): ?>

                <div class="popup success" id="popup">

                    <div class="popup-title">
                        <?= htmlspecialchars($lang['success'], ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <div>
                        <?= htmlspecialchars(
                            $success,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                </div>

            <?php endif; ?>


            <div class="divider"></div>

            <?php if ($state === State::NORMAL): ?>

                <div class="form-heading">
                    <?= htmlspecialchars($lang['make_offer'], ENT_QUOTES, 'UTF-8') ?>
                </div>

                <div class="form-description">
                    <?= htmlspecialchars($lang['form_description'], ENT_QUOTES, 'UTF-8') ?>
                </div>

            
                <form
                    id="proposalForm"
                    action="./actions/submit.php"
                    method="POST"
                    class="offer-form"
                >

                    <div class="field">

                        <label for="name">
                            <?= htmlspecialchars($lang['name'], ENT_QUOTES, 'UTF-8') ?>
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            placeholder="<?= htmlspecialchars($lang['name_placeholder'], ENT_QUOTES, 'UTF-8') ?>"
                            value="<?= htmlspecialchars(
                                $name,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            autocomplete="name"
                            required
                        >

                    </div>


                    <div class="field">

                        <label for="email">
                            <?= htmlspecialchars($lang['email'], ENT_QUOTES, 'UTF-8') ?>
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            placeholder="<?= htmlspecialchars($lang['email_placeholder'], ENT_QUOTES, 'UTF-8') ?>"
                            value="<?= htmlspecialchars(
                                $email,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            autocomplete="email"
                            required
                        >

                    </div>


                    <div class="field">

                        <label for="message">
                            <?= htmlspecialchars($lang['proposal'], ENT_QUOTES, 'UTF-8') ?>
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            placeholder="<?= htmlspecialchars($lang['message_placeholder'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        ><?= htmlspecialchars(
                            $message,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?></textarea>

                    </div>


                    <button type="submit">

                        <span>
                            <?= htmlspecialchars($lang['send'], ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <span class="button-arrow">
                            →
                        </span>

                    </button>

                </form>
            <?php endif; ?>


            <div class="privacy-note">
                <?= htmlspecialchars($lang['privacy'], ENT_QUOTES, 'UTF-8') ?>
            </div>

        </section>


        <footer>
            © <?= date('Y') ?>
            <?= htmlspecialchars(
                $domain,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </footer>

    </main>

    <div class="cookie-consent" id="cookieConsent" hidden>
        <div class="cookie-content">
            <div>
                <strong>Cookies</strong>
                <p>
                    We use cookies to remember your preferences and
                    improve your experience.
                </p>
            </div>

            <button type="button" id="acceptCookies">
                Accept
            </button>
        </div>
    </div>

    <script src="./js/index.js"></script>

</body>
</html>