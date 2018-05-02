<?php

session_start();
if(isset($_SESSION['email']) || !empty($_SESSION['email']))
                header('Location: index.php');
?>

<?php

require_once('./../php/RecupConf.php');
require_once('./../php/Database.php');

// Get all info from conf file
$conf = new RecupConf();

// Database class (connection and requests)
$database = new Database($conf->getDbName(), $conf->getDbLogin(), $conf->getDbPasswd());

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript">
var language;
  $(function() {
    var re = /\?(\w*)/;
    var str = document.location.href;

    if ((m = re.exec(str)) !== null) {
      if (m.index === re.lastIndex) {
        re.lastIndex++;
      }
      language = m[1];
    }

    if (language == undefined || (language != "eng" && language != "fr")) {
      language = "fr";
    }

    $.ajax({
      url: 'languages/langLogin.xml',
      success: function(xml) {
        $(xml).find('translation').each(function() {
          var id = $(this).attr('id');
          var text = $(this).find(language).text();
          $("." + id).html(text);
        });
      }
    });
  });
</script>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title langConnect">Connexion</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input id="email" class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input id="passwd" class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <!-- Color #fca514 -->
                                <a class="btn btn-lg btn-success btn-block langValidateConnect" onClick="onFormValidation();" style="background-color: #fca514;">Se connecter</a>
                            </fieldset>
                        </form>
						<br>
						<a href="inscription.php" class="btn btn-lg btn-success btn-block" style="background-color: #fca514;">S'inscrire</a>
                        <div id="errorBlock" class="panel-heading" style="display: none;">
                            <h3 id="errorText" class="panel-title"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

<script>

/*
 *
 * Action on form validation
 *
 */
function onFormValidation(){

    email = document.getElementById("email").value;
    passwd = document.getElementById("passwd").value;

    $.ajax({

        type: "GET",
        url: "makeConnection.php" ,
        data: { email: email, passwd: passwd},
        success : function(data) {

            if(data == 1){

                $.ajax({

                    type: "GET",
                    url: "isActivated.php" ,
                    data: { email: email},
                    success : function(data) {

                        if(data == 1){

                            document.getElementById("errorBlock").style = "display: none";
                            $.ajax({

                                type: "GET",
                                url: "createSession.php" ,
                                data: { email: email},
                                success : function(data) {

                                    document.location.href = "index.php";

                                }

                            });

                        }
                        else{

                            document.getElementById("errorText").style = "color: red";
                            if(language == "eng"){

                                document.getElementById("errorText").innerHTML = "Account not activated yet.";

                            }
                            else{

                                document.getElementById("errorText").innerHTML = "Le compte n'est pas encore actif.";

                            }
                            document.getElementById("errorBlock").style = "display: block";

                        }

                    }

                });
                                
            }
            else{

                document.getElementById("errorText").style = "color: red";
                if(language == "eng"){

                    document.getElementById("errorText").innerHTML = "Wrong email/password";

                }
                else{

                    document.getElementById("errorText").innerHTML = "Mauvaise combinaison email/mot de passe";

                }
                document.getElementById("errorBlock").style = "display: block";

            }

        }

    });

}

</script>

</html>
