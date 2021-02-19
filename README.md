# Agenda Symfony

Agenda inspirada en el anime Gintama, hecha con Symfony 4 y que permite añadir, editar, mostrar y borrar contactos (un CRUD). Estos contactos se separan según tipo ya sea _personal o profesional_.


## Forma de empleo

Al añadir un contacto, se deben completar una serie de campos para poder interactuar con este. Estos campos son:

| Option | Description |
| ------ | ----------- |
| Nombre  | Nombre del contacto |
| Apellidos | Apellido contacto |
| Mail   | Correo del contacto (debe de ser único) |
| Teléfono   |Telefono del contacto (debe de ser único) |
| Tipo   |Tipo de contacto, personal o profesional.|
| Notas   |Información relevante sobre el contacto (opcional) |

Tras agregar un contacto, se pueden mostrar por tipo según sea _personal_ o _profesional_, también se pueden mostrar todos al mismo tiempo, se pueden mostrar, editar y borrar por id.

## Requisitos previos

Es necesario tener los siguietes programas o librerias para que funcione:

* Symfony 4
* Composer
* XAMPP >3.2.0 (a ser posible)
* Un IDE tipo Visual Studio Code


## Guía de instalación

Para poder usar la Gintagenda, debes descargar el proyecto y guardarlo en la carpeta. Tras eso, dirígete a la ubicación del archivo desde el CMD y ejecuta el comando:

    symfony server:start

Tras esto, dirígete a la siguiente ruta:

	http://127.0.0.1:8000/contactos/

Podrás comprobar que la agenda estará funcionando adecuadamente.

## Tecnologías utilizadas.

* VS code
* Symfony 4
* Composer
* XAMPP
* SQLite


## Cómo hacer la agenda desde 0

(creamos el proyecto)

(creamos la entidad)
_php bin/console make:entity Contacto_

(hacemos el 95% de la agenda)
_php bin/console make:crud Contacto_

(configuramos la BBDD en el .env asegurandonos que lo tenemos en SQLite)

(reemplazamos parte de nuestro código de _base.html.twig_ por esto: )

<html>
	<head>
		<meta charset="UTF-8">
		<title>
			{% block title %}Agenda
			{% endblock %}
		</title>
		{% block stylesheets %}<link href="{{ asset('css/main.css') }}" rel="stylesheet"/>{% endblock %}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    </head>

(nos dirigimos a _config/packages/twig.yaml_ y ponemos lo siguiente:)

twig:
    form_themes: ['bootstrap_4_layout.html.twig']

**Con esto ya tendríamos hecha casi toda la agenda, con estilos de bootstrap y todo, faltarían cosas como el selector de tipo, que vendría siendo algo tal que así:**

/**
     * @Route("/list/{type}", name="list")
     */
    public function list(Request $request, $type, PaginatorInterface $paginator) : Response {

        if ($type == 'todos') {
            $dql   = "SELECT * FROM contactos";
            $contacto = $this->getDoctrine()
            ->getManager()
            ->getRepository(Contactos::class)
            ->findAll();
            # code...
        }
        else{
            $contacto = $this->getDoctrine()
            ->getManager()
            ->getRepository(Contactos::class)
            //->createQueryBuilder('p')

            ->findBy(['tipo' => $type]);
            //->getQuery();
        }
        $pagination = $paginator->paginate(
            // Doctrine Query, not results
            $contacto,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('contactos/list.html.twig',[
            'list' => $contacto,
            'type' =>$type,
            'pagination' => $pagination
        ]);
    }

    **Cabe destacar que habrá que incluir un fichero html.twig a mayores que sustituya el roll actual del index**

## Autor
Centryck **Carlos Santillana Garabana**

## Licencia

![Minion](https://tic100tifiko.files.wordpress.com/2018/10/cc-zero-badge.png?w=500)



## Cómo contribuir al proyecto.

Para acceder a este proyecto, acceda al siguiente link:

https://github.com/Centryck/AgendaSymfony
