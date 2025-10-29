<?php
/**
 * CRUD操作用の関数ファイル
 * 
 * @author 兎田ぺこら
 * @version 1.0.0
 * @created 2025-01-27
 */

require_once __DIR__ . '/database.php';

/**
 * レコードを挿入する関数
 * 
 * @param string $table テーブル名
 * @param array $data 挿入するデータ（カラム名 => 値）
 * @return int|false 挿入されたレコードのID、失敗時はfalse
 */
function insertRecord($table, $data) {
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute($data);
        
        return $pdo->lastInsertId();
        
    } catch (PDOException $e) {
        error_log("INSERT エラー ({$table}): " . $e->getMessage());
        return false;
    }
}

/**
 * レコードを取得する関数
 * 
 * @param string $table テーブル名
 * @param array $conditions WHERE条件（カラム名 => 値）
 * @param string $orderBy 並び順（オプション）
 * @param int $limit 取得件数制限（オプション）
 * @param int $offset オフセット（オプション）
 * @return array|false 取得したレコードの配列、失敗時はfalse
 */
function selectRecords($table, $conditions = [], $orderBy = '', $limit = null, $offset = null) {
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $sql = "SELECT * FROM `{$table}`";
        $params = [];
        
        // WHERE条件の追加
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "`{$column}` = :{$column}";
                $params[$column] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        // ORDER BY句の追加
        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // LIMIT句の追加
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
            if ($offset !== null) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("SELECT エラー ({$table}): " . $e->getMessage());
        return false;
    }
}

/**
 * 単一レコードを取得する関数
 * 
 * @param string $table テーブル名
 * @param array $conditions WHERE条件（カラム名 => 値）
 * @return array|false 取得したレコード、失敗時はfalse
 */
function selectRecord($table, $conditions) {
    $result = selectRecords($table, $conditions, '', 1);
    return $result !== false && !empty($result) ? $result[0] : false;
}

/**
 * レコードを更新する関数
 * 
 * @param string $table テーブル名
 * @param array $data 更新するデータ（カラム名 => 値）
 * @param array $conditions WHERE条件（カラム名 => 値）
 * @return int|false 更新されたレコード数、失敗時はfalse
 */
function updateRecord($table, $data, $conditions) {
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $setClause = [];
        $params = [];
        
        // SET句の構築
        foreach ($data as $column => $value) {
            $setClause[] = "`{$column}` = :set_{$column}";
            $params["set_{$column}"] = $value;
        }
        
        // WHERE句の構築
        $whereClause = [];
        foreach ($conditions as $column => $value) {
            $whereClause[] = "`{$column}` = :where_{$column}";
            $params["where_{$column}"] = $value;
        }
        
        $sql = "UPDATE `{$table}` SET " . implode(', ', $setClause) . 
               " WHERE " . implode(' AND ', $whereClause);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
        
    } catch (PDOException $e) {
        error_log("UPDATE エラー ({$table}): " . $e->getMessage());
        return false;
    }
}

/**
 * レコードを削除する関数
 * 
 * @param string $table テーブル名
 * @param array $conditions WHERE条件（カラム名 => 値）
 * @return int|false 削除されたレコード数、失敗時はfalse
 */
function deleteRecord($table, $conditions) {
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $whereClause = [];
        $params = [];
        
        // WHERE句の構築
        foreach ($conditions as $column => $value) {
            $whereClause[] = "`{$column}` = :{$column}";
            $params[$column] = $value;
        }
        
        $sql = "DELETE FROM `{$table}` WHERE " . implode(' AND ', $whereClause);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
        
    } catch (PDOException $e) {
        error_log("DELETE エラー ({$table}): " . $e->getMessage());
        return false;
    }
}

/**
 * レコード数をカウントする関数
 * 
 * @param string $table テーブル名
 * @param array $conditions WHERE条件（カラム名 => 値）
 * @return int|false レコード数、失敗時はfalse
 */
function countRecords($table, $conditions = []) {
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $sql = "SELECT COUNT(*) as count FROM `{$table}`";
        $params = [];
        
        // WHERE条件の追加
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "`{$column}` = :{$column}";
                $params[$column] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result['count'];
        
    } catch (PDOException $e) {
        error_log("COUNT エラー ({$table}): " . $e->getMessage());
        return false;
    }
}

/**
 * カスタムクエリを実行する関数
 * 
 * @param string $sql SQL文
 * @param array $params パラメータ
 * @return array|false 取得したレコードの配列、失敗時はfalse
 */
function executeQuery($sql, $params = []) {
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("クエリ実行エラー: " . $e->getMessage());
        return false;
    }
}

/**
 * カスタムクエリを実行して単一レコードを取得する関数
 * 
 * @param string $sql SQL文
 * @param array $params パラメータ
 * @return array|false 取得したレコード、失敗時はfalse
 */
function executeQuerySingle($sql, $params = []) {
    $result = executeQuery($sql, $params);
    return $result !== false && !empty($result) ? $result[0] : false;
}

/**
 * レコードの存在チェック関数
 * 
 * @param string $table テーブル名
 * @param array $conditions WHERE条件（カラム名 => 値）
 * @return bool レコードが存在する場合true、存在しない場合false
 */
function recordExists($table, $conditions) {
    $count = countRecords($table, $conditions);
    return $count !== false && $count > 0;
}

/**
 * バッチインサート関数
 * 
 * @param string $table テーブル名
 * @param array $dataArray 挿入するデータの配列
 * @return int|false 挿入されたレコード数、失敗時はfalse
 */
function batchInsert($table, $dataArray) {
    if (empty($dataArray)) {
        return 0;
    }
    
    $pdo = getDatabaseConnection();
    if ($pdo === null) {
        return false;
    }
    
    try {
        $columns = array_keys($dataArray[0]);
        $columnsStr = implode(', ', array_map(function($col) { return "`{$col}`"; }, $columns));
        
        $placeholders = [];
        $params = [];
        $paramIndex = 0;
        
        foreach ($dataArray as $rowIndex => $row) {
            $rowPlaceholders = [];
            foreach ($columns as $column) {
                $paramKey = "param_{$rowIndex}_{$column}";
                $rowPlaceholders[] = ":{$paramKey}";
                $params[$paramKey] = $row[$column];
            }
            $placeholders[] = "(" . implode(', ', $rowPlaceholders) . ")";
        }
        
        $sql = "INSERT INTO `{$table}` ({$columnsStr}) VALUES " . implode(', ', $placeholders);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
        
    } catch (PDOException $e) {
        error_log("バッチインサート エラー ({$table}): " . $e->getMessage());
        return false;
    }
}
