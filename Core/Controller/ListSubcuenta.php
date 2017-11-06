<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2013-2017  Carlos Garcia Gomez  <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Controller;

use FacturaScripts\Core\Base\ExtendedController;

/**
 * Controlador para la lista de Subcuentas
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 * @author Artex Trading sa <jcuello@artextrading.com>
 */
class ListSubcuenta extends ExtendedController\ListController
{

    /**
     * Devuelve los datos básicos de la página
     *
     * @return array
     */
    public function getPageData()
    {
        $pagedata = parent::getPageData();
        $pagedata['title'] = 'subaccounts';
        $pagedata['icon'] = 'fa-th-list';
        $pagedata['menu'] = 'accounting';

        return $pagedata;
    }

    /**
     * Procedimiento encargado de insertar las vistas a visualizar
     */
    protected function createViews()
    {
        $this->addView('FacturaScripts\Core\Model\Subcuenta', 'ListSubcuenta');
        $this->addSearchFields('ListSubcuenta', ['codsubcuenta', 'descripcion']);

        $this->addFilterSelect('ListSubcuenta', 'codejercicio', 'ejercicios', '', 'nombre');

        $this->addOrderBy('ListSubcuenta', 'codejercicio desc, codsubcuenta', 'code');
        $this->addOrderBy('ListSubcuenta', 'codejercicio desc, descripcion', 'description');
    }
}