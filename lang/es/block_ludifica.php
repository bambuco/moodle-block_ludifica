<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Strings for component 'block_ludifica', language 'es'
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Ludifica';

// Capabilities.
$string['ludifica:addinstance'] = 'Adicionar un nuevo bloque de Ludifica';
$string['ludifica:myaddinstance'] = 'Adicionar un nuevo bloque de Ludifica a Dashboard';
$string['ludifica:manage'] = 'Manage Ludifica block settings';

$string['privacy:metadata'] = 'El bloque de Ludifica no almacena datos personales de los usuarios.';

$string['available'] = 'Total disponible';
$string['availabledate'] = 'Hasta';
$string['avatar'] = 'Avatar';
$string['avatars'] = 'Avatares';
$string['avatarbust'] = 'Busto';
$string['avatarbuy'] = 'Comprar';
$string['avatarbuymessage'] = '¿Realmente desea comprar este avatar?';
$string['avatardelete'] = 'Borrar avatar';
$string['avatarnew'] = 'Nuevo avatar';
$string['avatarnotuse'] = 'No puede usar este avatar';
$string['avatarsources'] = 'Orígenes';
$string['avatarsources_help'] = 'Lista de URL de imágenes. Una por línea. La línea 0 es para el nivel 0 e igual para los otros niveles.
Puede usar las siguientes etiquetas dinámicas en la URL: {name}, {level} y {levelname}.';
$string['avatartype'] = 'Tipo';
$string['avatartype_normal'] = 'Normal';
$string['avatartype_user'] = 'Para usuario específico';
$string['avataruse'] = 'Usar el avatar';
$string['avatarused'] = 'Avatar asignado';
$string['avatarusemessage'] = '¿Realmente desea cambiar el avatar?';
$string['bought'] = 'Comprado';
$string['buy'] = 'Comprar';
$string['buyticket'] = 'Comprar beneficio';
$string['buyticketmessage'] = '¿Realmente desea comprar este beneficio?';
$string['changessaved'] = 'Cambios guardados';
$string['coinsbypoints'] = 'Monedas por puntos';
$string['coinsbypoints_help'] = '';
$string['cost'] = 'Costo';
$string['customtitle'] = 'Título personalizado';
$string['defaultlevel'] = 'Nivel por defecto';
$string['deleted'] = 'Registro borrado satisfactoriamente';
$string['durationfield'] = 'Campo Duración';
$string['durationfield_help'] = 'El nombre del campo para cargar la duración del curso.';
$string['edit'] = 'Editar';
$string['editnickname'] = 'Editar el alias';
$string['enabled'] = 'Activado';
$string['errornotavatardata'] = 'No hay datos del avatar';
$string['errornotticketdata'] = 'No hay datos del beneficio';
$string['eventavatar_created'] = 'Avatar creado';
$string['eventavatar_deleted'] = 'Avatar borrado';
$string['eventavatar_updated'] = 'Avatar actualizado';
$string['eventticket_created'] = 'Beneficio creado';
$string['eventticket_updated'] = 'Beneficio actualizado';
$string['eventticket_deleted'] = 'Beneficio borrado';
$string['generalstate'] = 'Estado general';
$string['generalsettings'] = 'General';
$string['gift'] = 'Regalo';
$string['give'] = 'Regalar';
$string['given'] = 'Beneficio regalado';
$string['giveticket'] = 'Regalar un beneficio';
$string['giveticketmessage'] = 'Elija el contacto destino';
$string['infodata'] = 'Datos relacionados (formato JSON)';
$string['labellevel'] = 'Nivel {$a}';
$string['levels'] = 'Niveles';
$string['levelrequired'] = 'Nivel <em>{$a}</em> requerido';
$string['levels_help'] = 'Un nivel por línea, con la estructura: Nombre del nivel|puntos máximos.<br />
Los puntos máximos en la última línea no son requeridos, en ese caso se asignan puntos ilimitados.<br />
El número de línea indica el orden del nivel.';
$string['moreinfo'] = 'Más información';
$string['maxtickets'] = 'Actualmente tiene la cantidad máxima de este beneficio.';
$string['missingfield'] = 'El campo es requerido';
$string['newblocktitle'] = 'Ludifica';
$string['newnickname'] = 'Nuevo valor para {$a}';
$string['newticket'] = 'Nuevo beneficio';
$string['nicknameexists'] = 'El alias ya está siendo usado por otro usuario, por favor elige otro alias.';
$string['nicknameunasined'] = 'Jugador {$a}';
$string['noavatars'] = 'Aún no hay avatares disponibles.';
$string['notavailable'] = 'No disponible para comprar.';
$string['notavailabledate'] = 'La fecha para comprar este beneficio ya pasó.';
$string['notbuy'] = 'Error al comprar';
$string['notcompliance'] = 'No cumple los requerimientos para comprar este beneficio.';
$string['notcontacts'] = 'No tiene contactos actualmente';
$string['notcostcompliance'] = 'Monedas insuficientes';
$string['notgive'] = 'Error regalando un beneficio';
$string['notickets'] = 'Aún no hay beneficios disponibles';
$string['nottopyet'] = 'Aún no hay información de posiciones';
$string['notusertickets'] = 'Todavía no has conseguido ningún beneficio';
$string['numcoins'] = '{$a} monedas';
$string['numpoints'] = '{$a} puntos';
$string['owner'] = 'Dueño';
$string['playerhead'] = 'Jugador';
$string['pointshead'] = 'Puntos';
$string['pointsbyendcourse'] = 'Puntos por completar un curso';
$string['pointsbyendcourse_help'] = '';
$string['pointsbyrecurrentlogin1'] = 'Puntos por autenticación recurrente';
$string['pointsbyrecurrentlogin1_help'] = '';
$string['pointsbyrecurrentlogin2'] = 'Puntos adicionales por día';
$string['pointsbyrecurrentlogin2_help'] = 'Puntos que se asignan los días siguientes cuando se ha cumplido los días mínimos de autenticación recurrente.';
$string['pointstocoins'] = 'Puntos para dar monedas';
$string['pointstocoins_help'] = '';
$string['positionhead'] = 'Pos';
$string['recurrentlogindays'] = 'Días de autenticación recurrente';
$string['recurrentlogindays_help'] = 'Días de autenticación continua para iniciar la asignación de puntos. 0 para no usar esta característica.';
$string['requiretext'] = 'Prerequisitos';
$string['searchticket'] = 'Buscar';
$string['store'] = 'Tienda';
$string['tablastmonth'] = 'Pestaña Posiciones en el último mes';
$string['tabprofile'] = 'Pestaña Perfil';
$string['tabtitle_lastmonth'] = 'Posiciones en el último mes';
$string['tabtitle_profile'] = 'Perfil';
$string['tabtitle_topbycourse'] = 'Posiciones en el curso';
$string['tabtitle_topbysite'] = 'Posiciones en el sitio';
$string['tabtopbycourse'] = 'Pestaña Posiciones en el curso';
$string['tabtopbysite'] = 'Pestaña Posiciones en el sitio';
$string['ticket'] = 'Beneficio';
$string['ticketdelete'] = 'Borrar beneficio';
$string['tickets'] = 'Beneficios';
$string['ticketstype_default'] = 'Por defecto';
$string['ticketstype'] = 'Tipo';
$string['ticketcode'] = 'Código';
$string['ticketcode_help'] = 'Si está vacío el sistema asigna un código aleatorio a cada usuario.';
$string['ticketavailabledate'] = 'Fecha disponible';
$string['ticketavailable'] = 'Cantidad disponible';
$string['ticketnotavailable'] = 'The beneficio no está disponible ya, quizá fue usado en otra sesión.';
$string['ticketbyuser'] = 'Cantidad máxima por usuario';
$string['ticketsvalidate'] = 'Validar beneficio';
$string['ticketused'] = 'Beneficio usado';
$string['thumbnail'] = 'Miniatura';
$string['unlimited'] = 'Ilimitado';
$string['use'] = 'Usar';
$string['usedat'] = 'Usado el {$a}';
$string['useddate'] = 'Fecha de uso';
$string['usercode'] = 'Código del usuario';
$string['usercodes'] = 'Códigos del usuario';
$string['usertickets'] = 'Beneficios del usuario';
$string['useup'] = 'Usar';
