<?php 
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('America/Louisville');

$HOST = 'HOSTNAME';
$USER_NAME = 'MYSQL_USERNAME';
$PASSWORD = 'MYSQL_PASSWORD';
$DATABASE_NAME = 'MYSQL_DBNAME';

$rootDir = dirname(dirname(dirname(dirname(__FILE__))));

set_include_path($rootDir . '/htdocs/library/');
require_once 'Zend/Db/Table.php';
require_once 'Zend/Db/Adapter/Abstract.php';
require_once 'Zend/Feed.php';
require_once 'Zend/Feed/Atom.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';

$db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => $HOST,
    'username' => $USER_NAME,
    'password' => $PASSWORD,
    'dbname'   => $DATABASE_NAME
));

$db->query("SET NAMES utf8");

$feed = new Zend_Feed_Atom('http://mix.chimpfeedr.com/0249b-Anonymous-sources');

foreach($feed as $entry) {
    
    $dom_document = new DOMDocument();
    $dom_document->loadHTML($entry->content());
    $dom_xpath = new DOMXpath($dom_document);
    
    $title = $entry->title();
    $date_published = $entry->updated();
    $date_updated = $entry->updated();
    // $url = $entry->link();
    
    // Extracts all links to news articles
    $elements = $dom_xpath->query("//td[2]/font/div[2]/a/@href");
    if (!is_null($elements)) {
    foreach ($elements as $element) {
       // echo "\n[". $element->nodeName. "]";
        $nodes = $element->childNodes;
        foreach ($nodes as $node) {
            // Exclude more links
            if(!substr_count($node->nodeValue,'news/more')) {
                $link = $node->nodeValue;
            }
        }
      }
    }
    
    // Extracts name of publication
    $elements = $dom_xpath->query("//div[2]/font/b/font");
    if (!is_null($elements)) {
    foreach ($elements as $element) {
        //echo "\n[". $element->nodeName. "]";
        $nodes = $element->childNodes;
        foreach ($nodes as $node) {
            // Exclude more links
            if(!substr_count($node->nodeValue,'news/more')) {
                $name = $node->nodeValue;
            }
        }
      }
    }
    
    // Extracts anonymous quote text
    $elements = $dom_xpath->query("//font[last()-2]");
    if (!is_null($elements)) {
    foreach ($elements as $element) {
        $text = '';
        $nodes = $element->childNodes;
        foreach ($nodes as $node) {
            $new_text = $node->nodeValue;
            $text = $text . $new_text;
        }
      }
    }

    $search = array(
        'did not wish to be identified',
        'did not want to be identified',
        'declined to be identified',
        'refused to be identified',
        'an anonymous source',
        'An anonymous source',
        'asked not to be identified',
        'declined to give her name',
        'declined to give his name',
        'on condition of anonymity',
        'on the condition of anonymity',
        'on a condition of anonymity',
        'requested anonymity',
        'asked that his name not be used',
        'asked that her name not be used',
        'refused to give her name',
        'refused to give his name',
        'sources close to',
        'a source close to',
        'A source close to',
        'asked not to be named',
        'declined to be named',
        'refused to be named',
        'wouldn\'t give his name',
        'wouldn\'t give her name',
        'spoke on background',
        'speaking on background',
        'spoke off the record',
        'speaking off the record',
        'speak off the record',
        'comment off the record',
        'would not speak for attribution',
        'declined to speak for attribution',
        'refused to speak for attribution',
        'asked to remain anonymous',
        'the source said',
        'a source said',
        'sources said',
        'according to people familiar with',
        'an official close to',
        'a person briefed on the matter',
        'insisted on anonymity',
        'chose to remain anonymous',
        'people familiar with the matter',
        'a person familiar with the matter',
        'person briefed on the matter',
        'officials briefed on the matter',
        'people briefed on the matter',
        'source briefed on the matter',
        'sources briefed on the matter',
        'executive briefed on the matter',
        'official briefed on the matter',
        'a person close to',
        'administration officials said',
        'an administration official said',
        'a state department official said',
        'state department officials said',
        'a senior administration official said',
        'senior administration officials said',
        'a defense department official said',
        'defense department officials said',
        'an fbi official said',
        'fbi officials said',
        'a justice department official said',
        'justice department officials said',
        'a senior government official said',
        'a government official said',
        'not authorized to speak on the record',
        'according to two people with direct knowledge',
        'according to a person with direct knowledge',
        'according to people with direct knowledge',
        'according to a person close to',
        'according to people close to',
        'according to someone close to',
        'according to one person who was briefed on the matter',
        'according to a person who was briefed on the matter',
        'according to people briefed on the matter',
        'according to people who were briefed on the matter',
        'according to a person familiar with',
        'according to people familiar with',
        'sources with specific knowledge'
        );
    
    $replace = array(
        '<b>did not wish to be identified</b>',
        '<b>did not want to be identified</b>',
        '<b>declined to be identified</b>',
        '<b>refused to be identified</b>',
        '<b>an anonymous source</b>',
        '<b>An anonymous source</b>',
        '<b>asked not to be identified</b>',
        '<b>declined to give her name</b>',
        '<b>declined to give his name</b>',
        '<b>on condition of anonymity</b>',
        '<b>on the condition of anonymity</b>',
        '<b>on a condition of anonymity</b>',
        '<b>requested anonymity</b>',
        '<b>asked that his name not be used</b>',
        '<b>asked that her name not be used</b>',
        '<b>refused to give her name</b>',
        '<b>refused to give his name</b>',
        '<b>sources close to</b>',
        '<b>a source close to</b>',
        '<b>A source close to</b>',
        '<b>asked not to be named</b>',
        '<b>declined to be named</b>',
        '<b>refused to be named</b>',
        '<b>wouldn\'t give his name</b>',
        '<b>wouldn\'t give her name</b>',
        '<b>spoke on background</b>',
        '<b>speaking on background</b>',
        '<b>spoke off the record</b>',
        '<b>speaking off the record</b>',
        '<b>speak off the record</b>',
        '<b>comment off the record</b>',
        '<b>would not speak for attribution</b>',
        '<b>declined to speak for attribution</b>',
        '<b>refused to speak for attribution</b>',
        '<b>asked to remain anonymous</b>',
        '<b>the source said</b>',
        '<b>a source said</b>',
        '<b>sources said</b>',
        '<b>according to people familiar with</b>',
        '<b>an official close to</b>',
        '<b>a person briefed on the matter</b>',
        '<b>insisted on anonymity</b>',
        '<b>chose to remain anonymous</b>',
        '<b>people familiar with the matter</b>',
        '<b>a person familiar with the matter</b>',
        '<b>person briefed on the matter</b>',
        '<b>officials briefed on the matter</b>',
        '<b>people briefed on the matter</b>',
        '<b>source briefed on the matter</b>',
        '<b>sources briefed on the matter</b>',
        '<b>executive briefed on the matter</b>',
        '<b>official briefed on the matter</b>',
        '<b>a person close to</b>',
        '<b>administration officials said</b>',
        '<b>an administration official said</b>',
        '<b>a state department official said</b>',
        '<b>state department officials said</b>',
        '<b>a senior administration official said</b>',
        '<b>senior administration officials said</b>',
        '<b>a defense department official said</b>',
        '<b>defense department officials said</b>',
        '<b>an fbi official said</b>',
        '<b>fbi officials said</b>',
        '<b>a justice department official said</b>',
        '<b>justice department officials said</b>',
        '<b>a senior government official said</b>',
        '<b>a government official said</b>',
        '<b>not authorized to speak on the record</b>',
        '<b>according to two people with direct knowledge</b>',
        '<b>according to a person with direct knowledge</b>',
        '<b>according to people with direct knowledge</b>',
        '<b>according to a person close to</b>',
        '<b>according to people close to</b>',
        '<b>according to someone close to</b>',
        '<b>according to one person who was briefed on the matter</b>',
        '<b>according to a person who was briefed on the matter</b>',
        '<b>according to people briefed on the matter</b>',
        '<b>according to people who were briefed on the matter</b>',
        '<b>according to a person familiar with</b>',
        '<b>according to people familiar with</b>',
        '<b>sources with specific knowledge</b>'
        );
    
     // Only store examples with anonymous phrasing
    foreach($search as $pattern){
        if(preg_match("/$pattern/",$text)) {
            $sql = "select url from anonymous where url = ?";
            $result = $db->fetchAll($sql, $link);
            $rowCount = count($result);
            // Only store examples we don't already have
            if($rowCount==0){
                $data = array(
                    'title'             => $title,
                    'summary'           => utf8_decode(str_replace($search, $replace, $text)),
                    'url'               => $link,
                    'outlet'            => $name,
                    'date_published'    => $date_published,
                    'date_updated'      => $date_updated,
                );
                try {
                    $db->insert('anonymous', $data);
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
       }    
    }
}

echo "Finished\n";