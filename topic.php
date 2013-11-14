<?php
require_once('common.php');
require_once('zendesk.php');
if(!empty($_GET['id'])){
  $topicId = $_GET['id'];
  $article = zendesk\retrieveArticle($topicId);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $article->title; ?> - ZMC Hotels, Inc</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="styles/styles.css" />
</head>
<body>
  <nav>
    <a href="/" class="back" title="Back to property list">Back to property list</a>
  </nav>
  <div class="content center">
    <?php echo $article->body; ?>
  </div>
</body>
</html>