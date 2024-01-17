Pasos para instalación:<br>

1. [ ] Una vez descargado el proyecto generar la base de datos con el nombre que vayamos a utilizar.<br>
2. [ ] Correr en la consola de laragon "composer run-script iniciar-projecto".<br>
3. [ ] Modificar en el .env los datos de la db para poder conectar.<br>
4. [ ] Correr en la consola de laragon "composer run-script migracion-completa".<br>

¡Ya se puede utilizar el sistema!<br>

Para cargar las consultas en thunder-client hacer lo siguiente<br>
1. [ ] Instalar la extensión de thunder client.<br>
2. [ ] Generar un env en la extensión con los siguientes datos: <br>
    * email -> admin@admin.com
    * password -> admin
    * token -> asd
    * hash -> asd
3. [ ] Guardar el env.<br>
4. [ ] Importar una nueva colección desde dentro del proyecto, en el root se encuentra un archivo de thunder.<br>
5. [ ] Una vez importada la colección se debe dar click en el menú e ir a settings, en la pestaña de enviroment utilizar el creado anteriormente.<br>
6. [ ] Para utilizar las consultar recuerde que debe hacer login en el sistema con la request de nombre login para que no patee por el token.<br>

¡Ya puede utilizar todo!
Happy programming!
