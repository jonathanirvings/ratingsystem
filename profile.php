<html>
<head>
	<?php
		include "constant.php";
		include "dbhandler.php";
		include "dboperation.php";
		include "processrating.php";
		$userID = $_GET["id"];

	?>
	<title>TOKI Unofficial Rating System</title>
	<h1>Welcome, <?php printUser($userID); ?></h1>
	<h2>Your rating is <?php echo $rating ?></h2>
	<link rel="stylesheet" href="style.css" type="text/css" charset="utf-8" />
	
</head>
<body>
	<?php
		function printUser($userID)
		{
			$dboperation = new DBOperation;
			global $rating;
			global $GRAY_RATING, $GREEN_RATING, $BLUE_RATING, $YELLOW_RATING;
			$className;
			$rating = $dboperation->getRating($userID);
			if ($rating < $GRAY_RATING)
				$className = "user-gray";
			else if ($rating < $GREEN_RATING)
				$className = "user-green";
			else if ($rating < $BLUE_RATING)
				$className = "user-blue";
			else if ($rating < $YELLOW_RATING)
				$className = "user-yellow";
			else 
				$className = "user-red";
			echo "<div style='display:inline-block;' class='".$className."'>".$userID."</div>";
		}
	?>
</body>
</html>