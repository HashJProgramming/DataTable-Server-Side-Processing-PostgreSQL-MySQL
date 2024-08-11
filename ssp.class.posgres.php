<?php

class SSP_PGSQL
{
    static function data_output($columns, $data)
    {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                if (isset($column['formatter'])) {
                    if (empty($column['db'])) {
                        $row[$column['dt']] = $column['formatter']($data[$i]);
                    } else {
                        $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                    }
                } else {
                    if (!empty($column['db'])) {
                        $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                    } else {
                        $row[$column['dt']] = "";
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    static function db($conn)
    {
        if (is_array($conn)) {
            return self::sql_connect($conn);
        }

        return $conn;
    }

    static function limit($request, $columns)
    {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['length']) . " OFFSET " . intval($request['start']);
        }

        return $limit;
    }

    static function order($request, $columns)
    {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                $columnIdx = $request['order'][$i]['column'];
                $requestColumn = $request['columns'][$columnIdx];
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC';
                    $orderBy[] = '"' . $column['db'] . '" ' . $dir;
                }
            }

            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }

    static function filter($request, $columns, &$bindings)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];

            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['searchable'] == 'true') {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $globalSearch[] = '"' . $column['db'] . '" ILIKE ' . $binding;
                    }
                }
            }
        }

        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn['search']['value'];

                if (
                    $requestColumn['searchable'] == 'true' &&
                    $str != ''
                ) {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $columnSearch[] = '"' . $column['db'] . '" ILIKE ' . $binding;
                    }
                }
            }
        }

        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    static function simple($request, $conn, $table, $primaryKey, $columns)
    {
        $bindings = array();
        $db = self::db($conn);

        if (isset($request['json'])) {
            $request = json_decode($request['json'], true);
        }

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        $data = self::sql_exec(
            $db,
            $bindings,
            "SELECT \"" . implode("\", \"", self::pluck($columns, 'db')) . "\"
             FROM \"$table\"
             $where
             $order
             $limit"
        );

        $resFilterLength = self::sql_exec(
            $db,
            $bindings,
            "SELECT COUNT(\"{$primaryKey}\")
             FROM   \"$table\"
             $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        $resTotalLength = self::sql_exec(
            $db,
            [],
            "SELECT COUNT(\"{$primaryKey}\")
             FROM   \"$table\""
        );
        $recordsTotal = $resTotalLength[0][0];

        /*
         * Output
         */
        return array(
            "draw"            => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => self::data_output($columns, $data)
        );
    }

    static function complex(
        $request,
        $conn,
        $table,
        $primaryKey,
        $columns,
        $whereResult = null,
        $whereAll = null
    ) {
        $bindings = array();
        $whereAllBindings = array();
        $db = self::db($conn);
        $whereAllSql = '';

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        if ($whereResult) {
            $str = $whereResult;

            if (is_array($whereResult)) {
                $str = $whereResult['condition'];

                if (isset($whereResult['bindings'])) {
                    self::add_bindings($bindings, $whereResult['bindings']);
                }
            }

            $where = $where ?
                $where . ' AND ' . $str :
                'WHERE ' . $str;
        }

        if ($whereAll) {
            $str = $whereAll;

            if (is_array($whereAll)) {
                $str = $whereAll['condition'];

                if (isset($whereAll['bindings'])) {
                    self::add_bindings($whereAllBindings, $whereAll['bindings']);
                }
            }

            $where = $where ?
                $where . ' AND ' . $str :
                'WHERE ' . $str;

            $whereAllSql = 'WHERE ' . $str;
        }

        $data = self::sql_exec(
            $db,
            $bindings,
            "SELECT \"" . implode("\", \"", self::pluck($columns, 'db')) . "\"
             FROM \"$table\"
             $where
             $order
             $limit"
        );

        $resFilterLength = self::sql_exec(
            $db,
            $bindings,
            "SELECT COUNT(\"{$primaryKey}\")
             FROM   \"$table\"
             $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        $resTotalLength = self::sql_exec(
            $db,
            $whereAllBindings,
            "SELECT COUNT(\"{$primaryKey}\")
             FROM   \"$table\" " .
                $whereAllSql
        );
        $recordsTotal = $resTotalLength[0][0];

        return array(
            "draw"            => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => self::data_output($columns, $data)
        );
    }

    static function sql_connect($sql_details)
    {
        try {
            $db = @new PDO(
                "pgsql:host={$sql_details['host']};port={$sql_details['port']};dbname={$sql_details['db']}",
                $sql_details['user'],
                $sql_details['pass'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );

            return $db;
        } catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. " .
                    $e->getMessage()
            );
        }
    }

    static function sql_exec($db, $bindings, $sql = null)
    {
        if ($sql === null) {
            $sql = $bindings;
        }

        $stmt = $db->prepare($sql);

        for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
            $binding = $bindings[$i];
            $stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }

        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    static function fatal($msg)
    {
        echo json_encode(array(
            "error" => $msg
        ));

        exit(0);
    }

    static function bind(&$a, $val, $type)
    {
        $key = ':binding_' . count($a);

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }

    static function pluck($a, $prop)
    {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if (empty($a[$i][$prop])) {
                continue;
            }

            $out[] = $a[$i][$prop];
        }

        return $out;
    }

    static function add_bindings(&$a, $bindings)
    {
        if (is_array($bindings)) {
            for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
                $a[] = $bindings[$i];
            }
        }
    }
}
