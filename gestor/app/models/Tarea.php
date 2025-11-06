<?php
class Tarea
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getTareasPaginadas(int $pagina, int $porPagina, string $rol, ?int $userId, bool $soloPendientes = false): array
    {
        $offset = ($pagina - 1) * $porPagina;

        $sql = "
            SELECT 
                t.tarea_id,
                t.client_contacto,
                t.client_telefono,
                t.client_email,
                t.client_direccion,
                t.client_poblacion,
                t.cp,
                t.provincia,
                t.estado,
                u.user_name,
                u.user_surname
            FROM tarea t
            LEFT JOIN usuario u ON t.user_id = u.user_id
        ";

        $where = [];
        $params = [];

        if ($rol === 'operario' && $userId) {
            $where[] = "t.user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        if ($soloPendientes) {
            $where[] = "t.estado = 'P'";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY FIELD(t.estado, 'P', 'B', 'R', 'C'), t.fecha_creacion DESC ";

        $countSql = "SELECT COUNT(*) FROM tarea t";
        if (!empty($where)) {
            $countSql .= " WHERE " . implode(' AND ', $where);
        }

        $totalStmt = $this->pdo->prepare($countSql);
        foreach ($params as $key => $value) {
            $totalStmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $totalStmt->execute();
        $total = (int) $totalStmt->fetchColumn();

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $tareas = $stmt->fetchAll();

        return [
            'tareas' => $tareas,
            'total' => $total
        ];
    }

    public function buscarTareas(array $filtros, string $rol, ?int $userId, int $pagina, int $porPagina): array
    {
        $offset = ($pagina - 1) * $porPagina;

        $sql = "
            SELECT 
                t.tarea_id,
                t.client_contacto,
                t.client_telefono,
                t.client_email,
                t.client_direccion,
                t.client_poblacion,
                t.cp,
                t.provincia,
                t.estado,
                u.user_name,
                u.user_surname
            FROM tarea t
            LEFT JOIN usuario u ON t.user_id = u.user_id
        ";

        $where = [];
        $params = [];

        if ($rol === 'operario' && $userId) {
            $where[] = "t.user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        foreach ($filtros as $filtro) {
            $campo = $filtro['campo'] ?? '';
            $valorRaw = $filtro['valor'] ?? '';
            
            if (is_array($valorRaw)) {
                continue;
            }
            
            $valor = trim($valorRaw);
            if ($campo === '' || $valor === '') continue;

            if ($campo === 'Operario') {
                // Buscar en nombre y/o apellidos

                $paramKey1 = ':valor_' . count($params);
                    $paramKey2 = ':valor_' . (count($params) + 1);
                    $paramKey3 = ':valor_' . (count($params) + 2);
                    
                    $where[] = "(
                        u.user_name LIKE {$paramKey1} 
                        OR u.user_surname LIKE {$paramKey2} 
                        OR CONCAT(u.user_name, ' ', u.user_surname) LIKE {$paramKey3}
                    )";
                    
                    $params[$paramKey1] = "%{$valor}%";
                    $params[$paramKey2] = "%{$valor}%";
                    $params[$paramKey3] = "%{$valor}%";


            } else {
                $columna = match($campo) {
                    'Contacto' => 't.client_contacto',
                    'Teléfono' => 't.client_telefono',
                    'Email' => 't.client_email',
                    'Dirección' => 't.client_direccion',
                    'Población' => 't.client_poblacion',
                    'CP' => 't.cp',
                    'Provincia' => 't.provincia',
                    'Estado' => 't.estado',
                    default => 't.client_contacto'
                };
                $paramKey = ':valor_' . count($params);
                $where[] = "{$columna} LIKE {$paramKey}";
                $params[$paramKey] = "%{$valor}%";
            }
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY FIELD(t.estado, 'P', 'B', 'R', 'C'), t.fecha_creacion DESC ";

        // === Consulta de conteo corregida ===
        $countSql = "SELECT COUNT(*) FROM tarea t";
        $needsJoin = false;

        // Verificar si se filtra por 'Operario' o el rol es 'operario'
        if ($rol === 'operario' || in_array('Operario', array_column($filtros, 'campo'))) {
            $countSql .= " LEFT JOIN usuario u ON t.user_id = u.user_id";
        }

        if (!empty($where)) {
            $countSql .= " WHERE " . implode(' AND ', $where);
        }

        $totalStmt = $this->pdo->prepare($countSql);
        foreach ($params as $key => $value) {
            $totalStmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $totalStmt->execute();
        $total = (int) $totalStmt->fetchColumn();

        // Consulta paginada
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'tareas' => $stmt->fetchAll(),
            'total' => $total
        ];
    }
}
?>