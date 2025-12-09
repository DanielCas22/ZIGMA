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
        $parametrosGenerales = $this->model('ParametrosGenerales')->getAll();
        $parametros = $paramModel->getParametrosLegales();
        $rangosSolidaridad = $paramModel->getRangosFondoSolidaridad();
        $tablaRetencion = $paramModel->getTablaRetencionFuente();
        $historial = $paramModel->getHistorialCambios();
        $this->view('admin/parametros', [
            'parametrosGenerales' => $parametrosGenerales,
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

    public function guardarParametrosGenerales() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $paramModel = $this->model('ParametrosGenerales');
        $result = $paramModel->update([
            'uvt' => $_POST['uvt'] ?? 0,
            'smlv' => $_POST['smlv'] ?? 0,
            'periodo_pago' => $_POST['periodo_pago'] ?? 'mensual',
            'formato_divisa' => $_POST['formato_divisa'] ?? '$',
            'formato_decimales' => $_POST['formato_decimales'] ?? 2,
            'formato_miles' => $_POST['formato_miles'] ?? '.',
            'ano_vigencia' => $_POST['ano_vigencia'] ?? date('Y')
        ]);
        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Parámetros generales actualizados correctamente.' : 'Error al actualizar parámetros.';
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
            // Actualizar auxilio de transporte para todos los empleados
            $empleadoModel = $this->model('Empleado');
            $empleadoModel->actualizarAuxilioTransporteTodos($auxilio);
            $_SESSION[$result ? 'success' : 'error'] = $result ? 'Auxilio de transporte actualizado y aplicado a todos los empleados.' : 'Error al actualizar.';
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

    public function guardarAportes() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $salud_empleador = isset($_POST['salud_empleador']) ? floatval($_POST['salud_empleador']) : null;
        $salud_empleado = isset($_POST['salud_empleado']) ? floatval($_POST['salud_empleado']) : null;
        $pension_empleador = isset($_POST['pension_empleador']) ? floatval($_POST['pension_empleador']) : null;
        $pension_empleado = isset($_POST['pension_empleado']) ? floatval($_POST['pension_empleado']) : null;
        $parafiscales = isset($_POST['parafiscales']) ? floatval($_POST['parafiscales']) : null;
        $prestaciones = isset($_POST['prestaciones']) ? floatval($_POST['prestaciones']) : null;
        $sena = isset($_POST['sena']) ? floatval($_POST['sena']) : 0;
        $icbf = isset($_POST['icbf']) ? floatval($_POST['icbf']) : 0;
        $paramModel = $this->model('ParametrosModel');
        $result = $paramModel->actualizarAportes([
            'salud_empleador' => $salud_empleador,
            'salud_empleado' => $salud_empleado,
            'pension_empleador' => $pension_empleador,
            'pension_empleado' => $pension_empleado,
            'parafiscales' => $parafiscales,
            'sena' => $sena,
            'icbf' => $icbf,
            'prestaciones' => $prestaciones
        ], $_SESSION['user']['id_doc']);
        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Aportes actualizados correctamente.' : 'Error al actualizar aportes.';
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }

    public function guardarSalarioRol() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $rol = $_POST['rol'] ?? null;
        $salario = $_POST['salario_rol'] ?? null;
        if (!$rol || !$salario || $salario < 0) {
            $_SESSION['error'] = 'Datos inválidos para salario por rol.';
            header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
            exit;
        }
        $salarioModel = $this->model('SalarioPorRol');
        $existe = $salarioModel->getRolInfo($rol);
        if ($existe) {
            $salarioModel->updateSalarioRol($rol, $salario);
        } else {
            $salarioModel->create($rol, $salario, 'Definido por admin');
        }
        $_SESSION['success'] = 'Salario base actualizado para el rol ' . htmlspecialchars($rol) . '.';
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }

    public function guardarPorcentajesHorasExtras() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /ZIGMA/public/index.php?url=Dashboard');
            exit;
        }
        $horas = $_POST['horas'] ?? [];
        $tipoHoraModel = $this->model('TipoHoraExtra');
        
        try {
            foreach ($horas as $index => $hora) {
                $nombre = isset($hora['nombre']) ? trim($hora['nombre']) : null;
                $porcentaje = isset($hora['porcentaje']) ? floatval($hora['porcentaje']) : null;
                
                if ($nombre && $porcentaje !== null) {
                    // Actualizar o crear el tipo de hora extra
                    $tipoHoraModel->guardarTipo($nombre, $porcentaje);
                }
            }
            $_SESSION['success'] = 'Porcentajes de horas extras actualizados correctamente.';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar porcentajes: ' . $e->getMessage();
        }
        
        header('Location: /ZIGMA/public/index.php?url=Admin/parametros');
        exit;
    }
}

