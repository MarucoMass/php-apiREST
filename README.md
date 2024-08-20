# API-REST - MARIO MASSONNAT

Este proyecto de API REST para reservas de clases fue desarrollado con PHP puro (versión 8.2.12). No es necesario realizar ninguna instalación adicional, solo necesitan tener PHP y MySQL (XAMPP) instalado.

En la carpeta database, encontrarán un archivo SQL para configurar la base de datos inicial.

En el archivo index.php están los ajustes iniciales y la configuración de las clases del modelo y el controlador del proyecto.
Cambiar los datos de $user y y $password según su usuario y contraseña.

El endpoint configurado en index.php es 'booking'. A partir de este endpoint, pueden utilizar los distintos métodos HTTP para probar la API. 
Para probarla, pueden usar Postman o alguna extensión de Visual Studio Code. En mi caso, utilicé la extensión Thunderclient para realizar las pruebas.

Para probar el método POST del endpoint, aquí hay un ejemplo de como ingresar los datos necesarios. 
El id del intervalo seleccionado, el nombre de la persona que reserva y la fecha según el dia elegido:

{
    "id": 16,
    "booked_by": "Jorge",
    "date": "2024-08-26"
}

Para el probar el método DELETE del endpoint deben ingresar en el mismo el id de la reserva que quieran eliminar:
https://localhost/booking/{id}

También hay un archivo llamado 'error_log.txt' que trackea todos los errores que puedan ir ocurriendo. 
La función encargada de llevar a cabo eso está en 'ErrorHandler.php'.
