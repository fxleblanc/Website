<?php
/**
 * Organizations controller
 *
 * @category Controller
 * @package  Website
 * @author   CakePHP <cakephp@email.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://cakephp.org CakePHP(tm) Project
 */
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Organizations controller
 *
 * @category Controller
 * @package  Website
 * @author   CakePHP <cakephp@email.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://cakephp.org CakePHP(tm) Project
 */
class OrganizationsController extends AppController
{
    private $_permissions = [
        'editStatus' => ['edit_organizations', 'edit_organization'],
        'add' => ['add_organization'],
        'submit' => ['submit_organization'],
        'edit' => ['edit_organizations', 'edit_organization'],
        'editValidated' => ['edit_organizations', 'edit_organization'],
        'editRejected' => ['edit_organizations', 'edit_organization'],
        'delete' => ['delete_organizations', 'delete_organization'],
        'addOwner' => ['edit_organization'],
        'addMember' => ['edit_organization'],
        'myOrganizations' => []
    ];

    /**
     * Check if the user has the rights to see the page
     *
     * @param array $user user's informations
     *
     * @return bool
     */
    public function isAuthorized($user)
    {
        $user = $this->Users->findById($user['id'])->first();

        if (isset($this->_permissions[$this->request->action])) {
            if ($user->hasPermissionName($this->_permissions[$this->request->action])) {
                return true;
            }
        }
    }

    /**
     * Filter preparation
     *
     * @param Event $event event
     *
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadModel("Users");
        $this->Auth->allow(['index', 'view', 'quit']);
    }

    /**
     * Add the RequestHandler component
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }


    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $user = $this->loadModel("Users")->findById($this->request->session()->read('Auth.User.id'))->first();

        if (!is_null($user) && $user->hasPermissionName(['list_organizations_all'])) {
            $this->adminIndex();
        } else {
            $data = $this->DataTables
                ->find(
                    'Organizations',
                    [
                        'fields' =>
                            [
                                'id',
                                'name',
                                'website',
                                'isValidated',
                                'isRejected'
                            ],
                            'join' =>
                            [
                                'table' => 'organizations_owners',
                                'alias' => 'o',
                                'type' => 'LEFT',
                                'conditions' => 'o.organization_id = Organizations.id'
                            ],
                            'conditions' =>
                            [
                                'OR' =>
                                    [
                                        [
                                            'isRejected' => 0,
                                            'isValidated' => 1,
                                        ],
                                        [
                                            'isRejected' => 0,
                                            'o.user_id' => (!is_null($user) ? $user->getId() : '')
                                        ]
                                    ]
                            ],
                            'group' => 'Organizations.id'
                    ]
                );

            $this->set(
                [
                    'data' => $data,
                    '_serialize' => array_merge($this->viewVars['_serialize'], ['data'])
                ]
            );
        }
    }

    /**
     * MyOrganizations method
     *
     * @return void
     */
    public function myOrganizations()
    {
        $user = $this->request->session()->read('Auth.User');

        $data = $this->DataTables
            ->find(
                'Organizations',
                [
                    'contain' =>
                        [
                            'Owners' =>
                                [
                                    'fields' =>
                                        [
                                            'id',
                                            'OrganizationsOwners.organization_id'
                                        ],
                                        'conditions' => 'Owners.id = ' . $user['id']
                                ]
                        ],
                        'fields' =>
                        [
                            'id',
                            'name',
                            'website',
                            'isValidated',
                            'isRejected',

                        ],
                        'join' =>
                        [
                            [
                                'table' => 'organizations_owners',
                                'alias' => 'o',
                                'type' => 'LEFT',
                                'conditions' => 'o.organization_id = Organizations.id',
                            ],
                            [
                                'table' => 'organizations_members',
                                'alias' => 'm',
                                'type' => 'LEFT',
                                'conditions' => 'm.organization_id = Organizations.id'
                            ]
                        ],
                        'conditions' =>
                        [
                            'OR' =>
                                [
                                    [
                                        'o.user_id' => $user['id']
                                    ],
                                    [
                                        'm.user_id' => $user['id']
                                    ]
                                ]
                        ],
                        'group' => 'Organizations.id'
                ]
            );

        $this->set(
            [
                'data' => $data,
                '_serialize' => array_merge($this->viewVars['_serialize'], ['data'])
            ]
        );
    }

    /**
     * Admin index method
     *
     * @return void
     */
    protected function adminIndex()
    {
        $data = $this->DataTables->find(
            'organizations',
            [
                'contain' => []
            ]
        );

        $this->set(
            [
                'data' => $data,
                '_serialize' => array_merge($this->viewVars['_serialize'], ['data'])
            ]
        );
        $this->render('adminIndex');
    }

    /**
     * View method
     *
     * @param string $id id
     *
     * @return redirect
     */
    public function view($id = null)
    {
        $organization = $this->Organizations->get(
            $id,
            [
                'contain' => ['Projects', 'Members', 'Owners']
            ]
        );

        $members = [];
        $ids = [];

        foreach ($organization->getOwners() as $owner) {
            array_push($members, $owner);
            array_push($ids, $owner->getId());
        }

        foreach ($organization->getMembers() as $member) {
            if (!in_array($member->getId(), $ids)) {
                array_push($members, $member);
            }
        }

        $user = $this->Users->findById($this->request->session()->read('Auth.User.id'))->first();

        if ($organization->getIsRejected() || !$organization->getIsValidated()) {
            if ($user && !$user->hasRoleName(['Administrator'])) {
                if (!$user->isMemberOf($id)) {
                    return $this->redirect(['action' => 'index']);
                }
            }
        }
        $this->set(compact('organization', 'user', 'members'));
        $this->set('_serialize', ['organization']);
    }

    /**
     * Add method
     *
     * @return redirect
     */
    public function add()
    {
        $organization = $this->Organizations->newEntity();

        $organization->editIsValidated(true);
        $organization->editIsRejected(false);
        $owner = $this->Users->findById($this->request->session()->read('Auth.User.id'))->first();
        $organization->editOwners([$owner]);
        $organization->editMembers([$owner]);

        if ($this->request->is('post')) {
            $organization = $this->Organizations->patchEntity($organization, $this->request->data);
            if ($this->Organizations->save($organization)) {
                $this->Flash->success(__('The organization has been saved.'));
                return $this->redirect(['action' => 'view', $organization->id]);
            } else {
                $this->Flash->error(__('The organization could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('organization'));
        $this->set('_serialize', ['organization']);
    }

    /**
     * Submit method
     *
     * @return redirect
     */
    public function submit()
    {
        if ($this->request->Session()->read('create_organization_redirect')) {
            $this->request->Session()->delete('create_organization_redirect');
        }

        $organization = $this->Organizations->newEntity();

        $organization->editIsValidated(false);
        $organization->editIsRejected(false);
        $owner = $this->Users->findById($this->request->session()->read('Auth.User.id'))->first();
        $organization->editOwners([$owner]);
        $organization->editMembers([$owner]);

        if ($this->request->is('post')) {
            $organization = $this->Organizations->patchEntity($organization, $this->request->data);
            if ($this->Organizations->save($organization)) {
                $this->Flash->success(__('The organization has been saved.'));
                return $this->redirect(['action' => 'submit', 'controller' => 'Projects']);
            } else {
                $this->Flash->error(__('The organization could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('organization'));
        $this->set('_serialize', ['organization']);
    }

    /**
     * Edit method
     *
     * @param string $id id
     *
     * @return redirect
     */
    public function edit($id = null)
    {
        $organization = $this->Organizations->get(
            $id,
            [
                'contain' => []
            ]
        );
        if ($this->request->is(['patch', 'post', 'put'])) {
            $organization = $this->Organizations->patchEntity($organization, $this->request->data);
            if ($this->Organizations->save($organization)) {
                $this->Flash->success(__('The organization has been saved.'));
                return $this->redirect(['action' => 'view', $organization->id]);
            } else {
                $this->Flash->error(__('The organization could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('organization'));
        $this->set('_serialize', ['organization']);
    }

    /**
     * Edit state method
     *
     * @return void
     */
    public function editStatus()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $data = $this->request->data;
            $organization = $this->Organizations->get(intval($data['id']), ['contain' => ['Owners']]);
            if ($data['state'] == '3') {
                $organization->editIsRejected((bool)$data['stateValue']);
                foreach ($organization->getOwners() as $owner) {
                    if ($data['stateValue']) {
                        $notifications = $this->loadModel("Notifications");
                        $notification = $notifications->newEntity();
                        $notification->editName(__("Your organization has been archived"));
                        $notification->editLink('organizations/view/' . $organization->id);
                        $notification->editUser($owner);
                        $notifications->save($notification);
                    } else {
                        $notifications = $this->loadModel("Notifications");
                        $notification = $notifications->newEntity();
                        $notification->editName(__("Your organization has been unarchived"));
                        $notification->editLink('organizations/view/' . $organization->id);
                        $notification->editUser($owner);
                        $notifications->save($notification);
                    }
                }
            } elseif ($data['state'] == '2') {
                if (!$organization->getIsValidated()) {
                    $organization->editIsValidated((bool)$data['stateValue']);
                    foreach ($organization->getOwners() as $owner) {
                        $notifications = $this->loadModel("Notifications");
                        $notification = $notifications->newEntity();
                        $notification->editName(__("Your organization has been approved"));
                        $notification->editLink('organizations/view/' . $organization->id);
                        $notification->editUser($owner);
                        $notifications->save($notification);
                    }
                }
            } else {
                $this->response->type('application/json');
                $json = json_encode(['error', __('Cannot perform the change.')]);
                $this->response->body($json);

                return $this->response;
            }
            $this->Organizations->save($organization);

            $this->response->type('application/json');
            $json = json_encode(['success', __('Your change has been saved')]);
            $this->response->body($json);

            return $this->response;
        } else {
            $this->Flash->error(__('Not an AJAX Query', true));
            $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Edit approved method
     *
     * @param string $id id
     *
     * @return redirect
     */
    public function editValidated($id)
    {
        $this->autoRender = false;
        $organization = $this->Organizations->get($id, ['contain' => ['Owners']]);
        $organization->editIsValidated(1);
        if ($this->Organizations->save($organization)) {
            foreach ($organization->getOwners() as $owner) {
                $notifications = $this->loadModel("Notifications");
                $notification = $notifications->newEntity();
                $notification->editName(__("Your organization has been approved"));
                $notification->editLink('organizations/view/' . $organization->id);
                $notification->editUser($owner);
                $notifications->save($notification);
            }
            $this->Flash->success(__('The organization has been approved.'));
            return $this->redirect(['action' => 'view', $id]);
        } else {
            $this->Flash->error(__('The organization could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit rejected method
     *
     * @param string $id id
     *
     * @return redirect
     */
    public function editRejected($id)
    {
        $this->autoRender = false;
        $organization = $this->Organizations->get($id, ['contain' => ['Owners']]);
        $organization->editIsRejected(!($organization->getIsRejected()));
        if ($this->Organizations->save($organization)) {
            foreach ($organization->getOwners() as $owner) {
                $notifications = $this->loadModel("Notifications");
                $notification = $notifications->newEntity();
                $notification->editName(__("Your organization has been archived"));
                $notification->editLink('organizations/view/' . $organization->id);
                $notification->editUser($owner);
                $notifications->save($notification);
            }
            $this->Flash->success(__('The organization has been saved.'));
            return $this->redirect(['action' => 'view', $id]);
        } else {
            $this->Flash->error(__('The organization could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string $id id
     *
     * @return redirect
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $organization = $this->Organizations->get($id);
        if ($this->Organizations->delete($organization)) {
            $this->Flash->success(__('The organization has been deleted.'));
        } else {
            $this->Flash->error(__('The organization could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Add member to the organization
     *
     * @param int $id organization id
     *
     * @return void
     */
    public function addMember($id = null)
    {
        $organization = $this->Organizations->get(
            $id,
            [
                'contain' => ['Members', 'Owners']
            ]
        );

        $usersTable = $this->loadModel("Users");
        $users = $usersTable->find('all')->toArray();
        $members = $organization->getMembers();
        $you = $this->request->session()->read('Auth.User.id');

        if ($this->request->is(['patch', 'post', 'put'])) {
            if (count($this->request->data)) {
                $usersSelected = $this->request->data['users'];

                if (!$usersTable->findById($you)->first()->hasRoleName(['Administrator'])) {
                    array_push($usersSelected, $you);
                }

                $haveOwner = $organization->modifyMembers($usersSelected);
            } else {
                if ($usersTable->findById($you)->first()->hasRoleName(['Administrator'])) {
                    $haveOwner = false;
                } else {
                    $haveOwner = $organization->modifyMembers([$you]);
                }
            }

            if ($haveOwner) {
                if ($this->Organizations->save($organization)) {
                    $this->Flash->success(__('The organization has been updated.'));
                    return $this->redirect(['action' => 'view', $organization->id]);
                } else {
                    $this->Flash->error(__('The organization could not be updated. Please,try again.'));
                }
            } else {
                $this->Flash->error(__('There must be at least one owner and one member'));
            }
        }

        $this->set(compact('organization', 'users', 'members', 'you'));
        $this->set('_serialize', ['organization']);
    }

    /**
     * Add owner to the organization
     *
     * @param int $id organization id
     *
     * @return void
     */
    public function addOwner($id = null)
    {
        $organization = $this->Organizations->get(
            $id,
            [
                'contain' => ['Owners', 'Members']
            ]
        );

        $usersTable = $this->loadModel("Users");
        $users = $organization->getMembers();

        $owners = $organization->getOwners();
        $you = $this->request->session()->read('Auth.User.id');

        if ($this->request->is(['patch', 'post', 'put'])) {
            if (count($this->request->data)) {
                $usersSelected = $this->request->data['users'];

                $organization->modifyOwners($usersSelected);

                if ($this->Organizations->save($organization)) {
                    $this->Flash->success(__('The user has been added.'));
                    return $this->redirect(['action' => 'view', $organization->id]);
                } else {
                    $this->Flash->error(__('The user could not be added. Please,try again.'));
                }
            } else {
                $this->Flash->error(__('There must be at least one owner'));
            }
        }

        $this->set(compact('organization', 'users', 'owners', 'you'));
        $this->set('_serialize', ['organization']);
    }

    /**
     * Quit method -- Allow member or owner to quit
     * If the owner is the last, it will archive the organization
     *
     * @param string $id id
     *
     * @return redirect
     */
    public function quit($id = null)
    {
        $organization = $this->Organizations->get(
            $id,
            [
                'contain' => [
                    'Owners',
                    'Members',
                    'Projects' => ['Mentors']
                ]
            ]
        );

        $users = $this->loadModel("Users")->find('all')->toArray();
        $owners = $organization->getOwners();
        $members = $organization->getMembers();
        $you = $this->request->session()->read('Auth.User.id');

        foreach ($organization->projects as $project) {
            foreach ($project->getMentors() as $mentor) {
                if ($mentor->getId() == $you) {
                    $this->Flash->error(__('You are a mentor of a project, you cannot leave the organization. You must remove yourself from the mentor list of the project to quit'));
                    return $this->redirect(['action' => 'view', $organization->id]);
                }
            }
        }


        $user = $this->loadModel("Users")->findById($this->request->session()->read('Auth.User.id'))->first();
        if ($user) {
            if (!$user->isMemberOf($organization->getId())) {
                return $this->redirect(['action' => 'view', $organization->id]);
            }
        } else {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            if (count($owners) == 1 && $user->isOwnerOf($organization->getId())) {
                $organization->editIsRejected(true);
                $organization->editMembers([]);
                $organization->editOwners([]);
            } else {
                $organization->removeMembers([$you]);
                $organization->removeOwners([$you]);
            }

            if ($this->Organizations->save($organization)) {
                $this->Flash->success(__('You have left the organization.'));

                if ($organization->isRejected) {
                    return $this->redirect(['action' => 'index']);
                } else {
                    return $this->redirect(['action' => 'view', $organization->id]);
                }
            } else {
                $this->Flash->error(__('There was an error. Please,try again.'));
            }
        }

        $this->set(compact('organization', 'you', 'user'));
        $this->set('_serialize', ['organization']);
    }
}
