<?php
App::uses('AppController', 'Controller');
App::uses('MeetingsController', 'Controller');
App::uses('TournamentsController', 'Controller');
App::uses('Tournament', 'Model');

class ParticipantsController extends AppController {

	public $components = array('Paginator');

	public function add($id) {
		if (!$this->Auth->user()) return;

		$options = array('conditions' => array('AND' => array(
			'Participant.tournamentId' => $id,
			'Participant.userId' => $this->Auth->user()['id']
		)));

		if (!empty($this->Participant->find('all', $options))) {
			$this->Flash->error(__('You already joined this tournament'));
			return $this->redirect(array('controller' => 'tournaments', 'action' => 'view', $id));
		}


		if ($this->request->is('post')) {
			$tournament = $this->Participant->Tournament->findById($id)['Tournament'];

			$this->Participant->create();

			$this->request->data['Participant']['tournamentId'] = $id;
			$this->request->data['Participant']['userId'] = $this->Auth->user()['id'];
			
			if ($tournament['deadline'] <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Time for joining expired'));
				return $this->redirect(array('controller' => 'tournaments', 'action' => 'view', $id));
			}

			if ($tournament['participants'] >= $tournament['maxParticipant']) {
				$this->Flash->error(__('Max number of participants reached'));
				return $this->redirect(array('controller' => 'tournaments', 'action' => 'view', $id));
			}

			$tournament['participants'] += 1;

			try {
				if ($this->Participant->save($this->request->data)) {
					if ($this->Participant->Tournament->save($tournament)) {
							$this->Flash->success(__('The participant has been saved.'));
							return $this->redirect(array('controller' => 'tournaments', 'action' => 'view', $tournament['id']));
						} else {
							$this->Flash->error(__('The participant could not be saved. Please, try again.'));
						}
					}

				}
			catch (PDOException $e) {
				if ($e->getCode() == 23000) {
					$this->Flash->error(__('Rank position and licence number must be unique'));
				} else {
					$this->Flash->error(__('Caught exception: '.$e->getMessage()));
				}
			}
		}
		$tournaments = $this->Participant->Tournament->find('list');
		$users = $this->Participant->User->find('list');
		$this->set(compact('tournaments', 'users'));
	}

	public function unJoin($tournamentId) {
		if (!$this->Auth->user()) return;
		if ($this->request->is(array('post', 'put'))) {
			$tournament = $this->Participant->Tournament->findById($tournamentId)['Tournament'];
			$userId = $this->Auth->user()['id'];

			$options = array('conditions' => array('AND' => array(
				'Participant.tournamentid'=> $tournamentId,
				'Participant.userid' => $userId
			)));

			$participant = $this->Participant->find('first', $options)['Participant'];

			if ($tournament['deadline'] <= (date("Y-m-d H:i:s"))) {
				$this->Flash->error(__('Time for unjoinin expired'));
				return $this->redirect(array('controller' => 'tournaments', 'action' => 'view', $tournamentId));
			}

			$tournament['participants'] -= 1;
			if ($this->Participant->Tournament->save($tournament)) {
				if ($this->Participant->delete($participant['id'])) {
					$this->Flash->success(__('The participant has been deleted.'));
					$this->redirect(array('controller' => 'tournaments', 'action' => 'view', $tournamentId));
				} else {
					$this->Flash->error(__('The participant could not be deleted. Please, try again.'));
				}
			}

		}

		$tournaments = $this->Participant->Tournament->find('list');
		$users = $this->Participant->User->find('list');
		$this->set(compact('tournaments', 'users'));
	}
}
