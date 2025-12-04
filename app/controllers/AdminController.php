<?php
namespace App\Controllers;
require_once __DIR__ . '/Controller.php';
use App\Controllers\Controller;

class AdminController extends Controller {
    public function parametros() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $paramModel = $this->model('ParametrosModel');
        $parametros = $paramModel->getParametrosLegales();
        $rangosSolidaridad = $paramModel->getRangosFondoSolidaridad();
        $tablaRetencion = $paramModel->getTablaRetencionFuente();
        $historial = $paramModel->getHistorialCambios();
        $this->view('admin/parametros', [
            'parametros' => $parametros,
            'rangosSolidaridad' => $rangosSolidaridad,
            'tablaRetencion' => $tablaRetencion,
            'historial' => $historial
        ]);
    }

    // El método guardarParametrosLegales ya no permite editar el SMLV, solo muestra error y redirige
    public function guardarParametrosLegales() {
        $_SESSION['error'] = 'La edición del salario mínimo no está permitida.';
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }

    public function guardarRangosSolidaridad() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $rangos = $_POST['rangos'] ?? [];
        $paramModel = $this->model('ParametrosModel');
        $result = $paramModel->actualizarRangosSolidaridad($rangos, $_SESSION['user']['id_doc']);
        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Rangos actualizados.' : 'Error en rangos.';
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }

    public function guardarTablaRetencion() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $tabla = $_POST['tabla'] ?? [];
        $paramModel = $this->model('ParametrosModel');
        $result = $paramModel->actualizarTablaRetencion($tabla, $_SESSION['user']['id_doc']);
        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Tabla de retención actualizada.' : 'Error en tabla.';
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }

    public function exportarConfiguracion() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $paramModel = $this->model('ParametrosModel');
        $data = $paramModel->getExportData();
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="configuracion_zigma.json"');
        echo json_encode($data);
        exit;
    }

    public function actualizarSalariosEmpleadosASMLV() {
        $paramModel = $this->model('ParametrosModel');
        $parametros = $paramModel->getParametrosVigentes();
        $nuevoSMLV = isset($parametros['smlv']) ? $parametros['smlv'] : null;
        if ($nuevoSMLV) {
            $empleadoModel = $this->model('Empleado');
            $empleadoModel->actualizarSalarioTodos($nuevoSMLV);
            $_SESSION['success'] = 'Todos los salarios base han sido actualizados al SMLV vigente.';
        } else {
            $_SESSION['error'] = 'No se pudo obtener el SMLV vigente.';
        }
        header('Location: /ZIGMA/public/index.php?url=Empleado');
        exit;
    }

    // Permite editar solo el auxilio de transporte y su año, no la regla de elegibilidad ni el SMLV
    public function guardarAuxilioTransporte() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $auxilio = isset($_POST['auxilio_transporte']) ? floatval($_POST['auxilio_transporte']) : null;
        $anio = isset($_POST['año_vigencia']) ? intval($_POST['año_vigencia']) : null;
        if ($auxilio && $anio) {
            $paramModel = $this->model('ParametrosModel');
            $result = $paramModel->actualizarAuxilioTransporte($auxilio, $anio, $_SESSION['user']['id_doc']);
            $_SESSION[$result ? 'success' : 'error'] = $result ? 'Auxilio de transporte actualizado.' : 'Error al actualizar.';
        } else {
            $_SESSION['error'] = 'Datos incompletos para actualizar el auxilio de transporte.';
        }
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }

    public function guardarSalarioMinimo() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $smlv = isset($_POST['salario_minimo']) ? intval($_POST['salario_minimo']) : null;
        if ($smlv && $smlv > 0) {
            $archivo = __DIR__ . '/../../config/nomina.php';
            $contenido = file_get_contents($archivo);
            $contenido = preg_replace('/define\(["\\\']SALARIO_MINIMO["\\\'],\s*\d+\s*\);/', "define('SALARIO_MINIMO', $smlv);", $contenido);
            file_put_contents($archivo, $contenido);
            $_SESSION['success'] = 'Salario mínimo actualizado correctamente.';
        } else {
            $_SESSION['error'] = 'Valor de salario mínimo inválido.';
        }
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }
}

