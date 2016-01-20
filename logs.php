<?php
	require_once 'class/Session.php';
	require_once 'class/Downloader.php';
	require_once 'class/FileHandler.php';

	$session = Session::getInstance();
	$file = new FileHandler;

	if(!$session->is_logged_in())
	{
		header("Location: login.php");
	}

	$files = $file->listLogs();

	if($session->is_logged_in() && isset($_GET["delete"]))
	{
		$file->deleteLog($_GET["delete"]);
		header("Location: logs.php");
	}

	require 'views/header.php';
?>
		<div class="container">
		<?php
			if(!empty($files))
			{
		?>
			<h2>List of logs:</h2>
			<table class="table table-striped table-hover ">
				<thead>
					<tr>
						<th style="min-width:800px; height:35px">Timestamp</th>
						<th style="min-width:80px">Size</th>
						<th style="min-width:110px">Delete link</th>
					</tr>
				</thead>
				<tbody>
			<?php
				$i = 0;
				$totalSize = 0;

				foreach($files as $f)
				{
					echo "<tr>";
					echo "<td><a href=\"".$file->get_logs_folder().'/'.$f["name"]."\">".$f["name"]."</a></td>";
					echo "<td>".$f["size"]."</td>";
					echo "<td><a href=\"./logs.php?delete=".sha1($f["name"])."\" class=\"btn btn-danger btn-sm\">Delete</a></td>";
					echo "</tr>";
					$i++;
				}
			?>
				</tbody>
			</table>
			<br/>
			<br/>
		<?php
			}
			else
			{
				echo "<br><div class=\"alert alert-warning\" role=\"alert\">No logs!</div>";
			}
		?>
			<br/>
		</div><!-- End container -->
<?php
	require 'views/footer.php';
?>