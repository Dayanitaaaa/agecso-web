<?php

class PageController
{
    private array $pages = [
        'inicio' => 'Inicio',
        'nosotros' => 'Nosotros',
        'servicios' => 'Servicios',
        'eventos' => 'Eventos',
        'cursos-webinar' => 'Cursos y Webinar',
        'agenda' => 'Agenda',
        'somos-agecso' => 'Somos AGECSO',
        'aliados' => 'Aliados',
        'noticias' => 'Noticias',
        'contacto' => 'Contacto',
    ];
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function show(string $page): void
    {
        if (!array_key_exists($page, $this->pages)) {
            http_response_code(404);
            $page = '404';
            $title = 'Página no encontrada';
        } else {
            $title = $this->pages[$page];
        }

        // Cargar datos según la página
        $data = [];
        $message = '';
        $messageType = '';

        switch ($page) {
            case 'noticias':
                $data = $this->getNoticias();
                break;
            case 'eventos':
                $data = $this->getEventos();
                break;
            case 'cursos-webinar':
                $data = $this->getCursos();
                break;
            case 'aliados':
                $data = $this->getAliados();
                break;
            case 'servicios':
                $data = $this->getServicios();
                break;
            case 'contacto':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $this->saveContacto();
                    $message = $result['message'];
                    $messageType = $result['type'];
                }
                break;
            case 'inicio':
                $data['noticias'] = $this->getNoticias(3);
                $data['eventos'] = $this->getEventos(3, true);
                $data['agenda'] = $this->getAgenda();
                $data['ruedas'] = $this->getRuedas();
                break;
            case 'agenda':
                $data['agenda'] = $this->getAgenda();
                $data['eventos'] = $this->getEventos();
                $data['ruedas'] = $this->getRuedas();
                break;
        }

        $viewPath = __DIR__ . '/../views/pages/' . $page . '.php';
        require __DIR__ . '/../views/layouts/main.php';
    }

    private function getAgenda() {
        try {
            // Asegurar tabla si no existe
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS agenda (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(255) NOT NULL,
                descripcion TEXT,
                imagen VARCHAR(255) DEFAULT NULL,
                fecha_inicio DATE NULL,
                fecha_fin DATE NULL,
                hora_inicio TIME NULL,
                hora_fin TIME NULL,
                lugar VARCHAR(255) DEFAULT NULL,
                tipo VARCHAR(50) DEFAULT 'general',
                link_registro VARCHAR(500) DEFAULT NULL,
                texto_boton VARCHAR(100) DEFAULT 'Registrarme',
                estado ENUM('activo', 'inactivo', 'destacado') DEFAULT 'activo',
                orden INT DEFAULT 999,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            $stmt = $this->pdo->query("SELECT * FROM agenda WHERE estado != 'inactivo' ORDER BY CASE WHEN estado = 'destacado' THEN 0 ELSE 1 END, orden ASC, fecha_inicio DESC, id DESC");
            return $stmt ? $stmt->fetchAll() : [];
        } catch (Exception $e) {
            return [];
        }
    }

    private function getNoticias(int $limit = 0) {
        // Verificar si la columna orden existe
        $orderClause = "fecha_publicacion DESC, id DESC";
        try {
            $stmt_check = $this->pdo->query("SHOW COLUMNS FROM noticias LIKE 'orden'");
            if ($stmt_check->fetch()) {
                $orderClause = "orden ASC, " . $orderClause;
            }
        } catch (Exception $e) {}

        $sql = "SELECT * FROM noticias WHERE estado = 'publicado' ORDER BY $orderClause";
        if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    private function getEventos(int $limit = 0, bool $upcomingOnly = false) {
        $where = "estado != 'cancelado'";
        $order = "fecha_evento DESC";
        
        if ($upcomingOnly) {
            // Solo mostrar eventos con fecha futura o sin fecha pero que estén marcados como programados
            $where .= " AND estado = 'programado' AND (fecha_evento >= CURDATE() OR fecha_evento IS NULL OR fecha_evento = '0000-00-00')";
            $order = "fecha_evento ASC";
        }

        $sql = "SELECT * FROM eventos WHERE $where ORDER BY $order";
        if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    private function getRuedas(int $limit = 0) {
        try {
            $ruedas = [];
            // 1. Conectar a la base de datos de ruedas (producción)
            try {
                $host = "localhost";
                $port = 3306;
                $db_name = "u152451479_agecso_rueda";
                $username = "u152451479_agecso_user";
                $password = "Lopez1007645229*";

                $pdo_rueda = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

                $sql = "SELECT * FROM ruedas_negocios 
                        WHERE estadoRueda NOT IN ('cancelada', 'finalizada') 
                        AND (fechaFin >= CURDATE() OR fechaFin IS NULL)
                        ORDER BY CASE WHEN estadoRueda = 'activa' THEN 0 WHEN estadoRueda = 'inscripciones' THEN 1 ELSE 2 END, fechaInicio DESC";
                if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
                $stmt = $pdo_rueda->query($sql);
                if ($stmt) $ruedas = $stmt->fetchAll();
            } catch (Exception $e) {
                // 2. Intentar consulta en la conexión actual
                try {
                    $sql = "SELECT * FROM u152451479_agecso_rueda.ruedas_negocios 
                            WHERE estadoRueda NOT IN ('cancelada', 'finalizada') 
                            AND (fechaFin >= CURDATE() OR fechaFin IS NULL)
                            ORDER BY CASE WHEN estadoRueda = 'activa' THEN 0 WHEN estadoRueda = 'inscripciones' THEN 1 ELSE 2 END, fechaInicio DESC";
                    if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
                    $stmt = $this->pdo->query($sql);
                    if ($stmt) $ruedas = $stmt->fetchAll();
                } catch (Exception $e2) {
                    $sql = "SELECT * FROM ruedas_negocios 
                            WHERE estadoRueda NOT IN ('cancelada', 'finalizada') 
                            AND (fechaFin >= CURDATE() OR fechaFin IS NULL)
                            ORDER BY CASE WHEN estadoRueda = 'activa' THEN 0 WHEN estadoRueda = 'inscripciones' THEN 1 ELSE 2 END, fechaInicio DESC";
                    if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
                    $stmt = $this->pdo->query($sql);
                    if ($stmt) $ruedas = $stmt->fetchAll();
                }
            }

            return $ruedas;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getCursos() {
        $stmt = $this->pdo->query("SELECT * FROM cursos WHERE estado != 'cancelado' ORDER BY fecha_inicio DESC");
        return $stmt->fetchAll();
    }

    private function getAliados() {
        $stmt = $this->pdo->query("SELECT * FROM aliados WHERE activo = 1 ORDER BY orden ASC, nombre ASC");
        return $stmt->fetchAll();
    }

    private function getServicios() {
        $stmt = $this->pdo->query("SELECT * FROM servicios WHERE activo = 1 ORDER BY orden ASC");
        return $stmt->fetchAll();
    }

    private function saveContacto(): array {
        // Antispam Honeypot check
        if (!empty($_POST['website_verification_code'])) {
            // Es un bot, ignoramos silenciosamente o damos error
            return ['message' => 'Error de validación antispam.', 'type' => 'danger'];
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $asunto = trim($_POST['asunto'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        // Validación extra: Si el mensaje contiene muchos enlaces, probablemente es spam
        $linkCount = preg_match_all('/http|www|<a href/i', $mensaje);
        if ($linkCount > 3) {
            return ['message' => 'Tu mensaje contiene demasiados enlaces y ha sido bloqueado por seguridad.', 'type' => 'danger'];
        }

        if (empty($nombre) || empty($email) || empty($mensaje)) {
            return ['message' => 'Por favor completa los campos obligatorios.', 'type' => 'danger'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['message' => 'El correo electrónico no es válido.', 'type' => 'danger'];
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO mensajes_contacto (nombre, email, telefono, asunto, mensaje) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $telefono, $asunto, $mensaje]);
            return ['message' => 'Mensaje enviado correctamente. Te contactaremos pronto.', 'type' => 'success'];
        } catch (Exception $e) {
            return ['message' => 'Hubo un error al enviar el mensaje. Intenta de nuevo.', 'type' => 'danger'];
        }
    }
}
