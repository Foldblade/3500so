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

<?php
if(is_array($_GET)&&count($_GET)>0) //先判断是否通过get传值了
{ 
    echo <<<EOF
    <div class="navbar-fixed">
        <nav class="nav-extended">
            <div class="nav-wrapper">
                <a href="index.php" class="brand-logo center">3500 So !</a>
            </div>
        </nav>
    </div>
EOF;
} else {
        echo <<<EOF
<div class="section no-pad-bot red lighten-1" id="index-banner">
    <div class="container">
        <a href="index.php"><h1 class="header center-on-small-only grey-text text-lighten-5" >3500 So !</h1></a>
    <div class="row center">
        <h4 class="header col s12 light center  grey-text text-lighten-5">一个可能的3500词查词服务</h4>
    </div>
    <div class="row center">
        <a href="#search" class="btn-large waves-effect waves-light red lighten-3">开始使用</a>
    </div>
    <div class="row center"><a class="red-text text-lighten-4" href="lite.php">为Kindle设计的简洁版</a></div>
    <br>
EOF;
}

?>
    </div>
</div>

<div class="container">
    <br>
    <br>
    <form action="index.php" method="get">
        <div class="row">
            <div class="col s8 m9 l10">
                <input name="reqword" type="text" class="autocomplete" value=''/>
            </div>
            <div class="col s4 m3 l2">
                <button class="btn waves-effect waves-light red lighten-2" id="search" type="submit" name="action">搜索</button>
            </div>
        </div>
    </form>

<?php

function search($word){

    class MyDB extends SQLite3{
        function __construct(){
            $this->open('.words.db');
        }
    }
    $db = new MyDB();
    if(!$db){
        echo $db->lastErrorMsg();
    } else {
        // echo "<blockquote>Server: Opened the database successfully.<br/></blockquote>";
    }


    $sql =<<<EOF
	SELECT detail_json FROM fb_word_detail WHERE word = $word
EOF;

$ret = $db->query($sql);
if (empty($ret->fetchArray(SQLITE3_ASSOC))){
    echo <<<EOF
    <i class="large material-icons">error</i>
    <p class="flow-text">你似乎来到了3500词所不存在的荒原。<br>数据库里没有这个词！<br>您确定这是3500书中的单词吗？</p>
EOF;

}
    while($row = $ret->fetchArray(SQLITE3_ASSOC)){
        $detail = json_decode($row['detail_json'],true);

        echo <<<EOF
    <div class="row">
      <div class="col s12 m10 l7">
        <div class="card horizontal grey lighten-5">
          <div class="card-content">
EOF;
        echo '<p class="flow-text grey-text text-darken-4"><b>' .$detail["word"].'</b></p>';


        if($detail["part_of_speech"] != ''){
            echo '<p><span class="light-blue-text text-darken-4">'.$detail["part_of_speech"].'</span>&nbsp;&nbsp;&nbsp;';
        }

        if($detail["en_phonetic_symbols"] != ''){
            echo '<span class="grey-text text-darken-2">'."英&nbsp;[". $detail["en_phonetic_symbols"].']&nbsp;&nbsp;&nbsp;';
        }
        
        if($detail["usa_phonetic_symbols"] != ''){
            echo "美&nbsp;[". $detail["usa_phonetic_symbols"].']</span></p>';
        }

        if($detail["lv_frequency"] != 0){
            echo '<div class="chip">'."词频：". $detail["lv_frequency"].'</div>';
        }

        if($detail["lv_write"] != 0){
            echo '<div class="chip">'."书面：". $detail["lv_write"].'</div>';
        }

        if($detail["lv_speak"] != 0){
            echo '<div class="chip">'."口语：". $detail["lv_speak"].'</div>';
        }

        if($detail["lv_read"] != 0){
            echo '<div class="chip">'."阅读：". $detail["lv_read"].'</div>';
        }

        if($detail["use_method"] != ''){
            echo '<p><span class="grey-text text-darken-3">'.$detail["use_method"].'</span></p>';
        }

        if($detail["antonym"] != ''){
            echo '<blockquote><i class="small material-icons">compare_arrows</i>反义词：'. $detail["antonym"].'</blockquote>';
        }

        if($detail["synonyms"] != ''){
            echo '<blockquote><i class="small material-icons">insert_link</i>同义词：'.$detail["synonyms"].'</blockquote>';
        }

        if($detail["family_word"] != ''){
            echo '<blockquote><i class="small material-icons">people_outline</i>同族词：'.$detail["family_word"].'</blockquote>';
        }

        echo '</div></div></div></div>';
        

        if (function_exists('get_example')) {} else{
            function get_example($gy_example) {
                foreach($gy_example as $example){
                    if($example["highlight"] != ''){
                        echo '<p>'.str_replace($example["highlight"], "<b>". $example["highlight"] ."</b>", $example["english"]).'</p>';
                    } else {
                        if($example["english"] != ''){
                            echo '<p>'.$example["english"].'</p>';
                        } else {
                            echo '<p>'.'[数据库中未包含对应示例]'.'</p>';
                        }
                        
                    }
    
                    if($example["chinese"] != ''){
                        echo '<p>'.$example["chinese"].'</p>';
                    } else {
                        echo '<p>'.'[数据库中未包含对应示例]'.'</p>';
                    }
                    
                    if($example["source"] != ''){
                        echo '<p class="light-blue-text text-accent-2 right-align">'.$example["source"].'</p>';
                    }
                    
                    if($example["description"] != ''){
                        echo '<p>'.$example["description"].'</p>';
                    }

                }
                unset($example);
            }
        }

        if (function_exists('get_biscrimination')) {} else{
            function get_biscrimination($gy_biscrimination) {
                foreach($gy_biscrimination as $biscrimination){
                    if($biscrimination["words"] != "" ){
                        echo '<p><b>'.$biscrimination["words"].'</b></p>';
                        echo '<p>'.$biscrimination["paraphrase"].'</p>';
                        if($biscrimination["gy_biscrimination_word"] != json_decode('[]',true)){
                            echo '<ul class="collapsible" data-collapsible="accordion">';
                            foreach($biscrimination["gy_biscrimination_word"] as $biscrimination_word){
                                echo '<li><div class="collapsible-header">'.$biscrimination_word["word"].'<br/>';
                                echo $biscrimination_word["description"].'</div>';
                                echo '<div class="collapsible-body">';
                                get_example($biscrimination_word["gy_example"]);
                                echo '</div></li>';
                            }
                        }
                        echo "</ul>";
                    }
                    
                }
                unset($biscrimination);
            }
        }

        echo <<<EOF
<div class="row">
<div class="col s12 m11 l10 offset-m1 offset-l1">
<ul class="collapsible" data-collapsible="accordion">
EOF;

        if($detail["gy_paraphrase"] != ''){
            foreach($detail["gy_paraphrase"] as $paraphrase){
                echo  '<li><div class="collapsible-header">';

                if($paraphrase["frequency_name"] != ''){
                    echo '<div class="chip">'.$paraphrase["frequency_name"].'</div>';
                }
                

                if($paraphrase["english"] != ''){
                    if($paraphrase["chinese"] != ''){
                        echo '<b class="flow-text">'.$paraphrase["chinese"];
                    } 
                    echo '<span class="flow-text light-blue-text text-darken-4">'.$paraphrase["english"].'</span></b>';
                } else{
                    if($paraphrase["chinese"] != ''){
                        echo '<b class="flow-text">'.$paraphrase["chinese"].'</b>';
                    } else {
                        echo '<div class="chip">展开查看详情</div><b class="flow-text">此释义【不可描述】TvT</b>';
                    }
                }

                echo '</div>';
                
                echo '<div class="collapsible-body">';
                
                if($paraphrase["antonym"] != ''){
                    echo '<blockquote><i class="small material-icons">compare_arrows</i>反义词：'.$paraphrase["antonym"].'</blockquote>';
                }

                if($paraphrase["synonyms"] != ''){
                    echo '<blockquote><i class="small material-icons">insert_link</i>同义词：'. $paraphrase["synonyms"].'</blockquote>';
                }

                if($paraphrase["gy_sentential_form"] != ''){ # 句型搭配
                    echo '<ol>';
                    foreach($paraphrase["gy_sentential_form"] as $sentential_form){
                        echo '<li>'.$sentential_form["sentential_form"].'</li>';
                        get_example($sentential_form["gy_example"]);
                    }
                    echo '</ol>';
                } 

                get_example($paraphrase["gy_example"]);
                
                if($paraphrase["gy_notes"] != json_decode('[]',true)){
                    foreach($paraphrase["gy_notes"] as $notes){
                        echo '<blockquote><i class="small material-icons">error_outline</i>注意:'.$notes["notes"].'</blockquote>';
    
                    }
                    unset($notes);
                }

                echo '</div></li>';

            }
        }
            
            echo '</ul></div></div>';

            

            if($detail["gy_exam_link"] != json_decode('[]',true)){
                echo <<<EOF
<div class="row">
<div class="col s12 m11 l10 offset-l1 offset-m1">   
<div class="card">
<div class="card-content">
<span class="card-title activator grey-text text-darken-4"><i class="material-icons">link</i>高考链接
<i class="material-icons right">more_vert</i></span>
<ol>
EOF;
                foreach($detail["gy_exam_link"] as $exam_link){
                    if($exam_link["answer_type"] == 0 ){ # 填空
                        echo '<li><p>'.$exam_link["subject"].'</p>';
                        echo '<p class="light-blue-text text-accent-2 right-align">'.$exam_link["source"].'</p></li>';
                    } elseif($exam_link["answer_type"] == 3 ){ # 选择
                        echo '<li><p>'.$exam_link["subject"].'</p>';
                        echo '<p>A.'.$exam_link["answer_a"].'&nbsp;&nbsp;B.'.$exam_link["answer_b"].
                        '&nbsp;&nbsp;C.'.$exam_link["answer_c"].'&nbsp;&nbsp;D.'.$exam_link["answer_d"].'</p>';
                        echo '<p class="light-blue-text text-accent-2 right-align">'.$exam_link["source"].'</p></li>';
                    } else{
                        # 这什么规则我可没见过，题目？也免了。
                    }
                }

                echo <<<EOF
</ol>
</div>
<div class="card-reveal">
<span class="card-title grey-text text-darken-4">对对答案吧<i class="material-icons right">close</i></span>
<ol>
EOF;
                foreach($detail["gy_exam_link"] as $exam_link){
                    if($exam_link["answer_type"] == 0 ){ # 填空
                        echo '<li><p>'.$exam_link["subject"].'</p>';
                        echo '<p class="light-blue-text text-accent-2 right-align">'.$exam_link["source"].'</p>';
                        echo '<blockquote>答案：'.$exam_link["answer"].'</blockquote></li>';
                    } elseif($exam_link["answer_type"] == 3 ){ # 选择
                        echo '<li><p>'.$exam_link["subject"].'</p>';
                        echo '<p>A.'.$exam_link["answer_a"].'&nbsp;&nbsp;B.'.$exam_link["answer_b"].
                        '&nbsp;&nbsp;C.'.$exam_link["answer_c"].'&nbsp;&nbsp;D.'.$exam_link["answer_d"].'</p>';
                        echo '<p class="light-blue-text text-accent-2 right-align">'.$exam_link["source"].'</p>';
                        echo '<blockquote>答案：'.$exam_link["answer"].'</blockquote>';
                    } else{
                        # 这什么规则我可没见过，题目没有答案也免了。
                    }
                    
                }unset($exam_link);
                echo "</ol></div></div></div></div>";
            }
        unset($paraphrase);

        if($detail["gy_fixed_collocation"] != json_decode('[]',true)){ # 固定搭配
            echo <<<EOF
<div class="row">
<div class="col s12 m11 l10 offset-l1 offset-m1">   
<div class="card">
<div class="card-content">
<span class="card-title activator grey-text text-darken-4">
<i class="small material-icons">lock</i>固定搭配</span>
EOF;
            foreach($detail["gy_fixed_collocation"] as $collocation){
                echo '<b>'.$collocation["fixed_word"].'</b>';
                if($collocation["gy_paraphrase"] != ''){
                    echo '<ul class="collapsible" data-collapsible="accordion">';
                    foreach($collocation["gy_paraphrase"] as $inner_paraphrase){
                        if($inner_paraphrase["frequency_name"] != ''){
                            echo '<li><div class="collapsible-header"><div class="chip">'.$inner_paraphrase["frequency_name"].'</div>';
                        } else{
                            echo '<li><div class="collapsible-header">';
                        }
                        echo $inner_paraphrase["chinese"].'</div>';
                        echo '<div class="collapsible-body">';
                        get_example($inner_paraphrase["gy_example"]);
                        if($inner_paraphrase["gy_notes"] != json_decode('[]',true)){
                            foreach($inner_paraphrase["gy_notes"] as $notes){
                                echo '<blockquote><i class="small material-icons">error_outline</i>注意:'.$notes["notes"].'</blockquote>';
    
                            }
                            unset($notes);
                        }
                        echo '</div></li>';
                    }
                    echo '</ul>';
                }
            }
            echo '</div></div></div></div>';
        
            
            foreach($detail["gy_fixed_collocation"] as $collocation){
                foreach($collocation["gy_paraphrase"] as $inner_paraphrase){
                    if($inner_paraphrase["gy_biscrimination"] != json_decode('[]',true)){ # 辨析
                        echo <<<EOF
<div class="row">
<div class="col s12 m11 l10 offset-l1 offset-m1">   
<div class="card">
<div class="card-content">
<span class="card-title activator grey-text text-darken-4">
<i class="small material-icons">flag</i>辨析</span>
EOF;
                    get_biscrimination($inner_paraphrase["gy_biscrimination"]);
                    echo "</div></div></div></div>";
                    }
                }
            }
            unset($collocation); 
        }
    
    echo "<hr>";
        
    }

    
        
// echo "<blockquote>Server: Operated successfully.<br/>Thanks.</blockquote>";
$db->close();
} // search函数结束

if(is_array($_GET)&&count($_GET)>0) //先判断是否通过get传值了
    {
        if(isset($_GET['reqword'])) //是否存在"reqword"的参数
        {
            $word = "'".$_GET['reqword']."'"; //存在
            search($word);
        }
    } else {
            echo <<<EOF
      <div class="row">
        <div class="col s3 offset-m2 m2 offset-l2 l2">
          <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div><div class="gap-patch">
                <div class="circle"></div>
              </div><div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div>
      
            <div class="spinner-layer spinner-red">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div><div class="gap-patch">
                <div class="circle"></div>
              </div><div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div>
      
            <div class="spinner-layer spinner-yellow">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div><div class="gap-patch">
                <div class="circle"></div>
              </div><div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div>
      
            <div class="spinner-layer spinner-green">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div><div class="gap-patch">
                <div class="circle"></div>
              </div><div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col s9 m8 l7">
          <p class="flow-text">服务器正摊开3500单词书严阵以待……</p>
        </div>
      </div>
EOF;
        }


?>


</div>
<footer class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">关于</h5>
                <p class="grey-text text-lighten-4">一个练手的小站。<br>制作的主要原因还是自己懒得翻词典。<br>
                网址速记：搜.拉普达.space，拉普达是天空之城的名字！</p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">链接</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="https://github.com/Foldblade/3500so">Github - 3500so</a></li>
                  <li><a class="grey-text text-lighten-3" href="http://materializecss.com">Materialize CSS</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            © 2018  F.B. V0.4  Made With ❤
            <a class="grey-text text-lighten-4 right" href="http://materializecss.com">采用 Materialize</a>
        </div>
    </div>
</footer>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>


</body>

</html>

