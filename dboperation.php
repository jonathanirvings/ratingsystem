<?php
	class DBOperation
	{
		private $dbhandler;

		public function DBOperation()
		{
			$this->dbhandler = new DBHandler;
		}

		public function getContest($contestID)
		{
			$query = "SELECT * FROM ".$contestID;
			$result = $this->dbhandler->getQuery($query);
			return $result;
		}

		/*
		*	Get a rating of a username
		*	Or create 1200 rating if the username doesn't exist yet 
		*/
		public function getRating($username)
		{
			global $DEFAULT_RATING;
			$query = "SELECT rating FROM rating WHERE username = \"".$username."\"";
			$result = $this->dbhandler->getQuery($query);
			if (count($result) > 0)
				return $result[0]["rating"];
			else 
			{
				$this->insertName($username);
				return $DEFAULT_RATING;
			}
		}

		public function updateRating($username,$newRating)
		{
			$query = "UPDATE rating SET rating = ".$newRating." WHERE username = \"".$username."\"";
			$this->dbhandler->doQuery($query);
		}

		public function getRounds($username)
		{
			$query = "SELECT rounds FROM rating WHERE username = \"".$username."\"";
			$result = $this->dbhandler->getQuery($query);
			if (count($result) > 0)
				return $result[0]["rounds"];
			else 
			{
				$this->insertName($username);
				return 0;
			}
		}

		public function updateRounds($username)
		{
			$newRound = $this->getRounds($username) + 1;
			$query = "UPDATE rating SET rounds = ".$newRound." WHERE username = \"".$username."\"";
			$this->dbhandler->doQuery($query);
		}

		public function getAllPeople()
		{
			$query = "SELECT * FROM rating";
			$result = $this->dbhandler->getQuery($query);
			return $result;
		}

		public function getAllContests()
		{
			$query = "SELECT * FROM rounds";
			$result = $this->dbhandler->getQuery($query);
			return $result;
		}

		public function insertName($username)
		{
			global $DEFAULT_RATING, $DEFAULT_VOLATILITY;
			$query = "INSERT INTO rating VALUES(\"".$username."\",".$DEFAULT_RATING.",".$DEFAULT_VOLATILITY.",0)";	
			$this->dbhandler->doQuery($query);
		}

		public function updateProcessed($contestID,$value)
		{
			$query = "UPDATE rounds SET processed = ".$value." WHERE contest_id = \"".$contestID."\"";
			$this->dbhandler->doQuery($query);
		}

		public function removeAllName()
		{
			$query = "DELETE FROM rating";	
			$this->dbhandler->doQuery($query);
		}
	}
?>


