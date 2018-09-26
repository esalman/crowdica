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

// load feed
try {
  $stmt = $db->prepare("SELECT decisions.user_id, decisions.image_id, decisions.volume, decisions.label_1, decisions.comment, images.file
    FROM decisions 
    LEFT JOIN images ON images.image_id = decisions.image_id 
    GROUP BY decisions.user_id, decisions.image_id, decisions.volume, decisions.label_1 
    ORDER BY time DESC");
  $stmt->execute();
  $feeds = $stmt->fetchAll();
}
catch (Exception $e) {
  $error = $e->getMessage();
}

// load images
try {
  $stmt = $db->prepare("SELECT * FROM images ORDER BY image_id DESC");
  $stmt->execute();
  $images = $stmt->fetchAll();
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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">

    <title>A Crowdsourced Spatial Map Labeller</title>
  </head>
  <body>
    <header>
      <div class="collapse bg-primary" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">About</h4>
              <p class="text-muted">This is a quick and dirty demo of a crowdsourced IC labeller. Anyone can upload a NIFTI image of activation. Others can see 
                the uploaded images and vote on those as networks/artifacts.</p>
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
              <h1>Activity Feed</h1>
              <ul class="list-unstyled">
                <?php
                foreach ($feeds as $key => $value) {
                  ?>
                  <li class="media">
                    <i class="fas fa-lg fa-brain" style="margin: 5px;"></i>
                    <div class="media-body">
                      <h5 class="mt-0 mb-1"><span class="text-warning"><?php echo explode('@', $value['user_id'])[0] ?></span> voted 
                        <span class="text-warning"><?php echo $value['file'].','.$value['volume'] ?></span> as <?php echo $value['label_1'] ?></h5>
                        <?php echo $value['comment'] ? $value['comment'] : '' ?>
                    </div>
                  </li>
                  <?php
                }
                ?>

              </ul>
            </div>

            <div class="col-md-6">
              
              <div class="row">
                <div class="col">
                  <h3>Add a new set of images</h3>
                  <p class="text-muted">You can upload a Nifti file below.</p>
                  <p>
                    <form class="" enctype="multipart/form-data" action="upload_map.php" method="POST">
                      <div class="form-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="customFile" name="nifti_file">
                          <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <input type="submit" class="btn btn-primary mb-2" value="Upload" />
                      </div>
                    </form>
                  </p>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <h3>Your images</h3>
                  <ul class="list-group">
                    <?php
                    foreach ($images as $key => $value) {
                      ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="image.php?id=<?php echo $value['image_id'] ?>"><?php echo $value['file'] ?></a>
                        <span class="badge badge-primary badge-pill">999</span>
                      </li>
                      <?php
                    }
                    ?>
                  </ul>
                </div>

              </div>
              
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
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>