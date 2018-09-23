<?php
// error_reporting(E_ALL^E_WARNING^E_NOTICE);
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
// if session set go to dashboard
if ( isset($_SESSION['u']) && !empty($_SESSION['u']) ) {
  header('Location: dashboard.php');
  exit();
}

// if email submitted, set session and go to dashboard
if ( isset($_POST['u']) && $_POST['u'] != '' ) {
  $_SESSION['u'] = $_POST['u'];
  header('Location: dashboard.php');
  exit();
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
            <div class="col-md-6">
              <h1 class="jumbotron-heading">Tell us your email address</h1>
              <p class="lead text-muted">So that we can remember you</p>
              <p>
                <form class="" action="index.php" method="POST">
                  <div class="form-group">
                    <input type="text" name="u" class="form-control mb-2 col" id="inlineFormInputName2" placeholder="jdoe@mrn.org">
                    <input type="submit" class="btn btn-primary mb-2" value="Submit" />
                  </div>
                </form>
              </p>
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