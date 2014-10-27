<?php
	class ProcessRating
	{
		private $dboperation;
		private $formula;

		public function ProcessRating()
		{
			$this->dboperation = new DBOperation;
			$this->formula = new Formula;
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

		private function SQR($x) {return $x * $x;}

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

		private function WP($username1,$username2)
		{
			$pembilang = $this->dboperation->getRating($username1) - $this->dboperation->getRating($username2);
			$penyebut = sqrt(2 * ($this->SQR($this->dboperation->getVolatility($username1)) + $this->SQR($this->dboperation->getVolatility($username2))));
			return 0.5 * ($this->formula->erf($pembilang / $penyebut) + 1);
		}

		public function processContest($contestID)
		{
			$AveRating = 0;
			global $DEFAULT_RATING;
			$standings = $this->dboperation->getContest($contestID);
			$listOfParticipants = [];
			for ($i = 0; $i < count($standings); ++$i)
			{
				$listOfParticipants[$i]["oldRating"] = $this->dboperation->getRating($standings[$i]["username"]);
				$listOfParticipants[$i]["volatility"] = $this->dboperation->getVolatility($standings[$i]["username"]);
				$listOfParticipants[$i]["rounds"] = $this->dboperation->getRounds($standings[$i]["username"]);
				$listOfParticipants[$i]["username"] = $standings[$i]["username"];
				$listOfParticipants[$i]["rank"] = $standings[$i]["rank"];
			}

			for ($i = 0; $i < count($listOfParticipants); ++$i)
				$AveRating += $listOfParticipants[$i]["oldRating"];
			$AveRating /= count($listOfParticipants);

			$CF = 0;
			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$CF += $this->SQR($listOfParticipants[$i]["volatility"]);
			}
			$CF /= count($listOfParticipants);
			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$CF += $this->SQR($listOfParticipants[$i]["oldRating"] - $AveRating);
			}
			$CF /= (count($listOfParticipants) - 1);
			$CF = sqrt($CF);

			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$ERank = 0.5;
				$ARank = $listOfParticipants[$i]["rank"];
				for ($j = 0; $j < count($listOfParticipants); ++$j)
				{
					$ERank += $this->WP($listOfParticipants[$i]["username"],$listOfParticipants[$j]["username"]);
				}
				$EPerf = -$this->formula->inverse_ncdf(($ERank - 0.5) / count($listOfParticipants));
				$APerf = -$this->formula->inverse_ncdf(($ARank - 0.5) / count($listOfParticipants));

				$PerfAs = $listOfParticipants[$i]["oldRating"] + $CF * ($APerf - $EPerf);
				$Weight = 1 / (1 - (0.42 / ($listOfParticipants[$i]["rounds"]+1) + 0.18)) - 1;

				$Cap = 150 + 1500 / ($listOfParticipants[$i]["rounds"] + 2);

				$listOfParticipants[$i]["newRating"] = ($listOfParticipants[$i]["oldRating"] + $Weight * $PerfAs) / (1 + $Weight);
				$listOfParticipants[$i]["newVolatility"] = $this->SQR($listOfParticipants[$i]["newRating"] - $listOfParticipants[$i]["oldRating"]) / $Weight;
				$listOfParticipants[$i]["newVolatility"] += $this->SQR($listOfParticipants[$i]["volatility"]) / ($Weight + 1);
				$listOfParticipants[$i]["newVolatility"] = sqrt($listOfParticipants[$i]["newVolatility"]);
			}

			for ($i = 0; $i < count($listOfParticipants); ++$i)
			{
				$this->dboperation->updateRating($listOfParticipants[$i]["username"],$listOfParticipants[$i]["newRating"]);
				$this->dboperation->updateVolatility($listOfParticipants[$i]["username"],$listOfParticipants[$i]["newVolatility"]);
				$this->dboperation->updateRounds($listOfParticipants[$i]["username"]);
			}
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

