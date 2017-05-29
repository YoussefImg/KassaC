<?php

// Nous créons une classe « Personnage ».

class User
{
    public $id;
    public $UUID;
    public $version;
    
    public $name;
    public $email;
    public $phone;
    public $createDate;
    public $city;
    public $credit;
    public $street;
    public $country;
    public $state;
    public $zip;
    
    public $registered;

   

  

    public function User( $id,$name,$email,$street,$state,$city,$country,$zip,$phone)

    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->street = $street;
        $this->state = $state;
        $this->city = $city;
        $this->country = $country;
        $this->zip = $zip;
        $this->phone = $phone;
        $this->createDate =(new \DateTime())->format('Y-m-d H:i:s');
  
     
    }
    /*
      public function ExistantUser( $id,$name,$email,$street,$phone,$mobile,$createdate,$credit,$city)

    {
        $instance = new self();
        $instance->id = $id;
        $instance->name = $name;
        $instance->email = $email;
        $instance->street = $street;
        $instance->phone = $phone;
        $instance->mobile = $mobile;
        $instance->createDate =$createdate;
        $instance->credit = $credit;
        $instance->city = $city;
        return $instance;
    }
    */


   
    public function toString()
    {
        echo '
            ID : '.$this->id.'
            UUID : '.$this->UUID.'
            Version : '.$this->version.'
            Name : '.$this->name.'
            Email : '.$this->email.'
            Street : '. $this->street .'
            City : '. $this->city .'
            State : '. $this->state .'
            Country : '. $this->country .'
            Zip : '. $this->zip .'
            Phone : '. $this->phone.'
            Createdate : '.$this->createDate.'
            Credit : '.$this->credit.'
            Registred : '.$this->registered.'
            
            
            
            
            ';
          
    }
}
    

?>