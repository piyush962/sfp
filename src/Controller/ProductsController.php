<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class ProductsController extends AppController
{
   
    public function index($id=null)
    {
        $productTable = TableRegistry::getTableLocator()->get('Products');
        // $productData=$productTable->find()->where(['client_id'=>$id])->contain(['Users','ProductDetails','Category'])->order(['Products.id'=>'DESC'])->toArray();
        // $productData = $this->Paginate([$productData]);
        $productData = $this->paginate($productTable->find()->where(['client_id' => $id])->contain(['Users', 'ProductDetails', 'Category'])->order(['Products.id' => 'DESC']));

        $pageTitle='Products'; 
        $this->set(compact('pageTitle','productData'));
    }
    public function add($id=null,$pid=null)
    {
        $catTable = TableRegistry::getTableLocator()->get('Category');         
        $catData = $catTable->find('list',
            keyField:'id',
            valueField: 'name'
        )->where(['parent_id'=>0,'status'=>1])->toArray();
       
        $validator = new Validator();
        $validator
        ->requirePresence('p_name')
        ->notEmptyString('p_name','Please fill packaging name');        
        $pageTitle='Add Product'; 
        $this->set(compact('pageTitle'));
          $productTable = TableRegistry::getTableLocator()->get('Products');
          $productDetailsTable = TableRegistry::getTableLocator()->get('ProductDetails');  
          if($pid > 0){
          $newProductentity=$productTable->find()->where(['id'=>$pid])->contain(['ProductDetails'])->first();
          } else{
          $newProductentity = $productTable->newEmptyEntity();  
          }
        //   pr($newProductentity);die;
          if($this->request->is(['post','put'])){
            $postData=$this->request->getData(); 
            // pr($postData);die;
            $file = $this->request->getData('image');
           
            if(!empty($file->getClientFilename())){ 
                $fileExtension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                $File_Name = time().'.'.$fileExtension;
                $uploadPath = WWW_ROOT . 'uploads' . DS;
                $file->moveTo($uploadPath . $File_Name);
                $postData['image'] = $File_Name;
            }else{
                if($pid > 0){
                    $postData['image'] = $newProductentity['image'];
                }else{
                    $postData['image'] ='';
                }
            }
            
            $errors = $validator->validate($postData);
            if(empty($errors)){
                $postData['client_id']=$id;
                $product = $productTable->patchEntity($newProductentity, $postData);                
                $savedproduct=$productTable->save($product);
                if($savedproduct){
                    if(!empty($postData['category-group'])){
                        foreach($postData['category-group'] as $k => $v){ 
                            if($v['edit_id'] == 0 || empty($v['edit_id'])){ 
                            $newproductDetailsentity = $productDetailsTable->newEmptyEntity(); 
                            }else{
                            $newproductDetailsentity = $productDetailsTable->find()->where(['id'=>$v['edit_id']])->first();
                            }
                            $productDetailsData = array();
                            $productDetailsData['product_id'] = $savedproduct->id;
                            $productDetailsData['p_dimension'] = $v['p_dimension'];
                            $customerdetails = $productDetailsTable->patchEntity($newproductDetailsentity, $productDetailsData);
                            $productDetailsTable->save($customerdetails);                            
                        }
                    }
                    if($pid > 0){
                        $this->Flash->success(__('Product Successfully Updated'));
                    }else{
                    $this->Flash->success(__('Product Successfully Added'));
                    }
                    return $this->redirect(['action' => 'index/'.$id]);
                }
            }
            
          }
        //   pr($newProductentity);die;
          if($pid > 0){
            $pageTitle='Update Product'; 
        }else{
        $pageTitle='Add Product'; 
        }
        $this->set(compact('newProductentity','pageTitle','catData'));
    }

   

   
}
