<?php
  $data="";
  $IDTIGO="";
  $IDINN="";
  $IP="";
  $errorMessage="";
  $show="all";
  $p="0";
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $IDTIGO=$_POST["id_t_s"];
    $IDTIGO=trim($IDTIGO);
    $IDINN=$_POST["id_i_s"];
    $IDINN=trim($IDINN);
    $IP=$_POST["ip_search"];
    $IP=trim($IP);
    $IDTIGO=strtoupper($IDTIGO);
    $IDINN=strtoupper($IDINN);
    if((empty($IDINN) && $IDINN!="0") && (empty($IDTIGO) && $IDTIGO!="0") && (empty($IP) && $IP!="0")) $errorMessage="Sin datos para buscar (Ingrese los datos en las cajas blancas)";
    else{
      $show="";
      $noindex=["id_tigo","id_innova","ip"];
      $info=[$IDTIGO,$IDINN,$IP];
      $comparation=[];
      $index=[];
      for($i=0;$i<3;$i++){
        if (!empty($info[$i]) || $info[$i]=="0") {
        array_push($comparation,$info[$i]);
        array_push($index,$noindex[$i]);
      }
      }
    }
  }
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<script>
  window.addEventListener("resize", function() {
    font_size();
  });

  function font_size(){
    var screenWidth = window.innerWidth;
    var screenHeight = window.innerHeight;
    var b1=document.getElementById("b1")
    var b2=document.getElementById("b2")
    var div=document.getElementById("div1")
    b1.style.fontSize = (screenWidth*0.0089)+"px";
    b2.style.fontSize = (screenWidth*0.0089)+"px";
    div.style.fontSize = (screenWidth*0.009)+"px";
  }
  function confirmation(url){
    if(confirm("¿Seguro que deseas eliminar el dato?"))
    window.location.href=url
  }
</script>
<body onload="font_size()">
<style>
  table {
    width: 50%;
    overflow: auto;
  }
  th{
    
    font-size: 16px;
    background-color: #D2E8FF;
  }
  tr {
    word-wrap: break-all;
    vertical-align: middle;
    border-top: 1px solid gray;
    font-size: 12px;
    border-bottom: 1px solid gray;
  }
  tr:first-child {
    border-top: none;
  }
  td {
    word-wrap: break-all;
    vertical-align: middle;
    min-width: 12px;
    font-size: 15px;
  }
  .sticky-header{
    position: absolute;
    top:0;
  }
  .head{
    position: sticky;
    top: 0;
    border-bottom: 1px solid gray;
    border-left: 1px solid gray;
    border-right: 1px solid gray;
    vertical-align: middle;
  }
  .fixed-button {
      font-size: 100%;
      position: fixed;
      bottom: 0.5%;
      right: 0.5%;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  .fixed-button2 {
    position: fixed;
    bottom: 0.5%;
    left: 0.5%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .search {
    position: fixed;
    bottom: 1.2%;
    left:12%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .fg{
    color: white;
  }
  .bottom-box {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 6.1%;
    background-color: black;
        }
  .messagebox{
    top: 100;
    left: 1;
    position: sticky;
    
  }
  .fixed-button3 {
    width: 15%;
    position: fixed;
    bottom: 8%;
    left: 0.5%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .expand{
    word-wrap: break-word; 
    max-height: 600px;
    overflow-y: auto;
  }

</style>
<?php
      if(!empty($errorMessage)){
        echo"
        <div class='alert alert-primary alert-dismissible fade show message' role='alert'>
          <strong>$errorMessage</strong>
          <button class='btn btn-close' type='button' data-bs-dismiss='alert'></button>
        </div>
        ";
      }
    ?>
  <div class="bottom-box"></div>
    <a class='btn btn-warning fixed-button' style="width: 15%; height: 5%;" href='/database/createanfora.php' id="b1">Ingresar Nuevo Dato 📩</a>
    
    <table class="table">
    <thead class="head">
        <tr style=background-color:#000000 class="head fg">
            <th style='width: 10px'>#</th>
            <th > Proveedor </th>
            <th> ID TIGO </th>
            <th> ID INNOVA </th>
            <th> Nombre de Sucursal </th>
            <th> IP </th>
            <th> Estado </th>
            <th> Ancho de Banda MB </th>
            <th> Tipo de Medio </th>
            <th> Ancho de Banda </th>
            <th> Latitud </th>
            <th> Longitud </th>
            <th> Direción </th>
            <th> Acción </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $server_name="ANF01-LSPLEON\SQLEXPRESS01";
        $username="root";
        $password="";
        $database="all_data";
        $connectionOptions = array(
          "Database" => "all_data",
          "CharacterSet" => "UTF-8"
          );
        //$conection=new mysqli($server_name,$username,$password,$database);
        $conection=sqlsrv_connect($server_name, $connectionOptions);
        //if ($conection->connect_error) 
        //    die("conection failed: ".$conection->connect_error);
        if (!$conection) {
          die(print_r(sqlsrv_errors(), true));
        }
      
        $sql="SELECT* FROM anfora";

        $result=sqlsrv_query($conection, $sql);
        //if(!$result)
        //    die("Invlid query: ".$conection->error);
        $cont=0;
        $general_cont=0;
        while ($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $general_cont++;
            $color="#FFFFFF";
            if($cont%2==1) $color="#E4E4E4";
            if($cont==113) echo "";
            if (!empty($show)){
            $cont+=1;
            echo"
            
            <tr class='expand'>
                <td style=background-color:$color> $general_cont </td>
                <td style=background-color:$color> $row[proveedor] </td>
                <td style=background-color:$color> $row[id_tigo] </td>
                <td style=background-color:$color> $row[id_innova] </td>
                <td style=background-color:$color> $row[nombre_sucursal] </td>
                <td style=background-color:$color> $row[ip] </td>
                <td style=background-color:$color> $row[estado] </td>
                <td style=background-color:$color> $row[ancho_banda_mb] </td>
                <td style=background-color:$color> $row[medo] </td>
                <td style=background-color:$color> $row[ancho_de_banda] </td>
                <td style=background-color:$color> $row[latitud] </td>
                <td style=background-color:$color> $row[longitud] </td>
                <td sclass'expand' style=background-color:$color> $row[direccion] </td>
                <td style=background-color:$color>
                  <a class='btn btn-primary btn-sm' href='/database/editanfora.php?id=$row[id]'>Editar</a>
                  <a class='btn btn-danger btn-sm' onclick=confirmation('/database/deleteanfora.php?id=$row[id]');>Eliminar</a>
                </td>
            </tr>
            ";
            }
            else{
              $flag=true;
              for($i=0;$i<count($comparation);$i++){
                //$flag=$flag && str_starts_with($row[$index[$i]],$comparation[$i]);
                $flag=$flag && strstr($row[$index[$i]], $comparation[$i]);
              }
              if($flag){
                $cont+=1;
                echo"
                <tr>
                    <td style=background-color:$color> $general_cont </td>
                    <td style=background-color:$color> $row[proveedor] </td>
                    <td style=background-color:$color> $row[id_tigo] </td>
                    <td style=background-color:$color> $row[id_innova] </td>
                    <td style=background-color:$color> $row[nombre_sucursal] </td>
                    <td style=background-color:$color> $row[ip] </td>
                    <td style=background-color:$color> $row[estado] </td>
                    <td style=background-color:$color> $row[ancho_banda_mb] </td>
                    <td style=background-color:$color> $row[medo] </td>
                    <td style=background-color:$color> $row[ancho_de_banda] </td>
                    <td style=background-color:$color> $row[latitud] </td>
                    <td style=background-color:$color> $row[longitud] </td>
                    <td style=background-color:$color> $row[direccion] </td>
                    <td style=background-color:$color>
                      <a class='btn btn-primary btn-sm' href='/database/editanfora.php?id=$row[id]'>Editar</a>
                      <a class='btn btn-danger btn-sm' onclick=confirmation('/database/deleteanfora.php?id=$row[id]');>Eliminar</a>
                    </td>
                </tr>
                ";
              }
            }
          }
          if($cont==0){
            echo"
            <div class='alert alert-primary alert-dismissible fade show message' role='alert'>
              <strong>No  hay coincidencias</strong>
              <button class='btn btn-close' type='button' data-bs-dismiss='alert'></button>
            </div>
            ";}
            ?>
          
    
    </tbody>
    </table>
    <?php
    if (empty($show)){echo"<a class='btn btn-secondary btn-lg fixed-button3 btn-outline-dark fg' href='/database/indexanfora.php' role='button'>🏠 Vista General</a>";
    echo"<br><br><br>";}
    
    ?>
    <br>
    <form method="POST">
    <div class="search fg" id="div1">
      <label> ID TIGO:</label>
      <input type="text" name="id_t_s" value="<?php echo $IDTIGO;?>">
      <label>ㅤID INNOVA:</label>
      <input type="text" name="id_i_s" value="<?php echo $IDINN;?>">
      <label>ㅤIP: </label>
      <input type="text" name="ip_search" value="<?php echo $IP;?>">
    </div>
    <button class='btn btn-warning fixed-button2' style="width: 11%; height: 5%;" type="submit" id="b2">Buscar Dato 🔍︎</button>
    </form>
    
</body>
</html>