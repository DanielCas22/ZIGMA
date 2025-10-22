<?php

class DesprendibleController extends Controller {
    
    public function __construct() {
        // Solo asegurar que la sesión esté iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Mostrar lista de empleados para seleccionar desprendible
     */
    public function index() {
        $desprendibleModel = $this->model('DesprendibleModel');
        $empleados = $desprendibleModel->obtenerEmpleadosParaDesprendible();
        
        $data = [
            'title' => 'Desprendibles de Nómina',
            'empleados' => $empleados
        ];
        
        $this->view('desprendible/index', $data);
    }
    
    /**
     * Mostrar desprendible específico
     */
    public function mostrar($empleadoId = null, $mes = null, $anio = null) {
        if (!$empleadoId) {
            header('Location: /ZIGMA/public/index.php?url=Desprendible');
            exit;
        }
        require_once __DIR__ . '/../models/RolePermissions.php';
        $currentRole = RolePermissions::getCurrentUserRole();
        $currentEmployeeId = RolePermissions::getCurrentEmployeeId();
        // Solo admin/rrhh pueden ver cualquier desprendible, empleados solo el suyo
        if ($currentRole === 'empleado' && $empleadoId != $currentEmployeeId) {
            header('Location: /ZIGMA/public/index.php?url=dashboard&error=no_permission');
            exit;
        }
        $desprendibleModel = $this->model('DesprendibleModel');
        $desprendible = $desprendibleModel->obtenerDesprendible($empleadoId, $mes, $anio);
        if (!$desprendible) {
            $_SESSION['error'] = 'No se encontró información para generar el desprendible';
            header('Location: /ZIGMA/public/index.php?url=Desprendible');
            exit;
        }
        $data = [
            'title' => 'Desprendible de Nómina',
            'desprendible' => $desprendible
        ];
        $this->view('desprendible/mostrar', $data);
    }

    /**
     * Generar PDF del desprendible (solo admin/rrhh)
     */
    public function pdf($empleadoId = null, $mes = null, $anio = null) {
        // Verificar autenticación
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header('Location: /ZIGMA/public/index.php');
            exit;
        }
        require_once __DIR__ . '/../models/RolePermissions.php';
        $currentRole = RolePermissions::getCurrentUserRole();
        if (!in_array($currentRole, ['admin', 'rrhh'])) {
            header('Location: /ZIGMA/public/index.php?url=dashboard&error=no_permission');
            exit;
        }
        if (!$empleadoId) {
            header('Location: /ZIGMA/public/index.php?url=Desprendible');
            exit;
        }
        $desprendibleModel = $this->model('DesprendibleModel');
        $desprendible = $desprendibleModel->obtenerDesprendible($empleadoId, $mes, $anio);
        if (!$desprendible) {
            $_SESSION['error'] = 'No se encontró información para generar el desprendible';
            header('Location: /ZIGMA/public/index.php?url=Desprendible');
            exit;
        }
        // Registrar notificación para el empleado
        $desprendibleModel->registrarNotificacionDesprendible($empleadoId, $mes, $anio);
        // Configurar headers para PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="desprendible_' . $desprendible['empleado']['nombre'] . '_' . date('Y-m') . '.pdf"');
        $data = [
            'title' => 'Desprendible de Nómina - PDF',
            'desprendible' => $desprendible,
            'formato_pdf' => true
        ];
        $this->view('desprendible/pdf', $data);
    }
    
    /**
     * Enviar desprendible por correo
     */
    public function enviarCorreo() {
        $empleadoId = $_POST['empleado_id'] ?? null;
        $email = $_POST['email'] ?? null;
        $mes = $_POST['mes'] ?? null;
        $anio = $_POST['anio'] ?? null;
        
        if (!$empleadoId || !$email) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
        
        $desprendibleModel = $this->model('DesprendibleModel');
        $desprendible = $desprendibleModel->obtenerDesprendible($empleadoId, $mes, $anio);
        
        if (!$desprendible) {
            echo json_encode(['success' => false, 'message' => 'No se encontró el desprendible']);
            return;
        }
        
        // Aquí se implementaría el envío de correo
        // Por ahora simulamos el éxito
        $success = true; // mail($email, $subject, $message, $headers);
        
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Desprendible enviado correctamente' : 'Error al enviar el desprendible'
        ]);
    }

    /**
     * Eliminar desprendible (solo admin/rrhh)
     */
    public function eliminar($empleadoId = null) {
        require_once __DIR__ . '/../models/RolePermissions.php';
        $currentRole = RolePermissions::getCurrentUserRole();
        if (!in_array($currentRole, ['admin', 'rrhh'])) {
            header('Location: /ZIGMA/public/index.php?url=dashboard&error=no_permission');
            exit;
        }
        if (!$empleadoId) {
            $_SESSION['error'] = 'Empleado no especificado.';
            header('Location: /ZIGMA/public/index.php?url=Desprendible');
            exit;
        }
        $desprendibleModel = $this->model('DesprendibleModel');
        $exito = $desprendibleModel->eliminarDesprendible($empleadoId);
        if ($exito) {
            $_SESSION['success'] = 'Desprendible eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo eliminar el desprendible.';
        }
        header('Location: /ZIGMA/public/index.php?url=Desprendible');
        exit;
    }
}