<?php
/**
 * Plugin Name: Loterias Post Type
 * Description: Este plugin registra um Custom Post Type para Loterias.
 * Version: 1.0
 * Author: Daniel Pellicano
 *
 * @package    LoteriasPlugin
 * @subpackage API
 * @version 1.0.0
 * @author Daniel Pellicano
 * @license GPL-2.0+
 */

/**
 * Loterias_Post_Type class.
 *
 * Esta classe é responsável por registrar o Custom Post Type de Loterias.
 */
class Loterias_Post_Type {

	/**
	 * Register action hooks.
	 *
	 * Esta função registra os hooks necessários para inicializar o Custom Post Type.
	 */
	public static function register() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
	}

	/**
	 * Register the 'loterias' post type.
	 *
	 * Esta função registra o Custom Post Type 'loterias' com os parâmetros especificados.
	 */
	public static function register_post_type() {
		$labels = array(
			'name'               => 'Loterias',
			'singular_name'      => 'Loteria',
			'add_new'            => 'Adicionar Novo',
			'add_new_item'       => 'Adicionar Nova Loteria',
			'edit_item'          => 'Editar Loteria',
			'new_item'           => 'Nova Loteria',
			'all_items'          => 'Todas as Loterias',
			'view_item'          => 'Ver Loteria',
			'search_items'       => 'Procurar Loterias',
			'not_found'          => 'Nenhuma Loteria encontrada',
			'not_found_in_trash' => 'Nenhuma Loteria encontrada na Lixeira',
			'menu_name'          => 'Loterias',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'loteria' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'custom-fields' ),
		);

		register_post_type( 'loterias', $args );
	}
}

// Chama a função register para iniciar o registro do Custom Post Type.
Loterias_Post_Type::register();
