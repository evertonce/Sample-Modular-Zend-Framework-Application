<?php

namespace Wednesday\Exception;

use Wednesday\Exception;

/**
 * UnexpectedValueException
 * 
 * @author James A Helly <james@wednesday-london.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @package Wednesday.Exception
 * @subpackage UnexpectedValueException
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class UnexpectedValueException 
    extends \UnexpectedValueException
    implements Exception
{}