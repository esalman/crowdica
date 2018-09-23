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

$dir = 'uploads/';
$file = $dir . basename($_FILES['nifti_file']['name']);

$error = '';
if (move_uploaded_file($_FILES['nifti_file']['tmp_name'], $file)) {
  try {
    // db entry
    $db->prepare("INSERT INTO images (user_id, file)  VALUES (?, ?)")
      ->execute([$_SESSION['u'], $_FILES['nifti_file']['name']]);
    $image_id = $db->lastInsertId();
  }
  catch (PDOException $e) {
    $error = $e->getMessage();
  }
}
else {
  $error = 'Error uploading file.';
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

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

          <div class="row">
            <div class="col">
              <?php
              if ( $error ) {
                ?>
                <h3>Uploading file...</h3>
                <p>
                  Sorry, there was a problem: <span class="text-danger"><?php echo $error ?></span>
                  <br /><a href="dashboard.php">Please try again.</a>
                </p>
                <?php
              }
              else {
                ?>
                <h3>Success!</h3>
                <p>Your image has been uploaded. <a href="image.php?id=<?php echo $image_id ?>">Let the fun begin...</a></p>
                <script type="text/javascript">
                  setTimeout( 'window.location.href = "image.php?id=<?php echo $image_id ?>"', 5000 )
                </script>
                <?php
              }
              ?>
            </div>
          </div>
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
  </body>
</html>