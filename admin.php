<html>
<head>
	<title>TOKI Unofficial Rating System Admin Panel</title>
	<h1>Welcome to TOKI Unofficial Rating System Admin Panel</h1>
	<?php
		include "constant.php";
		include "dbhandler.php";
		include "dboperation.php";
		include "processrating.php";
	?>
	<script src="external/jquery-1.10.2.js"></script>
	<script src="external/jquery-ui.js"></script>
</head>
<body>
	<?php
		$processrating = new ProcessRating;
		if (isset($_GET["clear"]))
		{
			$processrating->clear();
		} 
		if (isset($_GET["id"]))
		{
			$contest_ID = $_GET["id"];
			$processrating->processContest($contest_ID);
		} else
		{
			?>
			<table border="1">
			<tr>
				<td>Contest ID</td>
				<td>Status</td>
				<td>Process</td>
			</tr>
			<?php
				$contest = $processrating->getAllContests();
				for ($i = 0; $i < count($contest); ++$i)
				{
					echo "<tr>";
					echo "<td>".$contest[$i]["contest_id"]."</td>";
					echo "<td class='status ".$contest[$i]["contest_id"]."'>";
					if ($contest[$i]["processed"])
						echo "Processed";
					else echo "Not Processed";
					echo "</td>";
					echo "<td><button onclick=process('".$contest[$i]["contest_id"]."')>Process</button></td>";
					echo "</tr>";
				}
			?>
			<table>
			<button onclick=clearAll()>Clear All</button>
			<button onclick=processAll()>Process All</button>
			<?php
		}
	?>

	<script>
		function process(s)
		{
			$.ajax({
				url:"admin.php?id=" + s
			}).done(function()
			{
				//alert(s + " has been processed");
				$("." + s).html("Processed");
			});
		}

		function processAll()
		{
			<?php
				for ($i = 0; $i < count($contest); ++$i)
				{
					?>
						process(<?php echo "\"".$contest[$i]["contest_id"]."\""; ?>);
					<?php
				}
			?>
		}

		function clearAll()
		{
			$.ajax({
				url:"admin.php?clear=true"
			}).done(function()
			{
				//alert("All has been cleared");
				$(".status").html("Not Processed");
			});
		}
	</script>
</body>
</html>



