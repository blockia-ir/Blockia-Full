<?php
class DB extends PDO {
	
    public $tablePrefix = "";
    public $notExecute=array("drop");
    public $showError=false;
    
    public function __construct() 
	{
		require_once "config.php";
        $this->tablePrefix=TABLE_PREFIX;
        try
        {
            parent::__construct("mysql:host=localhost;dbname=".DBname.";charset=utf8mb4",DBuser, DBpass);
			//$this->exec("set names utf8");
			$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//تنظیم نوع پشفرض خروجی به آبجکت
        }
        catch (PDOException $e)
        {
            die('Database Error connection ');
        }
    }
	
    public function query($sql,$oneRow=false)
    {
		$sql=$this->removeNotExecute($sql);
        
        if(strpos($sql,"<bind>")>0)
        {
            preg_match_all("#<bind>(.*?)<\/bind>#s", $sql, $matches);
            $sql=str_replace($matches[0],"?",$sql);
            $stmt=parent::prepare($sql);
            
            $i=1;
            foreach((array)$matches[1] as $item)
            {
                $stmt->bindValue($i,$item);
                $i++;
            }
        }
        else
        {
            $stmt=parent::prepare($sql);
        }
		
        if(!$stmt->execute())
		{
            if($this->showError)
            {
                $errorInfo=$stmt->errorInfo();
                exit( "<span style='color:#ff8800' >query error: </span>".$errorInfo[2]);
            }
            else
            {
                exit( "<span style='color:#ff8800' >query error: </span>");
            }

        }
		
        if($oneRow)return $stmt->fetch();
        return $stmt->fetchAll();
    }
    
    public function prepare($sql,$options = NULL)
    {
        $sql=$this->removeNotExecute($sql);
        
        if(strpos($sql,"<bind>")>0)
        {
            preg_match_all("#<bind>(.*?)<\/bind>#s", $sql, $matches);
            $sql=str_replace($matches[0],"?",$sql);
            $stmt=parent::prepare($sql);
            
            $i=1;
            foreach((array)$matches[1] as $item)
            {
                $stmt->bindValue($i,$item);
                $i++;
            }
        }
        else
        {
            $stmt=parent::prepare($sql);
        }
		
        if(!$stmt->execute())
		{
            if($this->showError)
            {
                $errorInfo=$stmt->errorInfo();
                exit ("<span style='color:#ff8800' >prepare error: </span>".$errorInfo[2]);
            }
            else
            {
                exit ("<span style='color:#ff8800' >prepare error: </span>");
            }

        }
        
        return true;
    }
    
    /*
     *return first column in query
     */
    public function queryOutPut($sql)
    {
        $sql=$this->removeNotExecute($sql);
        
        if(strpos($sql,"<bind>")>0)
        {
            preg_match_all("#<bind>(.*?)<\/bind>#s", $sql, $matches);
            $sql=str_replace($matches[0],"?",$sql);
            $stmt=parent::prepare($sql);
            
            $i=1;
            foreach((array)$matches[1] as $item)
            {
                $stmt->bindValue($i,$item);
                $i++;
            }
        }
        else
        {
            $stmt=parent::prepare($sql);
        }

		
        if(!$stmt->execute())
		{
            if($this->showError)
            {
                $errorInfo=$stmt->errorInfo();
                exit ("<span style='color:#ff8800' >queryOutPut error: </span>".$errorInfo[2]);
            }
            else
            {
                 exit ("<span style='color:#ff8800' >queryOutPut error: </span>");
            }
            
        }
        return $stmt->fetchColumn();
    }
	
	/**
     *insert into  table
	 *@param table,
     *@pram array data ['id'=>3,'name'=>'block', . . . ]
     */
    public function insert($table,$data)
	{
		if($table=="")
		{
			exit("<span style='color:#ff8800' >table not set in insert</span>");
		}
		
		$field='`'.implode('`,`' , array_keys($data)).'`';
		$param=':' .implode(",:", array_keys($data));
		$stmt=parent::prepare("INSERT INTO `$table` ({$field}) VALUES ({$param})");
		$this->PDOBindArray($stmt,$data);
		$result=$stmt->execute();
		if(!$result)
        {
            if($this->showError)
            {
                $errorInfo=$stmt->errorInfo();
                exit("<span style='color:#ff8800' >Insert error $table: </span>".$errorInfo[2]);
            }
            else
            {
                exit("<span style='color:#ff8800' >DB Insert error check table name, TABLE_PREFIX  or INSERT query </span>");
            }

		}
		return $result;
    }
	
	/**
    *@param table,
    *@pram array data ['id'=>3,'name'=>'block', . . . ]
    */
	public function update($table,$data,$where="WHERE 0")
	{

		if($table=="")
		{
			exit("<span style='color:#ff8800' >table not set in update </span>");
		}
		
        if(!is_array($data))
        {
            exit("Update error: data must be array");
        }
        
        
        if($where=="WHERE 0" || $where=="")
        {
            exit("<span style='color:#ff8800' >Update error: where param not be empty</span>");
        }
        
        $where=$this->removeNotExecute($where);
        
        $fieldForUpdate=$this->fieldForUpdate($data);
        $stmt=parent::prepare("UPDATE `$table` SET $fieldForUpdate $where");
        $this->PDOBindArray($stmt,$data);        
        $result=$stmt->execute();
        if(!$result)
        {
            if($this->showError)
            {
                $errorInfo=$stmt->errorInfo();
                exit("<span style='color:#ff8800' >Update error $table : </span>".$errorInfo[2]);
            }
            else
            {
                exit("<span style='color:#ff8800' >DB UPDATE error check table name, TABLE_PREFIX  or UPDATE query  </span>");
            }
        } 
        return $result;
    }
	
	protected function PDOBindArray($PoStatment,$PaArray)
    {
        foreach($PaArray as $key=>$value)
        {
            $PoStatment->bindValue(':'.$key,$value);
        }
    }
	
	protected function fieldForUpdate($data)
    {
        $fieldNames=array_keys($data);
        $field=[];
        foreach($fieldNames as $fieldName){
           $field[]="`".$fieldName."`=:".$fieldName;
        }
        return implode(',' , $field);
    }
    
    protected function removeNotExecute($sql)
    {
        if(!is_array($this->notExecute))
            return $sql;
        
        if(count($this->notExecute)==0)
            return $sql;
        
        foreach($this->notExecute as $val)
        {
            $sql=str_ireplace($val,implode(' ',str_split($val)),$sql);
        }
        
        return $sql;
        
        
    }


}
