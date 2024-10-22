
## TIZIANO API

Api de ventas con lógica de negocios adaptada a solicitud del cliente.

## Instalación
Para instalar es necesario instalar las siguientes apps:
- **[Docker Desktop](https://www.docker.com/products/docker-desktop/)**
- **[Laragon](https://laragon.org/download/)**
- **[Docker Extencion para Visual estudio code](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker)**
- **[Heidisql o worbench o algo parecido](https://www.heidisql.com/)**

#### Pasos de instalacion
1. Cuando tengas las apps deberás descargar el proyecto en la siguiente ruta: ```C:\laragon\www```.
2. Iniciar Laragon presionando el boton de  ```Iniciar Todo```.
3. Presionar el botón de terminal que trae laragon y apuntar el path a la ruta de tu proyecto ej: ```cd tiziano-api```
4. Ejecutar en la consola ``` composer i```. Esto instalara todas las dependencias del proyecto.
5. Iniciar la app de docker y luego en la terminal escribir ```bash ./vendor/bin/sail up```. Esto construira y levantara el proyecto en una imagen de docker.
6. Ya al terminar el proceso anterior deberia estar corriendo en ```http://localhost``` la api
