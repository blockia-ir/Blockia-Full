<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}

$table="{$DB->tablePrefix}logins";//define var

if(isset($where))
	$where=$where;
else
	$where="";

$allowSearch=array("username","id");
//search if set
if(!empty($_POST['searchData']))
{
  $searchData=(strip_tags($_POST['searchData']));
  $searchData=json_decode($searchData,true);
  
  if(!is_array($searchData))
  {
    exit("error: search data not valid");
  }
  
  foreach((array)$searchData as $field=>$search)
  {
    if(!in_array($field,$allowSearch))
    {
      exit("error:  don't allow search in $field");
    }
    
    if($search['value']!="" && $search['dataType']=="text")
    {
        $serachValue=strip_tags(addslashes($search['value']));
        $where.="(`{$DB->tablePrefix}block_users`.`$field`=<bind>$serachValue</bind> OR `{$DB->tablePrefix}block_users`.`$field` LIKE <bind>%$serachValue%</bind> ) AND ";
    }

  }
  
  $where=$Func->strReplaceLast("AND","",$where);
}

if(!empty($where))
{
    $where="WHERE $where";
}
$pageRow=100;
$pageNum=1;

$countRow=$DB->queryOutPut("SELECT count(0) FROM {$DB->tablePrefix}logins
                    LEFT JOIN {$DB->tablePrefix}block_users on
                    {$DB->tablePrefix}logins.block_user_id={$DB->tablePrefix}block_users.id
                    $where ");
$lastPage=ceil($countRow/$pageRow);


if(isset($_POST['pageNum'])){
   $pageNum=strip_tags($_POST['pageNum']);
}

if(!is_numeric($pageNum))
{
    exit("error  page num not valid");
}

if($pageNum<1)
    $pageNum=1;

if(empty($limit))
	$limit="LIMIT ".($pageNum-1)*$pageRow.",".$pageRow;

$listData=$DB->query("SELECT {$DB->tablePrefix}logins.*,{$DB->tablePrefix}block_users.name,
                    {$DB->tablePrefix}block_users.username FROM {$DB->tablePrefix}logins
                    LEFT JOIN {$DB->tablePrefix}block_users on {$DB->tablePrefix}logins.block_user_id={$DB->tablePrefix}block_users.id
                    $where ORDER BY {$DB->tablePrefix}logins.date DESC $limit   ");
?>

<div id="listLoginsGrid" >

    <?php $Func->pagination("listLoginsGrid.php","listLoginsGrid",$countRow,$pageRow,$pageNum);?>
    <table class="table table-striped table-condensed border display" id="tableLogins" >
      <thead>
        <tr>
          <th class='' >block_user_id</th>
          <th class='' >username</th>
          <th class='' >name</th>
          <th class='' >ip</th>
          <th class='' >browser</th>
          <th class='' >os</th>
          <th class='' >date</th>
        </tr> 
      </thead>
      <?php foreach($listData as $login):?>
      <tr>
        <td class="d-none d-md-table-cell name" ><?= $login->block_user_id ?></td>
        <td class="d-none d-md-table-cell name" ><?= $login->username ?></td>
        <td class="d-none d-md-table-cell name" ><?= $login->name ?></td>
        <td class="d-none d-md-table-cell name" ><?= $login->ip ?></td>
        <td class="d-none d-md-table-cell name" ><?= $login->browser ?></td>
        <td class="d-none d-md-table-cell name" ><?= $login->os ?></td>
        <td class="d-none d-md-table-cell name" ><?= $Jdf->convertToJalali($login->date) ?></td>
        
      </tr>
      <?php endforeach?>
    </table>

</div>

