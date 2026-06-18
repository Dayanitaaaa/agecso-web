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
                $data['eventos'] = $this->getEventos(3);
                break;
        }

        $viewPath = __DIR__ . '/../views/pages/' . $page . '.php';
        require __DIR__ . '/../views/layouts/main.php';
    }

    private function getNoticias(int $limit = 0) {
        $sql = "SELECT * FROM noticias WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC";
        if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    private function getEventos(int $limit = 0) {
        $sql = "SELECT * FROM eventos WHERE estado != 'cancelado' ORDER BY fecha_evento DESC";
        if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
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
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $asunto = trim($_POST['asunto'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

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
