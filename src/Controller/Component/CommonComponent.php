<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Common component
 */
class CommonComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    public function generateRandomAlphaNumeric($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    } 
    public function sendcustomermail($name, $client_email, $number) {

        // Load the template file
        $template_path = WWW_ROOT . "/template/customermailtemplate.html";
        if (!file_exists($template_path)) {
            echo "Error: Email template file not found.";
            return;
        }
        
        $template = file_get_contents($template_path);
        if ($template === false) {
            echo "Error: Failed to load email template.";
            return;
        }
        $template = str_replace('{NAME}', $name, $template);
        $template = str_replace('{EMAIL}', $client_email, $template);
        $template = str_replace('{NUMBER}', $number, $template);
        $header = "" . "Reply-To:larav8288@laraveldevelopmentcompany.com\r\n" .
            "X-Mailer: PHP/" . phpversion();
        $header .= 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: larav8288@laraveldevelopmentcompany.com' . "\r\n";
        
        $email = $client_email;
        $sub = 'Welcome to SFP Packaging App! Your Account Has Been Successfully Created 🎉';
        if (mail($email, $sub, $template, $header)) {
            return "Email sent successfully.";
        } else {
            return "Failed to send email.";
        }
        die;
    }

    public function sendordermail($name, $client_email, $order_id, $admin_email ,$order_date,$order_deadline, $type,$productName,$quantity) {
       
        $template_path = WWW_ROOT . "/template/ordermailtemplate.html";
        if (!file_exists($template_path)) {
            echo "Error: Email template file not found.";
            return;
        }
        
        $template = file_get_contents($template_path);
        if ($template === false) {
            echo "Error: Failed to load email template.";
            return;
        }
    $data=array();
       if($type == 'addorderbyadmin'){
            $data[0]['subject']='Your Order Has Been Created!';
            $data[0]['name']='Hello '.$name;
            $data[0]['product_name']=$productName;
            $data[0]['msgtitle']='We are delighted to inform you that your order has been successfully created by our admin!';
            $data[0]['mail']=$client_email;
            $data[0]['qantity']=$quantity;
            
       }elseif($type == 'addorderbyclient'){
        $data = [
            [
                'subject' => 'Your Order Has Been Created!',
                'name'=>'Hello '.$name,
                'product_name'=>$productName,
                'msgtitle' => 'We are delighted to inform you that your order has been successfully created by our admin!',
                'mail' => $client_email,
                'quantity' => $quantity
            ],
            [
                'subject' => $name . ', request for more orders',
                'name'=>'',
                'product_name'=>$productName,
                'msgtitle' => $name . ' is demanding more orders',
                'mail' => $admin_email,
                'quantity' => $quantity
            ]
        ];
        
       }
        foreach($data as $k=>$v){
            
            $template = file_get_contents($template_path);
            $template = str_replace('{headmsg}', $v['subject'], $template);
            $template = str_replace('{name}', $v['name'], $template);
            $template = str_replace('{title}', $v['msgtitle'], $template);
            $template = str_replace('{order_id}', strval($order_id), $template);
            $template = str_replace('{order_date}', $order_date, $template);
            $template = str_replace('{product_name}', $productName, $template);
            $template = str_replace('{order_deadline}', $order_deadline, $template);
            $template = str_replace('{supporting_mail}', 'larav8288@laraveldevelopmentcompany.com', $template);
            $template = str_replace('{quantity}', $quantity, $template);
            $header = "" . "Reply-To:larav8288@laraveldevelopmentcompany.com\r\n" .
                "X-Mailer: PHP/" . phpversion();
            $header .= 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $header .= 'From: larav8288@laraveldevelopmentcompany.com' . "\r\n";
           
                if (mail($v['mail'], $v['subject'], $template, $header)) {
                    $return= true;
                }else{
                    $return= false;
                } 

        
        }
        return $return;
    
    }
  
    // public function sendstatuschangemail($name, $client_email, $number) {

    //     // Load the template file
    //     $template_path = WWW_ROOT . "/template/changestatusmailtemplate.html";
    //     if (!file_exists($template_path)) {
    //         echo "Error: Email template file not found.";
    //         return;
    //     }
        
    //     $template = file_get_contents($template_path);
    //     if ($template === false) {
    //         echo "Error: Failed to load email template.";
    //         return;
    //     }
    //     $template = str_replace('{NAME}', $name, $template);
    //     $template = str_replace('{EMAIL}', $client_email, $template);
    //     $template = str_replace('{NUMBER}', $number, $template);
    //     $header = "" . "Reply-To:larav8288@laraveldevelopmentcompany.com\r\n" .
    //         "X-Mailer: PHP/" . phpversion();
    //     $header .= 'MIME-Version: 1.0' . "\r\n";
    //     $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    //     $header .= 'From: larav8288@laraveldevelopmentcompany.com' . "\r\n";
        
    //     $email = $client_email;
    //     $sub = 'Ready for using app';
    //     if (mail($email, $sub, $template, $header)) {
    //         return "Email sent successfully.";
    //     } else {
    //         return "Failed to send email.";
    //     }
    //     die;
    // }
    
}
