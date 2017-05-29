<?php

// Nous créons une classe « Personnage ».

class Order
{
    public $UUID;
    public $version;
    public $ordername;
    public $id;
    public $name;
    public $customerID;
    public $createDate;
    public $productIDs;
    public $total;
 
    public function Order($id ,$ordername,$name,$customerID,$total,$createdate,$productids)

    {
        $this->id = $id;
        $this->ordername = $ordername;
        $this->name = $name;
        $this->total = $total;
        $this->customerID = $customerID;
        $this->createDate = $createdate;
        $this->productIDs = $productids;
    }

    public function toString()
    {
        echo 'Id : '.$this->id.'<br>
            Name : '.$this->name.'<br>
            CustomerID : '. $this->customerID .'<br>
            Total : '. $this->total.'<br>
            CreateDate : '. $this->createDate.'<br>
            Product ids : ';
        //THIS IS THE LINE ID , TO GET ID OF PRODUCT,FETCH IN POS.ORDER.LINE
        foreach($this->productIDs as $id)
        {
            echo $id.',';
        }
        echo'<br>
        
        
        
        
        ';
    }
}
?>