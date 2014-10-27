<html>
<head>
	<title>TOKI Unofficial Rating System</title>
	<h1>Welcome to TOKI Unofficial Rating System</h1>
	<link rel="stylesheet" href="style.css" type="text/css" charset="utf-8" />
	<?php
		include "constant.php";
		include "dbhandler.php";
		include "dboperation.php";
		include "processrating.php";
		include "external/formula.php";
	?>
</head>
<body>
	<table>	
		<tr><td>Rank</td><td>Username</td><td>Rating</td></tr>
		<?php
			function printUser($people)
			{
				global $GRAY_RATING, $GREEN_RATING, $BLUE_RATING, $YELLOW_RATING;
				$className;
				if ($people["rating"] < $GRAY_RATING)
					$className = "user-gray";
				else if ($people["rating"] < $GREEN_RATING)
					$className = "user-green";
				else if ($people["rating"] < $BLUE_RATING)
					$className = "user-blue";
				else if ($people["rating"] < $YELLOW_RATING)
					$className = "user-yellow";
				else 
					$className = "user-red";
				echo "<td><a href='profile.php?id=".$people["username"]."' class='".$className."'>".$people["username"]."</a></td>";
				echo "<td class='".$className."'>".$people["rating"]."</td>";
			}

			$processrating = new ProcessRating;
			$listAllPeople = $processrating->getAllPeople();
			for ($i = 0; $i < count($listAllPeople); ++$i)
			{
				echo "<tr>";
				echo "<td>";
				if ($i > 0 && $listAllPeople[$i]["rating"] == $listAllPeople[$i-1]["rating"])
					echo ($listAllPeople[$i]["rank"] = $listAllPeople[$i-1]["rank"]);
				else echo ($listAllPeople[$i]["rank"] = $i+1);
				echo "</td>";
				printUser($listAllPeople[$i]);
				//echo "<td>".$listAllPeople[$i]["username"]."</td>";
				//echo "<td>".$listAllPeople[$i]["rating"]."</td>";
				echo "</tr>";
			}
		?>
	</table>
</body>
</html>