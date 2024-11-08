<!doctype html>
<html lang="es">

  <head>
    <title>Mini Sistema de Facturación</title>
    <meta charset="utf-8">

      <link href="/semana4/sesion1/css/bootstrap.min.css" rel="stylesheet" />
      <link href="/semana4/sesion1/css/datatables.min.css" rel="stylesheet" />

      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
      
      <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
      <link rel="stylesheet" href="css/smart_wizard.min.css" type="text/css" />
      <link rel="stylesheet" href="css/smart_wizard_theme_arrows.min.css" type="text/css" />
      <link rel="stylesheet" href="css/estilos.css" type="text/css" />

      <link href="/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/css/datatables.min.css" rel="stylesheet" />

  </head>

  <body>

    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">CRUD</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/semana4/sesion1/">Facturación</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Administración
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="php/categorias/">Categorías</a></li>
                <li><a class="dropdown-item" href="#">Clientes</a></li>
                <li><a class="dropdown-item" href="#">Productos</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <?php
        require("php/conexion.php");
        $con = retornarConexion();

        $consulta = mysqli_query(
          $con, 
          "select
              fact.codigo as codigo,
              date_format(fecha, '%d/%m/%Y') as fecha,
              cli.nombre,
              round(sum(deta.precio * deta.cantidad), 2) AS importefactura
            from facturas AS fact
            join clientes AS cli ON cli.codigo = fact.codigocliente
            join detallefactura AS deta ON deta.codigofactura = fact.codigo
            GROUP BY deta.codigofactura
            ORDER BY codigo DESC
          ") or die(mysqli_error($con));

        $filas = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
      ?>

      <h1>Facturas emitidas</h1>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Factura</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th class="text-right">Importe</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($filas as $fila) {
          ?>
            <tr>
              <td><?php echo $fila['codigo']; ?></td>
              <td><?php echo $fila['nombre']; ?></td>
              <td><?php echo $fila['fecha']; ?></td>
              <td class="text-right"><?php echo '$' . number_format($fila['importefactura'], 2, ',', '.'); ?></td>
              <td class="text-right">
                <a class="btn btn-primary btn-sm botonimprimir" role="button" href="#" data-codigo="<?php echo $fila['codigo']; ?>">Imprime?</a>
                <a class="btn btn-primary btn-sm botonborrar" role="button" href="#" data-codigo="<?php echo $fila['codigo']; ?>">Borra?</a>
              </td>
            </tr>
          <?php
            }
          ?>
        </tbody>
      </table>
      <button type="button" id="btnNuevaFactura" class="btn btn-success">Emitir factura</button>
    </div>
<!-- ModalConfirmarBorrar -->
<div class="modal fade" id="ModalConfirmarBorrar" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width: 600px" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1>¿Realmente quiere borrar la factura?</h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnConfirmarBorrado" class="btn btn-success">Confirmar</button>
        <button type="button" data-dismiss="modal" class="btn btn-success">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {

    // Acción para emitir una nueva factura
    $('#btnNuevaFactura').click(function() {
      window.location = 'facturacion.php';  // Redirige a la página de facturación
    });

    var codigofactura;

    // Acción al hacer clic en el botón de borrar factura
    $('.botonborrar').click(function() {
      codigofactura = $(this).get(0).dataset.codigo;  // Obtiene el código de la factura
      $("#ModalConfirmarBorrar").modal();  // Muestra el modal de confirmación
    });

    // Acción para confirmar la eliminación de la factura
    $('#btnConfirmarBorrado').click(function() {
      window.location = 'borrarfactura.php?codigofactura=' + codigofactura;  // Redirige para borrar la factura
    });

    // Acción para imprimir la factura
    $('.botonimprimir').click(function() {
      window.open('pdffactura.php?' + '&codigofactura=' + $(this).get(0).dataset.codigo, '_blank');  // Abre el PDF de la factura en una nueva ventana
    });

  });
  </script>

</body>
</html>

