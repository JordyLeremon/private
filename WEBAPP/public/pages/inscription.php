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

    <title>Inscription</title>

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
      url: 'languages/langInscription.xml',
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
                        <h3 class="panel-title langEntrerInformations">Merci de remplir les champs indiqués</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input id="email" class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input id="passwd1" class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input id="passwd2" class="form-control" placeholder="Password (again)" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <!-- Color #fca514 -->
                                <a class="btn btn-lg btn-success btn-block langValiderInscription" onClick="onFormValidation();" style="background-color: #fca514;">Valider</a>
                            </fieldset>
                        </form>
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
 * GEt in javascript
 *
 */
function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace( 
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if ( param ) {
        return vars[param] ? vars[param] : null;    
    }
    return vars;
}

/*
 *
 * Validate the email format
 *
 */
function validateEmail(email) {

    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);

}

/*
 *
 * Both passwd are equivalent
 *
 */
function validatePasswd(passwd1, passwd2) {

    if(passwd1 == passwd2)
        return true;
    return false;

}

/*
 *
 * Action on form validation
 *
 */
function onFormValidation(){

    email = document.getElementById("email").value;
    passwd1 = document.getElementById("passwd1").value
    passwd2 = document.getElementById("passwd2").value

    $.ajax({
        type: "GET",
        url: "countUser.php" ,
        data: { email: email},
        success : function(data) {

            if(data == 0){

                if(validateEmail(email)){

                    if(validatePasswd(passwd1, passwd2)){

                        $.ajax({
                            type: "GET",
                            url: "addUser.php" ,
                            data: { email: email, passwd: passwd1},
                            success : function() {

                                document.getElementById("errorBlock").style = "display: none";
                                window.location.href = window.location.href.split('?')[0] + "?" + language + "&success=1";

                            }

                        });

                    }
                    else{

                        document.getElementById("errorText").style = "color: red";
                        if(language == "eng"){

                            document.getElementById("errorText").innerHTML = "Passwords you entered are different, try again.";

                        }
                        else{

                            document.getElementById("errorText").innerHTML = "Les deux mots de passe entrés sont différents, réessayez.";

                        }
                        document.getElementById("errorBlock").style = "display: block";

                    }

                }
                else{

                    document.getElementById("errorText").style = "color: red";
                    if(language == "eng"){

                        document.getElementById("errorText").innerHTML = "This email has a wrong format.";

                    }
                    else{

                        document.getElementById("errorText").innerHTML = "Cette adresse mail n'est pas au bon format.";

                    }
                    document.getElementById("errorBlock").style = "display: block";

                }
            }
            else{

                document.getElementById("errorText").style = "color: red";
                if(language == "eng"){

                    document.getElementById("errorText").innerHTML = "This email is already taken.";

                }
                else{

                    document.getElementById("errorText").innerHTML = "Cette adresse mail est déjà utilisé.";

                }
                document.getElementById("errorBlock").style = "display: block";

            }

        }
    });

}

if($_GET("success") == 1){

    document.getElementById("errorText").style = "color: green";
    if(language == "eng"){

    document.getElementById("errorText").innerHTML = "An administrator will activated your account shortly. Please wait.";

    }
    else{

        document.getElementById("errorText").innerHTML = "Un administrateur va activer votre compte dans les plus brefs délais, merci de patienter.";

    }
    document.getElementById("errorBlock").style = "display: block";

}

</script>

</html>
