<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2019 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Lib;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\Base\BusinessDocument;
use FacturaScripts\Core\Model\Base\BusinessDocumentLine;
use FacturaScripts\Dinamic\Model\Comision;

/**
 * Class for the calculation of sales commissions
 *
 * @author Artex Trading s.a. <jcuello@artextrading.com>
 */
class CommissionCalculate
{

    /**
     *
     * @var Comision
     */
    private $commission;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->commission = new Comision();
    }

    /**
     * Calculate commission sale of a document
     *
     * @param BusinessDocument $doc
     */
    public function recalculate(BusinessDocument &$doc)
    {
        $lines = $doc->getLines();
        $count = count($lines);
        if ($count == 0) {
            return;
        }

        $percentage = 0.00;
        foreach ($lines as $row) {
            $this->updateBusinessDocumentLine($doc, $row);
            $percentage += $row->porcomision;
        }

        $doc->porcomision = round($percentage / $count, 2);
        $doc->save();
    }

    /**
     * Update commission sale of a document line
     *
     * @param BusinessDocument $doc
     * @param BusinessDocumentLine $line
     * @return bool
     */
    private function updateBusinessDocumentLine(BusinessDocument &$doc, BusinessDocumentLine &$line)
    {
        $line->porcomision = $this->getCommision($doc, $line);
        return $line->save();
    }

    /**
     * Get the commission percentage for the sale line according to:
     *   - agent
     *   - customer
     *   - family
     *   - product
     *
     * @param BusinessDocument $doc
     * @param BusinessDocumentLine $line
     */
    protected function getCommision(BusinessDocument &$doc, BusinessDocumentLine &$line)
    {
        $where = [
            new DataBaseWhere('codagente', $doc->codagente),
            new DataBaseWhere('codagente', null, 'IS', 'OR'),

            new DataBaseWhere('codcliente', $doc->codcliente),
            new DataBaseWhere('codcliente', null, 'IS', 'OR'),

            new DataBaseWhere('codfamilia', $line->codfamilia),
            new DataBaseWhere('codfamilia', null, 'IS', 'OR'),

            new DataBaseWhere('referencia', $line->referencia),
            new DataBaseWhere('referencia', null, 'IS', 'OR'),
        ];

        $orderby = [
            'referencia' => 'ASC',
            'codfamilia' => 'ASC',
            'codcliente' => 'ASC',
            'codagente' => 'ASC'
        ];

        $this->commission->loadFromCode('', $where, $orderby);
        return $this->commission->porcentaje;
    }
}