<?php
namespace zendesk;
define('API_KEY', 'i9TcS5FtJ4hfCnnkBJGmf6eKrt0DnVPBvjKd35lA');
define('USERNAME', 'fvodden@zmchotels.com');
define('SUBDOMAIN', 'zmchotels');
define('ADMINUSER', 'admin');

require_once('class.zendeskapi.php');

function &getApi(){
  static $obj;

  if(!$obj) $obj = new ZendeskApi(API_KEY, USERNAME, SUBDOMAIN);
  return $obj;
}

function getForumId($forumName){
  $api =& getApi();
  $forumName = strtolower($forumName);

  if($results = $api->call('/forums','','GET')){
    if(!is_object($results) || !isset($results->forums) || !is_array($results->forums)) throw new \Exception('Invalid response from Zendesk API');

    foreach($results->forums as $f){
      if(strtolower($f->name) == $forumName){
        return $f->id;
      }
    }
  }

  return null;
}

/**
 * retrieveArticle
 *
 * @param int $id The ID of the article/topic to retrieve
 * @return array
 * @throws \Exception
 * @return object
 */
function retrieveArticle($id){
  $api =& getApi();
  $results = $api->call(sprintf('/topics/%d', $id),'','GET');
  if(!is_object($results) || !isset($results->topic)) throw new \Exception('Invalid response from Zendesk API');

  return $results->topic;
}

/**
 * retrieveTopics
 *
 * @param string $forumName The name of the forum that topics will be retrieved from
 * @throws \Exception
 * @return array
 */
function retrieveTopics($forumName){
  $topics = array();
  if(null !== ($id = getForumId($forumName))){
    $api =& getApi();

    // retrieve topics from forum
    $results = $api->call(sprintf('/forums/%d/topics', $id), '', 'GET');
    if(!is_object($results) || !isset($results->topics) || !is_array($results->topics)) throw new \Exception('Invalid response from Zendesk API');

    $lastNormalKey = 1000;
    foreach($results->topics as $t){
      $title = $t->title;
      $pieces = explode(' ', $title);
      $propertyId = $lastNormalKey;
      $lastNormalKey++;
      if(is_numeric($pieces[0])){
        $propertyId = intval($pieces[0]);
      }

      $include = true;
      if(!isAdmin()){
        if(strpos($t->body, 'bbh') === false){
          $include = false;
        }
      }

      if($include){
        $topics[$propertyId] = array(
          'title' => $t->title,
          'id' => $t->id
        );
      }
    }
  }

  ksort($topics);
  return $topics;
}

function isAdmin(){
  if(isset($_SERVER['REMOTE_USER']) && $_SERVER['REMOTE_USER'] == ADMINUSER){
    return true;
  }

  return false;
}