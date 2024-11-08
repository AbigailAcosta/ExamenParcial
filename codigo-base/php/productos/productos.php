<?php
header('Content-Type: application/json');
require("../conexion.php");

$conexion = retornarConexion();

switch ($_GET['accion']) {
    case 'listar':
        $respuesta = mysqli_query($conexion, "
            SELECT 
                pro.codigo as codigo,
                pro.descripcion as descripcion,
                cat.descripcion as descripcioncategoria,
                pro.precio
            FROM productos as pro
            JOIN categorias as cat on cat.codigo = pro.codigocategoria
        ");
        $resultado = mysqli_fetch_all($respuesta, MYSQLI_ASSOC);
        echo json_encode($resultado);
        break;

    case 'agregar':
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
        $codigocategoria = mysqli_real_escape_string($conexion, $_POST['codigocategoria']);
        
        $respuesta = mysqli_query($conexion, "
            insert into productos (descripcion, precio, codigocategoria) 
            VALUES ('$descripcion', '$precio', '$codigocategoria')
        ");
        echo json_encode($respuesta);
        break;

    case 'recuperar':
        $codigo = mysqli_real_escape_string($conexion, $_POST['codigo']);
        $respuesta = mysqli_query($conexion, "
            SELECT codigo, descripcion, precio, codigocategoria 
            FROM productos 
            WHERE codigo = '$codigo'
        ");
        $resultado = mysqli_fetch_all($respuesta, MYSQLI_ASSOC);
        echo json_encode($resultado);
        break;

    case 'borrar':
        $codigo = mysqli_real_escape_string($conexion, $_POST['codigo']);
        $respuesta = mysqli_query($conexion, "
            DELETE FROM productos 
            WHERE codigo = '$codigo'
        ");
        echo json_encode($respuesta);
        break;

    case 'modificar':
        $codigo = mysqli_real_escape_string($conexion, $_POST['codigo']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
        $codigocategoria = mysqli_real_escape_string($conexion, $_POST['codigocategoria']);
        
        $respuesta = mysqli_query($conexion, "
            UPDATE productos 
            SET descripcion = '$descripcion', precio = '$precio', codigocategoria = '$codigocategoria' 
            WHERE codigo = '$codigo'
        ");
        echo json_encode($respuesta);
        break;

    case 'listarcategorias':
        $respuesta = mysqli_query($conexion, "SELECT codigo, descripcion FROM categorias");
        $resultado = mysqli_fetch_all($respuesta, MYSQLI_ASSOC);
        echo json_encode($resultado);
        break;
}
?>
