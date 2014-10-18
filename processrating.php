<?php
	class ProcessRating
	{
		private $dboperation;

		public function ProcessRating()
		{
			$this->dboperation = new DBOperation;
		}

		public function clear()
		{
			$this->dboperation->removeAllName();
			$contests = $this->getAllContests();
			for ($i = 0; $i < count($contests); ++$i)
			{
				$this->dboperation->updateProcessed($contests[$i]["contest_id"],0);
			}
		}

		/*public function processContest($contestID)
		{
			global $DEFAULT_RATING;
			$standings = $this->dboperation->getContest($contestID);
			$listOfParticipants = [];
			for ($i = 0; $i < count($standings); ++$i)
			{
				$listOfParticipants[$i]["oldRating"] = $this->dboperation->getRating($standings[$i]["username"]);
				$listOfParticipants[$i]["username"] = $standings[$i]["username"];
				$listOfParticipants[$i]["rank"] = $standings[$i]["rank"];
			}
			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				for ($j = $i; $j < count($listOfParticipants); ++$j)
				{
					if ($listOfParticipants[$j]["oldRating"] > $listOfParticipants[$i]["oldRating"])
					{
						$temp = $listOfParticipants[$i];
						$listOfParticipants[$i] = $listOfParticipants[$j];
						$listOfParticipants[$j] = $temp;
					}
				}
			}
			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				if ($i > 0 && $listOfParticipants[$i]["oldRating"] == $listOfParticipants[$i-1]["oldRating"])
					$listOfParticipants[$i]["seed"] = $listOfParticipants[$i-1]["seed"];
				else $listOfParticipants[$i]["seed"] = $i+1;
				if ($listOfParticipants[$i]["oldRating"] == $DEFAULT_RATING)
					$listOfParticipants[$i]["seed"] = count($listOfParticipants) / 2;
			}
			$maxChange = 0;
			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$difference = $listOfParticipants[$i]["seed"] - $listOfParticipants[$i]["rank"];
				if ($maxChange < $difference * $difference)
					$maxChange = $difference * $difference;
			}
			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$listOfParticipants[$i]["newRating"] = $listOfParticipants[$i]["oldRating"]; 
				$difference = $listOfParticipants[$i]["seed"] - $listOfParticipants[$i]["rank"];
				if ($difference < 0)
					$listOfParticipants[$i]["newRating"] -= ($difference * $difference) / $maxChange * 400;
				else $listOfParticipants[$i]["newRating"] += ($difference * $difference) / $maxChange * 400;
			}
			print_r($listOfParticipants);

			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$this->dboperation->updateRating($listOfParticipants[$i]["username"],$listOfParticipants[$i]["newRating"]);
				$this->dboperation->updateRounds($listOfParticipants[$i]["username"]);
			}
			$this->dboperation->updateProcessed($contestID,true);
		}*/

		public function processContest($contestID)
		{
			
		}

		public function getAllPeople()
		{
			$result = $this->dboperation->getAllPeople();
			for ($i = 0; $i < count($result); ++$i)
			{
				for ($j = $i; $j < count($result); ++$j)
				{
					if ($result[$j]["rating"] > $result[$i]["rating"])
					{
						$temp = $result[$i];
						$result[$i] = $result[$j];
						$result[$j] = $temp;
					}
				}
			}
			return $result;
		}

		public function getAllContests()
		{
			$result = $this->dboperation->getAllContests();
			return $result;
		}

	}
?>

