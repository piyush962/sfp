<?php
declare(strict_types=1);

namespace App\Controller;
use Migrations\Migrations;
/**
 * Dbupdate Controller
 *
 */
class DbupdateController extends AppController
{   
    public function index()
    {       
        $migrations = new Migrations();
        $migrate = $migrations->migrate();  
        
        die;
    }

   
}
