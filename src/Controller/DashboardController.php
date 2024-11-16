<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\ORM\TableRegistry;
class DashboardController extends AppController
{
    public function index()
    {

        $userTable = TableRegistry::getTableLocator()->get('Users');
        $orderTable = TableRegistry::getTableLocator()->get('Orders');
        $userData = $userTable->find()->where(['role !=' => 1,'status'=>1])->toArray();
        $Orderdata = $orderTable->find()->order(['id'=>'Desc'])->toArray();
        $query=$orderTable->find();
        $query->select([
            'sumOfQuantity' => $query->func()->sum('Orders.quantity'),
            'sumOfPrice' => $query->func()->sum('Orders.total_price'), 
            'Orders.order_date',
            'Orders.order_deadline',
            'Orders.quantity',
            'Orders.total_price',
            'Orders.status',
            'Products.p_name',
            'Orders.client_id',
            'Users.name',
        ])
        ->group('Orders.client_id')
        ->contain(['Users', 'Products'])
        ->limit(5)
        ->order(['Orders.id' => 'DESC']);
    //    echo "<pre>";print_r($query);die;
        $totalUser = count($userData);
        $totalOrder = $orderTable->find()->where(['status'=>0])->count();       
        $deliveredOrder = $orderTable->find()->where(['status' => 3])->count();
        $dispatchOrder = $orderTable->find()->where(['status' => 2])->count();
        $processOrder = $orderTable->find()->where(['status' => 1])->count();
        $pageTitle='Dashboard'; 
        $this->set(compact('pageTitle','totalUser','totalOrder','query','processOrder','deliveredOrder','dispatchOrder'));
  
    }
    public function dynamicchart(){
        $prodTable = TableRegistry::getTableLocator()->get('Products');
        $query=$prodTable->find();
        $query->select([
            'productCount' => $query->func()->count('Products.p_category'),
            'Category.name'
        ])
        ->group('Products.p_category')
        ->contain(['Category'])
        ->limit(4);
        echo json_encode($query);die;
    }
}
