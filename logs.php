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
						<th>Timestamp</th>
						<th>Ended?</th>
						<th>Ok?</th>
						<th>Size</th>
						<th>Delete link</th>
					</tr>
				</thead>
				<tbody>
			<?php

				foreach($files as $f)
				{
					echo "<tr>";
					if (get_relative_log_folder)
					{
						echo "<td><div><a href=\"".rawurlencode($file->get_relative_log_folder()).'/'.rawurlencode($f["name"])."\" target=\"_blank\">".$f["name"]."</a></div><div>".$f["lastline"]."</div></td>";
					}
					else
					{
						echo "<td><div>".$f["name"]."</div><div>".$f["lastline"]."</div></td>";
					}
					echo "<td>".($f["ended"] ? '&#10003;' : '')."</td>";
					echo "<td>".($f["100"] ? '&#10003;' : '')."</td>";
					echo "<td>".$f["size"]."</td>";
					echo "<td><a href=\"./logs.php?delete=".sha1($f["name"])."\" class=\"btn btn-danger btn-sm\">Delete</a></td>";
					echo "</tr>";
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

