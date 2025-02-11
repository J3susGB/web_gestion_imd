<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Arbitros;
use Model\Partidos;
use Model\Perfiles;
use Model\Categorias;
use Model\Modalidades;
use Model\Designaciones;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FacturasController
{

    public static function index(Router $router)
    {
        if (!is_admin()) {
            header('Location: /');
        }

        session_start();

        $usuario = new Perfiles($_SESSION);

        // Obtener todas las designaciones
        $jornada = Designaciones::all();
        $jornada_generada = true;

        foreach ($jornada as $j) {
            if ($j->jornada_editada == 0) {
                $jornada_generada = false;
                break;
            }
        }

        if (empty($jornada)) {
            $jornada = [];
            $jornada_generada = false;
        }

        //  Agrupar designaciones por jornada_editada
        $jornadas_agrupadas = [];
        foreach ($jornada as $j) {
            $jornada_editada = $j->jornada_editada;
            if (!isset($jornadas_agrupadas[$jornada_editada])) {
                $jornadas_agrupadas[$jornada_editada] = [];
            }
            $jornadas_agrupadas[$jornada_editada][] = $j;
        }

        // debuguear($jornadas_agrupadas);

        // Render a la vista 
        $router->render('admin/facturas/index', [
            'titulo' => 'Gestión de Jornada',
            'usuario' => $usuario,
            'jornada' => $jornada,
            'jornada_generada' => $jornada_generada,
            'jornadas_agrupadas' => $jornadas_agrupadas
        ]);
    }

    public static function generar_jornada(Router $router)
    {
        if (!is_admin()) {
            header('Location: /');
        }
        session_start();

        $alertas = [];
        $usuario = new Perfiles($_SESSION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validamos el valor de jornada
            $numero_jornada = $_POST['jornada'] ?? null;

            if (!$numero_jornada || !is_numeric($numero_jornada)) {
                $alertas[] = 'El número de jornada es obligatorio y debe ser un valor numérico.';
            } else {
                // Traemos todas las designaciones
                $jornada = Designaciones::all();

                foreach ($jornada as $j) {
                    // Solo modificar si jornada_editada es 0
                    if ($j->jornada_editada == 0) {
                        $j->jornada_editada = $numero_jornada;

                        // Validamos formulario
                        $alertas = $j->validar_generar_designacion();

                        if (empty($alertas)) {
                            $resultado = $j->guardar();
                        }
                    }
                }

                if (empty($alertas)) {
                    header('Location: /admin/facturas');
                    exit;
                }
            }
        }

        // Render a la vista 
        $router->render('admin/facturas/generar_jornada', [
            'titulo' => 'Generar Jornada',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function ver(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        //Sacamos el titulo (que es el número de jornada editada) del get
        $titulo = $_GET['jornada_editada'];
        // debuguear($titulo);

        $jornada_editada = $_GET['jornada_editada'];

        // Traemos designaciones cuya jornada_editada sea esa
        $designaciones = Designaciones::where_all('jornada_editada', $titulo);
        // debuguear($designaciones);

        //Traemos las modalidades
        $modalidades = Modalidades::all();

        // Traemos los usuarios
        $arbitros = Arbitros::all();

        //Traemos las categorias
        $categorias = Categorias::all();
        // debuguear($categorias);

        // Render a la vista 
        $router->render('admin/facturas/ver', [
            'titulo' => 'Jornada ' . $titulo,
            'usuario' => $usuario,
            'designaciones' => $designaciones,
            'arbitros' => $arbitros,
            'categorias' => $categorias,
            'modalidades' => $modalidades,
            'jornada_editada' => $jornada_editada
        ]);
    }

    public static function editar(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $alertas = [];

        //Recupero el id de designación del get
        $id_designacion = $_GET['id'];
        //Traigo la designación con ese id
        $designacion_editar = Designaciones::find($id_designacion);
        $jornada_editada = $designacion_editar->jornada_editada;

        //Traemos todos los arbitros
        $arbitros = Arbitros::all();

        //Traemos todas las categorias
        $categorias = Categorias::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // debuguear($_POST);

            //Transformar el dato unidad del post en decimal
            $_POST['unidad'] = (float)$_POST['unidad'];

            //Traemos designacion a editar
            $designacion = Designaciones::find($id_designacion);

            //Sincronizamos con el post
            $designacion->sincronizar($_POST);

            //Añado datos del post al objeto designacion
            $designacion->id_arbitro = s($_POST['arbitro']);
            $designacion->categoria = s($_POST['categoria']);
            $designacion->grupo = s($_POST['grupo']);
            $designacion->fecha = s($_POST['fecha']);
            $designacion->hora = s($_POST['hora']);
            $designacion->terreno = s($_POST['terreno']);
            $designacion->distrito = s($_POST['distrito']);
            $designacion->local = s($_POST['local']);
            $designacion->visitante = s($_POST['visitante']);
            $designacion->observaciones = s($_POST['observaciones']);
            $designacion->modalidad = s($_POST['modalidad']);
            $designacion->unidad = s($_POST['unidad']);

            // debuguear($designacion);

            $alertas = $designacion->validar_designacion();

            // debuguear($alertas);

            if (empty($alertas)) {
                $resultado = $designacion->guardar();
                // debuguear($resultado);

                if ($resultado) {
                    sleep(2);
                    header('Location: /admin/facturas/ver?jornada_editada=' . $designacion->jornada_editada);
                }
            }
        }

        // Render a la vista 
        $router->render('admin/facturas/editar', [
            'titulo' => 'Editar designación',
            'usuario' => $usuario,
            'designacion_editar' => $designacion_editar,
            'jornada_editada' => $jornada_editada,
            'arbitros' => $arbitros,
            'categorias' => $categorias,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_unidad()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el cuerpo de la solicitud JSON
            $data = json_decode(file_get_contents("php://input"), true);

            // Validar datos recibidos
            if (!isset($data['id_partido']) || !isset($data['unidad'])) {
                http_response_code(400); // Código HTTP: Bad Request
                echo json_encode(["success" => false, "message" => "Faltan datos en la solicitud."]);
                return;
            }

            $id_partido = $data['id_partido'];
            $unidad = $data['unidad'];
            $id_designacion = $data['id_designacion'];

            //traemos del get el numero de jornada
            $jornada_editada = $_GET['jornada_editada'];

            //Traemos todas las categorias
            $categorias = Categorias::all();

            // Validar que los datos no estén vacíos
            if (empty($id_partido) || empty($unidad)) {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Datos incompletos."]);
                return;
            }

            $designacion = Designaciones::find($id_designacion);

            if (!$designacion) {
                http_response_code(404); // Código HTTP: Not Found
                echo json_encode(["success" => false, "message" => "Designación no encontrada."]);
                return;
            }

            // Actualizar datos
            $designacion->unidad = (float)$unidad;

            if ($designacion->modalidad == 1) { //Si es futbol
                foreach ($categorias as $c) {
                    if ($designacion->unidad == 0.00) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                // case "UNIFEM":
                                    $designacion->facturar = 0.00;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = -$c->oa / 2;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    }
                    if ($designacion->unidad == 0.25) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                // case "UNIFEM":
                                    $designacion->facturar = $c->tarifa / 4;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = (($c->tarifa - $c->oa) / 2)  - $designacion->facturar;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    } else if ($designacion->unidad == 0.50) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                // case "UNIFEM":
                                    $designacion->facturar = $c->tarifa / 2;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = (($c->tarifa - $c->oa) / 2);
                                    break;

                                case "PQ":
                                case "PX":
                                case "AX":
                                case "BX":
                                case "IX":
                                case "CX":
                                case "MINIFE":
                                case "UNIFEM":
                                    $designacion->facturar = $c->tarifa / 2;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = $c->pago_arbitro / 2;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    } else if ($designacion->unidad == 1.00) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                case "UNIFEM":
                                case "PQ":
                                case "PX":
                                case "AX":
                                case "BX":
                                case "IX":
                                case "CX":
                                case "MINIFE":
                                case "F7_DC_3H_DELEGADO DE CAMPO":
                                case "F7_DC_4H_DELEGADO DE CAMPO":
                                case "F7_DC_5H_DELEGADO DE CAMPO":
                                case "F7_DC_6H_DELEGADO DE CAMPO":
                                    $designacion->tarifa = $c->tarifa;
                                    $designacion->facturar = $c->facturar;
                                    $designacion->oa = $c->oa;
                                    $designacion->pago_arbitro = $c->pago_arbitro;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    } else if ($designacion->unidad == 2.00) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "SX":
                                case "JX":
                                // case "UNIFEM":
                                    $designacion->tarifa = $c->tarifa;
                                    $designacion->facturar = $c->tarifa;
                                    $designacion->oa = $c->oa;
                                    $designacion->pago_arbitro = $c->tarifa - $c->oa;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    }
                }
            } else if ($designacion->modalidad == 2) { //Si es Sala
                foreach ($categorias as $c) {
                    if ($designacion->unidad == 0.00) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                // case "UNIFEM":
                                    $designacion->facturar = 0.00;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = -$c->oa / 2;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    }
                    if ($designacion->unidad == 0.25) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                // case "UNIFEM":
                                    $designacion->facturar = $c->tarifa / 4;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = (($c->tarifa - $c->oa) / 2)  - ($c->tarifa / 4);
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    } else if ($designacion->unidad == 0.50) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                // case "UNIFEM":
                                    $designacion->facturar = $c->tarifa / 2;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = (($c->tarifa - $c->oa) / 2);
                                    break;

                                case "PQ":
                                case "PX":
                                case "AX":
                                case "BX":
                                case "IX":
                                case "CX":
                                case "MINIFE":
                                case "UNIFEM":
                                    $designacion->facturar = $c->tarifa / 2;
                                    $designacion->oa = $c->oa / 2;
                                    $designacion->pago_arbitro = $c->pago_arbitro / 2;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    } else if ($designacion->unidad == 1.00) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "JX":
                                case "SX":
                                case "UNIFEM":
                                case "PQ":
                                case "PX":
                                case "AX":
                                case "BX":
                                case "IX":
                                case "CX":
                                case "MINIFE":
                                case "SALA_DC_3H_DELEGADO DE CAMPO":
                                case "SALA_DC_4H_DELEGADO DE CAMPO":
                                case "SALA_DC_5H_DELEGADO DE CAMPO":
                                case "SALA_DC_6H_DELEGADO DE CAMPO":
                                    $designacion->tarifa = $c->tarifa;
                                    $designacion->facturar = $c->facturar;
                                    $designacion->oa = $c->oa;
                                    $designacion->pago_arbitro = $c->pago_arbitro;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    } else if ($designacion->unidad == 2.00) {
                        if ($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                            switch ($designacion->categoria) {
                                case "SX":
                                case "JX":
                                // case "UNIFEM":
                                    $designacion->tarifa = $c->tarifa;
                                    $designacion->facturar = $c->tarifa;
                                    $designacion->oa = $c->oa;
                                    $designacion->pago_arbitro = $c->tarifa - $c->oa;
                                    break;

                                default:
                                    http_response_code(500); // Código HTTP: Internal Server Error
                                    echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                                    return;
                            }
                        }
                    }
                }
            }

            if ($designacion->guardar()) {
                http_response_code(200); // Código HTTP: OK
                echo json_encode([
                    "success" => true,
                    "message" => "Unidad actualizada correctamente."
                ]);
                return;
            } else {
                http_response_code(500); // Código HTTP: Internal Server Error
                echo json_encode(["success" => false, "message" => "Error al guardar los datos."]);
                return;
            }
        } else {
            http_response_code(405); // Código HTTP: Method Not Allowed
            echo json_encode(["success" => false, "message" => "Método no permitido."]);
            return;
        }
    }

    public static function generar_excel_jornada()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $jornada_editada = $_GET['jornada_editada'];
            // debuguear($jornada_editada);

            // Traemos las designaciones
            $designaciones = Designaciones::where_all_ordered_by_fecha('jornada_editada', $jornada_editada);
            // debuguear($designaciones);

            // Traemos todos los arbitros
            $arbitros = Arbitros::all();

            // Crear un nuevo Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Estilo para los encabezados
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],  // Color negro
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFC000'], // Color de fondo (naranja)
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'], // Bordes negros
                    ],
                ],
            ];

            // Estilo para filas impares (fondo gris claro)
            $rowStyle = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D3D3D3'], // Color de fondo gris claro
                ],
            ];

            // Formatos de número para las columnas
            $numericStyle = [
                'numberFormat' => [
                    'formatCode' => '0.00', // Sin separador de miles y dos decimales
                ],
            ];

            $currencyStyle = [
                'numberFormat' => [
                    'formatCode' => '#,##0.00€', // Formato de moneda en Euros
                ],
            ];

            // Estilo para centrar contenido
            $centerStyle = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            // Encabezados del Excel
            $headers = [
                'A1' => 'M',
                'B1' => 'C',
                'C1' => 'G',
                'D1' => 'ID',
                'E1' => 'FECHA',
                'F1' => 'HORA',
                'G1' => 'TERRENO',
                'H1' => 'DISTRITO',
                'I1' => 'J',
                'J1' => 'LOCAL',
                'K1' => 'VISITANTE',
                'L1' => 'ARBITRO',
                'M1' => 'UNIDAD',
                'N1' => 'TARIFA',
                'O1' => 'FACTURAR',
                'P1' => 'PAGO ARBITRO',
                'Q1' => 'OA',
                'R1' => 'OBSERVACIONES',
            ];

            // Aplicar estilo a los encabezados
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->applyFromArray($headerStyle);
            }

            // Llenar filas con datos
            $row = 2;
            foreach ($designaciones as $d) {
                // Ajustar modalidad
                $d->modalidad = $d->modalidad == 1 ? 'F7' : 'FS';

                // Buscar árbitro correspondiente
                $arbitro = '';
                foreach ($arbitros as $a) {
                    if ($d->id_arbitro == $a->id) {
                        $arbitro = $a->apellido1 . " " . $a->apellido2 . ", " . $a->nombre;
                        break; // Salimos del loop si encontramos al árbitro
                    }
                }

                // Llenar datos en las celdas
                $sheet->setCellValue("A$row", $d->modalidad);
                $sheet->setCellValue("B$row", $d->categoria);
                $sheet->setCellValue("C$row", $d->grupo);
                $sheet->setCellValue("D$row", $d->id_partido);
                $sheet->setCellValue("E$row", $d->fecha);
                $sheet->setCellValue("F$row", $d->hora);
                $sheet->setCellValue("G$row", $d->terreno);
                $sheet->setCellValue("H$row", $d->distrito);
                $sheet->setCellValue("I$row", $d->jornada);
                $sheet->setCellValue("J$row", $d->local);
                $sheet->setCellValue("K$row", $d->visitante);
                $sheet->setCellValue("L$row", $arbitro);
                $sheet->setCellValue("M$row", $d->unidad);
                $sheet->setCellValue("N$row", $d->tarifa);
                $sheet->setCellValue("O$row", $d->facturar);
                $sheet->setCellValue("P$row", $d->pago_arbitro);
                $sheet->setCellValue("Q$row", $d->oa);
                $sheet->setCellValue("R$row", $d->observaciones);

                // Aplicar formato de fila impares (gris claro)
                if ($row % 2 != 0) {
                    $sheet->getStyle("A$row:R$row")->applyFromArray($rowStyle);
                }

                // Aplicar formato numérico y centrado a la columna M
                $sheet->getStyle("M$row")->applyFromArray($numericStyle + $centerStyle);

                // Aplicar formato de moneda (euros) y centrado a las columnas N, O, P y Q
                $sheet->getStyle("N$row")->applyFromArray($currencyStyle + $centerStyle);
                $sheet->getStyle("O$row")->applyFromArray($currencyStyle + $centerStyle);
                $sheet->getStyle("P$row")->applyFromArray($currencyStyle + $centerStyle);
                $sheet->getStyle("Q$row")->applyFromArray($currencyStyle + $centerStyle);

                $row++;
            }

            // Ajustar el ancho de las columnas automáticamente
            foreach (range('A', 'R') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Generar el archivo Excel
            $writer = new Xlsx($spreadsheet);

            // Enviar al navegador para descarga
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="facturas.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }
    }

    public static function generar_factura()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $jornadas_seleccionadas = $_POST['seleccionar'];

            // Traigo todas las designaciones
            $designaciones_bruto = Designaciones::all_orden_fecha();

            // Traigo todas las categorías
            $categorias = Categorias::all();

            $designaciones = [];
            foreach ($jornadas_seleccionadas as $j) {
                foreach ($designaciones_bruto as $d) {
                    if ($j == $d->jornada_editada) {
                        $designaciones[] = $d;
                    }
                }
            }

            // Cambio nombre de las categorías
            foreach ($designaciones as $d) {
                foreach ($categorias as $c) {
                    if ($d->categoria == $c->nombre2 && $d->modalidad == $c->id_modalidad) {
                        $d->categoria = $c->nombre;
                    }
                }
            }

            // Almaceno en variables nombres de categorías senior y juvenil de Sala
            $nombre_sx_sala = '';
            $nombre_jx_sala = '';
            $nombre_unifem_sala = '';

            foreach ($categorias as $c) {
                if ($c->id_modalidad == 2) {
                    if ($c->nombre2 == 'SX') {
                        $nombre_sx_sala = $c->nombre;
                    } else if ($c->nombre2 == 'JX') {
                        $nombre_jx_sala = $c->nombre;
                    } else if ($c->nombre2 == 'UNIFEM') {
                        $nombre_unifem_sala = $c->nombre;
                    }
                }
            }

            // Cambio importes de tarifa y facturar de JX y SX Sala y elimino los jugados
            foreach ($designaciones as $key => $d) {
                if ($d->modalidad == 2) {
                    if ($d->categoria == $nombre_sx_sala) {
                        $d->tarifa = 25.00;

                        // Modificar facturar
                        if ($d->unidad == 0.25) {
                            $d->facturar = 25.00 / 4;
                        } else if ($d->unidad == 0.50) {
                            $d->facturar = 25.00 / 2;
                        }

                        // Eliminar si unidad es igual a 1.00
                        if ($d->unidad == 1.00 || $d->unidad == 0.00) {
                            unset($designaciones[$key]); // Eliminar la designación del array
                        }
                    } else if ($d->categoria == $nombre_jx_sala) {
                        $d->tarifa = 22.00;

                        // Modificar facturar
                        if ($d->unidad == 0.25) {
                            $d->facturar = 25.00 / 4;
                        } else if ($d->unidad == 0.50) {
                            $d->facturar = 25.00 / 2;
                        }

                        // Eliminar si unidad es igual a 1.00 o 0.00
                        if ($d->unidad == 1.00 || $d->unidad == 0.00) {
                            unset($designaciones[$key]); // Eliminar la designación del array
                        }
                    } else if ($d->categoria == $nombre_unifem_sala) {
                        $d->tarifa = 25.00;

                        // Modificar facturar
                        if ($d->unidad == 1.00) {
                            $d->facturar = 25.00;
                        } else if ($d->unidad == 0.50) {
                            $d->facturar = 25.00 / 2;
                        }
                    }
                }
            }

            // Reindexar el array
            $designaciones = array_values($designaciones);

            foreach ($designaciones as $key => $d) { // Usa $key para acceder al índice del array
                if ($d->modalidad == 1) {
                    switch ($d->categoria) {
                        case "FUTBOL7_SX_ARBITRO":
                        case "FUTBOL7_JX_ARBITRO":
                        // case "FUTBOL7_UNIFEM_ARBITRO":
                            if ($d->unidad == 1.00 || $d->unidad == 0.00) {
                                unset($designaciones[$key]); // Eliminar la designación del array
                            }
                            break;
                    }
                }
            }

            //Añadimos las mesas de los senior, unifem y juv de sala suspendidos
            foreach ($designaciones as $key => $d) {
                if ($d->modalidad == 2) { // Si es FS
                    switch ($d->categoria) {
                        case "SALA_SX_ARBITRO":
                            if ($d->unidad == 0.25) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_SX_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50 / 4; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            } else if ($d->unidad == 0.50) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_SX_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50 / 2; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            } else if ($d->unidad == 2.00) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_SX_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            }

                            break;
                        case "SALA_UNIFEM_ARBITRO":
                            if ($d->unidad == 0.50) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_UNIFEM_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50 / 2; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            } else if ($d->unidad == 1.00) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_UNIFEM_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            }
                            break;
                        case "SALA_JX_ARBITRO":
                            if ($d->unidad == 0.25) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_JX_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50 / 4; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            } else if ($d->unidad == 0.50) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_JX_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50 / 2; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            } else if ($d->unidad == 2.00) {
                                // Crear nueva designación
                                $nueva_designacion = clone $d; // Clonar la designación actual
                                $nueva_designacion->categoria = "SALA_JX_MESA"; // Cambiar la categoría
                                $nueva_designacion->tarifa = 9.50; // Establecer nueva tarifa
                                $nueva_designacion->facturar = 9.50; // Calcular el valor de facturar

                                // Añadir la nueva designación al array
                                $designaciones[] = $nueva_designacion;
                            }
                            break;
                    }
                }
            }

            //GESTION DE DELEGADOS DE CAMPO. BORRAR EL id_partido
            foreach ($designaciones as $d) {
                switch ($d->categoria) {
                    case "F7_DC_3H_DELEGADO DE CAMPO":
                    case "F7_DC_4H_DELEGADO DE CAMPO":
                    case "F7_DC_5H_DELEGADO DE CAMPO":
                    case "F7_DC_6H_DELEGADO DE CAMPO":
                    case "SALA_DC_3H_DELEGADO DE CAMPO":
                    case "SALA_DC_4H_DELEGADO DE CAMPO":
                    case "SALA_DC_5H_DELEGADO DE CAMPO":
                    case "SALA_DC_6H_DELEGADO DE CAMPO":
                        $d->id_partido = '';
                        break;
                }
            }

            // Ordenar por fecha de menor a mayor
            usort($designaciones, function ($a, $b) {
                return strtotime($a->fecha) - strtotime($b->fecha);
            });

            // debuguear($designaciones);

            // Crear hoja de cálculo
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Añadir logo
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/build/img/logo_factura.png';
            if (file_exists($logoPath)) {
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath($logoPath);
                $drawing->setHeight(80);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);
            } else {
                error_log("Logo not found at: " . $logoPath);
            }

            // Título
            $sheet->setCellValue('A6', 'DETALLE DE FACTURA');
            $sheet->getStyle('A6')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ]
            ]);

            // Encabezados
            $headers = [
                'A' => 'M',
                'B' => 'C',
                'C' => 'G',
                'D' => 'ID',
                'E' => 'FECHA',
                'F' => 'DISTRITO',
                'G' => 'J',
                'H' => 'LOCAL',
                'I' => 'VISITANTE',
                'J' => 'UNIDAD',
                'K' => 'TARIFA',
                'L' => 'FACTURAR',
                'M' => 'OBSERVACIONES',
            ];

            $row = 8;
            foreach ($headers as $col => $value) {
                $sheet->setCellValue($col . $row, $value);
                $sheet->getStyle($col . $row)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1E5128']
                    ]
                ]);
            }

            // Añadir datos a la tabla
            $row = 9;
            foreach ($designaciones as $d) {
                $d->modalidad = $d->modalidad == 1 ? 'F7' : 'FS';

                $sheet->setCellValue("A$row", $d->modalidad);
                $sheet->setCellValue("B$row", $d->categoria);
                $sheet->setCellValue("C$row", $d->grupo);
                $sheet->setCellValue("D$row", $d->id_partido);
                $sheet->setCellValue("E$row", $d->fecha);
                $sheet->setCellValue("F$row", $d->distrito);
                $sheet->setCellValue("G$row", $d->jornada);
                $sheet->setCellValue("H$row", $d->local);
                $sheet->setCellValue("I$row", $d->visitante);
                $sheet->setCellValue("J$row", $d->unidad); // Unidad sin formatear aquí
                $sheet->setCellValue("K$row", $d->tarifa);
                $sheet->setCellValue("L$row", $d->facturar);
                $sheet->setCellValue("M$row", $d->observaciones);

                $row++;
            }

            // Aplicar formato a columnas específicas
            foreach (range('A', 'M') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Centrar las columnas J, K y L
            $sheet->getStyle('J8:J' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K8:K' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L8:L' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Formato decimal para columna J
            $sheet->getStyle('J8:J' . ($row - 1))->getNumberFormat()->setFormatCode('#0.00');

            // Formato moneda en columnas K y L
            $currencyFormat = '#,##0.00 €';
            $sheet->getStyle('K8:K' . ($row - 1))->getNumberFormat()->setFormatCode($currencyFormat);
            $sheet->getStyle('L8:L' . ($row - 1))->getNumberFormat()->setFormatCode($currencyFormat);

            // Limpiar buffers y generar archivo
            while (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="facturas.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    }
}
