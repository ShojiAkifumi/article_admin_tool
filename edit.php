<?php
require './db.php';

if (!isset($_GET['id'])) {
    die('記事IDが指定されていません。');
}

$imgPath = "/var/www/vhosts/test.cdn-org.sylvanianfamilies/includes_gl/img/news/ja-jp/thumbs/";

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM ck_news WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die('記事が見つかりません。');
}

//カテゴリー一覧取得
$stmt = $pdo->query("SELECT * FROM ck_news_categories WHERE country_id = 42");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

//画像取得
$stmt = $pdo->prepare("SELECT * FROM ck_news_images WHERE news_id = ? ORDER BY field_name ASC");
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 記事削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM ck_news WHERE id = ?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM ck_news_images WHERE news_id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?q=del');
    exit;
}

// 記事編集処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $title_lang1 = $_POST['title_lang1'];
    $body_lang1 = $_POST['body_lang1'];
    $news_category_id = $_POST['news_category_id'];
    $image1 = $_POST['image1'] ? $_POST['image1'] : "";
    $image2 = $_POST['image2'] ? $_POST['image2'] : "";
    $nsort = 0;
    $public_date = $_POST['public_date'];
    $public_end_date = $_POST['public_end_date'] ? $_POST['public_end_date'] : null;
    $country_id = 42;
    $modified = date('Y-m-d\TH:i:s');


    $stmt = $pdo->prepare("UPDATE ck_news SET
        title_lang1 = ?,
        title_lang2 = ?,
        title_lang3 = ?,
        title_lang4 = ?,
        body_lang1 = ?,
        body_lang2 = ?,
        body_lang3 = ?,
        body_lang4 = ?,
        news_category_id = ?,
        nsort = ?,
        public_date = ?,
        public_end_date = ?,
        is_public = ?,
        country_id = ?,
        created = ?,
        modified = ?
        WHERE id = ?"
    );
    $stmt->execute([
        $title_lang1,
        $article['title_lang2'],
        $article['title_lang3'],
        $article['title_lang4'],
        $body_lang1,
        $article['body_lang2'],
        $article['body_lang3'],
        $article['body_lang4'],
        $news_category_id,
        $nsort,
        $public_date,
        $public_end_date,
        $article['is_public'],
        $country_id,
        $article['created'],
        $modified,
        $article['id']
    ]);


    // 画像アップロード処理 名前被りは上書き
    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['image1']['tmp_name'], $imgPath.$image1);
    }
    $stmt = $pdo->prepare("UPDATE ck_news_images SET filename = ? WHERE id = ?");
    $stmt->execute([$image1, $images[0]['id']]);
    
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['image2']['tmp_name'], $imgPath.$image2);
    }
    $stmt = $pdo->prepare("UPDATE ck_news_images SET filename = ? WHERE id = ?");
    $stmt->execute([$image2, $images[1]['id']]);
    
    header('Location: index.php?q=edit');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事編集</title>
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
                <h1>記事編集</h1>
                <form action="" method="post" onsubmit="return confirm('本当に削除しますか？');">
                    <div class="right-align"><a class="waves-effect btn-flat" href="./">戻る</a><button type="submit" name="delete" class="waves-effect waves-red btn-flat red-text <?=$article['is_public']?'disabled':''?>">削除<i class="material-icons right">delete_forever</i></button></div>
                </form>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col m9 s12">
                            <div class="contents-board">
                                <div class="input-field">
                                    <input type="text" name="title_lang1" id="title_lang1" value="<?= htmlspecialchars($article['title_lang1']) ?>" required class="content-title">
                                    <label for="title_lang1">タイトル</label>
                                </div>
                            </div>
                            <div class="contents-board">
                                <textarea name="body_lang1" id="body_lang1" rows="10" class="content-textarea" placeholder="本文"><?= htmlspecialchars($article['body_lang1']) ?></textarea>
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>画像 1<i class="material-icons right">image</i></span>
                                        <input type="file" accept="image/*" name="image1" id="fileImage1">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" name="image1" value="<?=$images[0]['filename']?>" id="image1">
                                        <button class="btn-flat waves-effect waves-light red-text img-delete-btn" id="img-delete-btn-1"><i class="material-icons">clear</i></button>
                                    </div>
                                </div>
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>画像 2<i class="material-icons right">image</i></span>
                                        <input type="file" accept="image/*" name="image2" id="fileImage2">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" name="image2" value="<?=$images[1]['filename']?>" id="image2">
                                        <button class="btn-flat waves-effect waves-light red-text img-delete-btn" id="img-delete-btn-2"><i class="material-icons">clear</i></button>
                                    </div>
                                </div>
                                <p><small class="grey-text">画像アップロード先：/var/www/vhosts/test.cdn-org.sylvanianfamilies/includes_gl/img/news/ja-jp/thumbs/</small></p>
                            </div>
                        </div>
                        <div class="col m3 s12">
                            <div class="contents-board">
                                <div class="input-field">
                                    <select name="news_category_id">
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?=$category['id']?>" <?=$category['id']==$article["news_category_id"]?"selected":""?>><?=$category['name_lang1']?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <label>カテゴリー</label>
                                </div>
                            </div>
                            <div class="contents-board">
                                <div class="input-field">
                                    <label for="public_date">掲載日</label>
                                    <input type="date" name="public_date" id="public_date" value="<?= $article['public_date'] ?>" required>
                                </div>
                                <div class="input-field">
                                    <label for="public_end_date">掲載終了日</label>
                                    <input type="date" name="public_end_date" id="public_end_date" value="<?= $article['public_end_date'] ?>">
                                </div>
                            </div>
                            <div class="publish-btn"><button class="btn-large waves-effect waves-light" type="submit" name="update">保存<i class="material-icons right">save</i></button></div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="./assets/js/hamburger.js"></script>
    <script src="./assets/js/deleteImage.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');    
            var instances = M.FormSelect.init(elems);
        });
    </script>
</body>
</html>
