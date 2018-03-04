<!DOCTYPE html>

<html>

<head>
<!--Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--Import materialize.css-->
<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
<title>3500搜！</title>
	
<!--Let browser know website is optimized for mobile-->
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>

<div class="container">
<h2 align="center">一个可能的3500查词服务</h2>

<form action="simple.php" method="get">
<div class="row">
<div class="col s10">
<input name="reqword" type="text" value=''/>
</div>
<div class="col s2">
<!-- 此处废弃<input type="submit" value="搜索" class="btn waves-effect waves-light red lighten-3"> -->
<button class="btn waves-effect waves-light red lighten-3" type="submit" name="action">搜索</button>
</div></div>
</form>


<h5 align="center">alpha 0.3  2017</h5>



<?php

$word = "'".$_GET['reqword']."'";
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
      echo "Server:Opened database successfully<br>";
   }
   
    $sql =<<<EOF
	SELECT word,part_of_speech,chinese FROM fb_word WHERE word = $word
EOF;

$ret = $db->query($sql);
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	  echo "<hr><br><table class='striped bordered'>";
      echo "<tr><td>所查词：</td><td>". $row['word'] . "</td></tr>";
      echo "<tr><td>词性：</td><td class='centered'>". $row['part_of_speech'] ."</td></tr>";
      echo "<tr><td>中文：</td><td>". $row['chinese'] ."</td></tr></table><br>";
   }
 

 
 $sql =<<<EOF
	SELECT word,detail_json FROM fb_word_detail WHERE word = $word
EOF;

$ret = $db->query($sql);
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
      echo "——————————Test—————————— <br>";
	  echo $row['detail_json'] ."<br><br><br>";
   }

   echo "Server:Operation done successfully<br>Thanks.";
   $db->close();
?>

</div>
<body>

</html>

