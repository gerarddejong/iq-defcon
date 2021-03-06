<?php
  require_once("config.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	
    <title>IQ DefCon</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sticky-footer.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="navbar-brand" href="#">IQ DefCon</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.html">Logout</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="reset.php">Reset</a></li>
            </ul>
        </div>
      </div>
    </nav>

    <div class="col-sm-6 col-sm-offset-3">  
        <div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title">Access granted!</h3>
            </div>
            <div class="panel-body">
            <p>Welcome 
              <?php
                $id = $_GET['id'];
                $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
                mysql_select_db(DB_NAME, $link);

                $query = 'SELECT username FROM user WHERE id = ' . $id . ';';

                $result = mysql_query($query);
                $value = mysql_result($result, 0);

                print($value);

                file_put_contents('query.log', date('Y-m-dTH:i:s.u') . " " . $query . PHP_EOL, FILE_APPEND);

                mysql_free_result($result);
                mysql_close($link);
            ?>!
            </p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-sm-offset-3">  
        <div class="panel panel-primary">
            <div class="panel-heading">
            <h3 class="panel-title">Comments</h3>
            </div>
            <div class="panel-body">
              <form role="form" action="<?php print($_SERVER['PHP_SELF'] . '?id=' . $_GET['id']); ?>" method="post"> 
                <div class="form-group">
                  <input name="comment" type="text" class="form-control" id="comment" placeholder="Comment" value="">
                </div>
                <div class="form-group">
                  <button name="submit" value=true type="submit" class="btn btn-primary pull-right">Post</button>
                </div>
              </form>
            </div>
            <div class="panel-body">

            <?php
              $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
              $opt = [
                  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                  PDO::ATTR_EMULATE_PREPARES   => false,
              ];
              $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);

              if(isset($_POST['comment'])) {
                $comment = $_POST['comment'];
                $comment = htmlspecialchars($_POST['comment']);
                
                $insert = $pdo->prepare('INSERT INTO comment VALUES (NULL, :comment)');
                $insert->execute(['comment' => $comment]);
              }

              $stmt = $pdo->query('SELECT comment FROM comment');
              foreach ($stmt as $row) {
                  $comment = $row['comment'];
                  $comment = htmlspecialchars($row['comment']);
                  print('
            <div class="panel panel-primary">
              <div class="panel-body">
                <p>' . $comment . '</p>
              </div>
            </div>');
              }

          ?>
          </div>
        </div>
    </div>
		
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
  </html>