<?php
    // ここにDBに登録する処理を記述する
// １．データベースに接続する
$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8');










//1:DB接続//リモートのDB
/*$dsn = 'mysql:dbname=LAA0792964-onelinebbs;host=mysql103.phy.lolipop.lan';
$user = 'LAA0792964';
$password = '10t6020s';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8');
*/

//post送信が行われたとき
if(!empty($_POST)){
//htmlの変数をphp変数に代入する
  $nickname=$_POST['nickname'];
  $comment=$_POST['comment'];




if(($_GET['action']!='edit')){// ２．SQL文を実行する
//DB登録する
$sql = "INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,'".$nickname."','".$comment."',now())";//$変数に変更
$stmt = $dbh->prepare($sql);
$stmt->execute();

}

//post送信が行われて、editのボタンが押されるとき
else{
//sql文のupdateに変更
//$sql ="UPDATE `posts` SET `nickname`=`.$nickname.`,`comment`=`.$comment.` WHERE `id`=".$_GET['id'];
//$sql ="UPDATE `posts` SET `nickname`=`".$nickname."`,`comment`=`".$comment."` WHERE `id`=".$_GET['id'];
$sql="UPDATE `posts` SET `nickname`='"."$nickname"."',`comment`='"."$comment"."' WHERE `id`=".$_GET['id'];
//$sql="UPDATE posts SET nickname=:変更カントウ,comment=:変更 WHERE id=110";
//$sql = "INSERT INTO `posts`(`id`, `nickname`, `comment`, `created`) VALUES (null,'".$nickname."','"."変更"."',now())";//$変数に変更
$stmt = $dbh->prepare($sql);
$stmt->execute();


//リダイヤル
header('Location: bbs.php');//削除後、bbs.phpに戻る

}



}
//action=deleteがget送信で送られてきたとき
if(!empty($_GET)&&($_GET['action']=='delete')){


$sql = "DELETE FROM `posts` where `id`=".$_GET['id'];//delete文
$stmt = $dbh->prepare($sql);
$stmt->execute();

//二重に実行されないように、最初のURLへリダイレクト（最初の画面に移動する）
header('Location: bbs.php');//削除後、bbs.phpに戻る


}
//action=editがget送信で送られたとき　
//条件

if(!empty($_GET)&&($_GET['action']=='edit')){
$sql ="SELECT `id`,`nickname`, `comment` FROM `posts` WHERE `id`=".$_GET['id'];
//$sql ="SELECT `nickname`, `comment` FROM `posts` WHERE `id`=5";


//secect文実行

$stmt = $dbh->prepare($sql);
$stmt->execute();


//格納する変数の初期化
$posts_edit=array();

//繰り返し文でのデータ取得
while (1) {
  $rec_edit=$stmt->fetch(PDO::FETCH_ASSOC);
  if($rec_edit==false){
    break;
  }


//取得したデータを配列に格納しておく
  $posts_edit[]=$rec_edit;
}

}
 //echo "$_POST['action']";

//if($_POST['action']=='end_edit'){

    //$nickname=$_POST['nickname'];
    //$comment=$_POST['comment'];




//update：編集したテキストをDBにUPDATEする
//UPDATE `DB name` SET 更新したい　カラムと変更値`nickname`="kanto" WHERE 条件指定`id`="5"

//条件
//$sql ="UPDATE `posts` SET `nickname`=`.$nickname.`,`comment`=`.$comment.` WHERE `id`=".$_GET['id'];
//$sql="UPDATE posts SET nickname=:変更カントウ,comment=:変更 WHERE id=32";
//}

//sql文の作成
//$sql ='SELECT*FROM`posts`';
$sql ="SELECT `id`, `nickname`, `comment`, `created` FROM `posts` ORDER BY`created` DESC";


//secect文実行

$stmt = $dbh->prepare($sql);
$stmt->execute();


//変数にDBから取得したデータを格納→フェッチ　連想配列に変更するため　また複雑な処理を行うため



//格納する変数の初期化
$posts=array();

//繰り返し文でのデータ取得
while (1) {
  $rec=$stmt->fetch(PDO::FETCH_ASSOC);
  if($rec==false){
    break;
  }
//取得したデータを配列に格納しておく
  $posts[]=$rec;

}



// ３．データベースを切断する
$dbh = null;

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">
     
<p style="font-size: 100px;">    

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
          <!-- nickname -->

        <?php
          if(!empty($_GET)&&($_GET['action']=='edit')){ ?>

        
         <?php   foreach ($posts_edit as $post_each_edit) ?>
        <form action="bbs.php?id=<?php echo $post_each_edit['id'];?>&action=edit" method="post">

                          <div class="form-group">
            <div class="input-group">
             <input type="text" name="nickname" class="form-control" id="validate-text" value="<?php echo $post_each_edit['nickname'];?>"  required>

              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
         

            </div>
          </div>

         <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" required><?php echo $post_each_edit['comment'];?></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
 
 <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>再登録</button>
        </form>
      </div>






          <?php }
          else 
           {?>
        <form action="bbs.php" method="post">

          <div class="form-group">
            <div class="input-group">
             <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>

              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>

         <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
 
<!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>
      </div>



           <?php }
           ?>



      
  
          

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">

    <?php
      foreach ($posts as $post_each) { ?>
          <article class="timeline-entry">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <a href="bbs.php?id=<?php echo $post_each['id'];?>&action=edit"><i class="fa fa-cogs"></i></a >
                  </div>
                  <div class="timeline-label">
                      <h2><a href="#"><?php echo $post_each['nickname'].' '; ?></a>
                      <?php         
                        $created= strtotime($post_each['created']);
                        $created= date('Y/m/d', $created);
                      ?>
                      <span><?php echo $created;?></span></h2>
                      
                      <p><?php echo $post_each['comment'].' ';?></p>
                      <a href="bbs.php?id=<?php echo $post_each['id'];?>&action=delete"><i class="fa fa-trash"></i></a>
                      <!--編集機能の実装-->

                  </div>
              </div>
          </article>

    <?php }?>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>



