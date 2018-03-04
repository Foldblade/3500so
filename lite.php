<!DOCTYPE html>
<html>
<head>
<title>3500搜！</title>
</head>

<body>
<h1>3500 So !</h1>
<h3>一个可能的3500查词服务</h3>
<p>这是一个在alpha版本上修改、以供kindle使用的Lite版</p>
<p>主站网址后加"lite.php"即可直接访问本页面。</p>
<p>此处仅支持查看最简单的单词释义。</p>
<p><a href='index.php'>返回完整版</a></p>
<form action="lite.php" method="get">
<input name="reqword" type="text" value=''/>
<input type="submit" value="搜索" >
</form>
<?php

if(is_array($_GET)&&count($_GET)>0) //先判断是否通过get传值了
    {
        if(isset($_GET['reqword'])) //是否存在"reqword"的参数
{
    $word = "'".$_GET['reqword']."'"; //存在

   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('.words.db');
      }
   }
   $db = new MyDB();
   if(!$db){
      echo $db->lastErrorMsg();
   } else {
      // echo "Server:Opened database successfully<br>";
   }
   
    $sql =<<<EOF
	SELECT word,part_of_speech,chinese FROM fb_word WHERE word = $word
EOF;

$ret = $db->query($sql);
if (empty($ret->fetchArray(SQLITE3_ASSOC))){
    echo <<<EOF
    <p><b>你似乎来到了3500词所不存在的荒原。<br>数据库里没有这个词！<br>您确定这是3500书中的单词吗？</b></p>
EOF;

}
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	  echo "<hr><br><table border=1>";
      echo "<tr><td>所查词：</td><td>". $row['word'] . "</td></tr>";
      echo "<tr><td>词性：</td><td>". $row['part_of_speech'] ."</td></tr>";
      echo "<tr><td>中文：</td><td>". $row['chinese'] ."</td></tr></table><br>";
    }
    // echo "Server:Operation done successfully<br>Thanks.";
    $db->close();
    }
}
?>


<body>

</html>

