<?php
/**
 * Version: 1.0.0
 * Author: Daniel Pellicano
 * Main configuration shortcode.
 *
 * @package    LoteriasPlugin
 * @subpackage API
 * @version 1.0.0
 * @author Daniel Pellicano
 * @license GPL-2.0+
 */

/**
 * Configurações do Shortcode.
 *
 * Esta classe lida com a montagem e renderização do shortcode.
 */
class Loterias_Shortcode {

	/** Register Shortcode. */
	public static function register() {
		add_shortcode( 'loteria_resultado', array( __CLASS__, 'render_shortcode' ) );
	}

	/** Render Shortcode.
	 *
	 * @param mixed $atts Define os parâmetros do shortcode.
	 */
	public static function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'loteria'  => 'megasena',
				'concurso' => 'ultimo',
			),
			$atts,
			'loteria_resultado'
		);

		$loteria  = $atts['loteria'];
		$concurso = $atts['concurso'];

		// Verifica se o resultado já está no post type.
		$query_args = array(
			'post_type'  => 'loterias',
			'meta_query' => array(
				array(
					'key'   => 'concurso',
					'value' => $concurso,
				),
				array(
					'key'   => 'loteria',
					'value' => $loteria,
				),
			),
		);

		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			$query->the_post();
			return self::format_result( get_the_content() );
		}

		// Se não estiver no banco, consulta a API.
		$api    = new Loterias_API();
		$result = $api->get_result( $loteria, $concurso );

		if ( ! $result ) {
			return '<p>Não foi possível obter o resultado da loteria.</p>';
		}

		// Armazena o resultado no post type.
		$post_id = wp_insert_post(
			array(
				'post_title'   => esc_html( $loteria ) . ' Concurso ' . esc_html( $result['concurso'] ),
				'post_content' => wp_json_encode( $result ),
				'post_type'    => 'loterias',
				'post_status'  => 'publish',
				'meta_input'   => array(
					'concurso' => $result['concurso'],
					'loteria'  => $loteria,
				),
			)
		);

		return self::format_result( wp_json_encode( $result ) );
	}
	/**
	 * Formata o resultado.
	 *
	 * @param array $result O resultado da loteria.
	 */
	public static function format_result( $result ) {
		$result = json_decode( $result, true );
		ob_start();

		/**
		 * Soma os valores das premiações.
		 *
		 * @param array $premiacoes As premiações da loteria.
		 * @return float O valor total das premiações.
		 */
		function somar_valores_premiacoes( $premiacoes ) {
			$soma = 0;

			foreach ( $premiacoes as $premiacao ) {
				$soma += $premiacao['valorPremio'];
			}

			return $soma;
		}

		/**
		 * Substitui a string dos acertos se for sena, quina ou quadra.
		 *
		 * @param string $resultado O resultado da loteria.
		 * @return string A descrição dos acertos substituída.
		 */
		function substituir_acertos( $resultado ) {
			switch ( $resultado ) {
				case '6 acertos':
					return 'Sena';
				case '5 acertos':
					return 'Quina';
				case '4 acertos':
					return 'Quadra';
				default:
					return $resultado;
			}
		}
		?>
		<div class="loteria-resultado <?php echo esc_attr( $result['loteria'] ); ?>">
			<p class="concurso-data">Concurso <?php echo esc_html( $result['concurso'] ); ?> • <?php echo esc_html( $result['data'] ); ?></p>
			
			<ul class="dezenas">
				<?php foreach ( $result['dezenas'] as $dezenas ) : ?>
					<li>
						<?php echo esc_html( $dezenas ); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="premio">
				<h3>PRÊMIO</h3>
				<span>
				<?php
				$total = somar_valores_premiacoes( $result['premiacoes'] );
					echo 'R$ ' . esc_html( number_format( $total, 2, ',', '.' ) );
				?>
					</span>
				<table>
					<thead>
						<tr>
							<td>Faixas</td>
							<td>Ganhadores</td>
							<td>Prêmio</td>
						</tr>
					</thead>
					<tbody>
					<?php foreach ( $result['premiacoes'] as $premiacao ) : ?>
						<tr>
							<td><?php echo esc_html( substituir_acertos( $premiacao['descricao'] ) ); ?></td>
							<td><?php echo esc_html( $premiacao['ganhadores'] ); ?></td>
							<td><?php echo esc_html( number_format( $premiacao['valorPremio'], 2, ',', '.' ) ); ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
