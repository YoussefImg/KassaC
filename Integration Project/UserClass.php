<?php

// Nous créons une classe « Personnage ».

class User
{
    public $id;
    public $name;
    public $email;
    public $street;
    public $phone;
    public $mobile;
    public $createDate;
    public $credit;
    public $city;
    public $UUID;
        

  

    public function User( $id,$name,$email,$street,$phone,$mobile,$credit,$city)

    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->street = $street;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->createDate =(new \DateTime())->format('Y-m-d H:i:s');
        $this->credit = $credit;
        $this->city = $city;
    }
    

    
  
   
    public function toString()
    {
        echo 'ID : '.$this->id.'<br>
            Name : '.$this->name.'<br>
            Email : '.$this->email.'<br>
            Street : '. $this->street .'<br>
            Phone : '. $this->phone.'<br>
            Mobile : '.$this->mobile.'<br>
            Createdate : '.$this->createDate.'<br>
            City : '.$this->city.'<br>
            Credit : '.$this->credit.'<br>
            UUID : '.$this->UUID.'<br><br>';
    }
}
?>