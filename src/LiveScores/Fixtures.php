<?php


namespace App\LiveScores;


class Fixtures
{
	public static function random()
	{
		$teams = ["Arsenal", "Aston Villa", "Cardiff", "Chelsea", "Crystal Palace", "Everton", "Fulham", "Hull", "Liverpool", "Man City", "Man Utd", "Newcastle", "Norwich", "Southampton", "Stoke", "Sunderland", "Swansea", "Tottenham", "West Brom", "West Ham"];
		
		shuffle($teams);
		
		foreach ($teams as $team) {
			$id = uniqid('', true);
			$games[$id] = [
				'id' => $id,
				'home' => [
					'team' => $team,
					'score' => 0,
				],
				'away' => [
					'team' => $team,
					'score' => 0,
				],
			];
		}
		if (!empty($games)) {
			return $games;
		}
	}
	
}