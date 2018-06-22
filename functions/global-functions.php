<?php
	if ( ! defined('ABSPATH')) exit;
/**
 *  Check if an array is a multidimensional array.
 *
 *  @param   array   $arr  The array to check
 *  @return  boolean       Whether the the array is a multidimensional array or not
 */
function is_multi_array( $x ) {
    if( count( array_filter( $x,'is_array' ) ) > 0 ) return true;
    return false;
}
/**
 *  Convert an object to an array.
 *
 *  @param   array   $object  The object to convert
 *  @return  array            The converted array
 */
function object_to_array( $object ) {
    if( !is_object( $object ) && !is_array( $object ) ) return $object;
    return array_map( 'object_to_array', (array) $object );
}

/**
 *  Check if a value exists in the array/object.
 *
 *  @param   mixed    $needle    The value that you are searching for
 *  @param   mixed    $haystack  The array/object to search
 *  @param   boolean  $strict    Whether to use strict search or not
 *  @return  boolean             Whether the value was found or not
 */
function search_for_value( $needle, $haystack, $strict=true ) {
    $haystack = object_to_array( $haystack );
    if( is_array( $haystack ) ) {
        if( is_multi_array( $haystack ) ) {   // Multidimensional array
            foreach( $haystack as $subhaystack ) {
                if( search_for_value( $needle, $subhaystack, $strict ) ) {
                    return true;
                }
            }
        } elseif( array_keys( $haystack ) !== range( 0, count( $haystack ) - 1 ) ) {    // Associative array
            foreach( $haystack as $key => $val ) {
                if( $needle == $val && !$strict ) {
                    return true;
                } elseif( $needle === $val && $strict ) {
                    return true;
                }
            }
            return false;
        } else {    // Normal array
            if( $needle == $haystack && !$strict ) {
                return true;
            } elseif( $needle === $haystack && $strict ) {
                return true;
            }
        }
    }
    return false;
}

function multidimensional_search($parents, $searched) { 
  if (empty($searched) || empty($parents)) { 
    return false; 
  } 

  foreach ($parents as $key => $value) { 
    $exists = true; 
    foreach ($searched as $skey => $svalue) { 
      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue); 
    } 
    if($exists){ return $key; } 
  } 

  return false; 
} 


/**
 * Verifica chaves de arrays
 *
 * Verifica se a chave existe no array e se ela tem algum valor.
 *
 * @param array  $array O array
 * @param string $key   A chave do array
 * @return string|null  O valor da chave do array ou nulo
 */
function chk_array ( $array, $key ) {
	// Verifica se a chave existe no array
	if ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) ) {
		// Retorna o valor da chave
		return $array[ $key ];
	}
	
	// Retorna nulo por padrão
	return null;
} // chk_array

function chk_array_value ( $array, $key ) {
	// Verifica se a chave existe no array
	if ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) ) {
		// Retorna true se existir
		return true;
	}
	
	// Retorna nulo por padrão
	return null;
} // chk_array


/**
 * Função para carregar automaticamente todas as classes padrão
 */

spl_autoload_extensions('.php, .class.php');
spl_autoload_register('autoloader');

/**
	 * monta o caminho até o diretorio e a extensao do arquivo
	 * str_replace — Substitui todas as ocorrências da string de procura com a string de substituição. 
	 * Adiciona barras para servidores windows ou linux, nao testado em produção ainda...
*/
function autoloader($class_name) {
	
	$file = str_replace('\\', '/', ABSPATH . '/classes/' . $class_name);

	//extensao do arquivo
	$class_extension = '.php';

	//verifica se a extensao existe, senao troca pela segunda extensao
	if( ! file_exists( $file . $class_extension) )
		$class_bollean = false;
	else
		$class_bollean = true;	

	$class_extension = ($class_bollean) ? '.php' : '.class.php';

	$file .=  $class_extension;

	// Inclui o arquivo da classe ou 404 not found
	if ( ! file_exists( $file ) ) 
		require_once '..' . VIEW_DIR . 'pages/examples/404.php';
	else
		require_once $file;
} // __autoload

/**
* Função para debugar variaveis/objetos/arrays/etc
* utilize essa função quando for necessario printar algo entra a tag <pre>
*/
function getPrint($parametro)
{
	echo "<pre>";
		var_dump($parametro);
	echo "</pre>";
}

/**
* Função para ordernar o resultado das consultas simulando FULL OUTER JOIN
* parametros:
* $array, matriz que será ordenada
* $on, chave que sera ordenada
* $order, critério de ordenação, SORT_ASC ou SORT_DESC
*
*
*/
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
/*
*Esta é uma nova função multiclassificar para classificar em múltiplo subcampo como ele será em SQL: ' order by campo1, campo2 ' número de classificar campo é indefinido 
* Parametros: 
* $arg1, array que será ordenado
* $arg2 e $arg3 ...,colunas que serão ordenadas
*/
function multiSort() { 
    //get args of the function 
    $args = func_get_args(); 
    $c = count($args); 
    if ($c < 2) { 
        return false; 
    } 
    //get the array to sort 
    $array = array_splice($args, 0, 1); 
    $array = $array[0]; 
    //sort with an anoymous function using args 
    usort($array, function($a, $b) use($args) { 

        $i = 0; 
        $c = count($args); 
        $cmp = 0; 
        while($cmp == 0 && $i < $c) 
        { 
            $cmp = strcmp($a[ $args[ $i ] ], $b[ $args[ $i ] ]); 
            $i++; 
        } 

        return $cmp; 

    }); 

    return $array; 

} 




