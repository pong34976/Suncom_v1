<?php
 function printTestPage ($host, $printer, $type) {
    $response = null;
    try {
        $config = new ControlPanel(NULL);
        $response = $config->getBasicInfo();
        if (trim(mb_strtoupper($type, 'UTF-8')) == "USB") {
            $connector = new WindowsPrintConnector("smb://".$host."/".$printer);
            $printer = new Printer($connector);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer ->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text('--IMPRESIÓN DE PRUEBA--');
            $printer ->selectPrintMode();
            $printer -> feed();
            $printer -> text("============================================\n");
            $printer -> feed();
            if (count($response) > 0) {

                $printer->text($response[0]['encabezado']);
                $printer->feed();
            }
            for ($i = 0; $i < 10; $i++) {
                $printer
                    -> text("============================================\n");
            }
            if (count($response) > 0) {
                $printer->text($response[0]['pie']);
                $printer->feed();
                $printer -> text("============================================\n");
            }
            $printer -> feed(2);
            $printer -> cut();
            $printer -> close();
            $response = "Success|<span class='fa fa-info-circle cyan-text is-right-5'></span>Se ha impreso un ticket de prueba";
        }
    } catch (Exception $e) {
        $response = "Error|<span class='fa fa-times-circle red-text is-right-5'></span>Ocurrió un error al imprimir la página de prueba, causa: ".$e->getMessage();
    }
    return $response;
}