<?php
$imgPath = "https://test.sylvanianfamilies.com/assets/includes_gl/img/news/ja-jp/thumbs/";
//挿入処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['img']['tmp_name'], $imgPath.$_FILES['img']['tmp_name']);
    }
    
    // header('Location: index.php?q=creat');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form action="">
		<label for="img">image</label>
		<input type="file" name="img" id="img">
		<button type="submit">うｐ</button>
	</form>
</body>
</html>