<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Constants;

/**
 * Description of newPHPClass
 *
 * @author petar
 */
class StatusConstants {
    
    const USER2COURSE_STATUSES = array(
        3 => 'Završen',
        4 => 'Odobren',
        5 => 'Na odobrenju',
        6 => 'Odbijen',
        7 => 'Nije završio', 
    );
    
    const COURSE_STATUSES = array(
        1 => 'Planiran',
        2 => 'Potvrđen',
        3 => 'Otkazan',
        4 => 'Održan',
    );
}
