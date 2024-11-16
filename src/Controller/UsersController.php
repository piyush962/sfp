<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login']);
        
    }
  
    public function login(){   
         
        $this->viewBuilder()->setLayout('login');
        $userTable = TableRegistry::getTableLocator()->get('Users');
        if($this->request->is(['post'])){
            $PostData = $this->request->getData();
            
            if($PostData['type']==1){
                $user = $userTable->find()->where(['email' => $PostData['email_reset'],'role'=>1])->first();
                if ($user) {
                    $user->password = '12345';
                    if ($userTable->save($user)) {
                        $this->Flash->success(__('Password Updated Successfully'));
                        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                    }
                }else{
                    $this->Flash->error(__('User Not Found'));
                }
            }else{
                $LoggedAttempt = $this->Authentication->getResult();
                $LogginUser = $LoggedAttempt->getData();
                
                if ($LoggedAttempt->isValid() && $LogginUser['role'] == '1') {
                    $this->Authentication->setIdentity($LogginUser);
                    $redirect = $this->request->getQuery('redirect', [
                        'controller' => 'Dashboard',
                        'action' => 'index',
                    ]);
                    return $this->redirect($redirect);
                } else {
                    $this->Flash->error(__('Invalid username or password, try again'));
                }
            }
        }
    }
    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }    

    public function index()
    {
        $UserTabel = TableRegistry::getTableLocator()->get('Users');
        $userData = $UserTabel->find()->where(['role !='=>1])->order(['id'=>'DESC']);    
        $userData = $this->Paginate($userData); 
        $pageTitle='Customers';
        $this->set(compact('userData','pageTitle'));
        
    }
    
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }
   
    public function profile()
    { 
        $userTable = TableRegistry::getTableLocator()->get('Users');
        $userData = $userTable->find()->where(['role'=>1])->first();       
        if($this->request->is(['post','put'])){          
            $postData=$this->request->getData(); 
            $file = $this->request->getData('img_product');
            if(!empty($file->getClientFilename())){
                $fileExtension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                $File_Name = time().'.'.$fileExtension;
                $uploadPath = WWW_ROOT . 'uploads' . DS;
                $file->moveTo($uploadPath . $File_Name);
                $postData['image'] = $File_Name;
            }
            
            if($postData['new_password'] != ""){
                if($postData['new_password'] == $postData['confirm_password']){
                    $postData['password'] = $postData['new_password'];
                }else{
                    $this->Flash->error(__('Password Did Not Match!'));
                    return $this->redirect(['action' => 'profile']);
                }
            }
            //pr($postData);die;
            $userProfile = $userTable->patchEntity($userData, $postData);      
            $saveduser=$userTable->save($userProfile);
            $this->Flash->success(__('Yeah! Profile Updated Successfully'));
            return $this->redirect(['action' => 'profile']);
        }
        $pageTitle='Admin Profile'; 
        $this->set(compact('userData','pageTitle'));
    }
    public function updateStatus($id)
    { 
        $userTable = TableRegistry::getTableLocator()->get('Users');
        $newUserentity = $userTable->find()->where(['id'=>$id])->first();
        if($newUserentity->status==0 ){
            $newUserentity->status= 1;
        }else{  
            $newUserentity->status= 0;
        }
        $userTable->save($newUserentity);
        $this->Flash->success(__('Status Successfully Updated'));
        return $this->redirect(['action' => 'index']);
    }
     public function addcustomer($id=null)
     {       
        $validator = new Validator();
        $validator
        ->requirePresence('name')
        ->notEmptyString('name','Please fill name')
        ->requirePresence('company_name')
        ->notEmptyString('company_name','Please fill company name')
        ->requirePresence('email')
        ->notEmptyString('email', 'Please fill email field')
        ->add('email', [
                'format' => [
                    'rule' => ['email'],
                    'message' => 'Please enter a valid email address',
                ]
            ]
        )
        ->requirePresence('phone_number')
        ->notEmptyString('phone_number', 'Please fill phone number field')
        ->add('phone_number', 'custom', [
            'rule' => function ($value, $context) {
                return (is_numeric($value) && strlen($value) === 10);
            },
            'message' => 'Please enter a 10-digit numeric phone number',
        ]);       
          $userTable = TableRegistry::getTableLocator()->get('Users');
          $UserDetailsTable = TableRegistry::getTableLocator()->get('UserDetails');      
          if($id>0){
            $newUserentity = $userTable->find()->where(['Users.id'=>$id])->contain(['UserDetails'])->first(); 
            //pr($newUserentity);die;
          }else{
            $newUserentity = $userTable->newEmptyEntity();   
          }
         $errors = [];    
         if($this->request->is(['post','put'])){
            $postData=$this->request->getData(); 
          $postData['user_id']=$this->Common->generateRandomAlphaNumeric();
            if($id == 0){
                $getEmail=$postData['email'];  
                $checkEmail=$userTable->find()->where(['email'=>$getEmail])->first();
                $postData['password'] = '12345';
               
            }else{
                $checkEmail='';
            }
            if(empty($checkEmail)){
                $errors = $validator->validate($postData);
                if(empty($errors)){
                    $postData['role']=0;
                    $customer = $userTable->patchEntity($newUserentity, $postData);
                    $saveduser=$userTable->save($customer);
                    if($saveduser){    
                        if(!empty($postData['category-group'])){
                            foreach($postData['category-group'] as $k => $v){    
                                if($v['edit_id'] == 0 || empty($v['edit_id'])){
                                $newUserDetailsentity = $UserDetailsTable->newEmptyEntity(); 
                                }else{
                                    $newUserDetailsentity = $UserDetailsTable->find()->where(['id'=>$v['edit_id']])->first();
                                }
                                $UserDetailsData = array();
                                $UserDetailsData['user_id'] = $saveduser->id;
                                $UserDetailsData['shipping_address'] = $v['shipping_address'];
                                $UserDetailsData['billing_address'] = $v['billing_address'];
                                $customerdetails = $UserDetailsTable->patchEntity($newUserDetailsentity, $UserDetailsData);
                                $UserDetailsTable->save($customerdetails);                            
                            }
                            if($id == 0){
                                $this->Common->sendcustomermail($saveduser->name,$saveduser->email,$saveduser->phone_number);
                            }
                        }
                        if($id == 0){
                            $this->Flash->success(__('Customer Successfully Added'));
                        }else{
                            $this->Flash->success(__('Customer Successfully Updated'));
                        }
                        
                        return $this->redirect(['action' => 'index']);
                    }
                }else{
                    $this->Flash->error(__('Please fill out all fields correctly..'));
                }
            }else{
                $this->Flash->error(__('This email id is already exists..'));
                return $this->redirect(['action' => 'addcustomer']);
            }            
        }
        $pageTitle='Add Customer'; 
        $this->set(compact('newUserentity','pageTitle'));       
     }
    
    public function delmultiaddress(){
        echo "data";die;
        $getId=$this->request->getData();   
        $id=$getId['id'];
        $userDetailTable=TableRegistry::getTableLocator()->get('UserDetails');
        $getData=$userDetailTable->find()->where(['id'=>$id])->first();
        if($getData){      
            $userDetailTable->delete($getData);
            echo "1";
        }else{
            echo "0";
        }
        die;
    }

    public function deleteuserdata($id){
        $userTable=TableRegistry::getTableLocator()->get('Users');
        $userDetailTable=TableRegistry::getTableLocator()->get('UserDetails');
        $getuserData=$userTable->find()->where(['id'=>$id])->first();   
        if(!empty($getuserData)){  
            $userTable->delete($getuserData); 
            $query = $userDetailTable->deleteQuery();
            $query->where(['user_id' => $id])->execute();
            $this->Flash->success(__('Data successfully deleted'));
                return $this->redirect(['action' => 'index']);
        }
    }

    public function add()
    {
        $validator = new Validator();
        $validator
        ->requirePresence('name')
        ->notEmptyString('name', 'Please fill name field')
        ->add('name', [
        'length' => [
            'rule' => ['minLength', 10],
            'message' => 'Titles need to be at least 10 characters long',
        ]
        ])
        ->requirePresence('email')
        ->notEmptyString('email', 'Please fill email field')
        ->add('email', [
            'format' => [
                'rule' => ['email', 'custom', '/^[a-z0-9]+@gmail\.com$/'],
                'message' => 'Please enter a valid email address with lowercase characters, at least one number, and use @gmail.com domain',
            ]
            ]);
        $user = $this->Users->newEmptyEntity();
        $errors = [];
        if ($this->request->is('post')) {
            $errors = $validator->validate($this->request->getData(), false);
            if (empty($errors)) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }else{
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                    return $this->redirect(['action' => 'index']);
                }
            }else{
                $this->Flash->error(__('There was something wrong.'));
                
            }
            
        }        
        $this->set(compact('user','errors'));
    }    
    public function search(){
       $this->viewBuilder()->setLayout('ajax');
        $userTable=TableRegistry::getTableLocator()->get('Users');
        $conditionArray=[
            'role !='=>1
        ];
        
        if($this->request->is('post')){
            $Data = $this->request->getData();
            $conditionArray['OR']=[['name LIKE'=>'%'. $Data['key'] . '%'],['email LIKE'=>'%'. $Data['key'] . '%'],['phone_number LIKE'=>'%'. $Data['key'] . '%']]; 
            $userData = $userTable->find()->where($conditionArray)->order(['id'=>'DESC']);
            // pr($userData->toArray());die;
            $userData = $this->Paginate($userData); 
        }  
        
        $this->set(compact('userData'));
    }
    
}

