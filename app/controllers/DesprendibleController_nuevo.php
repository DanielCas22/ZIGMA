<?php
require_once 'Controller_nuevo.php';

class DesprendibleController_nuevo extends Controller {
    public function index() { return $this->ver(); }

    public function ver($nominaId = null) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }

    // Obtener DB desde cualquier modelo base
    $db = $this->model('EmpleadoModel')->getDb();

        if (!$nominaId) {
            $stmt = $db->prepare('SELECT id_nomina FROM nomina WHERE user_id = :uid ORDER BY id_nomina DESC LIMIT 1');
            $stmt->execute(['uid' => $_SESSION['user']['user_id']]);
            $nominaId = $stmt->fetchColumn();
        }

        if (!$nominaId) {
            $this->view('desprendible/ver', [ 'nomina' => null, 'dev' => null, 'ded' => null ]);
            return;
        }

        $stmt = $db->prepare('SELECT n.*, e.nombre, e.apellido FROM nomina n JOIN user u ON u.id_doc = n.user_id JOIN empleados e ON e.id_empleados = u.empleado_id WHERE n.id_nomina = :id');
        $stmt->execute(['id' => $nominaId]);
        $nomina = $stmt->fetch(PDO::FETCH_ASSOC);

        $dev = $db->prepare('SELECT * FROM total_devengado WHERE nomina_id = :id');
        $dev->execute(['id' => $nominaId]);
        $devengado = $dev->fetch(PDO::FETCH_ASSOC);

        $ded = $db->prepare('SELECT * FROM total_deducido WHERE nomina_id = :id');
        $ded->execute(['id' => $nominaId]);
        $deducido = $ded->fetch(PDO::FETCH_ASSOC);

        $this->view('desprendible/ver', [
            'nomina' => $nomina,
            'dev' => $devengado,
            'ded' => $deducido,
        ]);
    }

    // Exportar desprendible a PDF
    public function pdf($nominaId = null) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        require_once __DIR__ . '/../helpers/PdfHelper.php';
        $db = $this->model('EmpleadoModel')->getDb();
        if (!$nominaId) {
            $stmt = $db->prepare('SELECT id_nomina FROM nomina WHERE user_id = :uid ORDER BY id_nomina DESC LIMIT 1');
            $stmt->execute(['uid' => $_SESSION['user']['user_id']]);
            $nominaId = $stmt->fetchColumn();
        }
        if (!$nominaId) { die('No hay nómina disponible.'); }
        $stmt = $db->prepare('SELECT n.*, e.nombre, e.apellido FROM nomina n JOIN user u ON u.id_doc = n.user_id JOIN empleados e ON e.id_empleados = u.empleado_id WHERE n.id_nomina = :id');
        $stmt->execute(['id' => $nominaId]);
        $nomina = $stmt->fetch(PDO::FETCH_ASSOC);
        $dev = $db->prepare('SELECT * FROM total_devengado WHERE nomina_id = :id');
        $dev->execute(['id' => $nominaId]);
        $devengado = $dev->fetch(PDO::FETCH_ASSOC);
        $ded = $db->prepare('SELECT * FROM total_deducido WHERE nomina_id = :id');
        $ded->execute(['id' => $nominaId]);
        $deducido = $ded->fetch(PDO::FETCH_ASSOC);
        ob_start();
        include __DIR__ . '/../views/desprendible/ver_pdf.php';
        $html = ob_get_clean();
        PdfHelper::render($html, 'desprendible_nomina.pdf');
    }
}
