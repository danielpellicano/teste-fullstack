<?php
/**
 * Version: 1.
 * Author: Daniel Pellicano
 *
 * @package    LoteriasPlugin
 * @subpackage API
 * @version 1.0.0
 * @author Daniel Pellicano
 * @license GPL-2.0+
 */

/**
 * Classe API para o Plugin de Resultados das Loterias Caixa.
 *
 * Esta classe lida com as interações e chamadas de API para buscar os resultados
 * das Loterias Caixa. Ela inclui métodos para consultar resultados específicos
 * e tratar as respostas da API.
 */
class Loterias_API {

	/**
	 * URL base da API para buscar os resultados das Loterias.
	 *
	 * @var string
	 */
	private $api_url = 'https://loteriascaixa-api.herokuapp.com/api/';

	/**
	 * Obtém os resultados de uma loteria específica.
	 *
	 * Este método faz uma chamada à API para buscar os resultados da loteria especificada.
	 * Se o concurso não for informado, será buscado o resultado do último concurso.
	 *
	 * @param string $loteria  O nome da loteria para a qual os resultados serão buscados (ex: 'mega-sena', 'quina').
	 * @param string $concurso (Opcional) O número do concurso a ser buscado. O valor padrão é 'ultimo', que buscará o resultado mais recente.
	 *
	 * @return array Retorna um array com os resultados da loteria, incluindo números sorteados,
	 *               data do sorteio e informações adicionais.
	 */
	public function get_result( $loteria, $concurso = 'ultimo' ) {
		// Gerar uma chave única para o cache com base nos parâmetros.
		$cache_key = 'loterias_' . $loteria . '_' . $concurso;

		// Tentar obter o resultado do cache.
		$cached_result = get_transient( $cache_key );
		if ( false !== $cached_result ) { // Yoda Condition.
			return $cached_result;
		}

		// Se o concurso for 'ultimo', usa o sufixo 'latest', senão usa o número do concurso.
		$endpoint = $this->api_url . $loteria . '/' . ( 'ultimo' === $concurso ? 'latest' : $concurso ); // Yoda Condition.

		$response = wp_remote_get( $endpoint );
		if ( is_wp_error( $response ) ) {
			return null;
		}

		$body   = wp_remote_retrieve_body( $response );
		$result = json_decode( $body, true );

		// Armazenar o resultado no cache por 12 horas.
		set_transient( $cache_key, $result, 12 * HOUR_IN_SECONDS );
	}
}
