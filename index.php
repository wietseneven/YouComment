<?php
	$pageTitle = 'YouComment';
	$pageSubtitle = 'For Youtube';
	include_once('partials/header.php');
?>
	<div class="container">
		<form action="player.php" method="get">
			<fieldset class="form-group">
				<label for="ytID">YouTube video ID</label>
				<input type="text" class="form-control" name="ytID" id="ytID">
			</fieldset>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
<?php
	include_once('partials/footer.php');