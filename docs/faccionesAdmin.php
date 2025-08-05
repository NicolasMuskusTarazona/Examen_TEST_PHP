<?php
session_start();

// Proteger la vista: solo para admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: no-autorizado.php");
    exit();
}

$url = "http://127.0.0.1:8082/facciones";
$facciones = [];
$error = null;
$success = null;

// Eliminar facción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id = $_POST['eliminar_id'];

    $options = [
        'http' => [
            'method' => 'DELETE'
        ]
    ];
    $context = stream_context_create($options);
    $deleteUrl = $url . '/' . $id;

    $response = @file_get_contents($deleteUrl, false, $context);
    $success = $response !== false ? "Facción eliminada con éxito." : "Error al eliminar.";
}

// Editar facción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editando_id'])) {
    $id = $_POST['editando_id'];
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $lider_id = $_POST['lider_id'] ?? '';

    if ($nombre && $descripcion && $lider_id) {
        $data = json_encode([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'lider_id' => (int)$lider_id
        ]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => 'PUT',
                'content' => $data
            ]
        ];
        $context = stream_context_create($options);
        $putUrl = $url . '/' . $id;
        $response = @file_get_contents($putUrl, false, $context);
        $success = $response !== false ? "Facción actualizada correctamente." : "Error al actualizar.";
    } else {
        $error = "Todos los campos son obligatorios para editar.";
    }
}

// Crear nueva facción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['editando_id']) && empty($_POST['eliminar_id'])) {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $lider_id = $_POST['lider_id'] ?? '';

    if ($nombre && $descripcion && $lider_id) {
        $data = json_encode([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'lider_id' => (int)$lider_id
        ]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => $data
            ]
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        $success = $response !== false ? "Facción registrada con éxito." : "Error al registrar.";
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Obtener todas las facciones
$rawData = @file_get_contents($url);
if ($rawData !== false) {
    $facciones = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "Error al leer las facciones.";
    }
} else {
    $error = "No se pudo conectar con la API.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Facciones ADMIN - HCF</title>
    <link rel="stylesheet" href="./style/facciones.css">
</head>

<body>
    <header>
        <h1>Facciones HCF</h1>
        <nav>
            <a href="./productosAdmin.php">Facciones</a>
            <a href="./miembrosAdmin.php">Miembros</a>
            <a href="./claimsAdmin.php">Claims</a>
            <a href="./invitacionesAdmin.php">Invitaciones</a>
            <a href="./eventosAdmin.php">Eventos</a>
        </nav>
    </header>

    <main>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <h2>Registrar / Editar Facción</h2>
        <form method="POST">
            <input type="hidden" name="editando_id" id="editando_id">
            <label>Nombre:<br><input type="text" name="nombre" id="nombre" required></label><br><br>
            <label>Descripción:<br><textarea name="descripcion" id="descripcion" required></textarea></label><br><br>
            <label>Líder ID:<br><input type="number" name="lider_id" id="lider_id" required></label><br><br>
            <button type="submit">Guardar</button>
        </form>

        <h2>Facciones Registradas</h2>
        <?php if (empty($facciones)): ?>
            <p>No hay facciones registradas.</p>
        <?php else: ?>
            <div class="tarjetas-grid">
                <?php foreach ($facciones as $item): ?>
                    <div class="tarjeta">
                        <h3>ID: <?= $item['id'] ?></h3>
                        <p><strong>Nombre:</strong> <?= $item['nombre'] ?></p>
                        <p><strong>Descripcion:</strong> <?= $item['descripcion'] ?></p>
                        <p><strong>Lider ID:</strong> <?= $item['lider_id'] ?></p>
                        <p><strong>Fecha de Creacion:</strong> <?= $item['fecha_creacion'] ?></p>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="eliminar_id" value="<?= $item['id'] ?>">
                            <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar esta facción?')">Eliminar</button>
                        </form>
                        <button onclick="editarFaccion('<?= $item['id'] ?>','<?= htmlspecialchars($item['nombre']) ?>','<?= htmlspecialchars($item['descripcion']) ?>','<?= $item['lider_id'] ?>')">Editar</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <script>
        function editarFaccion(id, nombre, descripcion, lider_id) {
            document.getElementById('editando_id').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('lider_id').value = lider_id;
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>