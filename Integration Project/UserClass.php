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
    
    public $credit;
    public $street;
    public $country;
    public $state;
    public $zip;
    
    public $bar;

   

  

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
            ID : '.$this->id.'<br>
            UUID : '.$this->UUID.'<br>
            Name : '.$this->name.'<br>
            Email : '.$this->email.'<br>
            Street : '. $this->street .'<br>
            City : '. $this->city .'<br>
            State : '. $this->state .'<br>
            Country : '. $this->country .'<br>
            Zip : '. $this->zip .'<br>
            Phone : '. $this->phone.'<br>
            Createdate : '.$this->createDate.'<br>
            Credit : '.$this->credit.'<br>
            
            
            
            
            ';
    }
}
    

?>