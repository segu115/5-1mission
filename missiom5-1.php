<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>5-1</title>
</head>
<body>

<?php







// データベース接続設定
$dsn="mysql:dbname=データベース名;host=localhost";
$user="ユーザー名";
$password="パスワード";
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

// テーブル作成
$sql="CREATE TABLE IF NOT EXISTS  post"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date TEXT,"
. "password TEXT"
.");";
$stmt=$pdo->query($sql);

if(!empty($_POST["name"])){
    $name=$_POST["name"];
}
if(!empty($_POST["comment"])){
    $comment=$_POST["comment"];
}

if(!empty(date("Y/m/d/h:i:s"))){
    $date=date("Y/m/d/h:i:s");
}

if(!empty($_POST["deletenum"])){
    $delete=$_POST["deletenum"];
}

if(!empty($_POST["editor"])){
    $editor=$_POST["editor"];
}

if(!empty($_POST["passcode"])){
    $password=$_POST["passcode"];
}

if(!empty($_POST["delpasscode"])){
    $delpassword=$_POST["delpasscode"];
}
if(!empty($_POST["editpasscode"])){
    $editpassword=$_POST["editpasscode"];
}

// データの入力（データレコードの挿入）
if(!empty($name) && (!empty($comment)) && (empty(!$password))){

        if(empty($_POST["edit-number"])){

            // 新規投稿
            $sql=$pdo->prepare("INSERT INTO post(name,comment,date,password)  VALUES(:name,:comment,:date,:password)");
            $sql->bindParam(":name",$name,PDO::PARAM_STR);
            $sql->bindParam(":comment",$comment,PDO::PARAM_STR);
            $sql->bindParam(":date",$date,PDO::PARAM_STR);
            $sql->bindParam(":password" ,$password,PDO::PARAM_STR);
            $sql->execute();
            
        }else{
            // 編集
            $edinum=$_POST["edit-number"];
            $sql="UPDATE post SET name=:name,comment=:comment,password=:password WHERE id=:id";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":name",$name, PDO::PARAM_STR);
            $stmt->bindParam(":comment",$comment,PDO::PARAM_STR);
            $stmt->bindParam(":password",$password,PDO::PARAM_STR);
            $stmt->bindParam(":id",$edinum,PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    // 入力したデータを削除する
    if(!empty($delete) && (!empty($delpassword))){

            if(take_data_post($delete, "password" , $pdo)== $delpassword){

            

            $sql="delete from post where id=:id";
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(":id",$delete,PDO::PARAM_INT);
                $stmt->execute();
            
        }
    }
    

    
    // 入力されているデータレコードの内容を編集する
    if(!empty($editor) && (!empty($editpassword))){

        if(take_data_post($editor,"password", $pdo)==$editpassword){
            $ediname=take_data_post($editor,"name",$pdo);
            $edicom=take_data_post($editor,"comment",$pdo);
            
        }
    }







        // $id=$editor;
        // $sql="SELECT post SET name=:name,comment=:comment, password=:password WHERE id=:id";
        // $stmt=$pdo->prepare($sql);
        // $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        // $editorall=$stmt->fetchALL();
        // foreach($editorall as $editors){
        //     $edinum=$edit["password"];
        


    function take_data_post($id,$column_name,$pdo){
        $sql="SELECT *FROM post WHERE id=:id";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        $one_comments=$stmt->fetchALL();
        foreach($one_comments as $data){
            $target_data = $data[$column_name];
        }
        return $target_data;
    }
  
        
        
        
    ?>
    
    <form action=""method="post">
    【投稿フォーム】</br>
    名前:
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($ediname)){echo $ediname;}?>">
    </br>
    コメント:
    <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($edicom)) {echo $edicom;}?>">
    <input type="hidden" name="edit-number" value="<?php if(isset($editor)){echo $editor;}?>">
    </br>
    パスワード
    <input id="password" type="password" name="passcode" value="" placeholder="パスワード">
    <input type="submit" name="submit" value="送信する">
    </br>
    </br>
    【削除フォーム】</br>
    投稿番号：
    <input type="text" name="deletenum" placeholder="削除番号"></br>
    パスワード：
    <input id="password" type="password" name="delpasscode" value="" placeholder="パスワード">
    <input type="submit" name="deletesubmit" value="削除する"></br>
    </br>
    【編集フォーム】</br>
    編集番号:
    <input type="text" name="editor" placeholder="編集番号"></br>
    パスワード：
    <input id="password" type="password" name="editpasscode" value="" placeholder="パスワード">
    <input type="submit" name="editorsubmit" value="編集する">
    </form>
    
    <?php
    // php表示 機能
    echo "____________________________________________________________________"  ."</br>";
    echo "【投稿一覧】" . "</br>";
    
    header("Content-Type:text/html;charset=UTF-8");
    
    // // データベースのテーブル表示
    $sql="SHOW TABLES";
    $result=$pdo->query($sql);
    foreach($result as $row){
        echo "<br>";
    }

    // 入力したデータレコードを抽出し、表示する
    
    $sql="SELECT * FROM post";
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
        // $rowの中にはテーブルのカラム名が入る
        echo $row["id"]." ";
        echo $row["name"]." ";
        echo $row["comment"].",";
        echo $row["date"]. ",";
        echo $row["password"]. "<br>";
        
        echo "<hr>";
    }

    
    ?>
    
    
</body> 
</html>