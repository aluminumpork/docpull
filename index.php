<?php
require_once('common.php');
require_once('zendesk.php');
$topics = zendesk\retrieveTopics('Property Knowledge Base');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Knowledge Base Articles</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles/styles.css" />
  </head>
  <body>
    <nav></nav>
    <div class="content center">
      <h1>List of properties</h1>
      <ul>
      <?php foreach($topics as $t): ?>
        <li><a href="topic.php?id=<?php echo $t['id']; ?>"><?php echo $t['title']; ?></a></li>
      <?php endforeach; ?>
      </ul>
    </div>
  </body>
</html>