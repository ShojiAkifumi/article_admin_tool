<?php
require './db.php';

//カテゴリー一覧取得
$stmt = $pdo->query("SELECT * FROM ck_news_categories WHERE country_id = 42");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

//挿入処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_lang1 = $_POST['title_lang1'];
    $body_lang1 = $_POST['body_lang1'];
    $news_category_id = $_POST['news_category_id'];
    $nsort = 0;
    $public_date = $_POST['public_date'];
    $public_end_date = $_POST['public_end_date'] ? $_POST['public_end_date'] : null;
    $country_id = 42;
    $created = date('Y-m-d\TH:i:s');
    $imgPath = "/var/www/vhosts/test.cdn-org.sylvanianfamilies/includes_gl/img/news/ja-jp/thumbs/";


    $stmt = $pdo->prepare("INSERT INTO ck_news (
        title_lang1,
        title_lang2,
        title_lang3,
        title_lang4,
        body_lang1,
        body_lang2,
        body_lang3,
        body_lang4,
        news_category_id,
        nsort,
        public_date,
        public_end_date,
        is_public,
        country_id,
        created,
        modified
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $result= $stmt->execute([
        $title_lang1,
        "", //title_lang2
        "", //title_lang3
        "", //title_lang4
        $body_lang1,
        "", //body_lang2
        "", //body_lang3
        "", //body_lang4
        $news_category_id,
        $nsort,
        $public_date,
        $public_end_date,
        0, //is_public
        $country_id,
        $created, //created
        $created //modified
    ]);

    //PDOオブジェクトからINSERT直後のIDを取得
    $news_id = $pdo->lastInsertId();
    // 画像アップロード処理 名前被りは上書き
    $image1 = $_POST['image1'];

    $stmt = $pdo->prepare("INSERT INTO ck_news_images (
        news_id,
        field_name,
        filename,
        created
    ) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $news_id,
        "image1",
        $image1,
        $created
    ]);

    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['image1']['tmp_name'], $imgPath.$image1);
    }
    $image2 = $_POST['image2'];
    $stmt = $pdo->prepare("INSERT INTO ck_news_images (
        news_id,
        field_name,
        filename,
        created
    ) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $news_id,
        "image2",
        $image2,
        $created
    ]);
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['image2']['tmp_name'], $imgPath.$image2);
    }
    
    header('Location: index.php?q=creat');
    exit;
}

//記事コピー処理
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM ck_news WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$article) {
        die('記事が見つかりません。');
    }
    
    $stmt = $pdo->prepare("SELECT * FROM ck_news_images WHERE news_id = ? ORDER BY field_name ASC");
    $stmt->execute([$id]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$title_lang1 = isset($article) ? htmlspecialchars($article['title_lang1']) : "";
$body_lang1 = isset($article) ? htmlspecialchars($article['body_lang1']) : "";
$news_category_id = isset($article) ? htmlspecialchars($article['news_category_id']) : "";
$image1 = isset($images[0]) ? $images[0]['filename'] : "";
$image2 = isset($images[1]) ? $images[1]['filename'] : "";
$public_date = isset($article) ? $article['public_date'] : date('Y-m-d');
$public_end_date = isset($article) ? $article['public_end_date'] : "";

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事作成</title>
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
                <h1>記事作成</h1>
                <div class="right-align"><a class="waves-effect btn-flat" href="./">戻る</a></div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col m9 s12">
                            <div class="contents-board">
                                <div class="input-field">
                                    <input type="text" name="title_lang1" id="title_lang1" value="<?=$title_lang1?>" required>
                                    <label for="title_lang1">タイトル</label>
                                </div>
                            </div>
                            <div class="contents-board">
                                <textarea name="body_lang1" id="body_lang1" rows="10" class="content-textarea" placeholder="本文"><?= $body_lang1 ?></textarea>
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>画像 1<i class="material-icons right">image</i></span>
                                        <input type="file" accept="image/*" name="image1" id="fileImage1">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" name="image1" value="<?=$image1?>" id="image1">
                                        <button class="btn-flat waves-effect waves-light red-text img-delete-btn" id="img-delete-btn-1"><i class="material-icons">clear</i></button>
                                    </div>
                                </div>
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>画像 2<i class="material-icons right">image</i></span>
                                        <input type="file" accept="image/*" name="image2" id="fileImage2">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" name="image2" value="<?=$image2?>" id="image2">
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
                                        <option value="<?=$category['id']?>" <?=$category['id']==$news_category_id?"selected":""?>><?=$category['name_lang1']?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <label>カテゴリー</label>
                                </div>
                            </div>
                            <div class="contents-board">
                                <div class="input-field">
                                    <label for="public_date">掲載日</label>
                                    <input type="date" name="public_date" id="public_date" value="<?=$public_date?>" required>
                                </div>
                                <div class="input-field">
                                    <label for="public_end_date">掲載終了日</label>
                                    <input type="date" name="public_end_date" id="public_end_date" value="<?=$public_end_date?>">
                                </div>
                            </div>
                            <div class="publish-btn"><button class="btn-large waves-effect waves-light" type="submit" name="action"><span>テスト投稿<i class="material-icons right">send</i></span></button></div>
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
