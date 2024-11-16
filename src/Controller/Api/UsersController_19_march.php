<?php
declare(strict_types=1);

namespace App\Controller\Api;
use Cake\View\JsonView;
use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Http\Exception\NotFoundException;
use Cake\Datasource\Exception\RecordNotFoundException;
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
        
        $this->Authentication->addUnauthenticatedActions(['login','generateotp']);
        
    }
    public function viewClasses(): array
    {
        return [JsonView::class];
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->Users->find();
        $this->set(compact('users'));
        $this->viewBuilder()->setOption('serialize', ['users']);
    }

    public function generateotp(){
        $PostData = $this->request->getData();
        $ApiResponse = array();
        if(!empty($PostData['phone_number'])){
            $users = $this->Users->find()->where(['phone_number'=>$PostData['phone_number'],'role'=>0])->first();
            if(!empty($users)){
                if($users->status==1){
                    $OtpRandom = rand(1111,9999);
                    $users->otp = $OtpRandom;
                    $this->Users->save($users);
                    $ApiStatus = 200;
                    $ApiResponse['status'] = true;
                    $ApiResponse['message'] = 'OTP Has Been Sent';
                    $ApiResponse['otp'] = $OtpRandom;
                    
                }else{
                    $ApiStatus = 200;
                    $ApiResponse['status'] = false;
                    $ApiResponse['message'] = 'Account In Inactive';
                    
                }
            }else{
                $ApiStatus = 200;
                $ApiResponse['status'] = false;
                $ApiResponse['message'] = 'User Not Found';
                
                
            }
        }else{
            $ApiStatus = 400;
            $ApiResponse['status'] = false;
            $ApiResponse['message'] = 'Invalid Request';
            
        }
        $this->response = $this->response->withStatus($ApiStatus);
        $this->set('ApiResponse', $ApiResponse);
        $this->viewBuilder()->setOption('serialize', 'ApiResponse');
    }

    public function login(){        
        $PostData = $this->request->getData();
        if(!empty($PostData['phone_number']) && !empty($PostData['otp'])){
            $users = $this->Users->find()->where(['phone_number'=>$PostData['phone_number'] , 'otp'=>$PostData['otp']])->first();
            if(!empty($users)){
                $privateKey = file_get_contents(CONFIG . '/jwt.key');
                $payload = [
                    'iss' => 'myapp',
                    'sub' => $users->id,
                    'exp' => time() + 60000,
                ];
                $ApiStatus = 200;
                $ApiResponse['status'] = true;
                $ApiResponse['message'] = 'Successfully Logged In';
                $ApiResponse['token'] = JWT::encode($payload, $privateKey, 'RS256');
                
            }else{
                $ApiStatus = 200;
                $ApiResponse['status'] = false;
                $ApiResponse['message'] = 'Invalid OTP';
                
            }
        }else{
            $ApiStatus = 400;
            $ApiResponse['status'] = false;
            $ApiResponse['message'] = 'Invalid Request';
            
        }
        $this->response = $this->response->withStatus($ApiStatus);
        $this->set('ApiResponse', $ApiResponse);
        $this->viewBuilder()->setOption('serialize', 'ApiResponse');
    }

    public function overviewdetails(){
        $authenticationService = $this->request->getAttribute('authentication');
        $userData = $authenticationService->getIdentity();
        if($userData['id'] > 0){
            $customerid=$userData['id'];
        $orderTable=TableRegistry::getTableLocator()->get('Orders');
        $userTable=TableRegistry::getTableLocator()->get('Users');
        $userData=$userTable->find()->where(['id'=>$customerid])->first();
      
        $orderData=$orderTable->find()->where(['client_id'=>$customerid])->order(['id'=>'DESC'])->toArray();
        $ProcessOrders=$orderTable->find()->where(['client_id'=>$customerid,'status'=>1])->toArray();          
        $query = $orderTable->find();
        $query->select(['Orders.p_name', 'count' => $query->func()->count('*'),'Orders.quantity','Products.p_name','Products.id','Products.image'])
        ->contain('Products')
        ->group('Orders.p_name')
        ->where(['Orders.client_id'=>$customerid])
        ->orderDesc('count')
        ->limit(2);
        $Response = array();
        if(!empty($userData)){
        
        $Response['data']['overview']['total_orders'] = count($orderData);
        $Response['data']['overview']['orders_in_process'] = count($ProcessOrders);
        foreach($query as $k=>$v){
            if(!empty($v['product']->image)){
                $image= SITE_URL."uploads/".$v['product']->image;
            }else{
                $image='';
            }
            $Response['data']['top_products'][$k]['Product_id'] = $v['product']->id;
            $Response['data']['top_products'][$k]['image'] = $image;
            $Response['data']['top_products'][$k]['title'] = $v['product']->p_name;
            $Response['data']['top_products'][$k]['quantity'] = $v['quantity'];
        }
        foreach($orderData as $k=>$v){
        $status = $v['status'] == 1 ? "In Process" : ($v['status'] == 2 ? "Completed" : "Cancel");
        $Response['data']['recent_order'][$k]['client_id'] = $v['client_id'];
        $Response['data']['recent_order'][$k]['order_id'] = $v['id'];
        $Response['data']['recent_order'][$k]['order_number'] = $v['order_id'];
        $Response['data']['recent_order'][$k]['estimated_time'] = $v['order_deadline'];
        $Response['data']['recent_order'][$k]['orders_in_process'] = $status;
        }
    }else{
        $Response['status']= false;
        $Response['message']='User Not Found';
    }
    $Response['status']=true;
    $Response['data']="retrieve overview details";
    $this->response = $this->response->withStatus(200);
}else{
        $Response['status']= false;
        $Response['message']='Invalid access';
        $this->response = $this->response->withStatus(404);
}
        
        $this->set('Response', $Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    }
    public function orderhistory(){
        $authenticationService = $this->request->getAttribute('authentication');
        $userData = $authenticationService->getIdentity();
        if($userData['id'] > 0){
            $customerid=$userData['id'];
        $orderTable=TableRegistry::getTableLocator()->get('Orders');
        $categoryTable=TableRegistry::getTableLocator()->get('Category');
        $orderData=$orderTable->find()->where(['Orders.client_id'=>$customerid])->contain(['Products','ProductDetails'])->order(['Orders.id DESC'])->toArray();
        $Response = [
            'data' => [
                'In Process' => array(),
                'Completed' => array()
            ]
        ];
        if(!empty($orderData)){
                
            foreach($orderData as $k =>$v){
                $categoryData=$categoryTable->find()->where(['id'=>$v['product']->p_category])->first();
                if(!empty($categoryData)){
                    $categoryName=$categoryData->name;
                }else{
                    $categoryName='';
                }
                if($v->status==1){
                    $Text_Status = 'In Process';
                    
                }elseif($v->status==2){
                    $Text_Status = 'Completed';
                }
                
                $Response['data'][$Text_Status][$k]['client_id'] = $v['client_id'];
                $Response['data'][$Text_Status][$k]['order_id'] = $v['id'];
                $Response['data'][$Text_Status][$k]['order_number'] = $v['order_id'];
                $Response['data'][$Text_Status][$k]['estimated_time'] = $v['order_deadline'];
                $Response['data'][$Text_Status][$k]['orders_in_process'] = $Text_Status;
                $Response['data'][$Text_Status][$k]['quantity'] = $v['quantity'];
                $Response['data'][$Text_Status][$k]['unit_price'] = $v['unit_price'];
                $Response['data'][$Text_Status][$k]['packaging_material'] = $v['p_material'];
                $Response['data'][$Text_Status][$k]['product_category'] = $categoryName;
                $Response['data'][$Text_Status][$k]['order_date'] = $v['order_date'];
                $Response['data'][$Text_Status][$k]['order_deadline'] = $v['order_deadline'];
                $Response['data'][$Text_Status][$k]['packaging_dimensions'] = $v['product_detail']->p_dimension;
            }
        }else{
            $Response['status']= false;
            $Response['message']='User Not Found';
        }
        $Response['status']=true;
        $Response['data']="retrieve order history";
        $this->response = $this->response->withStatus(200);
    }else{
        $Response['status']= false;
        $Response['message']='Invalid access';
        $this->response = $this->response->withStatus(404);
    }        
        $this->set('Response', $Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    }
    public function allproducts(){
            $authenticationService = $this->request->getAttribute('authentication');
            $userData = $authenticationService->getIdentity();
            $Response=array();
        if($userData['id'] > 0){
            $customerid=$userData['id'];
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $productTable=TableRegistry::getTableLocator()->get('Products');
            $query = $orderTable->find();
            $query->select([
                'Orders.p_name',
                'Orders.id',
                'sumOfQuantity' => $query->func()->sum('Orders.quantity'), 
                'count' => $query->func()->count('*'),
                'Orders.client_id',
                'Products.p_name',
                'Products.id',
                'Products.image'
            ])
            ->contain('Products')
            ->group('Orders.p_name')
            ->where(['Orders.client_id'=>$customerid])
            ->orderDesc('count');
           
        foreach($query as $k => $v){
            if(!empty($v['product']->image)){
                $image= SITE_URL."uploads/".$v['product']->image;
            }else{
                $image='';
            }
            $Response['data']['All_Products'][$k]['order_id']=$v['id'];
            $Response['data']['All_Products'][$k]['product_id']=$v['p_name'];
            $Response['data']['All_Products'][$k]['client_id']=$v['client_id'];
            $Response['data']['All_Products'][$k]['product_image']=$image;
            $Response['data']['All_Products'][$k]['product_name']=$v['product']->p_name;
            $Response['data']['All_Products'][$k]['ordered_qty']=$v['sumOfQuantity'];
            
        }
        $Response['status']=true;
        $Response['data']="retrieve all products";
        $this->response = $this->response->withStatus(200);
    }else{
        $Response['status']= false;
        $Response['message']='Invalid access';
        $this->response = $this->response->withStatus(404);
    }
        $this->set('Response',$Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    }
    public function ordersummery($orderId){
            $productTable=TableRegistry::getTableLocator()->get('Products');
            $categoryTable=TableRegistry::getTableLocator()->get('Category');
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
          $query=$orderTable->find();
          $query->select([
            'Orders.id',
            'Products.id',
            'Products.image',
            'Products.p_category',
            'Orders.client_id',
            'ProductDetails.product_id',
            'ProductDetails.p_dimension'
            ])
            ->contain(['Products','ProductDetails'])
            ->where(['Orders.id'=>$orderId]);
        $Response=array();
          if(!empty($query)){
            foreach($query as $k => $v){
                if(!empty($v['product']->image)){
                    $image= SITE_URL."uploads/".$v['product']->image;
                }else{
                    $image='';
                }
                $categoryData=$categoryTable->find()->where(['id'=>$v['product']->p_category])->first();
                $Response['data']['order_summery'][$k]['product_id']=$v['product']->id;
                $Response['data']['order_summery'][$k]['client_id']=$v['client_id'];
                $Response['data']['order_summery'][$k]['order_id']=$v['id'];
                $Response['data']['order_summery'][$k]['category_name']=$categoryData->name;
                $Response['data']['order_summery'][$k]['product_image']=$image;
                $Response['data']['order_summery'][$k]['packaging_dimension']=$v['product_detail']->p_dimension;            
              }
                $Response['status']= true;
                $Response['message']='retrieve order summery';
                $this->response = $this->response->withStatus(200);
          }else{
                $Response['status']= false;
                $Response['message']='Invalid access';
                $this->response = $this->response->withStatus(404);
                }
                $this->response=$this->response->withStatus(200);
                $this->set('Response',$Response);
                $this->viewBuilder()->setOption('serialize', 'Response');
    }
    public function repeatorder(){
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $orderNewEntity=$orderTable->newEmptyEntity();
            $PostData = $this->request->getData();
            $ApiResponse = array();
            
        if(!empty($PostData['order_id']) && !empty($PostData['quantity'])){
                $findData=$orderTable->find()->where(['id'=>$PostData['order_id']])->first();
           if(!empty($findData)){
                $findDataArray=$findData->toArray();
                $findDataArray['quantity']=$PostData['quantity'];
                $findDataArray['order_id']=$this->Common->generateRandomAlphaNumeric();           
                $interval = date_diff($findData['order_deadline'], $findData['order_date']);
                $differenceInDays = $interval->format('%a');
                $currentDate=Date('Y-m-d H:i:s');
                $findDataArray['order_date']=$currentDate;
                $findDataArray['order_deadline']= date('Y-m-d H:i:s', strtotime($currentDate. ' + '.$differenceInDays.' days'));
                $findDataArray['unit_price']=$PostData['quantity'] * $findData['unit_price'];
                $OrderPatchEntity=$orderTable->patchEntity($orderNewEntity,$findDataArray);
                $savedOrder=$orderTable->save($OrderPatchEntity);

            if($savedOrder){
                $ApiStatus = 200;
                $ApiResponse['status'] = true;
                $ApiResponse['message'] = 'Order Submitted Successfully';
            }
           }else{
                $ApiStatus = 200;
                $ApiResponse['status'] = false;
                $ApiResponse['message'] = 'Order Not Found';            
           }          
        }else{
                $ApiStatus = 400;
                $ApiResponse['status'] = false;
                $ApiResponse['message'] = 'Order is Empty';
        }        
            $this->response = $this->response->withStatus($ApiStatus);
            $this->set('ApiResponse', $ApiResponse);
            $this->viewBuilder()->setOption('serialize', 'ApiResponse');
            
    }
    public function getdatafornotify($orderId){
        $productTable=TableRegistry::getTableLocator()->get('Products');
        $categoryTable=TableRegistry::getTableLocator()->get('Category');
        $orderTable=TableRegistry::getTableLocator()->get('Orders');
        $query=$orderTable->find()->where(['Orders.id'=>$orderId])->contain(['Products','ProductDetails'])->first();
     
        $Response=array();
            if($query['product']->p_category > 0){
            $categoryData=$categoryTable->find()->where(['id'=>$query['product']->p_category])->first();
            $categoryName=$categoryData->name;
            }else{
                $categoryName='';
            }
            if(!empty($query['product']->image)){
                $image= SITE_URL."uploads/".$query['product']->image;
            }else{
                $image='';
            }
        if(!empty($query)){
            $Response['data']['product_id']=$query['product']->id;
            $Response['data']['client_id']=$query['client_id'];
            $Response['data']['order_id']=$query['id'];
            $Response['data']['category_name']=$categoryName;
            $Response['data']['product_image']=$image;
            $Response['data']['packaging_dimension']=$query['product_detail']->p_dimension;
            $Response['data']['quantity']=$query['quantity'];
            $Response['data']['unit_price']=$query['unit_price'];
            $Response['data']['order_date']=$query['order_date'];
            $Response['data']['order_deadline']=$query['order_deadline'];
            $ApiStatus = 200;
            $Response['status'] = true;
            $Response['message'] = 'getting order detail';
        }else{
            $ApiStatus = 400;
            $Response['status'] = false;
            $Response['message'] = 'Order is Empty';
        }
        $this->response=$this->response->withStatus($ApiStatus);
        $this->set('Response',$Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    }
    public function submitnotify(){   
        $authenticationService = $this->request->getAttribute('authentication');
        $userData = $authenticationService->getIdentity();
        if($userData['id'] > 0){
            $customerid=$userData['id']; 
                $notificationTable=TableRegistry::getTableLocator()->get('Notifications');
                $userTable=TableRegistry::getTableLocator()->get('Users');
                $notificationNewEntity=$notificationTable->newEmptyEntity();
                $postData=$this->request->getData();       
                $userName=$userTable->find()->where(['id'=>$customerid])->first();
       
            $ApiResponse = array();
            if(!empty($postData['order_id']) && !empty($postData['notify_in'])){
                $notificationDate=$postData['notify_in'];
                $currentDate=Date('Y-m-d');
                $notificationDate=date('Y-m-d', strtotime($currentDate. ' + '.$notificationDate.' month'));
                $postData['message']='hello'.' '.$userName['name'];
                $postData['type']=1;
                $postData['customer_id']=$customerid;
                $postData['notification_date']=$notificationDate;
                $notificationPatchEntity=$notificationTable->patchEntity($notificationNewEntity,$postData);
               
                $saveNotification=$notificationTable->save($notificationPatchEntity);
                pr($saveNotification);die;
                $ApiStatus = 200;
                $ApiResponse['status'] = true;
                $ApiResponse['message'] = 'confirmed your order';
            }else{
                $ApiStatus = 400;
                $ApiResponse['status'] = false;
                $ApiResponse['message'] = 'Invalid order access';
            }
        }else{
                $ApiStatus = 400;
                $ApiResponse['status'] = false;
                $ApiResponse['message'] = 'Invalid user access';
            }
        
           $this->response = $this->response->withStatus($ApiStatus);
           $this->set('ApiResponse', $ApiResponse);
           $this->viewBuilder()->setOption('serialize', 'ApiResponse');
    }
    
    public function getuserdetails(){
            $authenticationService = $this->request->getAttribute('authentication');
            $userData = $authenticationService->getIdentity();
            $Response=array();
        if($userData['id'] > 0){
            $customerid=$userData['id'];
            $userTable=TableRegistry::getTableLocator()->get('Users');
            $userData=$userTable->find()->where(['id'=>$customerid])->first();
            
            if(!empty($userData)){  
                $Response['id']=$userData['id'];
              $Response['name']=$userData['name'];
              $Response['email']=$userData['email'];
              $Response['phone_number']=$userData['phone_number'];
              $Response['company_name']=$userData['company_name'];
            }
            $this->response = $this->response->withStatus(200);
            $Response['status']= true; 
            $Response['message']= 'retrieve user data'; 
        }else{
            $Response['status']= false;
            $Response['message']='Invalid access';
            $this->response = $this->response->withStatus(404);
        }
            $this->set('Response',$Response);
            $this->viewBuilder()->setOption('serialize', 'Response');
        
    }
    public function edituserdetails(){
        $postData=$this->request->getData();
        $userTable=TableRegistry::getTableLocator()->get('Users');
        $userEntity=$userTable->find()->where(['id'=>$postData['id']])->first();
        if(!empty($userEntity)){
            $Response=array();
           $userPatchEntity=$userTable->patchEntity($userEntity,$postData);
          $updateuser=$userTable->save($userPatchEntity);
          if($updateuser){
            $Response['status']= true;
            $Response['message']= "Profile successfully updated";
            $this->response = $this->response->withStatus(200);
          }else{
            $Response['status']= false;
            $Response['message']= "Profile not updated";
            $this->response = $this->response->withStatus(401);
          }
        }else{
            $Response['status']= false;
            $Response['message']= "Invalid access!";
            $this->response = $this->response->withStatus(404);
        }
    
        $this->set('Response',$Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    
    }
    
}
