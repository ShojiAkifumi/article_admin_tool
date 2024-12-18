<?php
require './db.php';

$stmt = $pdo->query("SELECT * FROM ck_news WHERE country_id = 42 ORDER BY created DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投稿記事一覧</title>
    <meta name="robots" content="noindex">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="side-bar" id="side-bar">
            <div class="top-logo">
            <a href="./"><img src="./assets/img/logo_sp.png" alt="SF"><h1>投稿管理ツール</h1></a>
            </div>
            <div class="nav">
                <a href="./">記事一覧</a>
                <a href="./add.php">記事作成</a>
                <a href="#" onclick="window.open('https://test.sylvanianfamilies.com/ja-jp/news/', 'プレビュー確認', 'width=1280,height=800'); return false;">プレビュー確認</a>
                <a href="#" onclick="window.open('https://test.sylvanianfamilies.com/ja-jp/news-honban/', '本番確認', 'width=1280,height=800'); return false;">本番確認</a>
                <a href="./manual.pdf" target="_blank">マニュアル</a>
            </div>
        </div>
        <div id="hamburger"><i class="material-icons i-menu">menu</i><i class="material-icons i-close">close</i></div>
        <main>
            <div class="container">
                <h1>記事一覧</h1>
                <div class="right-align"><a class="waves-effect waves-light btn" href="./add.php"><i class="material-icons right">add</i>記事作成</a></div>
                <div class="contents-board">
                    <?php foreach ($articles as $article): ?>
                    <div class="article-content">
                        <div class="article-text">
                            <a href="edit.php?id=<?= $article['id'] ?>"><h2><?= strip_tags($article['title_lang1']) ?></h2></a>
                            <?php
                            $limit = 150;
                            $content = strip_tags($article['body_lang1']);
                            if(mb_strlen($content) > $limit) { 
                                $content = mb_strcut($content,0,$limit).' ･･･';
                            }
                            ?>
                            <p><?=$content?></p>
                            <p>
                                <small>掲載日 : <?= $article['public_date'] ?></small>
                                <?php if(isset($article['public_end_date'])):?>
                                    <br><small>掲載終了日 : <?= $article['public_end_date'] ?></small>
                                <?php endif ?>
                            </p>
                        </div>
                        <div class="article-btns">
                            <a class="waves-effect waves-light btn" href="edit.php?id=<?= $article['id'] ?>">編集<i class="material-icons left">create</i></a>
                            <div class="switch">
                                <label>
                                    非公開
                                    <input
                                        type="checkbox"
                                        onchange="togglePublic(<?= $article['id'] ?>, this)"
                                        <?= $article['is_public'] ? 'checked' : '' ?>
                                    >
                                    <span class="lever"></span>
                                    本公開
                                </label>
                            </div>
                            <a class="waves-effect btn-flat teal-text" href="add.php?id=<?= $article['id'] ?>">コピー<i class="material-icons right copy-icon">content_copy</i></a>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.3.2/dist/confetti.browser.min.js"></script>
    <script src="./assets/js/hamburger.js"></script>
    <script src="./assets/js/confetti.js"></script>
    <script src="./assets/js/togglePublic.js"></script>
    <?php if(isset($_GET['q'])): ?>
    <script>
        const navigationEntries = performance.getEntriesByType("navigation");
        if (navigationEntries.length > 0) {
            const navigationType = navigationEntries[0].type;
            if (navigationType !== "reload" && navigationType !== "back_forward") {
            <?php switch ($_GET['q']){
                case 'creat':
                    echo 'M.toast({html: "記事を追加しました", displayLength: 2000});';
                    echo 'createdConfetti()';
                    break;
                case 'edit':
                    echo 'M.toast({html: "記事を更新しました", displayLength: 2000});';
                    break;
                case 'del':
                    echo 'M.toast({html: "記事を削除しました", displayLength: 2000});';
                    break;
                default: break;
            } ?>
            }
        }
    </script>
    <?php endif ?>
</body>
</html>
