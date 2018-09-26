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
$image_id = $_GET['id'];
$error = '';
try {
  $stmt = $db->prepare("SELECT user_id, file FROM images WHERE image_id = ?");
  $stmt->execute([$image_id]);
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
    <link rel="stylesheet" href="css/main.css">
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
              <div class="col-6">
                <form id="decisionForm">
                  <div class="form-group">
                    <h4><label for="">Is it a network or an artifact?</label></h4>
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <label class="btn btn-outline-success btn-lg active">
                        <input type="radio" name="label_1" id="artifact" autocomplete="off"> Artifact
                      </label>
                      <label class="btn btn-outline-success btn-lg">
                        <input type="radio" name="label_1" id="network" autocomplete="off"> Network
                      </label>
                      <label class="btn btn-outline-success btn-lg">
                        <input type="radio" name="label_1" id="unsure" autocomplete="off"> Unsure
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="decisionComment">You can also add a comment.</label>
                    <textarea class="form-control" id="decisionComment" rows="3"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>

                <div class="modal fade" id="decisionModal" role="dialog" aria-labelledby="decisionModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="decisionModalLabel">Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <hr />
                <h4>Here are some information to help you make a decision:</h4>
                <p class="decisionHelp">
                  <button type="button" class="btn btn-sm btn-primary">Overlap with white matter <span class="badge badge-light">99%</span></button>
                  <button type="button" class="btn btn-sm btn-info">Overlap with CSF <span class="badge badge-light">99%</span></button>
                  <button type="button" class="btn btn-sm btn-warning">Overlap with edge mask <span class="badge badge-light">99%</span></button>
                  <button type="button" class="btn btn-sm btn-success">Top 3 Neurosynth labels <span class="badge badge-light">task</span> <span class="badge badge-light">frontal</span> <span class="badge badge-light">temporal</span></button>
                  </p>

                <hr />
                <h4>What others are saying?</h4>
                <img src="images/vector-windrose-diagram-blank-template-weather-infographics-illustration-75200845.jpg" width="100%" />
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
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/papaya.js"></script>

    <script type="text/javascript">
      var params = []
      params['images'] = ['data/ch2better_aligned2EPI_resampled.nii', ['uploads/<?php echo $row['file'] ?>']]
      params['worldSpace'] = true
      params['showControlBar'] = true

      $(document).ready( function () {
        $('#decisionForm').submit( function (e) {
          e.preventDefault()

          t = papayaContainers[0].viewer.currentScreenVolume.currentTimepoint
          $.ajax({
            url: "save_decision.php",
            data: {
                id: <?php echo $image_id ?>,
                volume: t,
                label_1: $('input[name=label_1]:checked').attr('id'),
                comment: $('#decisionComment').val()
            },
            type: "POST",
            dataType : "json"
          })
          .done(function( xhr ) {
            console.log( xhr )

            s = xhr.error ? '<p class="text-danger">' + xhr.error + '</p>' : '<p class="text-success">Your decision was saved, thanks!</p>'
            $('#decisionModal .modal-body').html(s)
            $('#decisionModal').modal('show')
          })
          .fail(function( xhr, status, errorThrown ) {
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
            $('#decisionModal .modal-body').html(xhr.error)
            $('#decisionModal').modal('show')
          })

        } )
      } )
    </script>

  </body>
</html>