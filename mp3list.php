<!DOCTYPE html>
<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");

    if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Youtube-dl WebUI - List of MP3</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
    </head>
    <body >
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $mainPage; ?>">Youtube-dl WebUI</a>
            </div>
            <div class="navbar-collapse  collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo $mainPage; ?>">Download</a></li>
                    <li><a href="<?php echo $listPage; ?>">List of videos</a></li>
                    <li class="active"><a href="<?php echo $MP3listPage; ?>">List of MP3</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
<?php
if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1)
{
    if(isset($_GET['fileToDel']))
    {
        $fileToDel = $_GET['fileToDel'];

        if(file_exists($MP3folder.$fileToDel))
        {
            if(unlink($MP3folder.$fileToDel))
            {
                echo '<div class="panel panel-success">';
                echo '<div class="panel-heading"><h3 class="panel-title">File to delete : '.$fileToDel.'</h3></div>';
                echo '<div class="panel-body">File '.$fileToDel.' deleted !</div>';
                echo '</div>';
                echo '<p><a href="'.$MP3listPage.'">Go back</a></p>';
            }
            else{
                echo '<div class="panel panel-danger">';
                echo '<div class="panel-heading"><h3 class="panel-title">File to delete : '.$fileToDel.'</h3></div>';
                echo '<div class="panel-body">File '.$fileToDel.' not deleted !</div>';
                echo '</div>';
                echo '<p><a href="'.$MP3listPage.'">Go back</a></p>';
            }
        }
        else{
            echo '<div class="panel panel-danger">';
            echo '<div class="panel-heading"><h3 class="panel-title">File to delete : '.$fileToDel.'</h3></div>';
            echo '<div class="panel-body">File '.$fileToDel.' not found !</div>';
            echo '</div>';
            echo '<p><a href="'.$listPage.'">Go back</a></p>';
        }
    }
    elseif(!file_exists($MP3folder))
    {
            echo '<div class="alert alert-danger">
                    <strong>Error : </strong> Destination folder doesn\'t exist or is not found here.
                </div>';
    }
    else { ?>
        <h2>List of available MP3 :</h2>
            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
						<th style="min-width:800px; height:35px">Title</th>
                        <th style="min-width:80px">Size</th>
                        <th style="min-width:110px">Remove link</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
<?php
            foreach(glob($MP3folder."*") as $file)
            {
                $filename = str_replace($MP3folder, "", $file); // Need to fix accent problem with something like this : utf8_encode
                echo "<tr>"; //New line
                echo "<td height=\"30px\"><a href=\"$MP3folder$filename\">$filename</a></td>"; //1st col
                echo "<td>".human_filesize(filesize($MP3folder.$filename))."</td>"; //2nd col
                echo "<td><a href=\"".$MP3listPage."?fileToDel=$filename\" class=\"text-danger\">Delete</a></td>"; //3rd col
                echo "</tr>"; //End line
            }
        }
} 
else {
    echo '<div class="alert alert-danger"><strong>Access denied :</strong> You must sign in before !</div>';
} ?>
                    </tr>
                </tbody>
            </table>
            <br/>
            <?php if(!isset($_GET['fileToDel'])) echo "<a href=".$mainPage.">Back to download page</a>"; ?>
        </div><!-- End container -->
        <br>
        <footer>
            <div class="well text-center">
                <p><a href="https://github.com/p1rox/Youtube-dl-WebUI" target="_blank">Fork me on Github</a></p>
                <p>Created by <a href="https://twitter.com/p1rox" target="_blank">@p1rox</a> - Web Site : <a href="http://p1rox.fr" target="_blank">p1rox.fr</a></p> <p>Extended by TomGrun</p>
            </div>
        </footer>
    </body>
</html>