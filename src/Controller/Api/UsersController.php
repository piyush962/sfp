<?php
declare(strict_types=1);

namespace App\Controller\Api;
use Cake\View\JsonView;
use App\Controller\AppController;
use Firebase\JWT\JWT;
use Cake\Http\Exception\NotFoundException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login','generateotp','expired','logout']);        
    }
    public function viewClasses(): array
    {
        return [JsonView::class];
    }
   
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
            $users = $this->Users->find()->where(['phone_number'=>$PostData['phone_number'],'role'=>0,'status'=>1])->first();
            if(!empty($users)){
                if($users->status==1){
                    // $OtpRandom = rand(1111,9999);
                    $OtpRandom= 1111;
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
public function logout(){
    $privateKey = file_get_contents(CONFIG . '/jwt.key');
            $decoded = JWT::decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJteWFwcCIsInN1YiI6MzAsImV4cCI6MTcxMzMxNDE1MX0.wygVZ5UQkkBJwS3fXhvL95mjJeTAtBrp5aU3z3VxcnTmEqCeWkwAK_C2v-EKuDCGwUi7vlVJ8a5FGKvPNWc34zcfZNmyUz5cb6VMiRso9cu3KW1Lfo66HTDL1KKx-ZKC_uTHGG5wIX8AQX3Bbm4N3UIiRXbXoL6U2OGxfQIx0Go', $privateKey, 'HS256');
            $this->response = $this->response->withStatus(404);   
            $Response['message'] = 'logged out';
            $this->set('Response', $Response);
            $this->viewBuilder()->setOption('serialize', 'Response');  
            echo "asdasdd";die;
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
                    'exp' => time() + 60000000,
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
            
            $Response = [
                'data' => [
                    'top_products' => array(),
                    'recent_order' => array(),
                ]
                ];
        if($userData['id'] > 0){           
            $customerid=$userData['id'];
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $userTable=TableRegistry::getTableLocator()->get('Users');
            $categoryTable=TableRegistry::getTableLocator()->get('Category');
            $productDetailsTable=TableRegistry::getTableLocator()->get('ProductDetails');
            $usersData=$userTable->find()->where(['id'=>$customerid])->first();        
            $orderData=$orderTable->find()->where(['Orders.client_id'=>$customerid])->contain('Products')->order(['Orders.id'=>'DESC'])->toArray();
            $ProcessOrders=$orderTable->find()->where(['client_id'=>$customerid,'status'=>1])->toArray();          
            $query = $orderTable->find();
            $query->select([
             'count' => $query->func()->count('*'),
             'sumOfQuantity' => $query->func()->sum('Orders.quantity'),
             'Orders.p_name',
             'Orders.id',
             'Orders.p_dimensions',
             'Products.p_name',
             'Products.id',
             'Products.image',
             'ProductDetails.p_dimension',
             'ProductDetails.id'
             ])
            ->contain(['Products','ProductDetails'])
            ->group('Orders.p_dimensions')
            ->where(['Orders.client_id'=>$customerid])
            ->orderDesc('count');
            if(!empty($usersData)){               
                $Response['data']['overview']['total_orders'] = count($orderData);
                $Response['data']['overview']['order_status'] = count($ProcessOrders);
                foreach($query as $k=>$v){
                $dimensionData=$productDetailsTable->find()->where(['id'=>$v['p_dimensions']])->first();

                    if(!empty($v['product']->image)){
                        $image= SITE_URL."uploads/".$v['product']->image;
                    }else{
                        $image='https://shorturl.at/fnuxU';
                    }
                    $Response['data']['top_products'][$k]['Order_id'] = $v['id'];
                    $Response['data']['top_products'][$k]['Product_id'] = $v['product']->id;
                    $Response['data']['top_products'][$k]['image'] = $image;
                    $Response['data']['top_products'][$k]['product_name'] = $v['product']->p_name;
                    $Response['data']['top_products'][$k]['quantity'] = $v['sumOfQuantity'];   
                    $Response['data']['top_products'][$k]['dimension_text'] = $dimensionData['p_dimension'];
                    $Response['data']['top_products'][$k]['dimension_id'] = $v['p_dimensions'];                                
                }        

                foreach($orderData as $k=>$v){
                    $IneerKey = 0;
                    $categoryData=$categoryTable->find()->where(['id'=>$v['product']->p_category])->first();
                    $dimensionData=$productDetailsTable->find()->where(['id'=>$v['p_dimensions']])->first();
                    $status = $v['status'] == 0 ? "Approval Pending" : ($v['status'] == 1 ? "In Process" : ($v['status'] == 2 ? "In Process" : ($v['status'] == 3 ? "Delivered" : "Cancel")));

                    $Response['data']['recent_order'][$k]['data']['client_id'] = $v['client_id'];
                    $Response['data']['recent_order'][$k]['data']['order_id'] = $v['id'];
                    $Response['data']['recent_order'][$k]['data']['order_number'] = $v['order_id'];
                    $Response['data']['recent_order'][$k]['data']['status'] = $status;
                    $Response['data']['recent_order'][$k]['data']['order_date'] = $v['order_date'];

                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Product Name';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['product']->p_name;
                    $IneerKey++;
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Material';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['p_material'];
                    $IneerKey++;
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Quantity';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['quantity'];
                    $IneerKey++;
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Unit Price';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['unit_price'];
                    $IneerKey++;
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Total Price';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['total_price'];
                    $IneerKey++;
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Product Category';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $categoryData['name'];
                    $IneerKey++;                    
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Dimension';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $dimensionData['p_dimension'];
                    $IneerKey++;
                    if($v['status'] == 0){
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Order Date';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = null;
                    $IneerKey++;
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Deliver Date';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = null;
                    $IneerKey++;
                    }elseif($v['status'] == 1 || $v['status'] == 2){
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Order Date';
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['order_date'];
                        $IneerKey++;
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Deliver Date';
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = null;
                        $IneerKey++;
                    }elseif($v['status'] == 3){
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Order Date';
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['order_date'];
                        $IneerKey++;
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Delivered On';
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $v['order_deadline'];
                        $IneerKey++;
                    }elseif($v['status'] == 4){
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Order Date';
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = null;
                        $IneerKey++;
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Deliver Date';
                        $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = null;
                        $IneerKey++;
                    }
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['key'] = 'Order Status';
                    $Response['data']['recent_order'][$k]['inner_data'][$IneerKey]['value'] = $status;
                    $IneerKey++;
                }                   
                    $Response['status']=true;
                    $Response['message']="retrieve overview details";
                    $this->response = $this->response->withStatus(200);                   
            }else{
                $Response['status']= false;
                $Response['message']='User Not Found';            
            }
        }else{                       
            $Response['status']= false;
            $Response['message']='Invalid access';
            $this->response = $this->response->withStatus(404);
        }       
            $this->set('Response', $Response);
            $this->viewBuilder()->setOption('serialize', 'Response');            
    }

    public function alltoporder(){
        $authenticationService = $this->request->getAttribute('authentication');
        $userData = $authenticationService->getIdentity();
        $Response = array();
        if($userData['id'] > 0){           
            $customerid=$userData['id'];
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $userTable=TableRegistry::getTableLocator()->get('Users');
            $usersData=$userTable->find()->where(['id'=>$customerid])->first();          
            $query = $orderTable->find();
            $query->select(['Orders.p_name', 'count' => $query->func()->count('*'),'sumOfQuantity' => $query->func()->sum('Orders.quantity'),'Orders.quantity','Products.p_name','Products.id','Products.image'])
            ->contain('Products')
            ->group('Orders.p_name')
            ->where(['Orders.client_id'=>$customerid])
            ->orderDesc('count');
            if(!empty($usersData)){                
                foreach($query as $k=>$v){
                    if(!empty($v['product']->image)){
                        $image= SITE_URL."uploads/".$v['product']->image;
                    }else{
                        $image='https://shorturl.at/fnuxU';
                    }
                    $Response['data']['top_products'][$k]['Product_id'] = $v['product']->id;
                    $Response['data']['top_products'][$k]['image'] = $image;
                    $Response['data']['top_products'][$k]['title'] = $v['product']->p_name;
                    $Response['data']['top_products'][$k]['quantity'] = $v['sumOfQuantity'];                                 
                }
                    $Response['status']=true;
                    $Response['message']="retrieve all top order details";
                    $this->response = $this->response->withStatus(200);                
            }else{
                $Response['status']= false;
                $Response['message']='User Not Found';            
            }
        }else{                    
            $Response['status']= false;
            $Response['message']='Invalid access';
            $this->response = $this->response->withStatus(404);
        }       
            $this->set('Response', $Response);
            $this->viewBuilder()->setOption('serialize', 'Response');            
    }

    public function allrecentorder(){
        $authenticationService = $this->request->getAttribute('authentication');
        $userData = $authenticationService->getIdentity();
        $Response = array();
        if($userData['id'] > 0){           
            $customerid=$userData['id'];
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $userTable=TableRegistry::getTableLocator()->get('Users');
            $usersData=$userTable->find()->where(['id'=>$customerid])->first();    
            $orderData=$orderTable->find()->where(['client_id'=>$customerid])->order(['id'=>'DESC'])->toArray();        
            
            if(!empty($usersData)){
                foreach($orderData as $k=>$v){
                    $status = $v['status'] == 0 ? "Approval Pending" : ($v['status'] == 1 ? "In Process" : ($v['status'] == 2 ? "In Process" : ($v['status'] == 3 ? "Delivered" : "Cancel")));
                    $Response['data']['recent_order'][$k]['client_id'] = $v['client_id'];
                    $Response['data']['recent_order'][$k]['order_id'] = $v['id'];
                    $Response['data']['recent_order'][$k]['order_number'] = $v['order_id'];
                    $Response['data']['recent_order'][$k]['estimated_time'] = $v['order_deadline'];
                    $Response['data']['recent_order'][$k]['order_status'] = $status;
                }                   
                    $Response['status']=true;
                    $Response['message']="retrieve all recent order";
                    $this->response = $this->response->withStatus(200);               
            }else{
                $Response['status']= false;
                $Response['message']='User Not Found';        
            }
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
        $orderData=$orderTable->find()->where(['Orders.client_id'=>$customerid, 'OR'=>[['Orders.status'=>0],['Orders.status'=>1],['Orders.status'=>3]]])->contain(['Products','ProductDetails'])->order(['Orders.id DESC'])->toArray();
        $Response = [
        'data' => [
            'Approval Pending' => array(),
            'In Process' => array(),
            'Delivered' => array()
        ]
        ];        
        if(!empty($orderData)){
            $a=0;
            $b=0;
            $c=0;
        foreach($orderData as $k =>$v){
            $IneerKey=0;
            $categoryData=$categoryTable->find()->where(['id'=>$v['product']->p_category])->first();
            if(!empty($categoryData)){
                $categoryName=$categoryData->name;
            }else{
                $categoryName='';
            }
            if($v->status==0){
                $Text_Status = 'Approval Pending';
                $kudos=$a;
            }elseif($v->status==1){
                $Text_Status = 'In Process';
                $kudos=$b;
                
            }elseif($v->status==3){
                $Text_Status = 'Delivered';
                $kudos=$c;
            }                

            $Response['data'][$Text_Status][$kudos]['data']['client_id'] = $v['client_id'];
            $Response['data'][$Text_Status][$kudos]['data']['order_id'] = $v['id'];
            $Response['data'][$Text_Status][$kudos]['data']['order_number'] = $v['order_id'];
            $Response['data'][$Text_Status][$kudos]['data']['status'] = $Text_Status;
            $Response['data'][$Text_Status][$kudos]['data']['order_date'] = $v['order_date'];

            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Order Status';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $Text_Status;
            $IneerKey++;
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Quantity';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['quantity'];
            $IneerKey++;
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Unit Price';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['unit_price']; 
            $IneerKey++;
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] ='Total Price';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['total_price']; 
            $IneerKey++;
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Packaging Material';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['p_material']; // packaging material
            $IneerKey++;
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Product Name';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['product']->p_name;
            $IneerKey++;
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Product Category';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $categoryName; // product category
            $IneerKey++;
            if($v['status'] == 0){
                $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Order Date';
                $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value']= null;
                $IneerKey++;
                $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Deliver Date';
                $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value']= null;
                $IneerKey++;
                }elseif($v['status'] == 1 || $v['status'] == 2){
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Order Date';
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value']= $v['order_date'];
                    $IneerKey++;
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Deliver Date';
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = null;
                    $IneerKey++;
                }elseif($v['status'] == 3){
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Order Date';
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['order_date'];
                    $IneerKey++;
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Delivered On';
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['order_deadline'];
                    $IneerKey++;
                }elseif($v['status'] == 4){
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Order Date';
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = null;
                    $IneerKey++;
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Deliver Date';
                    $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = null;
                    $IneerKey++;
                }
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['key'] = 'Packaging Dimension';
            $Response['data'][$Text_Status][$kudos]['inner_data'][$IneerKey]['value'] = $v['product_detail']->p_dimension;
            $IneerKey++;
            if($v->status==0){
                $a++;                     
                }elseif($v->status==1){
                $b++;                     
                }elseif($v->status==3){
                $c++;
                }
        }
        }else{
        $Response['status']= false;
        $Response['message']='User Not Found';
        }
        $Response['status']=true;
        $Response['message']="retrieve order history";
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
           
 $Response = [
                'data' => [
                    'All_Products' => array(),                ]
                ];
        if($userData['id'] > 0){
            $customerid=$userData['id'];
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $productTable=TableRegistry::getTableLocator()->get('Products');
  $productDetailsTable=TableRegistry::getTableLocator()->get('ProductDetails');
            $query = $orderTable->find();
            $query->select([
                'Orders.p_name',
                'Orders.id',
                'sumOfQuantity' => $query->func()->sum('Orders.quantity'), 
                'count' => $query->func()->count('*'),
                'Orders.client_id',
                'Products.p_name',
                'Products.id',
                'Products.image',
'Orders.p_dimensions',
             

            ])
             ->contain(['Products'])
            ->group('Orders.p_dimensions')
            ->where(['Orders.client_id'=>$customerid])
            ->orderDesc('count');
           
        foreach($query as $k => $v){

$dimensionData=$productDetailsTable->find()->where(['id'=>$v['p_dimensions']])->first(); 
        if(!empty($v['product']->image)){
                $image= SITE_URL."uploads/".$v['product']->image;
            }else{
                $image='https://shorturl.at/fnuxU';
            }
            $Response['data']['All_Products'][$k]['order_id']=$v['id'];
            $Response['data']['All_Products'][$k]['product_id']=$v['p_name'];
            $Response['data']['All_Products'][$k]['client_id']=$v['client_id'];
            $Response['data']['All_Products'][$k]['product_image']=$image;
            $Response['data']['All_Products'][$k]['product_name']=$v['product']->p_name;
            $Response['data']['All_Products'][$k]['ordered_qty']=$v['sumOfQuantity'];
$Response['data']['All_Products'][$k]['dimension_id']=$v['p_dimensions'];
            $Response['data']['All_Products'][$k]['dimension_text']=$dimensionData->p_dimension;
        }
        $Response['status']=true;
        $Response['message']="retrieve all products";
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
            $productDetailsTable=TableRegistry::getTableLocator()->get('ProductDetails');
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $query=$orderTable->find();
            $query->select([
                'Orders.id',
                'Orders.p_material',
                'Orders.send_reminder',
                'Products.p_name',
                'Products.id',
                'Products.image',
                'Products.p_category',
                'Orders.client_id',
                'Orders.quantity',
                'Orders.unit_price',
                'Orders.total_price',
                'Orders.order_date',
                'Orders.order_deadline',
                'Orders.p_dimensions',
                ])
                ->contain(['Products'])
                ->where(['Orders.id'=>$orderId]);
               
        $Response=array();


          if(!empty($query)){
            foreach($query as $k => $v){
                $IneerKey=0;
            
    $dimensionData=$productDetailsTable->find()->where(['id'=>$v['p_dimensions']])->first(); 
                if(!empty($v['product']->image)){
                    $image= SITE_URL."uploads/".$v['product']->image;
                }else{
                    $image='https://shorturl.at/fnuxU';
                }
                $NotifyDurations=TableRegistry::getTableLocator()->get('NotifyDurations');
                $NotifyDurationsData=$NotifyDurations->find()->where(['id'=>$v['send_reminder']])->first();
                
                if($NotifyDurationsData){
                    $notificationduractiontext=$NotifyDurationsData['durations'];
                }else{
                    $notificationduractiontext='';
                }
               
                $categoryData=$categoryTable->find()->where(['id'=>$v['product']->p_category])->first();
                $productDetailsData=$productDetailsTable->find()->where(['product_id'=>$v['product']->id])->first();
                $Response['data']['order_summery'][$k]['data']['product_id']=$v['product']->id;
                $Response['data']['order_summery'][$k]['data']['client_id']=$v['client_id'];
                $Response['data']['order_summery'][$k]['data']['order_id']=$v['id'];
                $Response['data']['order_summery'][$k]['data']['product_image']=$image;
                $Response['data']['order_summery'][$k]['data']['product_name']=$v['product']->p_name;
                $Response['data']['order_summery'][$k]['data']['set_reminder_id']=$v['send_reminder'];
                $Response['data']['order_summery'][$k]['data']['set_reminder_text']=$notificationduractiontext;

                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Product Name';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['product']->p_name;
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Unit Price';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['unit_price'];
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Total Price';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['total_price'];
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Quantity';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['quantity'];
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Order Date';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['order_date'];
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Order Date Deadline';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['order_deadline'];
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Product Material';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$v['p_material'];
                $IneerKey++;
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Category Name';
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$categoryData->name;
                $IneerKey++;
                
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['key']='Packaging Dimension';    
                $Response['data']['order_summery'][$k]['inner_data'][$IneerKey]['value']=$dimensionData->p_dimension; 
                $IneerKey++;
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
        $notificationTable=TableRegistry::getTableLocator()->get('Notifications');
            $orderTable=TableRegistry::getTableLocator()->get('Orders');
            $userTable=TableRegistry::getTableLocator()->get('Users');
            $productTabel = TableRegistry::getTableLocator()->get('Products');
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
                $findDataArray['unit_price']=$findData['unit_price'];
                $findDataArray['total_price']=$PostData['quantity'] * $findData['unit_price'];
                $findDataArray['status']=0;
                $OrderPatchEntity=$orderTable->patchEntity($orderNewEntity,$findDataArray);
                $savedOrder=$orderTable->save($OrderPatchEntity);
            if($savedOrder){
                $userData=$userTable->find()->where(['id'=>$savedOrder['client_id']])->first();
                $adminData=$userTable->find()->where(['role'=>1])->first();
                $productName=$productTabel->find()->where(['id'=>$findDataArray['p_name']])->first();
                $this->Common->sendordermail($userData->name,$userData->email,$savedOrder['id'],$adminData['email'],date("Y-m-d",strtotime($findDataArray['order_date'])) ,date("Y-m-d",strtotime($findDataArray['order_deadline'])),'addorderbyclient',$productName['p_name'],$PostData['quantity']);
                // $this->Common->sendordermail($userdata->name,$userdata->email,$orderSaved->order_id,$admindata['email'],date("Y-m-d",strtotime($postData['order_date'])) ,date("Y-m-d",strtotime($postData['order_deadline'])),'addorderbyadmin',$productName['p_name'],$postData['quantity']);

                $notifydata=array();
                $notificationNewEntity=$notificationTable->newEmptyEntity();
                
                $notifydata['customer_id']=$savedOrder['client_id']; 
                $notifydata['order_id']=$savedOrder['id'];    
                $notifydata['client_message']="You Have updated the quantity of  ".$savedOrder['packaging_name'].', updated quantity is '.$savedOrder['quantity']." for this order #".$savedOrder['id']; 
                $notifydata['admin_message']=$userData['name']." has requested more orders, with the updated quantity of ".$savedOrder['packaging_name'].', Updated Quantity is '.$savedOrder['quantity']." for this order #".$savedOrder['id']; 

                $notifydata['type']='createorder';
                $notifydata['notification_date']=Date('Y-m-d');
                $notificationPatchEntity=$notificationTable->patchEntity($notificationNewEntity,$notifydata);               
                $saveNotification=$notificationTable->save($notificationPatchEntity);
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
                $image='https://shorturl.at/fnuxU';
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
            $Response['data']['total_price']=$query['total_price'];
            $Response['data']['order_date']=$query['order_date'];
            $Response['data']['order_deadline']=$query['order_deadline'];
            $ApiStatus = 200;
            $Response['status'] = true;
            $Response['message'] = 'getting order details';
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
                $notifyDurationTabel=TableRegistry::getTableLocator()->get('NotifyDurations');
                $userTable=TableRegistry::getTableLocator()->get('Users');
                $orderTable=TableRegistry::getTableLocator()->get('Orders');
                $notificationNewEntity=$notificationTable->newEmptyEntity();
                $postData=$this->request->getData();       
                $userName=$userTable->find()->where(['id'=>$customerid])->first();
                $orderId=$orderTable->find()->where(['id'=>$postData['order_id']])->first();
            $ApiResponse = array();
            if(!empty($postData['order_id']) && !empty($postData['notify_in'])){
                $notificationDate=$postData['notify_in'];
                $notifyData=$notifyDurationTabel->find()->where(['id'=>$postData['notify_in']])->first();            
                $durationString = explode(' ',$notifyData['durations']);
                $currentDate=Date('Y-m-d');
                if($durationString[1] == 'days'){
                    $notificationDate=date('Y-m-d', strtotime($currentDate. ' + '.$durationString[0].' day'));
                    // We'll send you a reminder in (default value) months before the item is expected to go out of stock.
                }else{
                    $notificationDate=date('Y-m-d', strtotime($currentDate. ' + '.$durationString[0].' month'));
                }
                // pr($notificationDate);die;
                $postData['client_message'] = 'New Order Created By ' . $userName['name'] . ' [ Order Id #'.$postData['order_id'].' ] Now Your Order On Approval Pending';
                $postData['admin_message'] = $userName['name'] ." Demand More Orders [ Order Id ".$postData['order_id']. "]";
                $postData['type']='notify_me';
                $postData['customer_id']=$customerid;
                $postData['notification_date']=$notificationDate;
                $notificationPatchEntity=$notificationTable->patchEntity($notificationNewEntity,$postData);
               
                $saveNotification=$notificationTable->save($notificationPatchEntity);
                if($saveNotification){
                    $orderData=$orderTable->find()->where(['id'=>$postData['order_id']])->first();
                    $postData['send_reminder']=$postData['notify_in'];
                    $orderPatchEntity=$orderTable->patchEntity($orderData,$postData);
                    $savereminder=$orderTable->save($orderPatchEntity);
                }
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
        $authenticationService = $this->request->getAttribute('authentication');
            $userData = $authenticationService->getIdentity();               
            $customerid=$userData['id'];
        $postData=$this->request->getData();
        $userTable=TableRegistry::getTableLocator()->get('Users');
        $userEntity=$userTable->find()->where(['id'=>$customerid])->first();
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
    public function notifyduration(){
        $notifydurationTable=TableRegistry::getTableLocator()->get('NotifyDurations');
        $durationdata=$notifydurationTable->find()->toArray();
        $Response=[];
        if(!empty($durationdata)){
        foreach($durationdata as $k => $v){
          $Response['data'][$k]['id']=$v['id'];
          $Response['data'][$k]['duration']=$v['durations'];
        }
        
        $Response['status']= true;
        $Response['message']= "Getting Notify Durations";
        $this->response = $this->response->withStatus(200);
      }else{
        $Response['status']= false;
        $Response['message']= "Data Not Found!";
        $this->response = $this->response->withStatus(404);
      }
        
        $this->set('Response',$Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
       
    }
   

    public function getalert(){
        $authenticationService = $this->request->getAttribute('authentication');
        $userData = $authenticationService->getIdentity();
        $ordersTable=TableRegistry::getTableLocator()->get('Orders');
        if($userData['id'] > 0){  
            $Response=array();         
            $customerid=$userData['id'];
            $notificationTable=TableRegistry::getTableLocator()->get('Notifications');
            $notifyData=$notificationTable->find()->where(['customer_id'=>$customerid])->order(['id'=>'DESC'])->toArray();
            $n=0;
            foreach($notifyData as $k=>$v){
                
                $orderdata=$ordersTable->find()->where(['Orders.id'=>$v['order_id']])->contain('Products')->first();
                if(!empty($orderdata)){
                    $Response['data'][$n]['type']=$v['type'];          
                $Response['data'][$n]['customer_id']=$v['customer_id'];
                $Response['data'][$n]['order_id']=$v['order_id'];
                $Response['data'][$n]['packaging_name']=$orderdata['packaging_name'];
                $Response['data'][$n]['product_name']=$orderdata->product['p_name'];
                $Response['data'][$n]['client_message']=$v['client_message'];
                $Response['data'][$n]['admin_message']=$v['admin_message'];
                $Response['data'][$n]['notification_date']=$v['notification_date']; 
                $n++;                
                }
                         
            }
                $Response['status']= true;
                $Response['message']='retrieve all alerts';
                $this->response = $this->response->withStatus(200);
        }else{
                $Response['status']= false;
                $Response['message']='Invalid User Access!';
                $this->response = $this->response->withStatus(400);
        }
        $this->set('Response',$Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    }
    public function expired(){
        $Response['status']= false;
        $Response['message']='Invalid User Access!';
        $this->response = $this->response->withStatus(401);
        $this->set('Response',$Response);
        $this->viewBuilder()->setOption('serialize', 'Response');
    }
    
}
