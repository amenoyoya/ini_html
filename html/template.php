<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <meta name="descrption" content="<?= $description ?>">
    <?php /* meta keywords はSEO的に意味がないと言われているが一応。。。 */ ?>
    <meta name="keywords" content="<?= implode(',', $keywords) ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
</head>
<body>
    <section class="section">
        <div class="container">
            <?= $content ?>
        </div>
    </section>
</body>
</html>