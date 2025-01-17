# **`PROYECTO GESTIÓN IMD`**

### *Jesús Gómez - Desarrollador Aplicaciones Web⚡*
[![Contact Me](https://img.shields.io/badge/Email-informational?style=for-the-badge&logo=Mail.Ru&logoColor=fff&color=c6362c)](mailto:jgomezbeltran88@gmail.com)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-informational?style=for-the-badge&logo=linkedin&logoColor=fff&color=0274b3)](https://www.linkedin.com/in/jesusgb-dev/)
[![Linktree](https://img.shields.io/badge/-Linktree-323330?style=for-the-badge&logo=linktree&logoColor=1de9b6)](https://linktr.ee/jesusgb?utm_source=linktree_admin_share)

### **`Información general:`**

#### **Gestión IMD** es una aplicación diseñada para gestionar la organización de partidos, árbitros y usuarios dentro de un sistema accesible y eficiente. Incluye funcionalidades específicas para administradores y usuarios, con roles claramente definidos y una interfaz intuitiva.

  - *Estructura general*:
    - **Vista Pública**: Accesible para cualquier usuario. Permite aceptar o rechazar partidos.
    - **Vista Privada**: Disponible solo para usuarios registrados, con diferentes niveles de permisos:
      - **Administrador**: Acceso completo a todas las funcionalidades.
      - **Usuario**: Acceso limitado a la gestión de partidos.

  - **Principales funcionalidades según roles:**
    - **Administrador**:
      - Crear, editar y eliminar partidos.
      - Crear, editar y eliminar árbitros.
      - Crear, editar y eliminar categorías.
      - Nombrar, borrar y asignar partidos a árbitros.
      - Generar, editar y eliminar jornadas.
      - Generar y editar facturas.
      - Crear, editar y eliminar usuarios.
      - Bloquear y desbloquear árbitros y usuarios.
    - **Usuario**:
      - Nombrar, borrar y asignar partidos a árbitros.

### **`Información técnica:`**

#### La aplicación se ha desarrollado siguiendo una estructura **Model View Controller (MVC)** y la convención **Active Record**. Además, se utiliza la metodología **BEM** para la organización de estilos.

  - **Lenguajes y tecnologías utilizadas:**
    - **Frontend**: SCSS (con BEM) y JavaScript.
    - **Backend**: PHP con MVC y Active Record.
    - **Responsividad**: Diseño adaptable a cualquier dispositivo.

  - **Funcionalidades globales:**
    - **Sistema de roles**: Control de acceso y personalización de vistas según el tipo de usuario.
    - **Optimización de rendimiento**: Scripts diseñados para minimizar tiempos de carga.
    - **Sistema de cacheo**: Mejora de la experiencia de usuario en navegación recurrente.
    - **Manipulación de documentos**: Uso de la librería PHP **PhpSpreadsheet** para generar documentos Excel, realizar cargas masivas y descargar archivos.

  - **Archivos y configuraciones adicionales:**
    - **Archivo robots.txt**: Configuración de rastreo para motores de búsqueda.
    - **Sitemap**: Organización para optimizar la indexación del sitio.
    - **Archivo manifest**: Proporciona información de la aplicación web para navegadores compatibles.

  - **Validaciones de datos en formularios:**
    - Se validan campos de entrada con PHP en el servidor y con JavaScript en el cliente.
    - Sistema de seguridad anti-bots con campos ocultos.

  - **Optimización visual:**
    - Utilización de gráficos y resúmenes interactivos para la gestión de datos.
    - Estructuración de vistas según el perfil del usuario.

#### **Tests de calidad:**
Se realizaron pruebas con herramientas como **Lighthouse** para garantizar rendimiento, accesibilidad, mejores prácticas y SEO óptimos.

---

### *Hecho con ❤️ por Jesús Gómez Freelancer Web Developer⚡*
