<?php 
App::uses('AppController', 'Controller');
App::uses('MeetingsController', 'Controller');
App::uses('TournamentsController', 'Controller');
App::uses('ParticipantsController', 'Controller');
App::uses('Tournament', 'Model');

class CreateMeetingsShell extends AppShell {
    public function main () {
        $this->out('Hello world');
        $this->createMeetings();
    }

    public function createMeetings() {

        $tournamentController = new TournamentsController();
		$participantController = new ParticipantsController();
		
		$options = array('conditions' => array('AND' => array(
			'Tournament.meetingCreated' => 0,
			'Tournament.deadline <=' => date("Y-m-d H:i:s")
		)));

		$tournament = $tournamentController->Tournament->find('all', $options);
		foreach ($tournament as $tournamentElement) {

			$tournamentId = $tournamentElement['Tournament']['id'];

			$options = array('conditions' => array('Participant.tournamentid'=> $tournamentId),
							'order' => array('Participant.rankPosition' => 'desc'));
			//tournament participants with descending rank
			$tournamentParticipants = $participantController->Participant->find('all', $options);
			
			$tournament = $tournamentElement['Tournament'];

			$meetingController = new MeetingsController();

			$round = ceil(log($tournament['participants'], 2));
			$diff = pow(2, $round) - $tournament['participants'];
			print('di'.$diff.'ff');
			print('round'.$round.'round');
			for ($i = 0; $i < $round-1; $i++) {
				for ($j = 0; $j < pow(2, $i); $j++) {
					$meeting = array();
					$meeting['id'] = null;
					$meeting['tournamentId'] = $tournamentId;
					$meeting['round'] = $i;
					$meeting['number'] = $j;

					$meetingController->Meeting->save($meeting);
				}
			}

			$numParticipants = count($tournamentParticipants);
			$halfParticipants = pow(2, $round-1);
			
			?> <br> <?php
			print($halfParticipants);
			?> <br> <?php

			if ($round == 0) {
				$meeting = array();
				$meeting['id'] = null;
				$meeting['tournamentId'] = $tournamentId;
				$meeting['round'] = 0;
				$meeting['number'] = 0;
				$meeting['player1'] = $tournamentParticipants[0]['Participant']['id'];
					$meeting['winner1'] = 1;
					$meeting['winner2'] = 1;
					$meeting['winner'] = 1;
				$meetingController->Meeting->save($meeting);
			
				$tournamentElement['Tournament']['meetingCreated'] = 1;
				$tournamentController->Tournament->save($tournamentElement);
				return;

			}

			for ($j = 0; $j < ($numParticipants - $diff)/2; $j++) {
				$meeting = array();
				$meeting['id'] = null;
				$meeting['tournamentId'] = $tournamentId;
				$meeting['round'] = $round-1;
				$meeting['number'] = $j;
				$meeting['player1'] = $tournamentParticipants[$j]['Participant']['id'];
				if ($j+$halfParticipants < $numParticipants) {
					$meeting['player2'] = $tournamentParticipants[$j+$halfParticipants]['Participant']['id'];
				} else {
					$meeting['winner1'] = 1;
					$meeting['winner2'] = 1;
					$meeting['winner'] = 1;

					$options = array('conditions' => array('AND' => array (
							'Meeting.round' => $round-2,
							'Meeting.tournamentId' => $tournamentId,
							'Meeting.number' => floor($j/2))));
					$nextMeeting = $meetingController->Meeting->find('first', $options);
								
					if ($j % 2 == 0) {
						$nextMeeting['Meeting']['player1'] = $tournamentParticipants[$j]['Participant']['id'];
					} else {
						$nextMeeting['Meeting']['player2'] = $tournamentParticipants[$j]['Participant']['id'];
					}

					
					?> <pre> <?php print_r($nextMeeting); ?> </pre> <?php
					


					
					?> <pre> <?php print ('next'); print_r($nextMeeting['Meeting']);
					print ('meeeting '); print_r($meeting); ?> </pre> <?php
					

					$meetingController->Meeting->save($nextMeeting['Meeting']);	

				}

				
				print($i.' j= '.$j);
				?> <br> <?php
				
				?> <pre> <?php print ('meeeting1 '); print_r($meeting); ?> </pre> <?php
					
				$meetingController->Meeting->save($meeting);
			}

			for ($j = ($numParticipants - $diff)/2; $j < pow(2, $round-1); $j++) {
				$meeting = array();
				$meeting['id'] = null;
				$meeting['tournamentId'] = $tournamentId;
				$meeting['round'] = $round-1;
				$meeting['number'] = $j;
				$meeting['player1'] = $tournamentParticipants[$j]['Participant']['id'];
					$meeting['winner1'] = 1;
					$meeting['winner2'] = 1;
					$meeting['winner'] = 1;

					$options = array('conditions' => array('AND' => array (
							'Meeting.round' => $round-2,
							'Meeting.tournamentId' => $tournamentId,
							'Meeting.number' => floor($j/2))));
					$nextMeeting = $meetingController->Meeting->find('first', $options);
								
					if ($j % 2 == 0) {
						$nextMeeting['Meeting']['player1'] = $tournamentParticipants[$j]['Participant']['id'];
					} else {
						$nextMeeting['Meeting']['player2'] = $tournamentParticipants[$j]['Participant']['id'];
					}
					
					?> <pre> <?php print_r($nextMeeting); ?> </pre> <?php
	
					?> <pre> <?php print ('next'); print_r($nextMeeting['Meeting']);
					print ('meeeting '); print_r($meeting); ?> </pre> <?php
					

					$meetingController->Meeting->save($nextMeeting['Meeting']);	
				
				print($i.' j= '.$j);
				?> <br> <?php
				
				?> <pre> <?php print ('meeeting2 '); print_r($meeting); ?> </pre> <?php
					
				$meetingController->Meeting->save($meeting);
			}


			$tournamentElement['Tournament']['meetingCreated'] = 1;
			$tournamentController->Tournament->save($tournamentElement);
		}


	}
	
    /*
    cd C:\xampp\htdocs\cake\app && cake create_meetings
    */
}

?>