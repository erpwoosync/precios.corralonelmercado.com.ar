<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precios</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    
    $idcodigo = $_REQUEST['ID'];

    if ($idcodigo == null)
    {
        $idcodigo = 38633;
    }

    if (!empty($idcodigo)) {

        $curl = curl_init();



        curl_setopt_array($curl, array(

            CURLOPT_URL => 'http://corralonelmercado.ddns.net:1083/articulos?include=listas&solo_activos=false&art_id=' . $idcodigo,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_ENCODING => '',

            CURLOPT_MAXREDIRS => 10,

            CURLOPT_TIMEOUT => 0,

            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => 'GET',

            CURLOPT_HTTPHEADER => array(

                'EmpID: 1',

                'Authorization: Basic V09PQ09NTUVSQ0U6V09PQ09NTUVSQ0UxMjM0'

            ),

        ));



        $response = curl_exec($curl);



        curl_close($curl);

        //echo '<pre>'; print_r($response); echo '</pre>';

        //echo $response;

        $lista = json_decode($response, true);



        $size = $lista['size'];

        //$nombreLista=$lista['content'][0]['listas'][0]['lista']['nombre'];



        $arrayListaPrecio = $lista['content'][0]['listas'];



        $cantidadElementos = count($arrayListaPrecio);



        $nroElemento = 0;



        for ($i = 0; $i < $cantidadElementos; $i++) {

            //echo $lista['content'][0]['listas'][$i]['id'];

            //echo $lista['content'][0]['listas'][$i]['lista'][0]['id'];

            //if ($lista['content'][0]['listas'][$i]['id']==131244){$nroElemento=$i;

            if ($lista['content'][0]['listas'][$i]['lista']['nombre'] == "LISTA JUJUY 1") {

                $nombreLista = $lista['content'][0]['listas'][$i]['lista']['nombre'];

                $nroElemento = $i;
            }
        }



        // echo $nroElemento;



        $pv = ($lista['content'][0]['listas'][$nroElemento]['pr_final']) * 0.83 * 1.2987;

        $desc_imp_fiscal = $lista['content'][0]['calc_desc'];

        $bucket_id = $lista['content'][0]['bucket_id'];

        $pos = strpos($lista['content'][0]['nota'], '@');

        //echo $pos;

        $nota = substr($lista['content'][0]['nota'], 1, $pos - 1);

        //Buscamos bucket-id

        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => 'https://corralonelmercado.ddns.net/recursos/api/buckets/' . $bucket_id . '/resources',

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_ENCODING => '',

            CURLOPT_MAXREDIRS => 10,

            CURLOPT_TIMEOUT => 0,

            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => 'GET',

        ));


        $resBucket = curl_exec($curl);

        curl_close($curl);

        //echo $resBucket;
        //echo $response;

        $listaBucket = json_decode($resBucket, true);

        //$imagen=$listaBucket['content'][0]['url'];

        $size = $listaBucket['total'];

        //$nombreLista=$lista['content'][0]['listas'][0]['lista']['nombre'];



        $arrayListaBucket = $listaBucket['content'][0];

        $cantidadElementos1 = count($arrayListaBucket);







        for ($y = 0; $y < $cantidadElementos1; $y++) {

            if ($listaBucket['content'][$y]['orden'] == "0" OR $listaBucket['content'][$y]['orden'] == "1" ) {

                $imagen = $listaBucket['content'][$y]['url'];
            }
        }

        if ($imagen == null)
        {
            $imagen = "/img/placeholder.jpg";
        }


        //echo $pv;

        // fin de buscamos bucket-id





    ?>



        <header class="header">
            <div class="contenido-header">
                <img class="logo" src="img/logo.png" alt="Logo tipo">
                <p class="titulo">
                    Producto Consultado
                </p>
            </div>
        </header>



        <main class="contenedor">
            <div class="grid">
                <div class="producto__informacion">
                    <p class="producto__nombre"> <?php echo $desc_imp_fiscal; ?></p>
                </div>

                <div class="producto">
                    <img class="producto__imagen" src=<?php echo $imagen; ?> alt="imagen">
                </div>

                <div class="nota">
                <!-- <p class="producto__nombre_lista"><?php echo $nombreLista; ?></p>-->
                    <p class="producto__precio"> $ <?php echo number_format($pv, 2, ',', '.'); ?></p>
                    <p class="nota__detalle"><?php echo $nota; ?></p>
                </div>
            </div>
        </main>

        <footer class="footer">
            <p class="footer__texto"> CONSULTA DE PRECIOS - Todos los derechos Reservados 2022</p>
        </footer>

    <?php





    }





    ?>

</body>

</html>