<?php

namespace ItBlaster\CounterManagementBundle\Model;

use ItBlaster\CounterManagementBundle\Model\om\BaseWebCounter;

class WebCounter extends BaseWebCounter
{

    /**
     * Возвращает true если требуется обновление кода счетчика
     * @return bool
     */
    public function isRequiredCodeGeneration() {

        return  $this->isColumnModified(WebCounterPeer::NUMBER)   ||
                $this->isColumnModified(WebCounterPeer::TYPE_KEY) || $this->getCode() === null;
    }

}
