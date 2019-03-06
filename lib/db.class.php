<?php



class DB
{
    
    public $db;
    public $setLog = 0;
    
    function __construct($param)
    {
        include __DIR__ ."/DbSimple/Generic.php";
        $this->db = \DbSimple_Generic::connect("mysqli://root:@localhost/mvc");  
    }
    
    function setLogger($set=1)
    {
        if($set){
           $this->db->setLogger([$this,'logger']); 
        }else{
            $this->db->setLogger('');
        }
    }
    
    function logger($db, $sql)
    {
        $this->setLogger(0);
        $caller = $this->db->findLibraryCaller();
        $this->insert("log",array(
            'name'=>"sql call at {$caller['file']} line {$caller['line']}",
            'log'=>$sql
        ));
        
        $this->setLogger(1);
    }
    
    function select()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'select'], $args);
    }
    
    function selectRow()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'selectRow'], $args);
    }
    
    function selectCell()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'selectCell'], $args);
    }
    
    function selectCol()
    {
        $args = func_get_args();
        return call_user_func_array([$this->db,'selectCol'], $args);
    }
    
    function query()
    {
        $args = func_get_args();
        $res = call_user_func_array([$this->db,'query'], $args);
        return $res;
    }
    
    function insert($table,$row)
    {
        return $this->db->query('insert into ?# (?#) values (?a)',$table,array_keys($row),array_values($row));
    }
    
    function update($table,$row,$id){
        return $this->db->query('update ?# set ?a where id=?d',$table,$row,$id);
    }
    
    function getError()
    {
        return $this->db->getErrors();
    }
}
