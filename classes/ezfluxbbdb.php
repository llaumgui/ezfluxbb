<?php
/*
 * #################### BEGIN LICENSE BLOCK ####################
 * This file is part of eZFluxBB.
 * Copyright (c) 2007 Guillaume Kulakowski and contributors. All
 * rights reserved.
 *
 * eZFluxBB is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * eZFluxBB is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See
 * the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public 
 * License along with ezipb; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA  02111-1307  USA
 * or visit http://www.gnu.org/licenses/gpl.html
 * ###################### END LICENSE BLOCK ####################
 *
 * Fonction de connection à la base de données du forum FluxBB.
 * 
 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 * @version 1.0
 */

class eZFluxBBDB
{
	
	/**
	 * Fonction d'instanciation
	 *
	 * @return instance DB
	 */
	static function instance()
	{
		/* On remarque la pauvretée de la fonction... Pour le moment, eZ et FluxBB doivent
		être sur la même base. Mais ça ouvre des perspective... Quoi que le construct de ezdb est private */
		return eZDB::instance();
	}
}

 ?>