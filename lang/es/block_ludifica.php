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

$string['allbadges'] = 'Ver todos';
$string['available'] = 'Total disponible';
$string['availabledate'] = 'Hasta';
$string['avatar'] = 'Avatar';
$string['avatars'] = 'Avatares';
$string['avatarbust'] = 'Busto';
$string['avatarbuy'] = 'Comprar';
$string['avatarbuymessage'] = '¿Realmente desea comprar este avatar?';
$string['avatardelete'] = 'Borrar avatar';
$string['avatar_help'] = 'Acá podrás adquirir un avatar, algunos son gratuitos y otros se pueden comprar usando las monedas ganadas. Para usar un avatar que hayas adquirido debes dar clic en el botón Usar, este te identificará en tu área personal.';
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
$string['badges'] = 'Logros';
$string['badges_help'] = '¡Obtén logros y compartelos en tus redes sociales!';
$string['badge_info'] = 'Acerca de este logro';
$string['badge_copy'] = 'Copiar enlace';
$string['badge_expire'] = 'Expira: ';
$string['badge_facebook'] = 'Compartir en Facebook';
$string['badgemanage'] = 'Gestionar insignias';
$string['badgelinkcopiedtoclipboard'] = 'El enlace se ha copiado al portapapeles';
$string['badge_linkedin'] = 'Compatir como certificado en Linkedin';
$string['badge_linkedin_bio'] = 'Compatir como publicación en Linkedin';
$string['badge_share'] = 'Compatir';
$string['badge_share_title'] = '¡Comparte tu logro!';
$string['badge_twitter'] = 'Compatir en Twitter';
$string['benefits'] = 'Beneficios';
$string['bought'] = 'Comprado';
$string['buy'] = 'Comprar';
$string['buyticket'] = 'Comprar beneficio';
$string['buyticketmessage'] = '¿Realmente desea comprar este beneficio?';
$string['changessaved'] = 'Cambios guardados';
$string['criteria_emisor'] = 'Emisor: ';
$string['coinsbypoints'] = 'Monedas por puntos';
$string['coinsbypoints_help'] = '';
$string['configheader_modules'] = 'Puntos por finalizar módulos del curso';
$string['configmodules_help'] = 'Asigne puntos a las actividades que deben ser consideradas.';
$string['cost'] = 'Costo';
$string['course-ranking_help'] = '¡Gana puntos y posiciónate en el ranking del curso!';
$string['currentlevel'] = 'Tu nivel actual es <strong>{$a}</strong>';
$string['customtitle'] = 'Título personalizado';
$string['defaultlevel'] = 'Nivel por defecto';
$string['deleted'] = 'Registro borrado satisfactoriamente';
$string['durationfield'] = 'Campo Duración';
$string['durationfield_help'] = 'El nombre del campo para cargar la duración del curso.';
$string['dynamichelps'] = 'Pestaña Ayuda';
$string['dynamic_help-coinsbypoints'] = '<strong>{$a} monedas</strong>.';
$string['dynamic_help-noactivities'] = 'No hay actividades que asignen puntos en este curso.';
$string['dynamic_help-pointsbyendcourse'] = 'Gana <strong>{$a} puntos</strong> por finalizar un curso.';
$string['dynamic_help-pointsbyendcourseduration_site'] = 'Gana <strong>{$a}*X puntos</strong> al finalizar un curso, donde <strong>X</strong> es la duración recomendada del curso.';
$string['dynamic_help-pointsbyendcourseduration'] = 'Gana <strong>{$a} puntos</strong> al finalizar este curso.';
$string['dynamic_help-pointsrecurrentlogin'] = 'y ganar <strong>{$a} puntos</strong>.';
$string['dynamic_help-pointsbyday'] = '¡Cuida tu racha! después de que inicies una, ganarás <strong>{$a} puntos cada día</strong>.';
$string['dynamic_help-pointstocoins'] = 'Cada que consigas <strong>{$a} puntos</strong> se te otorgarán ';
$string['dynamic_help-pointsbyendmodule'] = 'Obtén <strong>{$a} puntos</strong> por cada recurso que finalices en este curso.';
$string['dynamic_help-pointsbymodule'] = '{$a} puntos';
$string['dynamic_help-recurrentlogindays'] = 'Ingresa <strong>{$a} días</strong> para iniciar una racha ';
$string['dynamic_help_title'] = 'Obtén puntos por los siguientes criterios';
$string['edit'] = 'Editar';
$string['editnickname'] = 'Editar el alias';
$string['emailinvalidpattern'] = 'Regla para correo inválido';
$string['emailinvalidpattern_help'] = 'Para que al cambiar el correo un usuario obtenga puntos, el nuevo correo <strong>no puede coincidir</strong> con el patrón regular que acá se configure. Por ejemplo: con el patrón <em>@([^@]*\\\\.)?(pruebas\\\\.mail)</em> no obtendrán puntos quienes definan un correo <em>@pruebas.mail</em>.';
$string['emailvalidpattern'] = 'Regla para correo válido';
$string['emailvalidpattern_help'] = 'Para que al cambiar el correo un usuario obtenga puntos, el nuevo correo <strong>debe cumplir</strong> con el patrón regular que acá se configure. Por ejemplo: con el patrón <em>@([^@]*\\\\.)?(pruebas\\\\.mail)</em> solamente obtendrán puntos quienes definan un correo <em>@pruebas.mail</em>.';
$string['enabled'] = 'Activado';
$string['errornotavatardata'] = 'No hay datos del avatar';
$string['errornotticketdata'] = 'No hay datos del beneficio';
$string['eventavatar_created'] = 'Avatar creado';
$string['eventavatar_deleted'] = 'Avatar borrado';
$string['eventavatar_updated'] = 'Avatar actualizado';
$string['eventcriteria_created'] = 'Mejora de criterio creado';
$string['eventcriteria_updated'] = 'Mejora de criterio actualizado';
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
$string['helptitle_pointsbycoursemodule'] = 'Este curso da puntos por completar los siguientes recursos:';
$string['hideranking'] = 'Ocultar';
$string['home'] = 'Inicio';
$string['improvecriteria'] = 'Mejora de criterio para insignia';
$string['improvecriteria_ncourses'] = 'Finalización de N cursos';
$string['improvecriteria_ncourses_label'] = 'Finalización de {$a} cursos';
$string['improvecriteria_ncourses_n'] = 'Valor de N';
$string['improvecriteria_ncourses_n_help'] = 'Número de cursos finalizados requerido para aplicar el criterio de asignación de la insignia.';
$string['improvecriterianot'] = 'La insignia no tiene mejora de criterios';
$string['improvecriteriaudpate'] = 'Criterio de insignia actualizado';
$string['improvecriteriasaved'] = 'Criteria de insignia guardado';
$string['infodata'] = 'Datos relacionados (formato JSON)';
$string['invalidbadgecriteriatype'] = "Tipo de criterio de insignia no válido. Sólo de tipo cohorte están disponibles.";
$string['labellevel'] = 'Nivel {$a}';
$string['lastmonth-ranking_help'] = 'Listado de los mejores jugadores del mes actual.';
$string['levels'] = 'Niveles';
$string['level_help'] = '¡Acumula puntos para que tu avatar pase al siguiente nivel!';
$string['levelrequired'] = 'Nivel <em>{$a}</em> requerido';
$string['levels_help'] = 'Un nivel por línea, con la estructura: Nombre del nivel|puntos máximos.<br />
Los puntos máximos en la última línea no son requeridos, en ese caso se asignan puntos ilimitados.<br />
El número de línea indica el orden del nivel.';
$string['moreinfo'] = 'Más información';
$string['maxtickets'] = 'Actualmente tiene la cantidad máxima de este beneficio.';
$string['missingfield'] = 'El campo es requerido';
$string['name_help'] = 'Personaliza el nombre que aparece en tu inicio dando clic sobre el nombre. Una vez finalices presiona la tecla Enter para guardar los cambios. El nombre que definas aparecerá en la tabla de posiciones del sitio.';
$string['newblocktitle'] = 'Ludifica';
$string['newnickname'] = 'Nuevo valor para {$a}';
$string['newticket'] = 'Nuevo beneficio';
$string['nicknameexists'] = 'El alias ya está siendo usado por otro usuario, por favor elige otro alias.';
$string['nicknameunasined'] = 'Jugador {$a}';
$string['notuserbadges'] = 'Todavía no tienes logros.';
$string['noavatars'] = 'Aún no hay avatares disponibles.';
$string['notavailable'] = 'No disponible para comprar.';
$string['notavailabledate'] = 'La fecha para comprar este beneficio ya pasó.';
$string['notbuy'] = 'Error al comprar';
$string['notcompliance'] = 'No cumple los requerimientos para comprar este beneficio.';
$string['notcontacts'] = 'No tiene contactos actualmente';
$string['notcostcompliance'] = 'Monedas insuficientes';
$string['notenabledbadge'] = 'Aún no has obtenido esta insignia';
$string['notgive'] = 'Error regalando un beneficio';
$string['notickets'] = 'Aún no hay beneficios disponibles';
$string['nottopyet'] = 'Aún no hay información de posiciones';
$string['notusertickets'] = 'Todavía no has conseguido ningún beneficio';
$string['numcoins'] = '{$a} monedas';
$string['numpoints'] = '{$a} puntos';
$string['levelup'] = '¡Sube de nivel!';
$string['overcomelevel'] = 'Obten <strong>{$a->maxpoints} puntos</strong> para alcanzar el nivel <strong>{$a->name}</strong>.';
$string['owner'] = 'Dueño';
$string['playerhead'] = 'Jugador';
$string['pointshead'] = 'Puntos';
$string['pointsbychangemail'] = 'Puntos por actualizar el correo';
$string['pointsbychangemail_help'] = 'Puntos asignados al usuario cuando modifica su perfil y establece una dirección de correo válida.
Solamente se da puntos una vez por usuario.
Si no se configura ninguna regla, se dará puntos por este concepto sin importar el nuevo correo.
Se pueden configurar las dos o una única regla (válido o no válido) y se darán puntos por este concepto si el correo cumple con la regla configurada.';
$string['pointsbyendcourse'] = 'Puntos por completar un curso';
$string['pointsbyendcourse_help'] = '';
$string['pointsbyendcoursemodule'] = 'Puntos por completar un módulo';
$string['pointsbyendcoursemodule_help'] = 'Sólo aplica si la opción -Todos los módulos dan puntos- es afirmativa. En caso contrario, se evaluará la configuración en la instancia del bloque en cada curso';
$string['pointsbyendallmodules'] = 'Todos los módulos dan puntos';
$string['pointsbyendallmodules_help'] = 'Si es afirmativo no se tendrá en cuenta la configuración particular dentro de cada curso y se aplicará siempre la puntuación general.';
$string['pointsbynewuser'] = 'Puntos para cada nuevo usuario';
$string['pointsbynewuser_help'] = '';
$string['pointsbyrecurrentlogin1'] = 'Puntos por autenticación recurrente';
$string['pointsbyrecurrentlogin1_help'] = '';
$string['pointsbyrecurrentlogin2'] = 'Puntos adicionales por día';
$string['pointsbyrecurrentlogin2_help'] = 'Puntos que se asignan los días siguientes cuando se ha cumplido los días mínimos de autenticación recurrente.';
$string['points_help'] = 'A medida que ganes puntos se te irán otorgando monedas. ¡Úsalas en la tienda!';
$string['pointstocoins'] = 'Puntos para dar monedas';
$string['pointstocoins_help'] = '';
$string['positionhead'] = '#';
$string['ranking_button'] = 'Ranking';
$string['ranking_course'] = 'Curso';
$string['ranking_last'] = 'Último mes';
$string['ranking_site'] = 'Sitio';
$string['recorddeleted'] = 'Registro eliminado';
$string['recurrentlogindays'] = 'Días de autenticación recurrente';
$string['recurrentlogindays_help'] = 'Días de autenticación continua para iniciar la asignación de puntos. 0 para no usar esta característica.';
$string['requiretext'] = 'Prerequisitos';
$string['searchticket'] = 'Buscar';
$string['socialnetworks'] = 'Redes sociales para compartir logros';
$string['socialnetworks_help'] = '<em>Una red social por línea con la siguiente estructura:</em><br /><br />
twitter|https://twitter.com/intent/tweet?text={name}&url={url}<br /><br />
El nombre de la red social debe ser en minúscula.<br /><br />
Adicionalmente, si quieres compartir un logro como certificado de LinkedIn, puedes usar la siguiente estructura:<br /><br />
linkedin|https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name={name}&<strong>organizationId=<em>ID DE LA ORGANIZACIÓN</strong></em>&issueYear={badgeyear}&issueMonth={badgemonth}&certUrl={url}&certId={badgeid}&credentialDoesNotExpire=1<br /><br />
<em><strong>organizationId</strong></em> es el único parametro del enlace que necesitas editar, este es el ID del perfil de LinkedIn de la organización que emite el certificado, mira cómo obtenerlo <a href="https://bambuco.co/ludifica/ludifica-configuracion-general/#network" target="_blank">aquí</a>.<br /><br />
<em>No es necesario que incluyas todas las llaves o parámetros.</em>';
$string['socialnetworks_default'] = 'facebook|https://www.facebook.com/sharer/sharer.php?u={url}
twitter|https://twitter.com/intent/tweet?text={name}&url={url}
linkedin|https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name={name}&organizationId=ID DE LA ORGANIZACIÓN&issueYear={badgeyear}&issueMonth={badgemonth}&certUrl={url}&certId={badgeid}&credentialDoesNotExpire=1';
$string['settingsheaderpointscourse'] = 'Al finalizar un curso';
$string['settingsheaderpointslogin'] = 'Puntos por autenticación';
$string['settingsheaderpointsmodules'] = 'Puntos por módulos';
$string['settingsheaderpointsother'] = 'Otros puntos';
$string['settingsheadercoins'] = 'Monedas';
$string['settingsheaderlevels'] = 'Niveles';
$string['settingsheaderappearance'] = 'Apariencia';
$string['settingsheaderbadges'] = 'Logros';
$string['showicon'] = 'Solo iconos';
$string['showranking'] = 'Ver ranking';
$string['showtext'] = 'Solo texto';
$string['showtopbycourse'] = 'Top curso ►';
$string['showtopbysite'] = 'Top sitio ►';
$string['showlastmonth'] = 'Top último mes ►';
$string['site-ranking_help'] = '¡Gana puntos y posiciónate en el ranking del sitio!';
$string['benefits'] = 'Beneficios';
$string['tablastmonth'] = 'Pestaña Posiciones en el último mes';
$string['tabprofile'] = 'Pestaña Perfil';
$string['tabprofile_help'] = 'Todas las pestañas se habilitan si eliges esta opción.';
$string['tabtitle_lastmonth'] = 'Top último mes';
$string['tabtitle_profile'] = 'Perfil';
$string['tabtitle_topbycourse'] = 'Top curso';
$string['tabtitle_topbysite'] = 'Top sitio';
$string['tabtitle_dynamichelps'] = 'Ayuda';
$string['tabtopbycourse'] = 'Pestaña Posiciones en el curso';
$string['tabtopbysite'] = 'Pestaña Posiciones en el sitio';
$string['tabview'] = 'Vista de pestaña';
$string['tabview_help'] = 'Elige el diseño de las pestañas cuando hay disponible más de una vista';
$string['templatetype'] = 'Tema';
$string['templatetype_help'] = 'Elige un tema para cambiar el aspecto del bloque.';
$string['textandicon'] = 'Iconos y texto';
$string['ticket'] = 'Beneficio';
$string['ticketdelete'] = 'Borrar beneficio';
$string['tickets'] = 'Beneficios';
$string['ticketstype_default'] = 'Por defecto';
$string['ticketstype'] = 'Tipo';
$string['ticketcode'] = 'Código';
$string['ticketcode_help'] = 'Si está vacío el sistema asigna un código aleatorio a cada usuario.';
$string['ticketavailabledate'] = 'Fecha disponible';
$string['ticketavailable'] = 'Cantidad disponible';
$string['tickets_help'] = 'Adquiere un beneficio usando las monedas ganadas, estos pueden ser incentivos virtuales o del mundo real. El beneficio lo puedes conservar para uso personal o si lo deseas, puedes compartirlo con una persona de tu lista de contactos.';
$string['ticketnotavailable'] = 'The beneficio no está disponible ya, quizá fue usado en otra sesión.';
$string['ticketbyuser'] = 'Cantidad máxima por usuario';
$string['ticketsvalidate'] = 'Validar beneficio';
$string['ticketused'] = 'Beneficio usado';
$string['thumbnail'] = 'Miniatura';
$string['unlimited'] = 'Ilimitado';
$string['unavailablewarning'] = 'Todavía no has obtenido este logro.';
$string['use'] = 'Usar';
$string['usedat'] = 'Usado el {$a}';
$string['useddate'] = 'Fecha de uso';
$string['usercode'] = 'Código del usuario';
$string['usercodes'] = 'Códigos del usuario';
$string['usertickets'] = 'Beneficios del usuario';
$string['useup'] = 'Usar';
