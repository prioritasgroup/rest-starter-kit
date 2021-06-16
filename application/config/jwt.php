<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['jwt_key'] = 'jdsfoiafuoejoafkllaewkl';

/*Generated token will expire in 1 minute for sample code
* Increase this value as per requirement for production
*/
$jam = 1*60;
$config['token_timeout'] = $jam*24*7; //1jam

/* End of file jwt.php */
/* Location: ./application/config/jwt.php */