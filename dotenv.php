<?php

/**
 * A CLASSE A SEGUIR DESTINA-SE A PROTEGER OS DADOS SENSÍVEIS DE CADASTRO DO USUÁRIO AD
 * AS INFORMAÇÕES ESTÃO CONFINADAS EM UM ARQUIVO .env. A CLASSE ATUAL INTERPRETA O ARQUIVO
 * EM QUESTÃO E TRANSFORMA OS VALORES DEFINIDOS NELE EM VARIÁVEIS DE AMBIENTE PARA QUE POSSAM
 * SER ACESSADAS PELO INTERPRETADOR DO PHP
 * 
 * A CLASSE UTILIZADA CONSTA NO ENDEREÇO ABAIXO:
 * https://dev.to/fadymr/php-create-your-own-php-dotenv-3k2i 
 */

class DotEnv
{
    /**
     * O diretório onde pode-se localizar o arquivo .env.
     *
     * @var string
     */
    protected $path;


    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s não existe', $path));
        }
        $this->path = $path;
    }

    public function load() :void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('O arquivo %s não está legível', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
