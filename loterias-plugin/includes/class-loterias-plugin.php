<?php
/**
 * Version: 1.
 * Author: Daniel Pellicano
 *
 * @package LoteriasPlugin
 */

/**
 * Loterias_Plugin class.
 *
 * Esta classe é responsável por registrar o Custom Post Type e o Shortcode.
 */
class Loterias_Plugin {
	/**
	 * Initialize the plugin components.
	 *
	 * Esta função inicializa os componentes principais do plugin, registrando o Custom Post Type
	 * e o Shortcode relacionados às Loterias.
	 */
	public static function init() {
		Loterias_Post_Type::register();
		Loterias_Shortcode::register();
	}
}
