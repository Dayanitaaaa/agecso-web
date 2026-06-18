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
            header("Location: " . APP_URL . "/?page=login");
            exit();
        }

        // Inicializar modelos
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
     * Gestión de noticias
     */
    public function noticias() {
        $action = $_GET['action'] ?? 'list';
        $id = $_GET['id'] ?? null;
        
        switch ($action) {
            case 'list':
                $items = $this->models['noticias']->getAll('created_at DESC');
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
                header("Location: " . APP_URL . "/?page=admin&section=noticias");
                exit();
                
            default:
                header("Location: " . APP_URL . "/?page=admin&section=noticias");
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
                header("Location: " . APP_URL . "/?page=admin&section=eventos");
                exit();
                
            default:
                header("Location: " . APP_URL . "/?page=admin&section=eventos");
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
                header("Location: " . APP_URL . "/?page=admin&section=mensajes");
                exit();
                
            default:
                header("Location: " . APP_URL . "/?page=admin&section=mensajes");
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
            $currentImage = $item['imagen'] ?? null;
            
            // Handle image upload
            $imagen = $this->handleImageUpload('imagen', $currentImage);
            
            // Handle image deletion
            if (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == '1') {
                $imagen = null;
                if ($currentImage && file_exists(__DIR__ . '/../../public/uploads/' . $currentImage)) {
                    unlink(__DIR__ . '/../../public/uploads/' . $currentImage);
                }
            }
            
            $data = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'slug' => $this->createSlug(trim($_POST['titulo'] ?? '')),
                'resumen' => trim($_POST['resumen'] ?? ''),
                'contenido' => trim($_POST['contenido'] ?? ''),
                'estado' => $_POST['estado'] ?? 'borrador',
                'fecha_publicacion' => $_POST['fecha_publicacion'] ?? date('Y-m-d'),
                'imagen' => $imagen
            ];
            
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
                header("Location: " . APP_URL . "/?page=admin&section=noticias");
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
            $currentImage = $item['imagen'] ?? null;
            
            // Handle image upload
            $imagen = $this->handleImageUpload('imagen', $currentImage);
            
            // Handle image deletion
            if (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == '1') {
                $imagen = null;
                if ($currentImage && file_exists(__DIR__ . '/../../public/uploads/' . $currentImage)) {
                    unlink(__DIR__ . '/../../public/uploads/' . $currentImage);
                }
            }
            
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
                'imagen' => $imagen
            ];
            
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
                header("Location: " . APP_URL . "/?page=admin&section=eventos");
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
                header("Location: " . APP_URL . "/?page=admin&section=cursos");
                exit();
                
            default:
                header("Location: " . APP_URL . "/?page=admin&section=cursos");
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
                header("Location: " . APP_URL . "/?page=admin&section=aliados");
                exit();
                
            default:
                header("Location: " . APP_URL . "/?page=admin&section=aliados");
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
                header("Location: " . APP_URL . "/?page=admin&section=servicios");
                exit();
                
            default:
                header("Location: " . APP_URL . "/?page=admin&section=servicios");
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
                header("Location: " . APP_URL . "/?page=admin&section=cursos");
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
                header("Location: " . APP_URL . "/?page=admin&section=aliados");
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
                header("Location: " . APP_URL . "/?page=admin&section=servicios");
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
     * Manejar subida de imagen
     */
    private function handleImageUpload($fileInput, $currentImage = null) {
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
            return $currentImage;
        }

        $file = $_FILES[$fileInput];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return $currentImage;
        }

        if ($file['size'] > $maxSize) {
            return $currentImage;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = __DIR__ . '/../../public/uploads/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $filename;
        }

        return $currentImage;
    }
}
