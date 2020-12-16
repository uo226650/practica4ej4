<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <title>Noticias</title>
    <link rel="stylesheet" href="Ejercicio4.css" type="text/css">
</head>
<body>
	<h1>Noticias del mundo</h1>
    <section>
        <h2>¡Descubre cuánto se ha hablado de un tema en los medios!</h2>
        
        <form action='#' method='post' id="buscador" name='formulario'>

            <fieldset>
            <input type="radio" id="semana" name="rangoFecha" value="7" checked />
            <label for="semana">Última semana</label>
            <input type="radio" id="quincena" name="rangoFecha" value="15"/>
            <label for="quincena">Últimos quince días</label>
            <input type="radio" id="mes" name="rangoFecha" value="30" />
            <label for="mes">Último mes</label>
            </fieldset>
            <div>
                <label for="busqueda">Descubrir artículos que contengan la palabra:</label>
                <input type="text" id="busqueda" name="palabra" placeholder="palabra clave" />
                <input type='submit' name="descubrir" value='Descubrir'/>
            </div>
        
         </form>
    </section>
    <main>
        <?php
        
        define("API_KEY", "623c8a4d1ed0493b828f265e099c2c7b");
        //La API gratuíta solo permite recuperar noticias de hasta un mes de antigüedad.
        //Para acceder a noticias anteriores es necesario pasar a un plan de pago
        class Noticia {
            
            public function __construct(){
                $this->frecuencia = 7;//Últimos 7 días por defecto
            }

            public function submit(){
                if (count($_POST)>0) 
                {   
                    // Comprueba que el nombre no está en blanco
                    if($_POST["palabra"] != ""){
                        $this->setRango($_POST['rangoFecha']);
                        $this->pideDatos($_POST['palabra']);
                    }
                }
            }
        
            /**
             * Muestra la cuenta de artículos totales que contienen la palabra clave
             * y el titular de aquellos más populares
             * 
             * @param {String} palabraClave 
             */
            public function pideDatos($palabraClave){
                $dias = $this->frecuencia;
                $today = date("Y-m-d");

                $from = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - $dias,   date("Y")));

                $palabraClave =str_replace(' ', '', $palabraClave);

                $url = 'https://newsapi.org/v2/everything?q=' . $palabraClave .'&sortBy=popularity' . 
                "&from=" . $from . 
                "&to=" . $today . "&apiKey=" . API_KEY;

                $datos = file_get_contents($url);
                $this->parseDatos($datos);
        
            }
            
            public function parseDatos($json){
                $datos = json_decode($json);
                if($json==null) {
                    echo "<h2>Error en el archivo JSON recibido</h2>";
                }
                
                $articulos = $datos->articles;
                $html = "";
                forEach($articulos as $articulo) {
                    $html .= "<article>"
                                . "<img src='" . $articulo->urlToImage . "' alt='Imagen del artículo'/>"
                                . "<h2><a href='" . $articulo->url . "'>" 
                                . $articulo->title . "</a></h2>"
                                . "<p>" . $articulo->content
                                . "</p></article>";
                }

                echo ("<h2>Artículos publicados: " . $datos->totalResults . "</h2>");
                echo ("<h3>Artículos destacados: </h3>");
                echo $html;
                           
            }

        
            public function setRango($frecuencia){
                $this->frecuencia = $frecuencia;
            }
            
        
        }
        
        $noticia = new Noticia();
        $noticia->submit();
        
        ?>
    </main>
    
        
    <footer>
        
    </footer>
</body>
</html>


















