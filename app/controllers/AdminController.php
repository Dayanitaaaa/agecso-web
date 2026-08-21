<?php
require_once __DIR__ . '/../models/BaseModel.php';

class AdminController {
    private $pdo;
    private $models = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
        
        // Verificar autenticación
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . APP_URL . "/login");
            exit();
        }

        // Asegurar tabla de agenda
        try {
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
        } catch (Exception $e) {}

        // Inicializar modelos
        $this->models['agenda'] = new BaseModel($pdo);
        $this->models['agenda']->table = 'agenda';

        $this->models['noticias'] = new BaseModel($pdo);
        $this->models['noticias']->table = 'noticias';
        
        $this->models['eventos'] = new BaseModel($pdo);
        $this->models['eventos']->table = 'eventos';
        
        $this->models['cursos'] = new BaseModel($pdo);
        $this->models['cursos']->table = 'cursos';
        
        $this->models['aliados'] = new BaseModel($pdo);
        $this->models['aliados']->table = 'aliados';
        
        $this->models['servicios'] = new BaseModel($pdo);
        $this->models['servicios']->table = 'servicios';
        
        $this->models['mensajes'] = new BaseModel($pdo);
        $this->models['mensajes']->table = 'mensajes_contacto';
    }

    /**
     * Panel principal del admin
     */
    public function index() {
        $stats = [
            'agenda' => $this->models['agenda']->count(),
            'noticias' => $this->models['noticias']->count(),
            'eventos' => $this->models['eventos']->count(),
            'cursos' => $this->models['cursos']->count(),
            'aliados' => $this->models['aliados']->count(),
            'servicios' => $this->models['servicios']->count(),
            'mensajes' => $this->models['mensajes']->count()
        ];

        $title = 'Panel de Administración';
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Gestión de agenda y convocatorias
     */
    public function agenda() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['agenda']->getAll('orden ASC, fecha_inicio DESC, id DESC');
                $title = 'Gestión de Agenda';
                require __DIR__ . '/../views/admin/agenda/list.php';
                break;
                
            case 'create':
                $this->handleAgendaForm();
                break;
                
            case 'edit':
                $this->handleAgendaForm($id);
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['agenda']->delete($id);
                    $this->setFlash('Elemento de agenda eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/agenda");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/agenda");
                exit();
        }
    }

    /**
     * Manejar formulario de agenda
     */
    private function handleAgendaForm($id = null) {
        $item = $id ? $this->models['agenda']->getById($id) : null;
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'fecha_inicio' => !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null,
                'fecha_fin' => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
                'hora_inicio' => !empty($_POST['hora_inicio']) ? $_POST['hora_inicio'] : null,
                'hora_fin' => !empty($_POST['hora_fin']) ? $_POST['hora_fin'] : null,
                'lugar' => trim($_POST['lugar'] ?? ''),
                'tipo' => $_POST['tipo'] ?? 'evento',
                'link_registro' => trim($_POST['link_registro'] ?? ''),
                'texto_boton' => trim($_POST['texto_boton'] ?? 'Registrarme'),
                'estado' => $_POST['estado'] ?? 'activo',
                'orden' => (int)($_POST['orden'] ?? 999)
            ];

            // Manejar imagen
            if (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == '1') {
                $data['imagen'] = null;
            } else {
                $imgUploaded = $this->handleImageUpload('imagen', $item['imagen'] ?? null);
                if ($imgUploaded) {
                    $data['imagen'] = $imgUploaded;
                }
            }
            
            if (empty($data['titulo'])) {
                $error = 'El título de la actividad es obligatorio.';
            } else {
                if ($id) {
                    $this->models['agenda']->update($id, $data);
                    $this->setFlash('Actividad de agenda actualizada correctamente');
                } else {
                    $this->models['agenda']->insert($data);
                    $this->setFlash('Actividad agregada a la agenda correctamente');
                }
                header("Location: " . APP_URL . "/admin/agenda");
                exit();
            }
        }
        
        $title = $id ? 'Editar Agenda' : 'Nueva Actividad en Agenda';
        require __DIR__ . '/../views/admin/agenda/form.php';
    }

    /**
     * Gestión de noticias
     */
    public function noticias() {
        // Intentar asegurar que las columnas existen solo al cargar la lista (GET)
        if (!$_POST) {
            try {
                $this->pdo->exec("ALTER TABLE noticias ADD COLUMN IF NOT EXISTS orden INT DEFAULT 999 AFTER estado");
                $this->pdo->exec("ALTER TABLE eventos ADD COLUMN IF NOT EXISTS orden INT DEFAULT 999 AFTER estado");
            } catch (Exception $e) { }
        }

        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                // Verificar si la columna 'orden' existe antes de ordenar por ella
                $orderClause = 'created_at DESC';
                $stmt_check_order = $this->pdo->query("SHOW COLUMNS FROM noticias LIKE 'orden'");
                if ($stmt_check_order->fetch()) {
                    $orderClause = 'orden ASC, created_at DESC';
                }
                $items = $this->models['noticias']->getAll($orderClause);
                $title = 'Gestión de Noticias';
                require __DIR__ . '/../views/admin/noticias/list.php';
                break;
                
            case 'create':
                $this->handleNoticiaForm();
                break;
                
            case 'edit':
                $this->handleNoticiaForm($id);
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['noticias']->delete($id);
                    $this->setFlash('Noticia eliminada correctamente');
                }
                header("Location: " . APP_URL . "/admin/noticias");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/noticias");
                exit();
        }
    }

    /**
     * Gestión de eventos
     */
    public function eventos() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['eventos']->getAll('fecha_evento DESC');
                $title = 'Gestión de Eventos';
                require __DIR__ . '/../views/admin/eventos/list.php';
                break;
                
            case 'create':
                $this->handleEventoForm();
                break;
                
            case 'edit':
                $this->handleEventoForm($id);
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['eventos']->delete($id);
                    $this->setFlash('Evento eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/eventos");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/eventos");
                exit();
        }
    }

    /**
     * Gestión de multimedia
     */
    public function multimedia() {
        $action = $_GET['action'] ?? 'list';
        $filename = $_GET['id'] ?? null; // En este caso el ID es el nombre del archivo
        $uploadDir = __DIR__ . '/../../public/uploads/';

        // Asegurar que el directorio existe
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            file_put_contents($uploadDir . '.gitkeep', '');
        }

        switch ($action) {
            case 'list':
                $files = array_diff(scandir($uploadDir), array('.', '..', '.gitkeep'));
                $items = [];
                foreach ($files as $file) {
                    if (is_file($uploadDir . $file)) {
                        $items[] = [
                            'name' => $file,
                            'size' => filesize($uploadDir . $file),
                            'date' => filemtime($uploadDir . $file),
                            'path' => APP_URL . '/uploads/' . $file
                        ];
                    }
                }
                
                // Ordenar por fecha descendente
                usort($items, function($a, $b) {
                    return $b['date'] <=> $a['date'];
                });

                $title = 'Biblioteca Multimedia';
                require __DIR__ . '/../views/admin/multimedia/list.php';
                break;

            case 'delete':
                if ($filename && file_exists($uploadDir . $filename)) {
                    unlink($uploadDir . $filename);
                    $this->setFlash('Archivo eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/multimedia");
                exit();

            case 'upload':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $res = $this->handleImageUpload('file');
                    if ($res) {
                        $this->setFlash('Imagen subida correctamente');
                    }
                }
                header("Location: " . APP_URL . "/admin/multimedia");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/multimedia");
                exit();
        }
    }

    /**
     * Gestión de mensajes de contacto
     */
    public function mensajes() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['mensajes']->getAll('created_at DESC');
                $title = 'Mensajes de Contacto';
                require __DIR__ . '/../views/admin/mensajes/list.php';
                break;
                
            case 'view':
                $item = $id ? $this->models['mensajes']->getById($id) : null;
                if ($item && !$item['leido']) {
                    $this->models['mensajes']->update($id, ['leido' => 1]);
                }
                $title = 'Ver Mensaje';
                require __DIR__ . '/../views/admin/mensajes/view.php';
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['mensajes']->delete($id);
                    $this->setFlash('Mensaje eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/mensajes");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/mensajes");
                exit();
        }
    }

    /**
     * Manejar formulario de noticia
     */
    private function handleNoticiaForm($id = null) {
        $item = $id ? $this->models['noticias']->getById($id) : null;
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => $_POST['titulo'] ?? '',
                'slug' => $this->createSlug($_POST['titulo'] ?? ''),
                'resumen' => $_POST['resumen'] ?? null,
                'contenido' => $_POST['contenido'] ?? '',
                'fecha_publicacion' => $_POST['fecha_publicacion'] ?? date('Y-m-d'),
                'estado' => $_POST['estado'] ?? 'borrador',
                'orden' => (int)($_POST['orden'] ?? 999)
            ];

            // Manejar imagen principal
            if (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == '1') {
                $data['imagen'] = null;
            } else {
                $data['imagen'] = $this->handleImageUpload('imagen', $item['imagen'] ?? null);
            }

            // Manejar carrusel de imágenes
            $currentImagesJson = $item['imagenes'] ?? '[]';
            if (isset($_POST['eliminar_imagenes']) && is_array($_POST['eliminar_imagenes'])) {
                $currentImages = json_decode($currentImagesJson, true);
                $remainingImages = array_filter($currentImages, function($img) {
                    return !in_array($img, $_POST['eliminar_imagenes']);
                });
                $currentImagesJson = !empty($remainingImages) ? json_encode(array_values($remainingImages)) : '[]';
            }
            $data['imagenes'] = $this->handleMultipleImagesUpload('imagenes', $currentImagesJson);

            // Verificar si las columnas existen antes de intentar guardar
            $cols = $this->pdo->query("SHOW COLUMNS FROM noticias")->fetchAll(PDO::FETCH_COLUMN);
            
            if (!in_array('imagenes', $cols)) unset($data['imagenes']);
            if (!in_array('orden', $cols)) unset($data['orden']);

            if (empty($data['titulo'])) {
                $error = 'El título es obligatorio.';
            } else {
                if ($id) {
                    $this->models['noticias']->update($id, $data);
                    $this->setFlash('Noticia actualizada correctamente');
                } else {
                    $this->models['noticias']->insert($data);
                    $this->setFlash('Noticia creada correctamente');
                }
                header("Location: " . APP_URL . "/admin/noticias");
                exit();
            }
        }
        
        $title = $id ? 'Editar Noticia' : 'Nueva Noticia';
        require __DIR__ . '/../views/admin/noticias/form.php';
    }

    /**
     * Manejar formulario de evento
     */
    private function handleEventoForm($id = null) {
        $item = $id ? $this->models['eventos']->getById($id) : null;
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'slug' => $this->createSlug(trim($_POST['titulo'] ?? '')),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'fecha_evento' => $_POST['fecha_evento'] ?? null,
                'hora_inicio' => $_POST['hora_inicio'] ?? null,
                'hora_fin' => $_POST['hora_fin'] ?? null,
                'lugar' => trim($_POST['lugar'] ?? ''),
                'estado' => $_POST['estado'] ?? 'programado',
                'tipo' => $_POST['tipo'] ?? 'evento',
                'orden' => (int)($_POST['orden'] ?? 999)
            ];

            // Manejar imagen principal
            if (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == '1') {
                $data['imagen'] = null;
            } else {
                $data['imagen'] = $this->handleImageUpload('imagen', $item['imagen'] ?? null);
            }

            // Manejar carrusel de imágenes
            $currentImagesJson = $item['imagenes'] ?? '[]';
            if (isset($_POST['eliminar_imagenes']) && is_array($_POST['eliminar_imagenes'])) {
                $currentImages = json_decode($currentImagesJson, true);
                $remainingImages = array_filter($currentImages, function($img) {
                    return !in_array($img, $_POST['eliminar_imagenes']);
                });
                $currentImagesJson = !empty($remainingImages) ? json_encode(array_values($remainingImages)) : '[]';
            }
            $data['imagenes'] = $this->handleMultipleImagesUpload('imagenes', $currentImagesJson);
            
            // Verificar si las columnas existen antes de intentar guardar (evita Fatal Error)
            $stmt_check_ev_img = $this->pdo->query("SHOW COLUMNS FROM eventos LIKE 'imagenes'");
            if (!$stmt_check_ev_img->fetch()) {
                unset($data['imagenes']);
            }

            $stmt_check_ev_ord = $this->pdo->query("SHOW COLUMNS FROM eventos LIKE 'orden'");
            if (!$stmt_check_ev_ord->fetch()) {
                unset($data['orden']);
            }
            
            if (empty($data['titulo'])) {
                $error = 'El título es obligatorio.';
            } else {
                if ($id) {
                    $this->models['eventos']->update($id, $data);
                    $this->setFlash('Evento actualizado correctamente');
                } else {
                    $this->models['eventos']->insert($data);
                    $this->setFlash('Evento creado correctamente');
                }
                header("Location: " . APP_URL . "/admin/eventos");
                exit();
            }
        }
        
        $title = $id ? 'Editar Evento' : 'Nuevo Evento';
        require __DIR__ . '/../views/admin/eventos/form.php';
    }

    /**
     * Gestión de cursos (capacitaciones/webinars realizados por AGECSO)
     */
    public function cursos() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['cursos']->getAll('fecha_inicio DESC');
                $title = 'Cursos y Webinars Realizados';
                require __DIR__ . '/../views/admin/cursos/list.php';
                break;
                
            case 'create':
                $this->handleCursoForm();
                break;
                
            case 'edit':
                $this->handleCursoForm($id);
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['cursos']->delete($id);
                    $this->setFlash('Curso/Webinar eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/cursos");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/cursos");
                exit();
        }
    }

    /**
     * Gestión de aliados
     */
    public function aliados() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['aliados']->getAll('orden ASC, nombre ASC');
                $title = 'Gestión de Aliados';
                require __DIR__ . '/../views/admin/aliados/list.php';
                break;
                
            case 'create':
                $this->handleAliadoForm();
                break;
                
            case 'edit':
                $this->handleAliadoForm($id);
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['aliados']->delete($id);
                    $this->setFlash('Aliado eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/aliados");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/aliados");
                exit();
        }
    }

    /**
     * Gestión de servicios
     */
    public function servicios() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['servicios']->getAll('orden ASC');
                $title = 'Gestión de Servicios';
                require __DIR__ . '/../views/admin/servicios/list.php';
                break;
                
            case 'create':
                $this->handleServicioForm();
                break;
                
            case 'edit':
                $this->handleServicioForm($id);
                break;
                
            case 'delete':
                if ($id) {
                    $this->models['servicios']->delete($id);
                    $this->setFlash('Servicio eliminado correctamente');
                }
                header("Location: " . APP_URL . "/admin/servicios");
                exit();
                
            default:
                header("Location: " . APP_URL . "/admin/servicios");
                exit();
        }
    }

    /**
     * Manejar formulario de curso
     */
    private function handleCursoForm($id = null) {
        $item = $id ? $this->models['cursos']->getById($id) : null;
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'slug' => $this->createSlug(trim($_POST['titulo'] ?? '')),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'tipo' => $_POST['tipo'] ?? 'curso',
                'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
                'fecha_fin' => $_POST['fecha_fin'] ?? null,
                'duracion' => trim($_POST['duracion'] ?? ''),
                'instructor' => trim($_POST['instructor'] ?? ''),
                'estado' => $_POST['estado'] ?? 'finalizado'
            ];
            
            if (empty($data['titulo'])) {
                $error = 'El título es obligatorio.';
            } else {
                if ($id) {
                    $this->models['cursos']->update($id, $data);
                    $this->setFlash('Curso/Webinar actualizado correctamente');
                } else {
                    $this->models['cursos']->insert($data);
                    $this->setFlash('Curso/Webinar creado correctamente');
                }
                header("Location: " . APP_URL . "/admin/cursos");
                exit();
            }
        }
        
        $title = $id ? 'Editar Curso/Webinar' : 'Nuevo Curso/Webinar';
        require __DIR__ . '/../views/admin/cursos/form.php';
    }

    /**
     * Manejar formulario de aliado
     */
    private function handleAliadoForm($id = null) {
        $item = $id ? $this->models['aliados']->getById($id) : null;
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentLogo = $item['logo'] ?? null;
            
            // Handle logo upload
            $logo = $this->handleImageUpload('logo', $currentLogo);
            
            // Handle logo deletion
            if (isset($_POST['eliminar_logo']) && $_POST['eliminar_logo'] == '1') {
                $logo = null;
                if ($currentLogo && file_exists(__DIR__ . '/../../public/uploads/' . $currentLogo)) {
                    unlink(__DIR__ . '/../../public/uploads/' . $currentLogo);
                }
            }
            
            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'slug' => $this->createSlug(trim($_POST['nombre'] ?? '')),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'sitio_web' => trim($_POST['sitio_web'] ?? ''),
                'categoria' => trim($_POST['categoria'] ?? ''),
                'orden' => (int)($_POST['orden'] ?? 0),
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'logo' => $logo
            ];
            
            if (empty($data['nombre'])) {
                $error = 'El nombre es obligatorio.';
            } else {
                if ($id) {
                    $this->models['aliados']->update($id, $data);
                    $this->setFlash('Aliado actualizado correctamente');
                } else {
                    $this->models['aliados']->insert($data);
                    $this->setFlash('Aliado creado correctamente');
                }
                header("Location: " . APP_URL . "/admin/aliados");
                exit();
            }
        }
        
        $title = $id ? 'Editar Aliado' : 'Nuevo Aliado';
        require __DIR__ . '/../views/admin/aliados/form.php';
    }

    /**
     * Manejar formulario de servicio
     */
    private function handleServicioForm($id = null) {
        $item = $id ? $this->models['servicios']->getById($id) : null;
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'slug' => $this->createSlug(trim($_POST['titulo'] ?? '')),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'contenido' => trim($_POST['contenido'] ?? ''),
                'icono' => trim($_POST['icono'] ?? 'bi-star'),
                'orden' => (int)($_POST['orden'] ?? 0),
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            if (empty($data['titulo'])) {
                $error = 'El título es obligatorio.';
            } else {
                if ($id) {
                    $this->models['servicios']->update($id, $data);
                    $this->setFlash('Servicio actualizado correctamente');
                } else {
                    $this->models['servicios']->insert($data);
                    $this->setFlash('Servicio creado correctamente');
                }
                header("Location: " . APP_URL . "/admin/servicios");
                exit();
            }
        }
        
        $title = $id ? 'Editar Servicio' : 'Nuevo Servicio';
        require __DIR__ . '/../views/admin/servicios/form.php';
    }

    /**
     * Crear slug a partir de texto
     */
    private function createSlug($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }

    /**
     * Establecer mensaje flash
     */
    private function setFlash($message, $type = 'success') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    /**
     * Manejar subida de múltiples imágenes
     */
    private function handleMultipleImagesUpload($fileInput, $currentImagesJson = '[]') {
        if (!isset($_FILES[$fileInput]) || empty($_FILES[$fileInput]['name'][0])) {
            return $currentImagesJson;
        }

        $files = $_FILES[$fileInput];
        $uploadedFilenames = json_decode($currentImagesJson, true);
        if (!is_array($uploadedFilenames)) $uploadedFilenames = [];

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 12 * 1024 * 1024; // 12MB
        $uploadDir = realpath(__DIR__ . '/../../public/uploads') . DIRECTORY_SEPARATOR;
        if (!$uploadDir || $uploadDir === DIRECTORY_SEPARATOR) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            
            $extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) continue;
            if ($files['size'][$i] > $maxSize) continue;

            $filename = uniqid() . '_' . time() . '_' . $i . '.' . $extension;
            
            if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $filename)) {
                $uploadedFilenames[] = $filename;
            }
        }

        return json_encode(array_values($uploadedFilenames));
    }

    /**
     * Manejar subida de imagen
     */
    private function handleImageUpload($fileInput, $currentImage = null) {
        // Si no se envió el archivo o no se seleccionó nada
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] === UPLOAD_ERR_NO_FILE) {
            return $currentImage;
        }

        // Si hay algún otro error en la subida
        if ($_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = "Error en la subida de la imagen principal: ";
            switch ($_FILES[$fileInput]['error']) {
                case UPLOAD_ERR_INI_SIZE: $errorMsg .= "El archivo es demasiado grande para la configuración del servidor."; break;
                case UPLOAD_ERR_FORM_SIZE: $errorMsg .= "El archivo excede el tamaño del formulario."; break;
                case UPLOAD_ERR_PARTIAL: $errorMsg .= "La subida se interrumpió."; break;
                case UPLOAD_ERR_NO_TMP_DIR: $errorMsg .= "Falta carpeta temporal en el servidor."; break;
                default: $errorMsg .= "Error código " . $_FILES[$fileInput]['error']; break;
            }
            $this->setFlash($errorMsg, 'error');
            return $currentImage;
        }

        $file = $_FILES[$fileInput];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 12 * 1024 * 1024; // Aumentar a 12MB

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            $this->setFlash("Formato no permitido para '{$file['name']}'. Usa JPG, PNG o WEBP.", 'error');
            return $currentImage;
        }

        if ($file['size'] > $maxSize) {
            $this->setFlash("La imagen es demasiado pesada (" . round($file['size']/1024/1024, 2) . "MB). Máximo 12MB.", 'error');
            return $currentImage;
        }

        // Limpiar el nombre del archivo original para evitar problemas con caracteres raros
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadDir = realpath(__DIR__ . '/../../public/uploads') . DIRECTORY_SEPARATOR;
        
        // Si realpath falló, intentar el camino directo
        if (!$uploadDir || $uploadDir === DIRECTORY_SEPARATOR) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return $filename;
        }

        $this->setFlash("Error interno: No se pudo mover el archivo al directorio de destino. Revisa los permisos de la carpeta uploads.", 'error');
        return $currentImage;
    }
}
