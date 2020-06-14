<?php
App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

App::uses('Tournament', 'Model');
App::uses('Meeting', 'Model');
App::uses('Participant', 'Model');

App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
		$this->Auth->allow('add', 'forgotPassword', 'verify');
	}

	public function index() {
			return $this->redirect(array(action => 'view'));
	}

	public function view($id = null) {
		if ($this->Auth->user()['id'] == $id || $id == null) {
			$id = $this->Auth->user()['id'];
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->set('user', $this->User->find('first', $options));

			$participantModel = new Participant();
			$tournamentModel = new Tournament();
			$meetingModel = new Meeting();

			$pastTournamentArray = array();
			$futureTournamentArray = array();
			$meetingArray = array();

			$options = array('conditions' => array('Participant.userId' => $id));
			$myParticipants = $participantModel->find('all', $options);

			foreach ($myParticipants as $myParticipant) {
				$options = array('conditions' => array('Tournament.id' => $myParticipant['Participant']['tournamentId']));
				$tournament = $tournamentModel->find('first', $options);
				if ($tournament['Tournament']['time'] <= date("Y-m-d H:i:s"))
					array_push ($pastTournamentArray, $tournament);
				else array_push ($futureTournamentArray, $tournament);

				$options = array('conditions' => array('AND' => array(
					'Meeting.tournamentId' => $myParticipant['Participant']['tournamentId'],
					'OR' => array (
						array('Meeting.player1' => $myParticipant['Participant']['id'],
								'Meeting.winner1' => null),
						array('Meeting.player2' => $myParticipant['Participant']['id'],
								'Meeting.winner2' => null)
					),
					'not' => array('Meeting.player1' => null,
									'Meeting.player2' => null),
					'Meeting.winner' => null
					
				)));

				$tmpMeeting = $meetingModel->find('first', $options);
				if (!empty($tmpMeeting)) {
					array_push ($meetingArray, $tmpMeeting);
				}
			}

			$this->set('myPastTournaments', $pastTournamentArray);
			$this->set('myFutureTournaments', $futureTournamentArray);
			$this->set('myMeetings', $meetingArray);

		} else {
			$this->redirect('/');
		}
	}

	public function add() {
		if ($this->Auth->user()) {
			return $this->redirect('/');
		}
		
		if ($this->request->is('post')) {
			$this->User->create();
			
			$passwordHasher = new BlowfishPasswordHasher();
			$this->request->data['User']['password'] = $passwordHasher->hash($this->request->data['User']['password']);
			
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$activeToken = '';
				for ($i = 0; $i < 10; $i++) {
					$activeToken .= $characters[rand(0, $charactersLength - 1)];
				}
				
			$tokenHasher = new BlowfishPasswordHasher();
			$hashedToken = $tokenHasher->hash($activeToken);
			$hashedToken = str_replace('/', '_', $hashedToken);

			$this->request->data['User']['active'] = $hashedToken;
			
			try {
				if ($this->User->save($this->request->data)) {
					$this->Flash->success(__('The user has been saved.'));

					$Email = new CakeEmail();
					$Email->from(array('cakephp@gmail.com' => 'My Site'));
					$Email->to($this->request->data['User']['email']);
					$Email->subject('Verify account');
					$Email->send('Visit localhost/cake/users/verify/'.$hashedToken.' to verify your account');

					return $this->redirect(array('controller'=> 'tournaments', 'action' => 'index'));
				} else {
					$this->request->data['User']['password'] = '';
					$this->Flash->error(__('The user could not be saved. Please, try again.'));
				}
			} catch (PDOException $e) {
				if ($e->getCode() == 23000) {
					$this->Flash->error(__('Email already used'));
					$this->request->data['User']['password'] = '';
				} else {
					$this->Flash->error(__('Caught exception: '.$e->getMessage()));
				}
			}
		}
	}

	public function verify($token) {
		$user = $this->User->findByActive($token);

		if (!empty($user)) {
			$user['User']['active'] = '1';

			if ($this->User->save($user)) {
				$this->Flash->success(__('Verification completed'));
				return $this->redirect(array('controller' => 'Users', 'action' => 'login'));
			} else {
				$this->Flash->error(__('Some error occured while verifiaction.'));
				return $this->redirect('/');
			}
		} else {
			$this->Flash->error(__('Some error occured while verifiaction.'));
			return $this->redirect('/');
		}
	}

	public function login() {
		if ($this->Auth->user()) {
			return $this->redirect('/');
		}

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				if($this->Auth->user()['active'] !== '1') {			
					$this->Auth->logout();
					$this->Flash->error(__('You should active your account'));
					return $this->redirect(array('action'=>'login'));
				}
				else {
					$this->Flash->success(__('Logged in'));
					return $this->redirect($this->Auth->redirectUrl());
				}
			} else {
				$this->Flash->error(__('Can\'t log in'));
			}
		}
	}

	public function logout() {
		$this->Auth->logout();
		return $this->redirect('/');
	}

	public function forgotPassword() {
		if ($this->request->is('post')) {
			$foundUser = $this->User->findByEmail($this->request->data['User']['email']);
			
			if ($foundUser) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$tempPassword = '';
				for ($i = 0; $i < 10; $i++) {
					$tempPassword .= $characters[rand(0, $charactersLength - 1)];
				}

				$this->request->data['User'] = $foundUser['User'];
			
				$passwordHasher = new BlowfishPasswordHasher();
				$this->request->data['User']['password'] = $passwordHasher->hash($tempPassword);
						
				if ($this->User->save($this->request->data)) {
					$this->Flash->success(__('Email with temporary password sent.'));

					$Email = new CakeEmail();
					$Email->from(array('cakephp@gmail.com' => 'My Site'));
					$Email->to($this->request->data['User']['email']);
					$Email->subject('Forgot password');
					$Email->send('Your temporary password is '.$tempPassword.'. Go to page and change your password!');

					return $this->redirect('/');
				} else {
					$this->Flash->error(__('Some error occured. Please, try again.'));
				}
			} else {
				$this->Flash->error(__('Account not found'));
			}
		}
	}

	public function changePassword($id) {
		if ($this->request->is('post')) {
			$foundUser = $this->User->findById($id);
			
			if ($foundUser) {
				$newPassword = $this->request->data['User']['password'];
				$this->request->data['User'] = $foundUser['User'];
				
				$passwordHasher = new BlowfishPasswordHasher();
				$this->request->data['User']['password'] = $passwordHasher->hash($newPassword);
						
				if ($this->User->save($this->request->data)) {
					$this->Flash->success(__('The password has been changed.'));
					return $this->redirect(array('controller'=>'Users', 'action'=>'view'));
				} else {
					$this->Flash->error(__('The user could not be updated. Please, try again.'));
				}
			} else {
				$this->Flash->error(__('User not found'));
			}
		}
	}
	
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('oldUser', $this->User->findById($id)['User']);
		if ($this->request->is(array('post', 'put'))) {
			try {
				$this->request->data['User']['id'] = $id;
				
				if ($this->User->save($this->request->data)) {
					$this->Flash->success(__('The user has been saved.'));
					return $this->redirect(array('controller'=> 'tournaments', 'action' => 'index'));
				} else {
					$this->request->data['User']['password'] = '';
					$this->Flash->error(__('The user could not be saved. Please, try again.'));
				}
			} catch (PDOException $e) {
				if ($e->getCode() == 23000) {
					$this->Flash->error(__('Email already used'));
					$this->request->data['User']['password'] = '';
				} else {
					$this->Flash->error(__('Caught exception: '.$e->getMessage()));
				}
			}
		}
	}
}
