<?php
App::uses('AppController', 'Controller');

class SponsorsController extends AppController {

	public $components = array('Paginator');

	public function add($tournamentId = null) {
		if ($tournamentId == null) {
			return $this->redirect(array('controller'=> 'tournaments', 'action' => 'index'));
		}

		if ($this->request->is('post')) {
			$file = $this->request->data['Sponsor']['upload'];

			$ext = substr(strtolower(strrchr($file['name'], '.')), 1);
			$name = substr(strtolower($file['name']), 0, -strlen($ext)-1);
			$name = str_replace(' ', '_', $name);

			if(!file_exists(WWW_ROOT . 'img\\' . $file['name']))
			{
					//do the actual uploading of the file. First arg is the tmp name, second arg is 
					move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img\\' . $file['name']);

					//prepare the filename for database entry
					$this->request->data['Sponsor']['fileName'] = $file['name'];
			} else {
				$i = 0;
				while (file_exists(WWW_ROOT . 'img\\' .$name.$i.'.'.$ext)) {
					$i += 1;
				}
				//do the actual uploading of the file. First arg is the tmp name, second arg is 
				move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img\\' .$name.$i.'.'.$ext);

				//prepare the filename for database entry
				$this->request->data['Sponsor']['fileName'] = $name.$i.'.'.$ext;
			}

			$this->request->data['Sponsor']['tournamentId'] = $tournamentId;

			$this->Sponsor->create();
			if ($this->Sponsor->save($this->request->data)) {
				$this->Flash->success(__('The sponsor has been saved.'));
				return $this->redirect(array('controller'=>'tournaments', 'action' => 'edit', $tournamentId));
			} else {
				$this->Flash->error(__('The sponsor could not be saved. Please, try again.'));
			}
		}

		$tournaments = $this->Sponsor->Tournament->find('list');
		$this->set(compact('tournaments'));
	}

	public function edit($id = null) {
		if (!$this->Sponsor->exists($id)) {
			throw new NotFoundException(__('Invalid sponsor'));
		}

		$options = array('conditions' => array('Sponsor.' . $this->Sponsor->primaryKey => $id));
		$oldSponsor = $this->Sponsor->find('first', $options);
		
		if ($this->request->is(array('post', 'put'))) {
			$file = $this->request->data['Sponsor']['upload'];

			$ext = substr(strtolower(strrchr($file['name'], '.')), 1);
			$name = substr(strtolower($file['name']), 0, -strlen($ext)-1);
			$name = str_replace(' ', '_', $name);

			if(!file_exists(WWW_ROOT . 'img\\' . $file['name']))
			{
					//do the actual uploading of the file. First arg is the tmp name, second arg is 
					move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img\\' . $file['name']);

					//prepare the filename for database entry
					$this->request->data['Sponsor']['fileName'] = $file['name'];
			} else {
				$i = 0;
				while (file_exists(WWW_ROOT . 'img\\' .$name.$i.'.'.$ext)) {
					$i += 1;
				}
				//do the actual uploading of the file. First arg is the tmp name, second arg is 
				move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img\\' .$name.$i.'.'.$ext);

				//prepare the filename for database entry
				$this->request->data['Sponsor']['fileName'] = $name.$i.'.'.$ext;
			}

			print_r($oldSponsor);
			
			$this->request->data['Sponsor']['id']=$oldSponsor['Sponsor']['id'];
			$this->request->data['Sponsor']['tournamentId']=$oldSponsor['Sponsor']['tournamentId'];
			
			
			$this->Sponsor->create();
			
			if ($this->Sponsor->save($this->request->data)) {
				$this->Flash->success(__('The sponsor has been updated.'));
				return $this->redirect(array('controller'=>'tournaments', 'action' => 'edit', $oldSponsor['Sponsor']['tournamentId']));
			} else {
				$this->Flash->error(__('The sponsor could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $oldSponsor;
		}
		
		$tournaments = $this->Sponsor->Tournament->find('list');
		$this->set(compact('tournaments'));
	}

	public function delete($id = null) {
		if (!$this->Sponsor->exists($id)) {
			throw new NotFoundException(__('Invalid sponsor'));
		}
		$this->request->allowMethod('post', 'delete');
		$tournamentId = $this->Sponsor->findById($id)['Sponsor']['tournamentId'];
		
		if ($this->Sponsor->delete($id)) {
			$this->Flash->success(__('The sponsor has been deleted.'));
		} else {
			$this->Flash->error(__('The sponsor could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('controller' => 'Tournaments', 'action' => 'edit', $tournamentId));
	}
}
