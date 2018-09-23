<?php
// error_reporting(E_ALL^E_WARNING^E_NOTICE);
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if ( !isset($_SESSION['u']) || empty($_SESSION['u']) ) {
  header('Location: index.php');
  exit();
}

// db connection
try {
  $db = new PDO('mysql:host=localhost;dbname=crowdica', 'root', '');
} catch (PDOException $e) {
  print "Database error!: " . $e->getMessage() . "<br/>";
  die();
}

# get the image
$error = '';
try {
  $stmt = $db->prepare("SELECT user_id, file FROM images WHERE image_id = ?");
  $stmt->execute([$_GET['id']]);
  $row = $stmt->fetch();
  if ( !$row ) throw new Exception("Invalid image!", 1);
}
catch (Exception $e) {
  $error = $e->getMessage();
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- iOS meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/papaya.css" />

    <title>A Crowdsourced Spatial Map Labeller</title>
  </head>
  <body>
    <header>
      <div class="collapse bg-primary" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">About</h4>
              <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <!-- greet -->
              <h4 class="text-white">Hi, <?php echo $_SESSION['u'] ?></h4>
              <h4 class="text-white">Contact</h4>
              <ul class="list-unstyled">
                <li><a href="#" class="text-white">Follow on Twitter</a></li>
                <li><a href="#" class="text-white">Like on Facebook</a></li>
                <li><a href="#" class="text-white">Email me</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container d-flex justify-content-between">
          <a href="./" class="navbar-brand d-flex align-items-center">
            <i class="fas fa-lg fa-brain"></i> &nbsp; &nbsp; 
            <strong>Crowd ICA</strong>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <main role="main">

      <div class="py-5 bg-light">
        <div class="container">
          <?php
          if ( $error ) {
            ?>
            <div class="row">
              <p>Sorry, there was a problem: <span class="text-danger"><?php echo $error ?></span>
            </div>
            <?php
          }
          else {
            ?>
            <div class="row">
              <div class="col-6">
                <div class="papaya" data-params="params"></div>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </div>

    </main>

    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Back to top</a>
        </p>
        <p>&copy; MIALAB</p>
        <p><a href="http://mialab.mrn.org">Visit the homepage</a></p>
      </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/papaya.js"></script>

    <script type="text/javascript">
      var params = [];
      params["images"] = ["uploads/<?php echo $row['file'] ?>"];
    </script>

  </body>
</html>