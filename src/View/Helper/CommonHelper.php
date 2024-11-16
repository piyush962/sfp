<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\ORM\TableRegistry;

/**
 * Common helper
 */
class CommonHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];
    public function getCategoryName($categoryId){
        $catTable = TableRegistry::getTableLocator()->get('Category'); 
        $CategoriesName = $catTable->find()->where(['id'=>$categoryId])->first() ;
        return $CategoriesName['name'];
    }
    public function getProductName($productId){
        $productsTable = TableRegistry::getTableLocator()->get('Products'); 
        $productsName = $productsTable->find()->where(['id'=>$productId])->first() ;
        return $productsName['p_name'];
    }
  
    public function getnotification(){
        $notificationTable = TableRegistry::getTableLocator()->get('Notifications'); 
        $orderDetails = $notificationTable->find()->order(['id'=>'DESC'])->limit(5)->toArray();
        return $orderDetails;
        
    }
    public function getadminprofile(){
        $userTable = TableRegistry::getTableLocator()->get('Users'); 
        $userDetails = $userTable->find()->where(['role'=>1])->first();
        return $userDetails;
        
    }
    public function getproductcount($customerId){
        $orderTable = TableRegistry::getTableLocator()->get('Orders'); 
        $query = $orderTable->find();
        $query->select([
            'productCount'=>$query->func()->count('Orders.p_name'),

        ])
        ->group('client_id')
        ->where(['client_id'=>$customerId]);
        // pr($query->toArray());
        return $query->first();
        
    }
}
