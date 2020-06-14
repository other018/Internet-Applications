<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Participant', 'Model');
App::uses('Meeting', 'Model');

class TournamentsController extends AppController {

	//public $components = array('Paginator', 'Session', 'Flash');
	public $components = array('Paginator');

	public function beforeFilter() {
		$this->Auth->allow('index', 'view');
		$this->Paginator->settings = array(
			'maxLimit' => 10,
		);
	}
	
	public function index() {
		$this->Tournament->recursive = 0;
		if ($this->request->is('post')) {

			if ($this->request->data['Searching']['Phrase'] === null) {
				$this->set('tournaments', $this->Paginator->paginate());
			}
			elseif ($this->request->data['Searching']['Field'] == 1) {
				$this->set('tournaments', $this->Paginator->paginate(array('Tournament.name LIKE' => '%'.$this->request->data['Searching']['Phrase'].'%')));
			} 
			elseif ($this->request->data['Searching']['Field'] == 2) {
				$this->set('tournaments', $this->Paginator->paginate(array('Tournament.discipline LIKE' => '%'.$this->request->data['Searching']['Phrase'].'%')));
			}
		}
		else $this->set('tournaments', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->Tournament->exists($id)) {
			throw new NotFoundException(__('Invalid tournament'));
		}

		$options = array('conditions' => array('Tournament.' . $this->Tournament->primaryKey => $id));
		$tournament = $this->Tournament->find('first', $options);
		$this->set('tournament', $tournament);
		
		$userModel = new User();
		$this->set('organizator', $userModel->findById($tournament['Tournament']['organizerId']));

		$userId = $this->Auth->user()['id'];
		
		$participantModel = new Participant();
		$this->set('participant', $participantModel->find('first', array('conditions' => array('AND' => array(
			'Participant.tournamentid'=> $id,
			'Participant.userid' => $userId)))));

		$userNames = array();
		$userModel = new User();
		foreach ($tournament['Participant'] as $tParticipant) {
			$tmpArray = array();
			$tmpUser = $userModel->findById($tParticipant['userId'])['User'];
			$tmpArray['uId'] = $tmpUser['id'];
			$tmpArray['firstName'] = $tmpUser['firstName'];
			$tmpArray['secondName'] = $tmpUser['secondName'];
			$userNames[$tParticipant['id']] = $tmpArray;
		}
		$this->set('userNames', $userNames);


		if ($this->request->is('post')) {
			$meetingModel = new Meeting ();
			$requestMeeting = $this->request->data['Meeting'];

			if ($meetingModel->save($requestMeeting)) {
				//$this->Flash->success(__('Meeting has been updated.'));

				$databaseMeeting = $meetingModel->findById($requestMeeting['id'])['Meeting'];

				if ($databaseMeeting['winner1'] !== null && $databaseMeeting['winner2'] !== null) {
					if ($databaseMeeting['winner1'] === $databaseMeeting['winner2']) {
						$databaseMeeting['winner'] = $databaseMeeting['winner1'];

						if ($meetingModel->save($databaseMeeting)) {
							//$this->Flash->success(__('Meeting has been updated.'));
						
							if ($databaseMeeting['round'] > 0) {
								$options = array('conditions' => array('AND' => array (
									'Meeting.round' => $databaseMeeting['round']-1,
									'Meeting.tournamentId' => $databaseMeeting['tournamentId'],
									'Meeting.number' => floor($databaseMeeting['number']/2))));
								$nextMeeting = $meetingModel->find('first', $options);
								
								if ($databaseMeeting['number'] % 2 == 0) {
									$nextMeeting['Meeting']['player1'] = $databaseMeeting['player'.$databaseMeeting['winner']];
								} else {
									$nextMeeting['Meeting']['player2'] = $databaseMeeting['player'.$databaseMeeting['winner']];
								}

								if ($meetingModel->save($nextMeeting)) {
									$this->Flash->success(__('Meeting has been updated.'));
								} else {
									$this->Flash->error(__('The meeting could not be updated. Please, try again.'));
								}
							}
							else $this->Flash->success(__('Meeting has been updated.'));
							
						} else {
							$this->Flash->error(__('The meeting could not be updated. Please, try again.'));
						}

					}
					else {
						$databaseMeeting['winner1'] = null;
						$databaseMeeting['winner2'] = null;
						if ($meetingModel->save($databaseMeeting)) {
							$this->Flash->success(__('Meeting has been updated. Diffrent results, put winner again.'));
						} else {
							$this->Flash->error(__('The meeting could not be updated. Please, try again.'));
						}	
					}
				} else {
					$this->Flash->success(__('Meeting has been updated.'));
				}
				$this->redirect(array('action'=>'view', $id));
			} else {
				$this->Flash->error(__('The meeting could not be updated. Please, try again.'));
			}
		}

	}

	public function add() {
			
		if ($this->request->is('post')) {
			$this->Tournament->create();
			
			$tournamentData = $this->request->data['Tournament'];
			$tournamentData['organizerId'] = $this->Auth->user()['id'];
			$tournamentData['participants'] = 0;

			$tournamentTime = date('Y-m-d H:i:s', mktime(
				$tournamentData['time']['meridian'] == 'am' ?
					$tournamentData['time']['hour'] : $tournamentData['time']['hour'] + 12, //hour
				$tournamentData['time']['min'], //minute
				0, //second
				$tournamentData['time']['month'], //month
				$tournamentData['time']['day'], //day
				$tournamentData['time']['year'] //year
			));

			if ($tournamentTime <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Can\'t create tournament in past'));
				return;
			}

			$tournamentDeadline = date('Y-m-d H:i:s', mktime(
				$tournamentData['deadline']['meridian'] == 'am' ?
					$tournamentData['deadline']['hour'] : $tournamentData['deadline']['hour'] + 12, //hour
				$tournamentData['deadline']['min'], //minute
				0, //second
				$tournamentData['deadline']['month'], //month
				$tournamentData['deadline']['day'], //day
				$tournamentData['deadline']['year'] //year
			)); 
			
			if ($tournamentDeadline <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Can\'t create tournament with deadline from past'));
				return;
			}
			
			if ($tournamentTime <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Can\'t create tournament in past'));
				return;
			}
			
			if ($tournamentData['deadline'] > $tournamentData['time']) {
				$this->Flash->error(__('Deadline should be before tournament date'));
				return;
			}

			$tournamentData['meeting_created'] = 0;
			if ($this->Tournament->save($tournamentData)) {
				$this->Flash->success(__('The tournament has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The tournament could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		if (!$this->Tournament->exists($id)) {
			throw new NotFoundException(__('Invalid tournament'));
		}
		
		if ($this->Tournament->findById($id)['Tournament']['organizerId'] != $this->Auth->user()['id']) {
			return $this->redirect(array('action' => 'index'));
		}

		$options = array('conditions' => array('Tournament.' . $this->Tournament->primaryKey => $id));
		$tournament = $this->Tournament->find('first', $options);
		$this->set('oldTournament', $tournament);
		
		if ($this->request->is(array('post', 'put'))) {
			$this->Tournament->create();
			
			$oldTournament = $this->Tournament->findById($id)['Tournament'];
			$tournamentData = $this->request->data['Tournament'];
			$tournamentData['organizerId'] = $this->Auth->user()['id'];

			$tournamentTime = date('Y-m-d H:i:s', mktime(
				$tournamentData['time']['meridian'] == 'am' ?
					$tournamentData['time']['hour'] : $tournamentData['time']['hour'] + 12, //hour
				$tournamentData['time']['min'], //minute
				0, //second
				$tournamentData['time']['month'], //month
				$tournamentData['time']['day'], //day
				$tournamentData['time']['year'] //year
			));

			if ($tournamentTime <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Can\'t modify tournament in past'));
				return;
			}

			$tournamentDeadline = date('Y-m-d H:i:s', mktime(
				$tournamentData['deadline']['meridian'] == 'am' ?
					$tournamentData['deadline']['hour'] : $tournamentData['deadline']['hour'] + 12, //hour
				$tournamentData['deadline']['min'], //minute
				0, //second
				$tournamentData['deadline']['month'], //month
				$tournamentData['deadline']['day'], //day
				$tournamentData['deadline']['year'] //year
			)); 
			
			if ($tournamentDeadline != $oldTournament['deadline']) {
				if ($tournamentDeadline <= (date("Y-m-d H:i:s"))) {
					$this->Flash->error(__('Can\'t edit tournament with deadline from past'));
					return;
				}
			}
			
			if ($tournamentTime <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Can\'t modify tournament in past'));
				return;
			}
			
			if ($tournamentData['deadline'] > $tournamentData['time']) {
				$this->Flash->error(__('Deadline should be before tournament date'));
				return;
			}


			
			foreach ($oldTournament as $key=>$value) {
				if (!array_key_exists ($key, $tournamentData))
				   $tournamentData[$key] = $value;
			}


			if ($this->Tournament->save($tournamentData)) {
				$this->Flash->success(__('The tournament has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The tournament could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tournament.' . $this->Tournament->primaryKey => $id));
			$this->request->data = $this->Tournament->find('first', $options);
		}
	}

	public function delete($id = null) {
		if (!$this->Tournament->exists($id)) {
			throw new NotFoundException(__('Invalid tournament'));
		}
		$this->request->allowMethod('post', 'delete');
		$tournament = $this->Tournament->findById($id)['Tournament'];
		$deadline = $tournament['deadline'];
		if ($deadline <= date("Y-m-d H:i:s")) {
			$this->Flash->error(__('Can\'t remove tournament with meetings.'));
			return $this->redirect(array('action' => 'view', $id));
		}

		$participantModel = new Participant();
		$participants = $participantModel->find('all', array('conditions' => array('Participant.Tournamentid' => $id)));

		if ($this->Tournament->delete($id)) {
			foreach ($participants as $participant) {
				print($participant['Participant']['id']);
				$participantModel->delete($participant['Participant']['id']);
			}
			$this->Flash->success(__('The tournament has been deleted.'));
			return $this->redirect(array('action' => 'index'));

		} else {
			$this->Flash->error(__('The tournament could not be deleted. Please, try again.'));
		}

		//return $this->redirect(array('action' => 'index'));

	}

	public function join ($id) {
		if ($this->Auth->user()) {
			return $this->redirect(array('controller'=> 'participants', 'action' => 'add', $id));
		}
	}

	public function unJoin ($id) {
		if ($this->Auth->user()) {
			return $this->redirect(array('controller'=> 'participants', 'action' => 'unJoin', $id));
		}
	}
}
?>