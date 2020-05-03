<?php return [
    'plugin' => [
        'name' => 'Utils',
        'description' => 'Utils',
    ],
    'fields' => [
        'page' => 'Página',
        'select_page' => 'Selecciona Página',
        'title' => 'Título',
        'description' => 'Descripción',
        'keywords' => 'Keywords',
        'og_title' => 'og:title',
        'og_description' => 'og:description',
        'seoImage' => 'og:image',
        'robots' => 'Robots.txt',
        'sitemap' => 'Sitemap.xml',
        'name' => 'Nombre',
        'is_active' => 'Activo',
        'order' => 'Orden',
        'created_at' => 'Creado en',
        'updated_at' => 'Actualizado en',
        'script' => 'Script',
        'noscript' => 'NoScript',
        'parent_page' => 'Página padre',
        'dom_element' => 'Elemento en la página',
        'detect_browser_language' => 'Detectar idioma navegador',
        'prefer_user_session' => 'Mantener sesión del usuario',
        'is_expired' => 'Expirado',
        'expires_at' => 'Expira en'
    ],
    'permissions' => [
        'utils' => 'Utils',
        'seo' => 'Seo',
        'config' => 'Configuración',
        'scripts' => 'Scripts',
        'page' => 'Contenido de las páginas',
    ],
    'menu' => [
        'seo' => 'Seo',
        'settings' => 'Configuración',
        'scripts' => 'Scripts',
        'headers' => 'Cabeceras',
        'breadcrumbs' => 'Breadcrumbs',
    ],
    'tabs' => [
        'seo' => 'Seo',
    ],
    'errors' => [
        'create_dir' => 'Error al crear el directorio',
        'create_file' => 'Error al crear el fichero',
        'delete_directory' => 'Error al eliminar el directorio'
    ],
    'controller' => [
        'behavior_sortable_relations_controller' => 'Sortable Relations controller behavior',
        'behavior_sortable_relations_controller_description' => 'Manage the sort order of model relations directly in the view list of the relation controller.',
        'property_behavior_sortable_relations_parent_class' => 'Parent model class',
        'property_behavior_sortable_relations_parent_class_description' => 'A model class name, the parent model that holds the relations on the form.',
        'property_behavior_sortable_relations_parent_class_placeholder' => '--select model--',
        'property_behavior_sortable_relations_parent_class_required' => 'Please select a model class'
    ],
    'common' => [
        'reorder' => 'Reorder'
    ],
];
