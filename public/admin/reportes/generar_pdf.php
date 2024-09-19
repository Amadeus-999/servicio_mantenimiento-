<?php
require '../../../config/database.php';
require_once __DIR__ . '../../../../vendor/autoload.php'; // Incluye MPDF

$npesonal = $_GET['npesonal'] ?? '';
$numero_reporte = $_GET['numero_reporte'] ?? '';

// Obtener los datos del docente
$stmt_docente = $pdo->prepare("SELECT * FROM t_docente WHERE npesonal = ?");
$stmt_docente->execute([$npesonal]);
$docente = $stmt_docente->fetch(PDO::FETCH_ASSOC);

$nombre_solicitante = $docente ? $docente['nombre'] . ' ' . $docente['apellido_p'] . ' ' . $docente['apellido_m'] : 'Docente no encontrado';

// Crear el HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; }
        .container { margin: 0 auto; width: 100%; }
        h1 { text-align: center; }
        .section-title { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 8px; border: 1px solid #000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reporte de Servicio Técnico</h1>
        <p>Número de Reporte: ' . $numero_reporte . '</p>
        <p>Fecha: ' . date('Y-m-d') . '</p>
        
        <div class="section-title">Datos del Solicitante</div>
        <table>
            <tr><th>Nombre</th><td>' . $nombre_solicitante . '</td></tr>
            <tr><th>Número Personal</th><td>' . $docente['npesonal'] . '</td></tr>
            <tr><th>Correo</th><td>' . $docente['correo'] . '</td></tr>
        </table>

        <div class="section-title">Datos del Equipo</div>
        <!-- Aquí puedes agregar más información como los datos del equipo -->
    </div>
</body>
</html>
';

// Crear instancia de MPDF
$mpdf = new \mpdf\Mpdf();
$mpdf->WriteHTML($html);

// Generar y enviar el PDF al navegador
$mpdf->Output('reporte_' . $numero_reporte . '.pdf', 'I');
?>
