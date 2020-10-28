<?php


class ApiEmailCreci
{
  private $regional;

  /**
   * ApiEmailCreci constructor.
   * @param int $regional
   */
  public function __construct($regional)
  {
    $this->regional = $regional;
  }

  /**
   * Obtém o token da API.
   * @return string|false - False caso não haja token para o regional.
   */
  private function getToken()
  {
    switch ($this->regional) {
      case 8:
        return 'jHNYrAvLbL2IiuoKMg9px/K+Ez8VMB25sCSZBMQ7U97H/kSGq8/bHVRTMIq+dNlD';
      case 9:
        return 'y2cDlm/5BnPWHOcqo+0rBw==';
      case 12:
        return 'jZDP4joE/sfxng6Q+fb8TWlt8u/CWsQx6Qq3AJ9Mteg=';
      case 14:
        return 'TlgIh80bDLMyhriNnRXTbV18cQkziKLinqv0SlwzlIY=';
      case 15:
        return 'LyCKrx8Wg0GC8n4GwOcz6w==';
      case 16:
        return 'F8lF0kavvXc8h+BAnw0sAA==';
      case 18:
        return 'AfgorC3c/Tr3u1kfrWEQk6AemiNY1pUrFa962nSWT/s8VshsSGzMMQlbpn+D8w5Z';
      case 20:
        return 'CSXTmD6BL5r/nAuCwPlBSDslabiV158BTIwCe5/lUUA=';
      case 21:
        return 'a6dcJJ5OeBvF+zZ5pzCEig==';
      case 22:
        return 'rBacL7Ls0wcbFoTXNUPm8Q==';
      case 23:
        return 'JPYpYNcU5fIPn9FgWOBCBQ==';
      case 24:
        return 'WHHbye18ydAslLg0TGdAXPvdzxje1QX8l2dk13blw5E=';
      case 26:
        return '30yDg5PDoBpDwLHdy0KJmQ==';
      default:
        return false;
    }
  }

  /**
   * Realiza uma requisição para o servidor de API do CRECI-SP.
   * @param string $url - Endpoint da API.
   * @param array $body - Dados que serão transformados em JSON na requisição.
   * @return array|false
   */
  private function requisicao($url, $body) {
    $curl = curl_init($url);
    $config = array(
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => array('token: '.$this->getToken(), 'Content-Type: application/json; charset=utf-8'),
      CURLOPT_POSTFIELDS => json_encode($body),
    );
    curl_setopt_array($curl, $config);
    $responseData = curl_exec($curl);
    if (curl_errno($curl)) return false;
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
    return array(
      'body' => $responseData,
      'code' => $responseCode,
      'content-type' => $contentType,
    );
  }

  /**
   * Verifica se o email está sendo utilizado em algum redirecionamento do CRECI.ORG
   * @param $emailPessoal - Email pessoal
   * @return mixed|false
   */
  public function verificarEmail($emailPessoal) {
    return $this->requisicao('http://criar.creci.org.br/WebService.asmx/VerificaEmailRedirecionamento', array(
      'Email' => trim($emailPessoal),
    ));
  }

  /**
   * Atualiza o redirecionamento do email CRECI.ORG para um novo email particular.
   * @param string $cpf - Numeros do CPF.
   * @param int $numCreci - Numero de CRECI do corretor.
   * @param string $emailPessoal - Novo email pessoal.
   * @return mixed|false
   */
  public function atualizarEmail($cpf, $numCreci, $emailPessoal) {
    return $this->requisicao('http://criar.creci.org.br/WebService.asmx/AtualizaEmailRedirecionamento', array(
      'NrRegistro' => strval($numCreci),
      'CPF' => $cpf,
      'Email' => trim($emailPessoal),
    ));
  }

  /**
   * Atualiza a situação do corretor de imóveis no servidor dos emails CRECI.ORG
   * @param string $emailCreciOrg
   * @param bool $ativo
   * @param bool $regular
   * @return mixed|false
   */
  public function atualizarSituacaoCorretor($emailCreciOrg, $ativo, $regular) {
    return $this->requisicao('http://criar.creci.org.br/WebService.asmx/AtualizaSituacao', array(
      'email' => trim($emailCreciOrg),
      'situacao' => $ativo ? 'ATIVO' : 'INATIVO',
      'regularidade' => $regular ? 'REGULAR' : 'IRREGULAR',
    ));
  }
}