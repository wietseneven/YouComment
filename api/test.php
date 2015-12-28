<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>test</title>
</head>
<body>
<button id="button">ABC DEF</button>
<script src="../assets/js/libs/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var formData = {
			method:    'push',
			comment:   'ABC def ghij',
			videoID:   '1f_iw2RHNhI',
			videoTime: '78',
			userID:    'wneven',
			type:      'inVideo',
			location:  '{x: 10%, y: 20%}'
		};
		$('#button').click(function() {
//			$.ajax({
//				method: "POST",
//				url: "index.php",
//				data: formData
//			})
//			.done(function( msg ) {
//				alert( "Data Saved: " + msg );
//			});
			$.post('index.php', formData, function(data, status){
				alert("Data: " + data + "\nStatus: " + status);
			});
		});
	});
</script>
</body>
</html>