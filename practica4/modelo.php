<?php
    require 'bd/conexion_bd.php';
    
    class dodo {

        public function Insertar($Fecha,$Hora,$Dispositivo) {
            $obj = new BD_PDO();
            $fecha = $Fecha;
            $hora = $Hora;
            $dispositivo = $Dispositivo;
            $obj->Ejecutar_Instruccion("Insert into dodo(fecha,hora,dispositivo) values('{$fecha}','{$hora}','{$dispositivo}','{$calificacion}')");
        }
        
        public function Actualizar($Fecha,$Hora,$Dispositivo) {
            $obj = new BD_PDO();
            $id = $_POST['txtid'];
		    $fecha = $Fecha;
            $hora = $Hora;
            $dispositivo = $Dispositivo;
		    $obj->Ejecutar_Instruccion("update dodo set fecha='{$fecha}',hora='{$hora}',dispositivo='{$dispositivo}' where id='{$id}'");
        }

        public function Buscar($buscar) {
            $obj = new BD_PDO();
            $dispositivo = $buscar;
            $result = $obj->Ejecutar_Instruccion("Select * from dodo where dispositivo like '%{$dispositivo}%'");

            $tabla ='';
            foreach($result as $renglon){
                $tabla.='<tr>';
                    $tabla.='<td>'.$renglon[0].'</td>';
                    $tabla.='<td>'.$renglon[1].'</td>';
                    $tabla.='<td>'.$renglon[2].'</td>';
                    $tabla.='<td>'.$renglon[3].'</td>';
                    $tabla.='<td>
                        <input type="button" class="accion" id="btneliminar" name="btneliminar" value="eliminar" onclick="Eliminar('.$renglon[0].')">
                        <input type="button" class="accion" id="btnmodificar" name="btnmodificar" value="modificar" onclick="Modificar('.$renglon[0].')">
                    </td>
                </tr>';
            }
            return $tabla;
        }

        public function Eliminar($id) {
            $obj = new BD_PDO();
            $id = $_GET['id'];
            $obj->Ejecutar_Instruccion("Delete from dodo where id = '{$id}'");
        }

        public function Modificar($id) {
            $obj = new BD_PDO();
            // $id = $_GET['Id'];
		    $result_modificar = $obj->Ejecutar_Instruccion("select * from dodo where id = '{$id}'");
            return $result_modificar;
        }
    
    }
?>