<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\ORM\TableRegistry;

class OrdersController extends AppController
{
    
    public function index($customerid=null)
    {
        $pageActive = $this->request->getParam('page');

    if ($pageActive && $pageActive > 0) {
        echo $pageActive;
        die;
    }
        $orderTabel = TableRegistry::getTableLocator()->get('Orders');
        $userTabel = TableRegistry::getTableLocator()->get('Users');
        if($customerid > 0){
            $ConditionArray = [
                'Orders.client_id'=>$customerid
            ];
            if(isset($_GET['orderid']) && !empty($_GET['orderid']) && $_GET['orderid']>0){
                $ConditionArray['Orders.id'] = $_GET['orderid'];
            }
            $orderData = $orderTabel->find()->contain(['Users'])->where($ConditionArray)->order(['Orders.id'=>'DESC']);
            $customerDetails=$userTabel->find()->where(['id'=>$customerid])->first();
        }else{
            $ConditionArray = [
                'Orders.id >'=>0
            ];
            if(isset($_GET['orderid']) && !empty($_GET['orderid']) && $_GET['orderid']>0){
                $ConditionArray['Orders.id'] = $_GET['orderid'];
            }
            $orderData = $orderTabel->find()->contain(['Users'])->where($ConditionArray)->order(['Orders.id'=>'DESC']); 
            $customerDetails=[];
        } 
     
        $orderData = $this->Paginate($orderData);  
        $pageTitle='Orders'; 
        $this->set(compact('pageTitle', 'orderData', 'customerDetails'));
    }
    //  order module start...................
    public function add($Customer_id=null,$orderid=null)
    { 
        // pr($_REQUEST['reorder']);die;

        $catTable = TableRegistry::getTableLocator()->get('Category');
        $userTable = TableRegistry::getTableLocator()->get('Users');
        $userDetailsTable = TableRegistry::getTableLocator()->get('UserDetails');
        $orderTable=TableRegistry::getTableLocator()->get('Orders');
        $productTabel = TableRegistry::getTableLocator()->get('Products');
        $notificationTable=TableRegistry::getTableLocator()->get('Notifications');
        $adminData =$userTable->find()->where(['role' => 1])->first();
        $productDetailsTabel = TableRegistry::getTableLocator()->get('ProductDetails');
        $notifyDurationTabel = TableRegistry::getTableLocator()->get('NotifyDurations');
        $durationData=$notifyDurationTabel->find('list',
        keyField:'id',
        valueField:'durations'
        )->toArray();
        if($orderid > 0){
            $orderNewEntity=$orderTable->find()->where(['id'=>$orderid])->first();
            $Alldimension = $productDetailsTabel->find('list', keyField: 'id', valueField: 'p_dimension')->where(['product_id'=>$orderNewEntity->p_name]);
          
        }else{
            $orderNewEntity=$orderTable->newEmptyEntity();
            $Alldimension=[]; 
        } 
        if($Customer_id > 0){
            $AllAddress = $userDetailsTable->find('list',
                keyField:'id',
                valueField:'shipping_address',
            )->where(['user_id'=>$Customer_id]);
            $AllProductName = $productTabel->find('list',
                keyField:'id',
                valueField:'p_name',
            )->where(['client_id'=>$Customer_id]);
            $cusName=$userTable->find()->where(['id'=>$Customer_id])->first();
        }else{
            $AllAddress=[]; 
            $AllProductName=[]; 
        }   
        $customerName = $userTable->find('list', keyField: 'id', valueField: 'name')->where(['role'=>0]);
        // $customerName=$userTable->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role'=>0]);          
        if($this->request->is(['post','put'])){
            $postData=$this->request->getData();
            if($orderid == 0){        
                $postData['order_id'] = $this->Common->generateRandomAlphaNumeric();
            }                    
            $time = date('H:i:s');
            $postData['order_date'] =date('Y-m-d H:i:s', strtotime($postData['order_date'].' '.$time));
            $postData['order_deadline']=date('Y-m-d H:i:s', strtotime($postData['order_deadline'].' '.$time));  
            $postData['unit_price']=$postData['unit_price']*$postData['quantity'];            
      
            $cusName=$userTable->find()->where(['id'=>$postData['client_id']])->first();
            if(isset($_REQUEST['reorder']) && $_REQUEST['reorder'] == 1){                
                $orderNewEntity=$orderTable->newEmptyEntity();  
                $postData['order_id'] = $this->Common->generateRandomAlphaNumeric();              
            }
            
            $orderPatchEntity=$orderTable->patchEntity($orderNewEntity,$postData); 
            $orderSaved=$orderTable->save($orderPatchEntity);
            if($orderSaved){
                if($orderid == 0){
                    $userdata=$userTable->find()->where(['id'=>$orderSaved->client_id])->first();
                    $admindata=$userTable->find()->where(['role'=>1])->first();
                    $productName=$productTabel->find()->where(['id'=>$postData['p_name']])->first();
                    $this->Common->sendordermail($userdata->name,$userdata->email,$orderSaved->order_id,$admindata['email'],date("Y-m-d",strtotime($postData['order_date'])) ,date("Y-m-d",strtotime($postData['order_deadline'])),'addorderbyadmin',$productName['p_name'],$postData['quantity']);
               }
               $productData=$productTabel->find()->where(['id'=>$orderSaved['p_name']])->first();
                $notificationNewEntity=$notificationTable->newEmptyEntity();
                $notifyData=$notifyDurationTabel->find()->where(['id'=>$postData['send_reminder']])->first();            
                $durationString = explode(' ',$notifyData['durations']);
                $currentDate=Date('Y-m-d');
                if($durationString[1] == 'days'){
                    $notificationDate=date('Y-m-d', strtotime($currentDate. ' + '.$durationString[0].' day'));
                }else{
                    $notificationDate=date('Y-m-d', strtotime($currentDate. ' + '.$durationString[0].' month'));
                } 
                $postData['client_message'] = 'Hi! A new product '.$productData->p_name.' has been created by SinghalsFlexipack for you [ Order Id '.$orderSaved['id'].'] Now Your Order On Approval Pending';
                $postData['admin_message'] = 'Hi! A new product '.$productData->p_name.' has been created by You For '.$cusName['name'].' [ Order Id '.$orderSaved['id'].'] Now Order Is On Approval Pending';
                $postData['type']='createorder';
                $postData['order_id']=$orderSaved->id;
                $postData['customer_id']=$orderSaved->client_id;
                $postData['notification_date']=$notificationDate;
                $notificationPatchEntity=$notificationTable->patchEntity($notificationNewEntity,$postData);               
                $saveNotification=$notificationTable->save($notificationPatchEntity);
            }
            
            if($orderid > 0){
                $this->Flash->success(__('Yeah! Order Successfully Updated'));
                
            }else{
                $this->Flash->success(__('Yeah! Order Successfully Added'));
                
            }   
                  
            return $this->redirect(['action' => 'index/'.$Customer_id]);
        }
        if($orderid > 0){
            $pageTitle='Update Order'; 
        }else{
            $pageTitle='Add New Order'; 
        }            
        $this->set(compact('pageTitle','customerName','orderNewEntity','AllAddress','Customer_id','AllProductName','Alldimension','durationData'));
    }    

    public function getshippingAdd(){
        $getCustomerId=$this->request->getData();
        $userDetailsTable=TableRegistry::getTableLocator()->get('UserDetails'); 
        $shippingAddressListing=$userDetailsTable->find()->where(['user_id'=> $getCustomerId['customerId']])->toArray();
        $option="<option value=''>Select Shipping Address</option>";
        foreach($shippingAddressListing as $k => $v){
            $option.='<option value="'.$v['id'].'">'.$v['shipping_address'].'</option>';
        }
       echo $option; die;
    }

    public function getproductName(){
        $getCustomerId=$this->request->getData();
        $productsTable=TableRegistry::getTableLocator()->get('Products'); 
        $productNameListing=$productsTable->find()->where(['client_id'=> $getCustomerId['customerId']])->toArray();
        $option="<option value=''>Select Product Name</option>";
        foreach($productNameListing as $k => $v){
            $option.='<option value="'.$v['id'].'">'.$v['p_name'].'</option>';
        }
    echo $option; die;
    }

    public function getDimension(){
        $getProductId=$this->request->getData();
        $productDetailsTable=TableRegistry::getTableLocator()->get('ProductDetails'); 
        $dimensionListing=$productDetailsTable->find()->where(['product_id'=> $getProductId['productId']])->toArray();
        $option="<option value=''>Select Dimensions</option>";
        foreach($dimensionListing as $k => $v){
            $option.='<option value="'.$v['id'].'">'.$v['p_dimension'].'</option>';
        }
    echo $option; die;
    }

    // ..............order module end...................

    // ..............category module start...................
    public function category($id=null){
            $catTable = TableRegistry::getTableLocator()->get('Category');       
            $catData = $catTable->find()->where(['parent_id'=>0])->order(['id'=>'DESC']);
            if($id>0){
                $newCategoryEntity = $catTable->find()->where(['id'=>$id])->first(); 
            }else{
                $newCategoryEntity=$catTable->newEmptyEntity();  
            }                     
        if($this->request->is(['post','put'])){
            $postData=$this->request->getData();
            $postData['parent_id']='0';
            $addCategory=$catTable->patchEntity($newCategoryEntity,$postData);  
            $savedcategory=$catTable->save($addCategory);     
            $this->Flash->success(__('Yeah! Category Updated Successfully'));
            return $this->redirect(['action' => 'category']);  
        }        
        if($id>0){
            $pageTitle='Update Category';
            }else{
            $pageTitle='Add Category';
            }       
            $this->set(compact('catData','pageTitle','newCategoryEntity'));
    }

    public function updateStatus($id)
    { 
        $categoryTable = TableRegistry::getTableLocator()->get('Category');
        $newcategoryentity = $categoryTable->find()->where(['id'=>$id])->first();
        $newsubcategoryentity = $categoryTable->find()->where(['parent_id'=>$id])->first();
    if($newcategoryentity->status==0 ){
        $newcategoryentity->status= 1;
        $newsubcategoryentity->status= 1;
    }else{  
        $newcategoryentity->status= 0;
        $newsubcategoryentity->status= 0;
    }
        $categoryTable->saveMany([$newcategoryentity, $newsubcategoryentity]);
        $this->Flash->success(__('Status Successfully Updated'));
        return $this->redirect(['action' => 'category']);
    }

// ..............category module end...................
// ..............sub category module start...................

    public function subcategory($id=null){
        $catTable = TableRegistry::getTableLocator()->get('Category');       
        $CategoryList = $catTable->find('list',[
            'keyField'=>'id',
            'valueField' => 'name'
        ])->where(['parent_id '=> 0 , 'status'=>1])->toArray();
        $AllSubCategories = $catTable->find()->where(['parent_id >'=>0, 'status'=>1])->order(['id'=>'DESC']);  
        if($id>0){
            $subcatEntity=$catTable->find()->where(['id'=>$id])->first();
        } else{    
            $subcatEntity=$catTable->newEmptyEntity();
        } 
    if($this->request->is(['post','put'])){
        $postData=$this->request->getData();
        $postData['parent_id']=$postData['catname'];
        
        $addsubcat=$catTable->patchEntity($subcatEntity,$postData);
        $savesubcat=$catTable->save($addsubcat);
        $this->Flash->success(__('Yeah! Sub Category Updated Successfully'));
            return $this->redirect(['action' => 'subcategory']);  
    }   
    if($id>0){
        $pageTitle='Update Sub Category';
        }else{
        $pageTitle='Add Sub Category';
        }   
        $this->set(compact('CategoryList','pageTitle','AllSubCategories','subcatEntity'));
    }
    // ..............sub category module end...................
    public function changeorderstatus($orderId=null,$optionval=null){
        $orderTable = TableRegistry::getTableLocator()->get('Orders');
        $orderEntity=$orderTable->find()->where(['id'=>$orderId])->first();
        $notificationsTable = TableRegistry::getTableLocator()->get('Notifications');
        $orderData=[];
        $orderData['status']=$optionval;
        $orderPatchEntity=$orderTable->patchEntity($orderEntity,$orderData);
        $orderSaved=$orderTable->save($orderPatchEntity);
        if($orderSaved){
            // $this->Common->sendstatuschangemail();
            $notifyEntity=$notificationsTable->find()->where(['order_id'=>$orderSaved['id']])->first();
            if(!$notifyEntity){
                $notifyEntity=$notificationsTable->newEmptyEntity();
            }
            $notifyData=[];
            $notifyData['customer_id']=$orderSaved['client_id'];
            $notifyData['order_id']=$orderSaved['id'];   
            if($orderSaved['status'] == 1){
                $notifyData['client_message']='Your reorder request with '.$orderSaved['id'].' has been approved .';
            }elseif($orderSaved['status'] == 3){
                $notifyData['client_message']= 'Your order '.$orderSaved['id'].' is delivered.';
            }elseif($orderSaved['status'] == 4){
                $notifyData['client_message']= 'Your order '.$orderSaved['id'].' is canceled.';
            }
            $notifyData['type']=($orderSaved['status'] == 0 ? "Approval Pending" : ($orderSaved['status'] == 1 ? "In Process" : ($orderSaved['status'] == 2 ? "In Process" : ($orderSaved['status'] == 3 ? "Delivered" : "Cancel"))));
            $notifyData['notification_date']=date('Y-m-d');            
            $notifyPatchEntity=$notificationsTable->patchEntity($notifyEntity,$notifyData);
            $notifySaved=$notificationsTable->save($notifyPatchEntity);
                $this->Flash->success(__('Yeah! Status Is Updated'));
                return $this->redirect(['action' => 'index']); 
        }
    }

    public function deleteorderlist($customerId=null,$orderId=null){
        $orderTable=TableRegistry::getTableLocator()->get('Orders');
        $orderData=$orderTable->find()->where(['id'=>$orderId])->first(); 
        $orderTable->delete($orderData);  
        $this->Flash->success(__('Order successfully deleted'));
                return $this->redirect(['action' => 'index/'.$customerId]);
    }
}
