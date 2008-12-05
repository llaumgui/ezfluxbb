<?php
//
// Definition of eZFluxBB13 class
//
// Created on: <01-Sep-2008 19:00:00 bf>
//
// SOFTWARE NAME: eZFluxBB
// SOFTWARE RELEASE: 1.0
// BUILD VERSION:
// COPYRIGHT NOTICE: Copyright (c) 2008 Guillaume Kulakowski and contributors
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//


/*! \file ezfluxbb13.php
*/

/*!
  \class eZFluxBB13 ezfluxbb13.php
  \brief Fonction FluxBB dans eZ Publish. Fonction propre à la version 1.3 de FluxBB.
  \TODO No comment...
 */
class eZFluxBB13 extends eZFluxBB
{

    /*!
     Récupération des informations sur l'utilisateurs courant

     \return array
     */
    public function getCurrentUserInfo()
    {
        /*if ( !array_key_exists( 'id', $this->fluxBBUser ) )
        {
            $this->checkCookie( $this->fluxBBUser );
        }*/

        return;
    }
}

?>