<?php

class DataBase extends PDO{

    protected $transactionCounter = 0;

    public function beginTransaction() {

        if(!$this::$transactionCounter++) {
            return parent::beginTransaction();
        }
        $this->exec('SAVEPOINT trans'.$this->transactionCounter);
        return $this->transactionCounter >= 0;

    }//beginTransaction

    public function commit() {

        if(!--$this->transactionCounter) {
            return parent::commit();
        }
        return $this->transactionCounter >= 0;

    }//commit

    public function rollBack() {

        if (!--$this->transactionCounter) {
            $this->exec('ROLLBACK TO trans'.$this->transactionCounter + 1);
            return true;
        }
        return parent::rollback();
        
    }//rollBack

}//db_connect

?>