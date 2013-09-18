<?php
/**
 * Internationalisation file for extension Scribunto.
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

/** English
 * @author Victor Vasiliev
 */
$messages['en'] = array(
	'scribunto-desc' => 'Framework for embedding scripting languages into MediaWiki pages',
	'scribunto-ignore-errors' => 'Allow saving code with errors',
	'scribunto-line' => 'at line $1',
	'scribunto-module-line' => 'in $1 at line $2',
	'scribunto-parser-error' => 'Script error',
	'scribunto-parser-dialog-title' => 'Script error',
	'scribunto-error-short' => 'Script error: $1',
	'scribunto-error-long' => 'Script errors:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Documentation for this module may be created at [[$1]]''",
	'scribunto-doc-page-show' => '{{$1}}
<hr />',
	'scribunto-doc-page-header' => "'''This is the documentation page for [[$1]]'''",

	'scribunto-console-intro' => '* The module exports are available as the variable "p", including unsaved modifications.
* Precede a line with "=" to evaluate it as an expression, or use print().
* Use mw.log() in module code to send messages to this console.',
	'scribunto-console-title' => 'Debug console',
	'scribunto-console-too-large' => 'This console session is too large. Please clear the console history or reduce the size of the module.',
	'scribunto-console-current-src' => 'console input',
	'scribunto-console-clear' => 'Clear',
	'scribunto-console-cleared' => 'The console state was cleared because the module was updated.',
	'scribunto-console-cleared-session-lost' => 'The console state was cleared because the session data was lost.',


	'scribunto-common-error-category' => 'Pages with script errors',
	'scribunto-common-nosuchmodule' => 'Script error: No such module.',
	'scribunto-common-nofunction' => 'Script error: You must specify a function to call.',
	'scribunto-common-nosuchfunction' => 'Script error: The function you specified did not exist.',
	'scribunto-common-timeout' => 'The time allocated for running scripts has expired.',
	'scribunto-common-oom' => 'The amount of memory allowed for running scripts has been exceeded.',
	'scribunto-common-backtrace' => 'Backtrace:',
	'scribunto-lua-in-function' => 'in function "$1"',
	'scribunto-lua-in-main' => 'in main chunk',
	'scribunto-lua-in-function-at' => 'in the function at $1:$2',
	'scribunto-lua-backtrace-line' => '$1: $2',
	'scribunto-lua-error-location' => 'Lua error $1: $2.',
	'scribunto-lua-error' => 'Lua error: $2.',
	'scribunto-lua-noreturn' => 'Script error: The module did not return a value. It is supposed to return an export table.',
	'scribunto-lua-notarrayreturn' => 'Script error: The module returned something other than a table. It is supposed to return an export table.',
	'scribunto-luastandalone-proc-error' => 'Lua error: Cannot create process.',
	'scribunto-luastandalone-proc-error-msg' => 'Lua error: Cannot create process: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Lua error: Cannot create process: proc_open is not available. Check PHP\'s "disable_functions" configuration directive.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Lua error: Cannot create process. Note that PHP\'s deprecated "safe_mode" configuration directive is enabled.',
	'scribunto-luastandalone-decode-error' => 'Lua error: Internal error: Unable to decode message.',
	'scribunto-luastandalone-write-error' => 'Lua error: Internal error: Error writing to pipe.',
	'scribunto-luastandalone-read-error' => 'Lua error: Internal error: Error reading from pipe.',
	'scribunto-luastandalone-gone' => 'Lua error: Internal error: The interpreter has already exited.',
	'scribunto-luastandalone-signal' => 'Lua error: Internal error: The interpreter has terminated with signal "$2".',
	'scribunto-luastandalone-exited' => 'Lua error: Internal error: The interpreter exited with status $2.',
);

/** Message documentation (Message documentation)
 * @author Amire80
 * @author Anomie
 * @author Gomada
 * @author Mormegil
 * @author Shirayuki
 * @author Siebrand
 * @author Snævar
 */
$messages['qqq'] = array(
	'scribunto-desc' => '{{desc|name=Scribunto|url=http://www.mediawiki.org/wiki/Extension:Scribunto}}',
	'scribunto-ignore-errors' => 'Label for a checkbox on the edit page. When clicked, parse errors are ignored on save.',
	'scribunto-line' => 'Reference to a code location. Parameters:
* $1 is a line number.',
	'scribunto-module-line' => 'Reference to a code location. Parameters:
* $1 is a module (may also be {{msg-mw|Scribunto-console-current-src}});
* $2 is a line number.',
	'scribunto-parser-error' => 'Error message.',
	'scribunto-parser-dialog-title' => 'Error message.',
	'scribunto-error-short' => 'Error message. Parameters:
* $1 are the error details.',
	'scribunto-error-long' => 'Peyama çewtiyê. Parametre:
* $1 hûragahiyên çewtiyê ne.',
	'scribunto-doc-page-name' => '{{doc-important|Do not translate the namespace "Module:"}}
Page name for module documentation. Parameters:
* $1 - the unprefixed name of the module',
	'scribunto-doc-page-does-not-exist' => 'Message displayed if the documentation page does not exist. Parameters:
* $1 - the prefixed title of the doc page',
	'scribunto-doc-page-show' => 'Message displayed if the documentation page does exist. $1 is the prefixed title of the doc page. Should probably transclude that page.',
	'scribunto-doc-page-header' => 'Message displayed at the top of the documentation page. Parameters:
* $1 - the prefixed title of the module',
	'scribunto-console-intro' => 'An explanatory message shown to module programmers in the debug console, where they can run Lua commands and see how they work.

"Module exports" are the names that are exported. See the chapter [http://www.lua.org/pil/15.2.html Privacy] in the book "Programming in Lua".',
	'scribunto-console-title' => 'Legend for the debug console fieldset',
	'scribunto-console-too-large' => 'Error message displayed when the console history contains too much data.',
	'scribunto-console-current-src' => 'Name of the fictional Lua module created in the debugging console.

May appear e.g. in Lua error messages (like $1 in {{msg-mw|Scribunto-module-line}})',
	'scribunto-console-clear' => 'Used as button text which enables to clear the console.
{{Identical|Clear}}',
	'scribunto-console-cleared' => 'Message displayed in the console when the module source has been changed.',
	'scribunto-console-cleared-session-lost' => 'Message displayed in the console when the session has expired.',
	'scribunto-common-error-category' => 'Tracking category for pages with errors from #invoke',
	'scribunto-common-nosuchmodule' => 'Error message displayed when referencing a non-existing module.',
	'scribunto-common-nofunction' => 'Error message displayed when not specifying a function to call.',
	'scribunto-common-nosuchfunction' => 'Error message displayed when referencing a non-existing function.',
	'scribunto-common-timeout' => 'Error message displayed when script execution has passed a threshold.',
	'scribunto-common-oom' => 'Error message displayed when the script requires more memory than the threshold.',
	'scribunto-common-backtrace' => 'A backtrace is a list of the function calls that are currently active in a thread. This message is followed by a backtrace.',
	'scribunto-lua-in-function' => 'Reference to a function name. Parameters:
* $1 is a function name.',
	'scribunto-lua-in-main' => 'Part of the backtrace creation routines. Refers to the main part of the code.',
	'scribunto-lua-in-function-at' => 'Part of the backtrace creation routines. Parameters:
* $1 is a function name;
* $2 is a line number.',
	'scribunto-lua-backtrace-line' => '{{optional}}',
	'scribunto-lua-error-location' => 'Error message when module and line are given. Parameters:
* $1 - code line from {{msg-mw|Scribunto-line}} or {{msg-mw|Scribunto-module-line}}
* $2 - error message, not localized. (e.g. "too many language codes requested")',
	'scribunto-lua-error' => 'Error message. Parameters:
* $1 - (Unused)
* $2 - error message, not localized. (e.g. "too many language codes requested")',
	'scribunto-lua-noreturn' => 'Error message.',
	'scribunto-lua-notarrayreturn' => 'Error message.',
	'scribunto-luastandalone-proc-error' => 'Exception message.',
	'scribunto-luastandalone-proc-error-msg' => 'Exception message. Parameters:
* $1 - (Unused)
* $2 - Warning/error text from PHP',
	'scribunto-luastandalone-proc-error-proc-open' => "Exception message displayed when PHP's proc_open function is not available, which is needed by the LuaStandalone engine.",
	'scribunto-luastandalone-proc-error-safe-mode' => 'Exception message displayed when PHP\'s "safe_mode" configuration directive is enabled.',
	'scribunto-luastandalone-decode-error' => 'Exception message.',
	'scribunto-luastandalone-write-error' => 'Exception message.',
	'scribunto-luastandalone-read-error' => 'Exception message.',
	'scribunto-luastandalone-gone' => 'Exception message.',
	'scribunto-luastandalone-signal' => 'Exception message. Parameters:
* $1 - (Unused)
* $2 - an exit status (may also be a signal name)',
	'scribunto-luastandalone-exited' => 'Exception message. Parameters:
* $1 - (Unused)
* $2 - an exit status',
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'scribunto-line' => 'op reël $1',
	'scribunto-module-line' => 'in $1 op reël $2',
	'scribunto-parser-dialog-title' => 'Skripfout',
	'scribunto-error-short' => 'Skripfout: $1',
	'scribunto-error-long' => 'Skripfoute:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Dokumentasie vir hierdie module kan geskep word by: [[$1]]''",
	'scribunto-doc-page-header' => "'''Hierdie is die dokumentasieblad vir [[$1]]'''",
	'scribunto-common-error-category' => 'Bladsye met skripfoute',
	'scribunto-common-nosuchmodule' => 'Skripfout: die module bestaan nie.',
	'scribunto-lua-in-function' => 'in funksie "$1"',
	'scribunto-lua-in-main' => 'in die hoofgedeelte',
	'scribunto-lua-in-function-at' => 'in die funksie op $1:$2',
	'scribunto-lua-error-location' => 'Luafout $1: $2',
	'scribunto-lua-error' => 'Luafout: $2',
);

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'scribunto-desc' => 'Infraestructura pa incrustar llinguaxes de script nes páxines de MediaWiki',
	'scribunto-ignore-errors' => 'Permite guardar códigu con errores',
	'scribunto-line' => 'na llinia $1',
	'scribunto-module-line' => 'en $1 na llinia $2',
	'scribunto-parser-error' => 'Error de script',
	'scribunto-parser-dialog-title' => 'Error de script',
	'scribunto-error-short' => 'Error de script: $1',
	'scribunto-error-long' => 'Errores de script:

$1',
	'scribunto-doc-page-name' => 'Módulu:$1/usu',
	'scribunto-doc-page-does-not-exist' => "''La documentación pa esti módulu pue crease'n [[$1]]''",
	'scribunto-doc-page-header' => "'''Esta ye la páxina de documentación pa [[$1]]'''",
	'scribunto-console-intro' => '* Les esportaciones del módulu tan disponibles como la variable "p", incluyendo los cambios ensin guardar.
* Ponga un "=" delantre de la llinia pa evaluala como una espresión, o use print().
* Use mw.log() nel códigu del módulu pa unviar los mensaxes a esta consola.',
	'scribunto-console-title' => 'Consola de depuración',
	'scribunto-console-too-large' => "La sesión d'esta consola ye enforma grande. Llimpíe l'historial de la consola o reduza'l tamañu del módulu.",
	'scribunto-console-current-src' => 'entrada de la consola',
	'scribunto-console-clear' => 'Llimpiar',
	'scribunto-console-cleared' => "Llimpióse l'estáu de la consola porque'l módulu anovóse.",
	'scribunto-console-cleared-session-lost' => "Llimpióse l'estáu de la consola porque perdieronse los datos de la sesión.",
	'scribunto-common-error-category' => 'Páxines con errores de script',
	'scribunto-common-nosuchmodule' => 'Error de script: Nun esiste esi módulu.',
	'scribunto-common-nofunction' => "Error de script: Tien d'especificar una función a la que llamar.",
	'scribunto-common-nosuchfunction' => "Error de script: La función qu'especificó nun esiste.",
	'scribunto-common-timeout' => "Acabó'l tiempu acutáu pa executar scripts.",
	'scribunto-common-oom' => 'Superóse la cantidá de memoria permitida pa executar scripts.',
	'scribunto-common-backtrace' => 'Trazáu inversu (backtrace):',
	'scribunto-lua-in-function' => 'na función "$1"',
	'scribunto-lua-in-main' => 'nel fragmentu principal',
	'scribunto-lua-in-function-at' => 'na función en $1:$2',
	'scribunto-lua-error-location' => 'Error de Lua $1: $2.',
	'scribunto-lua-error' => 'Error de Lua: $2.',
	'scribunto-lua-noreturn' => "Error de script: El módulu nun devolvió nengún valor. Esperase que devuelva una tabla d'esportación.",
	'scribunto-lua-notarrayreturn' => "Error de script: El módulu devolvió una cosa distinta d'una tabla. Esperase que devuelva una tabla d'esportación.",
	'scribunto-luastandalone-proc-error' => "Error de Lua: Nun pue crease'l procesu.",
	'scribunto-luastandalone-proc-error-msg' => "Error de Lua: Nun pue crease'l procesu: $2",
	'scribunto-luastandalone-proc-error-proc-open' => 'Error de Lua: Nun pue crease\'l procesu: proc_open nun ta disponible. Compruebe la direutiva de configuración "disable_functions" de PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Error de Lua: Nun pue crese\'l procesu. Tenga en cuenta que la direutiva de configuración anticuada "safe_mode" de PHP ta activada.',
	'scribunto-luastandalone-decode-error' => "Error de Lua: Error internu: Nun pudo descodificase'l mensaxe.",
	'scribunto-luastandalone-write-error' => 'Error de Lua: Error internu: Error al escribir nun conductu (pipe).',
	'scribunto-luastandalone-read-error' => "Error de Lua: Error internu: Error al lleer d'un conductu (pipe).",
	'scribunto-luastandalone-gone' => "Error de Lua: Error internu: L'intérprete yá acabó.",
	'scribunto-luastandalone-signal' => 'Error de Lua: Error internu: L\'intérprete acabó cola señal "$2".',
	'scribunto-luastandalone-exited' => "Error de Lua: Error internu: L'intérprete acabó col estáu $2.",
);

/** Belarusian (Taraškievica orthography) (беларуская (тарашкевіца)‎)
 * @author Wizardist
 */
$messages['be-tarask'] = array(
	'scribunto-desc' => 'Фрэймворк для выкарыстаньня скрыптавых моваў на старонках MediaWiki',
	'scribunto-ignore-errors' => 'Дазволіць захаваньне коду з хібамі',
	'scribunto-line' => 'на радку $1',
	'scribunto-module-line' => 'у $1 на радку $2',
	'scribunto-parser-error' => 'Памылка скрыпту',
	'scribunto-parser-dialog-title' => 'Памылка скрыпту',
	'scribunto-error-short' => 'Памылка скрыпту: $1',
	'scribunto-error-long' => 'Памылкі скрыпту:

$1',
	'scribunto-doc-page-name' => 'Module:$1/Дакумэнтацыя',
	'scribunto-doc-page-does-not-exist' => "''Дакумэнтацыю да гэтага модуля можна стварыць у [[$1]]''",
	'scribunto-doc-page-header' => "''Гэта падстаронка-дакумэнтацыя для [[$1]]''",
	'scribunto-console-intro' => '* Экспартаваныя зьвесткі модуля даступныя празь зьменную «p», у тым ліку незахаваныя зьмены.
* Калі хочаце вылічыць радок як выраз, ужывайце перад радком знак «=», або выкарыстоўвайце print().
* Каб дасылаць паведамленьні ў гэтую кансоль, выкарыстоўвайце mw.log() у кодзе модуля.',
	'scribunto-console-title' => 'Кансоль адладкі',
	'scribunto-console-too-large' => 'Гэтая сэсія кансолі надта вялікая. Калі ласка, ачысьціце гісторыю кансолі ці паменшыце памер модуля.',
	'scribunto-console-current-src' => 'увод кансолі',
	'scribunto-console-clear' => 'Ачысьціць',
	'scribunto-console-cleared' => 'Стан кансолі ачышчаны праз абнаўленьне модуля.',
	'scribunto-console-cleared-session-lost' => 'Стан кансолі ачышчаны праз страту зьвестак сэсіі.',
	'scribunto-common-error-category' => 'Старонкі з памылкамі ў скрыптах',
	'scribunto-common-nosuchmodule' => 'Памылка скрыпта: модуль не існуе.',
	'scribunto-common-nofunction' => 'Памылка скрыпта: вы мусіце пазначыць функцыю, якую выклікаеце.',
	'scribunto-common-nosuchfunction' => 'Памылка скрыпта: пазначаная вамі функцыя не існуе.',
	'scribunto-common-timeout' => 'Час, адведзены пад выкананьне скрыптаў, выйшаў.',
	'scribunto-common-oom' => 'Перавышаная вобласьць памяці, адведзенай пад выкананьне скрыптаў.',
	'scribunto-common-backtrace' => 'Шлях выклікаў:',
	'scribunto-lua-in-function' => 'у функцыі «$1»',
	'scribunto-lua-in-main' => 'у асноўнай частцы коду',
	'scribunto-lua-in-function-at' => 'у функцыі $1:$2',
	'scribunto-lua-error-location' => 'Памылка Lua $1: $2.',
	'scribunto-lua-error' => 'Памылка Lua: $2.',
	'scribunto-lua-noreturn' => 'Памылка скрыпта: модуль не вярнуў значэньне. Модуль мусіў вярнуць табліцу экспарту.',
	'scribunto-lua-notarrayreturn' => 'Памылка скрыпта: модуль вярнуў значэньне, якое ня ёсьць табліцай экспарту. Модуль мусіў вярнуць табліцу экспарту.',
	'scribunto-luastandalone-proc-error' => 'Памылка Lua: немагчыма стварыць працэс.',
	'scribunto-luastandalone-proc-error-msg' => 'Памылка Lua: немагчыма стварыць працэс — $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Памылка Lua: немагчыма стварыць працэс — proc_open недаступны. Праверце значэньне дырэктывы «disable_functions» у наладах PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Памылка Lua: немагчыма стварыць працэс. Заўважце, што састарэлая дырэктыва наладаў PHP «safe_mode» уключаная.',
	'scribunto-luastandalone-decode-error' => 'Памылка Lua: Унутраная памылка: не атрымалася раскадаваць паведамленьне.',
	'scribunto-luastandalone-write-error' => 'Памылка Lua: Унутраная памылка: памылка запісу ў канвэер.',
	'scribunto-luastandalone-read-error' => 'Памылка Lua: Унутраная памылка: памылка чытаньня з канвэеру.',
	'scribunto-luastandalone-gone' => 'Памылка Lua: Унутраная памылка: інтэрпрэтатар ужо скончыў працу.',
	'scribunto-luastandalone-signal' => 'Памылка Lua: Унутраная памылка: інтэрпрэтатар быў спынены з сыгналам «$2».',
	'scribunto-luastandalone-exited' => 'Памылка Lua: Унутраная памылка: інтэрпрэтатар завяршыў працу з станам $2.',
);

/** Bulgarian (български)
 * @author DCLXVI
 * @author Termininja
 * @author පසිඳු කාවින්ද
 */
$messages['bg'] = array(
	'scribunto-ignore-errors' => 'Позволяване съхраняването на код с грешки',
	'scribunto-line' => 'на ред $1',
	'scribunto-module-line' => 'в $1 на ред $2',
	'scribunto-parser-error' => 'Грешка в скрипта',
	'scribunto-parser-dialog-title' => 'Грешка в скрипта',
	'scribunto-error-short' => 'Грешка в скрипта: $1',
	'scribunto-error-long' => 'Грешки в скрипта:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Документация за този модул може да бъде създадена на [[$1]]''",
	'scribunto-doc-page-header' => "'''Това е страница с документация за [[$1]]'''",
	'scribunto-console-title' => 'Конзола за отстраняване на грешки',
	'scribunto-console-clear' => 'Изчистване',
	'scribunto-common-error-category' => 'Страници с грешки в скрипта',
	'scribunto-common-nosuchmodule' => 'Грешка в скрипта: Няма такъв модул.',
	'scribunto-common-nofunction' => 'Грешка в скрипта: Необходимо е да се посочи име на функция.',
	'scribunto-common-nosuchfunction' => 'Грешка в скрипта: Посочената функция не съществува.',
	'scribunto-common-timeout' => 'Времето, отделено за изпълнение на скриптове, е изтекло.',
	'scribunto-common-oom' => 'Количеството памет, отделено за изпълнение на скриптове, е надвишено.',
	'scribunto-lua-in-function' => 'във функцията „$1“',
	'scribunto-lua-in-main' => 'в основната част от кода',
	'scribunto-lua-in-function-at' => 'във функцията $1:$2',
	'scribunto-lua-error-location' => 'Грешка в Lua $1: $2.',
	'scribunto-lua-error' => 'Грешка в Lua: $2.',
	'scribunto-luastandalone-proc-error' => 'Грешка в Lua: Не може да бъде създаден процес.',
	'scribunto-luastandalone-decode-error' => 'Грешка в Lua: Вътрешна грешка: Не може да се декодира съобщението.',
);

/** Bengali (বাংলা)
 * @author Aftab1995
 * @author Bellayet
 */
$messages['bn'] = array(
	'scribunto-ignore-errors' => 'ত্রুটিসহ কোড সংরক্ষণের অনুমতি দিন',
	'scribunto-line' => '$1নং লাইনে',
	'scribunto-module-line' => '$1 এর $2নং লাইনে',
	'scribunto-parser-error' => 'স্ক্রিপ্ট ত্রুটি',
	'scribunto-parser-dialog-title' => 'স্ক্রিপ্ট ত্রুটি',
	'scribunto-error-short' => 'স্ক্রিপ্ট ত্রুটি: $1',
	'scribunto-error-long' => 'স্ক্রিপ্ট ত্রুটি:

$1',
	'scribunto-doc-page-name' => 'Module:$1/নথি',
	'scribunto-doc-page-does-not-exist' => "''এই মডিউলের জন্য [[$1]]-এ নথিপত্র তৈরি করা হয়ে থাকতে পারে''",
	'scribunto-doc-page-header' => "'''এই নথির পাতাটি [[$1]]-এর জন্য'''",
	'scribunto-console-title' => 'ডিবাগ কনসোল',
	'scribunto-console-current-src' => 'কনসোল ইনপুট',
	'scribunto-console-clear' => 'পরিস্কার',
	'scribunto-common-error-category' => 'স্ক্রিপ্ট ত্রুটিসহ পাতা',
	'scribunto-common-nosuchmodule' => 'স্ক্রিপ্ট ত্রুটি: এমন মডিউল নেই।',
	'scribunto-lua-in-function' => '"$1" ফাংশনে',
	'scribunto-lua-error-location' => 'লুয়া ত্রুটি $1: $2।',
	'scribunto-lua-error' => 'লুয়া ত্রুটি: $2।',
	'scribunto-luastandalone-proc-error' => 'লুয়া ত্রুটি: প্রক্রিয়া তৈরি করতে পারবে না।',
	'scribunto-luastandalone-exited' => 'লুয়া ত্রুটি: অভ্যন্তরীণ ত্রুটি: প্রস্থানের সময় অনুবাদকের অবস্থা $2।',
);

/** Breton (brezhoneg)
 * @author Fohanno
 * @author Y-M D
 */
$messages['br'] = array(
	'scribunto-ignore-errors' => 'Aotren enrollañ kod gant fazioù',
	'scribunto-line' => "d'al linenn $1",
	'scribunto-module-line' => "e $1 d'al linenn $2",
	'scribunto-console-clear' => 'Riñsañ',
);

/** Bosnian (bosanski)
 * @author DzWiki
 */
$messages['bs'] = array(
	'scribunto-doc-page-name' => 'Module:$1/dok',
	'scribunto-doc-page-header' => "'''Ovo je stranica dokumentacije za [[$1]]'''",
	'scribunto-console-clear' => 'Očisti',
);

/** Catalan (català)
 * @author Toniher
 * @author Vriullop
 */
$messages['ca'] = array(
	'scribunto-desc' => "Un entorn de treball per a incrustar llenguatges d'script a les pàgines de MediaWiki",
	'scribunto-ignore-errors' => 'Permet desar codi amb errors',
	'scribunto-line' => 'a la línia $1',
	'scribunto-module-line' => 'a $1 a la línia $2',
	'scribunto-parser-error' => "Error de l'script",
	'scribunto-parser-dialog-title' => "Error de l'script",
	'scribunto-error-short' => "Error de l'script: $1",
	'scribunto-error-long' => "Errors de l'script:

$1",
	'scribunto-doc-page-name' => 'Module:$1/ús',
	'scribunto-doc-page-does-not-exist' => "''La documentació d'ús d'aquest mòdul es pot crear a [[$1]]''",
	'scribunto-doc-page-header' => "'''Aquesta és la pàgina de documentació per a [[$1]]'''",
	'scribunto-console-intro' => "* Les exportacions del mòdul són disponibles com a part de la variable «p», incloent-hi aquelles modificacions que no s'han desat.
* Escriviu «=» davant d'una línia per a avaluar-ho com a expressió, o bé feu servir print().
* Utilitzeu mw.log() al codi del mòdul per a enviar missatges a la consola.",
	'scribunto-console-title' => 'Consola de depuració',
	'scribunto-console-too-large' => "La sessió de la consola és massa gran. Netegeu l'historial de la consola o reduïu la mida del mòdul.",
	'scribunto-console-current-src' => 'entrada de la consola',
	'scribunto-console-clear' => 'Neteja',
	'scribunto-console-cleared' => "S'ha netejat l'estat de la consola perquè el mòdul s'ha actualitzat.",
	'scribunto-console-cleared-session-lost' => "S'ha netejat l'estat de la consola perquè s'han perdut les dades de la sessió.",
	'scribunto-common-error-category' => 'Pàgines amb errors de script',
	'scribunto-common-nosuchmodule' => "Error de l'script: no existeix el mòdul.",
	'scribunto-common-nofunction' => "Error de l'script: cal que especifiqueu una funció per cridar.",
	'scribunto-common-nosuchfunction' => "Error de l'script: la funció que heu especificat no existeix.",
	'scribunto-common-timeout' => "S'ha exhaurit el temps assignat per a l'execució d'scripts.",
	'scribunto-common-oom' => "S'ha exhaurit la quantitat de memòria permesa per a l'execució d'scripts.",
	'scribunto-common-backtrace' => 'Traça inversa:',
	'scribunto-lua-in-function' => 'a la funció «$1»',
	'scribunto-lua-in-main' => 'en el fragment principal',
	'scribunto-lua-in-function-at' => 'a la funció a $1:$2',
	'scribunto-lua-error-location' => 'Error de Lua $1: $2.',
	'scribunto-lua-error' => 'Error de Lua: $2.',
	'scribunto-lua-noreturn' => "Error de l'script: el mòdul no ha retornat cap valor. Se suposa que havia de retornar una taula d'exportació.",
	'scribunto-lua-notarrayreturn' => "Error de l'script: el mòdul ha retornat quelcom que no és una taula. Se suposa que havia de retornar una taula d'exportació.",
	'scribunto-luastandalone-proc-error' => 'Error de Lua: no es pot crear el procés.',
	'scribunto-luastandalone-decode-error' => 'Error de Lua: error intern: no es pot descodificar el missatge.',
	'scribunto-luastandalone-write-error' => "Error de Lua: error intern: s'ha produït un error en escriure al conducte.",
	'scribunto-luastandalone-read-error' => "Error de Lua: error intern: s'ha produït un error en llegir del conducte.",
	'scribunto-luastandalone-gone' => "Error de Lua: error intern: l'intèrpret ja ha sortit.",
	'scribunto-luastandalone-signal' => "Error de Lua: error intern: l'intèrpret ha terminat amb el senyal «$2».",
	'scribunto-luastandalone-exited' => "Error de Lua: error intern: l'intèrpret ha sortit amb l'estat «$2».",
);

/** Chechen (нохчийн)
 * @author Умар
 */
$messages['ce'] = array(
	'scribunto-common-error-category' => 'Срипташан гӀалаташ долу агӀонаш',
);

/** Czech (česky)
 * @author Danny B.
 * @author Mormegil
 */
$messages['cs'] = array(
	'scribunto-desc' => 'Framework pro vkládání skriptovacích jazyků do stránek MediaWiki',
	'scribunto-ignore-errors' => 'Povolit uložení kódu s chybami',
	'scribunto-line' => 'na řádku $1',
	'scribunto-module-line' => 'v modulu $1 na řádku $2',
	'scribunto-parser-error' => 'Chyba skriptu',
	'scribunto-parser-dialog-title' => 'Chyba skriptu',
	'scribunto-error-short' => 'Chyba skriptu: $1',
	'scribunto-error-long' => 'Chyby skriptu:

$1',
	'scribunto-doc-page-name' => 'Module:$1/Dokumentace',
	'scribunto-doc-page-does-not-exist' => "''Dokumentaci tohoto modulu lze vytvořit na stránce [[$1]]''",
	'scribunto-doc-page-header' => "'''Toto je dokumentace pro [[$1]]'''",
	'scribunto-console-intro' => '* Exporty z modulu jsou dostupné jako proměnná „p“ včetně neuložených změn.
* Řádek začínající „=“ se vyhodnotí jako výraz, případně můžete použít print().
* V kódu modulu můžete zprávy do konzole poslat pomocí mw.log().',
	'scribunto-console-title' => 'Ladicí konzole',
	'scribunto-console-too-large' => 'Toto konzolové sezení je příliš velké. Vymažte historii konzole nebo zmenšete modul.',
	'scribunto-console-current-src' => 'konzolový vstup',
	'scribunto-console-clear' => 'Vymazat',
	'scribunto-console-cleared' => 'Stav konzole byl vymazán, protože modul byl změněn.',
	'scribunto-console-cleared-session-lost' => 'Stav konzole byl vymazán, protože se ztratila data sezení.',
	'scribunto-common-error-category' => 'Stránky s chybami skriptů',
	'scribunto-common-nosuchmodule' => 'Chyba skriptu: Zadaný modul neexistuje.',
	'scribunto-common-nofunction' => 'Chyba skriptu: Musíte uvést funkci, která se má zavolat.',
	'scribunto-common-nosuchfunction' => 'Chyba skriptu: Zadaná funkce neexistuje.',
	'scribunto-common-timeout' => 'Vypršel čas povolený pro provádění skriptů.',
	'scribunto-common-oom' => 'Byla překročena velikost paměti povolená pro provádění skriptů.',
	'scribunto-common-backtrace' => 'Zásobník volání:',
	'scribunto-lua-in-function' => 've funkci „$1“',
	'scribunto-lua-in-main' => 'v hlavním bloku',
	'scribunto-lua-in-function-at' => 've funkci na $1:$2',
	'scribunto-lua-error-location' => 'Chyba Lua $1: $2.',
	'scribunto-lua-error' => 'Chyba Lua: $2.',
	'scribunto-lua-noreturn' => 'Chyba skriptu: Modul nevrátil žádnou hodnotu. Měl by vrátit tabulku exportů.',
	'scribunto-lua-notarrayreturn' => 'Chyba skriptu: Modul vrátil něco jiného než tabulku. Měl by vrátit tabulku exportů.',
	'scribunto-luastandalone-proc-error' => 'Chyba Lua: Nelze vytvořit proces.',
	'scribunto-luastandalone-proc-error-msg' => 'Chyba Lua: Nelze vytvořit proces: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Chyba Lua: Nelze vytvořit proces: Není k dispozici proc_open. Zkontrolujte direktivu „disable_functions“ v konfiguraci PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Chyba Lua: Nelze vytvořit proces. Vězte, že je v konfiguraci PHP zapnuta zavržená direktiva „safe_mode“.',
	'scribunto-luastandalone-decode-error' => 'Chyba Lua: Interní chyba: Nelze dekódovat zprávu.',
	'scribunto-luastandalone-write-error' => 'Chyba Lua: Interní chyba: Chyba zápisu do roury.',
	'scribunto-luastandalone-read-error' => 'Chyba Lua: Interní chyba: Chyba čtení z roury.',
	'scribunto-luastandalone-gone' => 'Chyba Lua: Interní chyba: Interpret již byl ukončen.',
	'scribunto-luastandalone-signal' => 'Chyba Lua: Interní chyba: Interpret byl ukončen signálem „$2“.',
	'scribunto-luastandalone-exited' => 'Chyba Lua: Interní chyba: Interpret byl ukončen s výsledkem $2.',
);

/** Danish (dansk)
 * @author Peter Alberti
 */
$messages['da'] = array(
	'scribunto-desc' => 'Gør det muligt at indlejre skriptsprog i MediaWiki-sider',
	'scribunto-ignore-errors' => 'Tillad at gemme kode med fejl',
	'scribunto-line' => 'i linje $1',
	'scribunto-module-line' => 'i $1 på linje $2',
	'scribunto-parser-error' => 'Skriptfejl',
	'scribunto-parser-dialog-title' => 'Skriptfejl',
	'scribunto-error-short' => 'Skriptfejl: $1',
	'scribunto-error-long' => 'Skriptfejl:

$1',
	'scribunto-doc-page-name' => 'Module:$1/dok',
	'scribunto-doc-page-does-not-exist' => "''Dokumentation for dette modul kan oprettes på [[$1]]''",
	'scribunto-doc-page-header' => "'''Dette er dokumentationssiden for [[$1]]'''",
	'scribunto-console-title' => 'Fejlsøgningskonsol',
	'scribunto-console-current-src' => 'konsolinput',
	'scribunto-console-clear' => 'Ryd',
	'scribunto-common-error-category' => 'Sider med skriptfejl',
	'scribunto-common-nosuchmodule' => 'Skriptfejl: Intet modul med det navn.',
	'scribunto-common-nosuchfunction' => 'Skriptfejl: Den angivne funktion findes ikke.',
	'scribunto-common-oom' => 'Mængden af hukommelse, der er tilladt for kørsel af skripts, er overskredet.',
	'scribunto-common-backtrace' => 'Tilbagesporing:',
	'scribunto-lua-in-function' => 'i funktionen "$1"',
	'scribunto-lua-in-main' => 'i hoveddelen',
	'scribunto-lua-in-function-at' => 'i funktionen ved $1:$2',
	'scribunto-lua-error-location' => 'Lua-fejl $1: $2.',
	'scribunto-lua-error' => 'Lua-fejl: $2.',
	'scribunto-luastandalone-proc-error' => 'Lua-fejl: Kan ikke oprette proces.',
);

/** German (Deutsch)
 * @author Kghbln
 * @author Metalhead64
 */
$messages['de'] = array(
	'scribunto-desc' => 'Ermöglicht eine Umgebung zum Einbetten von Skriptsprachen in Wikiseiten',
	'scribunto-ignore-errors' => 'Speicherung von fehlerhaftem Code zulassen',
	'scribunto-line' => 'in Zeile $1',
	'scribunto-module-line' => 'in $1, Zeile $2',
	'scribunto-parser-error' => 'Skriptfehler',
	'scribunto-parser-dialog-title' => 'Skriptfehler',
	'scribunto-error-short' => 'Skriptfehler: $1',
	'scribunto-error-long' => 'Skriptfehler:

$1',
	'scribunto-doc-page-name' => 'Module:$1/Doku',
	'scribunto-doc-page-does-not-exist' => "''Die Dokumentation für dieses Modul kann unter [[$1]] erstellt werden''",
	'scribunto-doc-page-header' => "'''Dies ist die Dokumentationsseite für [[$1]]'''",
	'scribunto-console-intro' => '* Modulexporte sind über die Variable „p“ verfügbar. Sie enthalten auch nicht gespeicherte Änderungen.
* Einer Zeile „=“ voranstellen, um sie als Ausdruck auszuwerten oder <code>print()</code> nutzen.
* Innerhalb des Modulcodes <code>mw.log()</code> nutzen, um Nachrichten zu dieser Konsole zu senden.',
	'scribunto-console-title' => 'Fehlerbereinigungskonsole',
	'scribunto-console-too-large' => 'Dieser Konsolensitzung ist zu umfangreich. Bitte deaktiviere die Konsolenprotokollierung oder verringere die Größe des Moduls.',
	'scribunto-console-current-src' => 'Konsoleneingabe',
	'scribunto-console-clear' => 'Leeren',
	'scribunto-console-cleared' => 'Die Konsole wurde geleert, da das Modul aktualisiert wurde.',
	'scribunto-console-cleared-session-lost' => 'Der Konsolenstatus wurde gelöscht, da die Sitzungsdaten verloren gegangen sind.',
	'scribunto-common-error-category' => 'Seiten mit Skriptfehlern',
	'scribunto-common-nosuchmodule' => 'Skriptfehler: Ein solches Modul ist nicht vorhanden.',
	'scribunto-common-nofunction' => 'Skriptfehler: Es muss eine aufzurufende Funktion angegeben werden.',
	'scribunto-common-nosuchfunction' => 'Skriptfehler: Die angegebene Funktion ist nicht vorhanden.',
	'scribunto-common-timeout' => 'Die Zeit zum Ausführen von Skripten vorgesehene Zeit ist abgelaufen.',
	'scribunto-common-oom' => 'Der zum Ausführen von Skripten vorgesehene Arbeitsspeicher wurde erschöpft.',
	'scribunto-common-backtrace' => 'Ablaufrückverfolgung:',
	'scribunto-lua-in-function' => 'in der Funktion „$1“',
	'scribunto-lua-in-main' => 'im Hauptsegment',
	'scribunto-lua-in-function-at' => 'in der Funktion bei  $1:$2',
	'scribunto-lua-error-location' => 'Lua-Fehler  $1: $2',
	'scribunto-lua-error' => 'Lua-Fehler: $2',
	'scribunto-lua-noreturn' => 'Skriptfehler: Das Modul gab keinen Wert zurück, obwohl es eine Tabelle zum Export hätte zurückgeben sollen.',
	'scribunto-lua-notarrayreturn' => 'Skriptfehler: Das Modul gab etwas anderes als eine Tabelle zum Export zurück. Es hätte eine Tabelle zum Export hätte zurückgeben sollen.',
	'scribunto-luastandalone-proc-error' => 'Lua-Fehler: Der Vorgang kann nicht erstellt werden.',
	'scribunto-luastandalone-proc-error-msg' => 'Lua-Fehler: Der Prozess konnte nicht erstellt werden: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Lua-Fehler: Der Prozess konnte nicht erstellt werden: proc_open ist nicht verfügbar. „disable_functions“ der PHP-Konfigurationsanweisung überprüfen.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Lua-Fehler: Der Prozess konnte nicht erstellt werden. Die veraltete PHP-Konfigurationsanweisung „safe_mode“ ist aktiviert.',
	'scribunto-luastandalone-decode-error' => 'Interner Lua-Fehler: Die Nachricht konnte nicht dekodiert werden.',
	'scribunto-luastandalone-write-error' => 'Interner Lua-Fehler: Es trat ein Fehler beim Schreiben auf.',
	'scribunto-luastandalone-read-error' => 'Interner Lua-Fehler: Es trat ein Fehler beim Lesen auf.',
	'scribunto-luastandalone-gone' => 'Interner Lua-Fehler: Der Interpreter wurde bereits beendet.',
	'scribunto-luastandalone-signal' => 'Interner Lua-Fehler: Der Interpreter beendet sich mit dem Signal „$2“.',
	'scribunto-luastandalone-exited' => 'Interner Lua-Fehler: Der Interpreter beendet sich mit dem Status $2.',
);

/** German (formal address) (Deutsch (Sie-Form)‎)
 * @author Kghbln
 */
$messages['de-formal'] = array(
	'scribunto-console-too-large' => 'Dieser Konsolensitzung ist zu umfangreich. Bitte deaktivieren Sie die Konsolenprotokollierung oder verringeren Sie die Größe des Moduls.',
);

/** Zazaki (Zazaki)
 * @author Erdemaslancan
 */
$messages['diq'] = array(
	'scribunto-parser-error' => 'Xırabiya scripti',
	'scribunto-parser-dialog-title' => 'Xırabiya scripti',
	'scribunto-error-long' => 'Xırabiya scripti:
$1',
	'scribunto-console-clear' => 'Bestern',
);

/** Lower Sorbian (dolnoserbski)
 * @author Michawiki
 * @author Tlustulimu
 */
$messages['dsb'] = array(
	'scribunto-desc' => 'Wobcerk za zasajźenje skriptowych rěcow do bokow MediaWiki',
	'scribunto-ignore-errors' => 'Składowanje koda ze zmólkami dowóliś',
	'scribunto-line' => 'w smužce $1',
	'scribunto-module-line' => 'w $1, w smužce $2',
	'scribunto-parser-error' => 'Skriptowa zmólka',
	'scribunto-parser-dialog-title' => 'Skriptowa zmólka',
	'scribunto-error-short' => 'Skriptowa zmólka: $1',
	'scribunto-error-long' => 'Skriptowe zmólki:

$1',
	'scribunto-doc-page-name' => 'Modul:$1/Documentacija',
	'scribunto-doc-page-does-not-exist' => "''Dokumentacija za toś ten modul dajo se na [[$1]] napóraś''",
	'scribunto-doc-page-header' => "'''To jo dokumentaciski bok za [[$1]]'''",
	'scribunto-console-intro' => '* Modulowe eksporty stoje ako wariabla "p" k dispoziciji, inkluziwnje njeskładowanych změnow.
* Staj "=" pśed smužku, aby ju ako wuraz wugódnośił, abo wužyj print().
* Wužyj mw.log() w modulowem koźe, aby powěsći na konsolu pósłał.',
	'scribunto-console-title' => 'Konsola za wótpóranje zmólkow',
	'scribunto-console-too-large' => 'Toś to konsolowe pósejźenje jo pśewjelike. Pšosym wuprozni konsolowu historiju abo reducěruj wjelikosć modula.',
	'scribunto-console-current-src' => 'konsolowe zapódaśe',
	'scribunto-console-clear' => 'Wuprozniś',
	'scribunto-console-cleared' => 'Konsola jo se wuprozniła, dokulaž modul jo se zaktualizěrował.',
	'scribunto-console-cleared-session-lost' => 'Status konsole jo se wulašował, dokulaž pósejźeńske daty su se zgubili.',
	'scribunto-common-error-category' => 'Boki ze skriptowymi zmólkami',
	'scribunto-common-nosuchmodule' => 'Skriptowa zmólka: Taki modul njejo.',
	'scribunto-common-nofunction' => 'Skriptowa zmólka: Musyš funkciju pódaś, kótaraž ma se wołaś.',
	'scribunto-common-nosuchfunction' => 'Skriptowa zmólka: Funkcija, kótaruž sy pódał, njeeksistěrujo.',
	'scribunto-common-timeout' => 'Cas, kótaryž jo se za wuwjeźenje skriptow póstajił, jo se minył.',
	'scribunto-common-oom' => 'Wjelikosć źěłowego składa, kótaraž jo za wuwjeźenje skriptow dowólona, jo pśekšocona.',
	'scribunto-common-backtrace' => 'Slědkslědowanje:',
	'scribunto-lua-in-function' => 'we funkciji "$1"',
	'scribunto-lua-in-main' => 'w głownem segmenśe',
	'scribunto-lua-in-function-at' => 'we funkciji pśi $1:$2',
	'scribunto-lua-error-location' => 'Lua-zmólka $1:  $2.',
	'scribunto-lua-error' => 'Lua-zmólka:  $2.',
	'scribunto-lua-noreturn' => 'Skriptowa zmólka: Modul njejo gódnotu wrośił, lěcrownož by měł eksportowu tabelu wrośiś.',
	'scribunto-lua-notarrayreturn' => 'Skriptowa zmólka: Modul jo něco druge ako tabelu wrośił, wón by měł eksportowu tabelu wrośiś.',
	'scribunto-luastandalone-proc-error' => 'Lua-zmólka: Proces njedajo se napóraś.',
	'scribunto-luastandalone-decode-error' => 'Lua-zmólka: Nutśkowna zmólka: Powěźeńka njedajo se dekoděrowaś.',
	'scribunto-luastandalone-write-error' => 'Lua-zmólka: Nutśkowna zmólka: Pśi pisanju jo zmólka nastała.',
	'scribunto-luastandalone-read-error' => 'Lua-zmólka: Nutśkowna zmólka: Pśi cytanju jo zmólka nastała.',
	'scribunto-luastandalone-gone' => 'Lua-zmólka: Nutśkowna zmólka: Interpreter jo se južo skóńcył.',
	'scribunto-luastandalone-signal' => 'Lua-zmólka: Nutśkowna zmólka: Interpreter jo se ze signalom "$2" skóńcył.',
	'scribunto-luastandalone-exited' => 'Lua-zmólka: Nutśkowna zmólka: Interpreter jo se ze statusom $2 skóńcył.',
);

/** Esperanto (Esperanto)
 * @author Michawiki
 * @author Tlustulimu
 */
$messages['eo'] = array(
	'scribunto-desc' => 'Framo por enkorpigi skriptlingvojn en paĝojn de MediaWiki',
	'scribunto-ignore-errors' => 'Permesi konservadon de kodo kun eraroj',
	'scribunto-line' => 'en linio $1',
	'scribunto-module-line' => 'en $1, linio $2',
	'scribunto-parser-error' => 'Skripteraro',
	'scribunto-parser-dialog-title' => 'Skripteraro',
	'scribunto-error-short' => 'Skripteraro: $1',
	'scribunto-error-long' => 'Skripteraroj:

$1',
	'scribunto-doc-page-name' => 'Modulo:$1/dokumentado',
	'scribunto-doc-page-does-not-exist' => "''Dokumentado por ĉi tiu modulo povas esti kreatata en [[$1]]''",
	'scribunto-doc-page-header' => "'''Tio estas la paĝo de la dokumentado por [[$1]]'''",
	'scribunto-console-intro' => '* La modulaj eksportoj estas disponeblaj kiel variablo "p" , inkluzive de la nekonservitaj ŝanĝoj.
* Prefiksu linion per "=" por taksi ĝin kiel esprimon aŭ uzu print().
* Uzu mw.log() en la modulkodo por sendi mesaĝojn al ĉi tiu konzolo.',
	'scribunto-console-title' => 'Sencimiga konzolo',
	'scribunto-console-too-large' => 'Ĉi tiu konzola seanco estas tro ampleksa. Bonvolu vakigi la konzolan historion aŭ reduku la grandecon de la modulo.',
	'scribunto-console-current-src' => 'Konzola enigo',
	'scribunto-console-clear' => 'Vakigi',
	'scribunto-console-cleared' => 'Konzola stato estis vakigata, ĉar la modulo estis ĝisdatigata.',
	'scribunto-console-cleared-session-lost' => 'La konzola stato estis vakigata, ĉar la seancaj datumoj perdiĝis.',
	'scribunto-common-error-category' => 'Paĝoj kun skripteraroj',
	'scribunto-common-nosuchmodule' => 'Skripteraro: Tia modulo ne ekzistas.',
	'scribunto-common-nofunction' => 'Skripteraro: Vi devas specifi vokotan funkcion.',
	'scribunto-common-nosuchfunction' => 'Skripteraro: La funkcio, kiun vi specifis, ne ekzistis.',
	'scribunto-common-timeout' => 'La planita tempo por plenumi skriptojn pasis.',
	'scribunto-common-oom' => 'La sumo de memoro permesata por lanĉi skriptojn estas transpasita.',
	'scribunto-common-backtrace' => 'Retrospurado:',
	'scribunto-lua-in-function' => 'en funkcio "$1"',
	'scribunto-lua-in-main' => 'en la ĉefsegmento',
	'scribunto-lua-in-function-at' => 'en la funkcio ĉe $1: $2',
	'scribunto-lua-error-location' => 'Lua-eraro $1: $2.',
	'scribunto-lua-error' => 'Lua-eraro: $2.',
	'scribunto-lua-noreturn' => 'Skripteraro: La modulo  ne liveris valoron. Ĝi liveru eksportotabelon.',
	'scribunto-lua-notarrayreturn' => 'Skripteraro: La modulo liveris ion alian ol tabelon. Ĝi liveru eksportotabelon.',
	'scribunto-luastandalone-proc-error' => 'Lua-eraro: Ne povas krei proceson.',
	'scribunto-luastandalone-decode-error' => 'Lua-eraro: Interna eraro: Ne eblas dekodi mesaĝon.',
	'scribunto-luastandalone-write-error' => 'Lua-eraro: Interna eraro: Eraro skribante al dukto.',
	'scribunto-luastandalone-read-error' => 'Lua-eraro: Interna eraro: Eraro legante el dukto.',
	'scribunto-luastandalone-gone' => 'Lua-eraro: Interna eraro: La interpretilo estas jam eligita.',
	'scribunto-luastandalone-signal' => 'Lua-eraro: Interna eraro: La interpretilo finiĝis kun signalo "$2".',
	'scribunto-luastandalone-exited' => 'Lua-eraro: Interna eraro: La interpretilo finiĝis kun statuso "$2".',
);

/** Spanish (español)
 * @author Armando-Martin
 * @author Jewbask
 * @author Maor X
 */
$messages['es'] = array(
	'scribunto-desc' => 'Marco para la incorporación de lenguajes de script en páginas de MediaWiki',
	'scribunto-ignore-errors' => 'Permite guardar el código con errores',
	'scribunto-line' => 'en la línea $1',
	'scribunto-module-line' => 'en $1 en la línea $2',
	'scribunto-parser-error' => 'Error de script',
	'scribunto-parser-dialog-title' => 'Error de script',
	'scribunto-error-short' => 'Error de secuencia de comandos: $1',
	'scribunto-error-long' => 'Errores de secuencia de comandos (script):

$1',
	'scribunto-console-intro' => '* Las exportaciones del módulo están disponibles como la variable "p", incluídas las modificaciones sin guardar.
* Inicie una línea con el carácter "=" para evaluarla como una expresión; o use print().
* Use mw.log() en el código del módulo para enviar mensajes a esta consola.',
	'scribunto-console-title' => 'Consola de depuración de errores',
	'scribunto-console-too-large' => 'La sesión de esta consola es demasiado grande. Limpia el historial de la consola o reduce el tamaño del módulo.',
	'scribunto-console-current-src' => 'entrada de la consola',
	'scribunto-console-clear' => 'Limpiar',
	'scribunto-console-cleared' => 'El estado de la consola se ha limpiado debido a que el módulo se ha actualizado.',
	'scribunto-common-error-category' => 'Páginas con errores de secuencia de comandos (script)',
	'scribunto-common-nosuchmodule' => 'Error de secuencia de comandos (script): no existe ese módulo.',
	'scribunto-common-nofunction' => 'Error de script: debe especificar una función a la que llamar.',
	'scribunto-common-nosuchfunction' => 'Error de script: la función especificada no existe.',
	'scribunto-common-timeout' => 'Ha caducado el tiempo asignado para ejecutar secuencias de comandos (scripts).',
	'scribunto-common-oom' => 'Se ha superado la cantidad de memoria permitida para ejecutar secuencias de comandos (script).',
	'scribunto-common-backtrace' => 'LLamadas de funciones activas (backtrace):',
	'scribunto-lua-in-function' => 'en la función "$1"',
	'scribunto-lua-in-main' => 'en el campo principal',
	'scribunto-lua-in-function-at' => 'en la función en $1: $2',
	'scribunto-lua-error-location' => 'Error de Lua $1: $2.',
	'scribunto-lua-error' => 'Error de Lua: $2.',
	'scribunto-lua-noreturn' => 'Error de secuencia de comandos: El módulo no devolvió ningún valor; debería devolver una tabla de exportación.',
	'scribunto-lua-notarrayreturn' => 'Error de secuencia de comandos: El módulo devolvió algo que no era una tabla; debería devolver una tabla de exportación.',
	'scribunto-luastandalone-proc-error' => 'Error de Lua: No se puede crear el proceso.',
	'scribunto-luastandalone-decode-error' => 'Error de Lua: Error interno: No se pudo decodificar el mensaje.',
	'scribunto-luastandalone-write-error' => 'Error de Lua: Error interno: Error al escribir en la canalización (pipe).',
	'scribunto-luastandalone-read-error' => 'Error de Lua: Error interno: Error al leer desde la canalización (pipe).',
	'scribunto-luastandalone-gone' => 'Error de Lua: Error interno: El intérprete ya ha finalizado.',
	'scribunto-luastandalone-signal' => 'Error de Lua: Error interno: El intérprete ha finalizado con la señal "$2".',
	'scribunto-luastandalone-exited' => 'Error de Lua: Error interno: El intérprete ha finalizado con el estado $2.',
);

/** Estonian (eesti)
 * @author Avjoska
 * @author Pikne
 */
$messages['et'] = array(
	'scribunto-ignore-errors' => 'Luba salvestada kood tõrgetega',
	'scribunto-line' => '$1. real',
	'scribunto-parser-error' => 'Skriptitõrge',
	'scribunto-parser-dialog-title' => 'Skriptitõrge',
	'scribunto-error-short' => 'Skriptitõrge: $1',
	'scribunto-error-long' => 'Skriptitõrked:

$1',
	'scribunto-doc-page-does-not-exist' => "''Selle mooduli dokumentatsiooni saab kirjutada asukohta [[$1]]''.",
	'scribunto-doc-page-header' => "'''See on üksuse [[$1]] dokumentatsiooni lehekülg.'''",
	'scribunto-console-clear' => 'Puhasta',
	'scribunto-common-error-category' => 'Skriptitõrgetega leheküljed',
	'scribunto-common-nosuchmodule' => 'Skriptitõrge: Sellist moodulit pole.',
	'scribunto-common-nosuchfunction' => 'Script error: Määratud funktsioon puudub.',
	'scribunto-lua-in-function' => 'funktsioonis "$1"',
	'scribunto-lua-error-location' => 'Lua tõrge $1: $2.',
	'scribunto-lua-error' => 'Lua tõrge: $2.',
);

/** Basque (euskara)
 * @author පසිඳු කාවින්ද
 */
$messages['eu'] = array(
	'scribunto-console-clear' => 'Garbitu',
);

/** Persian (فارسی)
 * @author Mjbmr
 * @author Reza1615
 * @author ZxxZxxZ
 * @author جواد
 */
$messages['fa'] = array(
	'scribunto-desc' => 'چارچوبی برای تعبیه‌کردن زبان‌های اسکریپتی در صفحه‌های مدیاویکی',
	'scribunto-ignore-errors' => 'اجازهٔ ذخیره‌سازی کدهای خطادار را بده',
	'scribunto-line' => 'در خط $1',
	'scribunto-module-line' => 'در $1 در خط $2',
	'scribunto-parser-error' => 'خطای اسکریپتی',
	'scribunto-parser-dialog-title' => 'خطای اسکریپتی',
	'scribunto-error-short' => 'خطای اسکریپتی: $1',
	'scribunto-error-long' => 'خطاهای اسکریپتی:

$1',
	'scribunto-doc-page-name' => 'Module:$1/توضیحات',
	'scribunto-doc-page-does-not-exist' => "''توضیحات این پودمان می‌تواند در [[$1]] قرار گیرد.''",
	'scribunto-doc-page-header' => "'''این صفحهٔ توضیحات [[$1]] است'''",
	'scribunto-console-title' => 'میزفرمان اشکال‌زدایی',
	'scribunto-console-too-large' => 'نشست میز فرمان خیلی بزرگ است. لطفاً تاریخچهٔ میز فرمان را پاک کنید یا حجم پودمان را کمتر کنید.',
	'scribunto-console-current-src' => 'ورودی میزفرمان',
	'scribunto-console-clear' => 'پاک‌سازی',
	'scribunto-console-cleared' => 'وضعیت میز فرمان پاک‌سازی شد، چون پودمان تغییر کرد.',
	'scribunto-console-cleared-session-lost' => 'وضعیت میزفرمان پاک‌سازی شد، چون اطلاعات نشست از دست رفت.',
	'scribunto-common-error-category' => 'صفحه‌های دارای خطاهای اسکریپتی',
	'scribunto-common-nosuchmodule' => 'خطای اسکریپتی: چنین ماژولی وجود ندارد.',
	'scribunto-common-nofunction' => 'خطای اسکریپتی: باید تابعی را برای فراخوانی مشخص کنید.',
	'scribunto-common-nosuchfunction' => 'خطای اسکریپتی: تابعی که مشخص کردید وجود ندارد.',
	'scribunto-common-timeout' => 'زمان مجاز برای اجرای اسکریپت‌ها منقضی شده است.',
	'scribunto-common-oom' => 'مقدار حافظهٔ استفاده شده برای اجرای اسکریپت از حد مجاز فراتر رفته است.',
	'scribunto-lua-in-function' => 'در تابع «$1»',
	'scribunto-lua-in-function-at' => 'در تابع در $1:$2',
	'scribunto-lua-error-location' => 'خطای لوآ $1: $2.',
	'scribunto-lua-error' => 'خطای لوآ: $2.',
	'scribunto-lua-noreturn' => 'خطای اسکریپتی: پودمان مقداری را برنگرداند. (باید یک جدول برون‌ریزی برگرداند)',
	'scribunto-lua-notarrayreturn' => 'خطای اسکریپتی: پودمان چیزی غیر از یک جدول برگرداند. (باید یک جدول برون‌ریزی برگرداند)',
	'scribunto-luastandalone-proc-error' => 'خطای لوآ: نمی‌تواند فرآیند را ایجاد کند.',
	'scribunto-luastandalone-decode-error' => 'خطای لوآ: خطای داخلی: نمی‌تواند پیغام را کدبرداری کند.',
	'scribunto-luastandalone-write-error' => 'خطای لوآ: خطای داخلی: خطا در نوشتن لوله.',
	'scribunto-luastandalone-read-error' => 'خطای لوآ: خطای داخلی: خطا در خواندن لوله.',
	'scribunto-luastandalone-gone' => 'خطای لوآ: خطای داخلی: مفسر قبلاً خارج شده است.',
	'scribunto-luastandalone-signal' => 'خطای لوآ: خطای داخلی: مفسر با سیگنال «$2» خاتمه یافته است.',
	'scribunto-luastandalone-exited' => 'خطای لوآ: خطای داخلی: مفسر با وضعیت $2 خارج شده است.',
);

/** Finnish (suomi)
 * @author Beluga
 * @author Crt
 * @author Silvonen
 * @author Stryn
 * @author VezonThunder
 */
$messages['fi'] = array(
	'scribunto-ignore-errors' => 'Salli virheitä sisältävän koodin tallentaminen',
	'scribunto-line' => 'rivillä $1',
	'scribunto-doc-page-name' => 'Module:$1/ohje',
	'scribunto-doc-page-does-not-exist' => "''Tämän moduulin ohjeistuksen voi tehdä sivulle [[$1]]''",
	'scribunto-console-clear' => 'Tyhjennä',
	'scribunto-lua-error-location' => 'Lua-virhe $1: $2.',
	'scribunto-lua-error' => 'Lua-virhe: $2.',
);

/** French (français)
 * @author Brunoperel
 * @author Cquoi
 * @author Crochet.david
 * @author Erkethan
 * @author Gomoko
 * @author IAlex
 * @author Urhixidur
 */
$messages['fr'] = array(
	'scribunto-desc' => "Cadre pour l'intégration des langages de script dans des pages de MediaWiki",
	'scribunto-ignore-errors' => "Permettre l'enregistrement de code avec des erreurs",
	'scribunto-line' => 'à la ligne $1',
	'scribunto-module-line' => 'dans $1 à la ligne $2',
	'scribunto-parser-error' => 'Erreur de script',
	'scribunto-parser-dialog-title' => 'Erreur de script',
	'scribunto-error-short' => 'Erreur de script : $1',
	'scribunto-error-long' => 'Erreur de script :

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''La documentation pour ce module peut être créée à [[$1]]''",
	'scribunto-doc-page-header' => "'''Voici la page de documentation pour [[$1]]'''",
	'scribunto-console-intro' => "* Les exportations de module sont représentés par la variable « p », y compris les modifications non enregistrées. 
* Faites précéder une ligne par « = » pour l'évaluer comme une expression, ou utilisez print(). 
* Utilisez mw.log() dans le code du module pour envoyer des messages à cette console.",
	'scribunto-console-title' => 'Console de débogage',
	'scribunto-console-too-large' => 'Cette session de console est trop grande. Veuillez effacer l’historique de la console ou réduire la taille du module.',
	'scribunto-console-current-src' => 'entrée de la console',
	'scribunto-console-clear' => 'Effacer',
	'scribunto-console-cleared' => "L'état de la console a été effacé parce que le module a été mis à jour.",
	'scribunto-console-cleared-session-lost' => 'L’état de la console a été nettoyé car les données de sessions ont été perdues.',
	'scribunto-common-error-category' => 'Pages avec des erreurs de script',
	'scribunto-common-nosuchmodule' => 'Erreur de script : Pas de tel module.',
	'scribunto-common-nofunction' => 'Erreur de script : vous devez spécifier une fonction à appeler.',
	'scribunto-common-nosuchfunction' => 'Erreur de script : la fonction que vous avez spécifiée n’existe pas.',
	'scribunto-common-timeout' => 'Le temps alloué pour l’exécution des scripts a expiré.',
	'scribunto-common-oom' => 'La quantité de mémoire pour exécuter des scripts a été dépassée.',
	'scribunto-common-backtrace' => 'Trace arrière:',
	'scribunto-lua-in-function' => 'dans la fonction « $1 »',
	'scribunto-lua-in-main' => 'dans le segment principal',
	'scribunto-lua-in-function-at' => 'dans la fonction $1 : $2',
	'scribunto-lua-backtrace-line' => '$1 : $2',
	'scribunto-lua-error-location' => 'Erreur Lua $1: $2.',
	'scribunto-lua-error' => 'Erreur Lua : $2',
	'scribunto-lua-noreturn' => "Erreur de script: Le module n'a pas renvoyé de valeur, il doit renvoyer un tableau d'export.",
	'scribunto-lua-notarrayreturn' => "Erreur de script: Le module a renvoyé quelque chose d'autre qu'une table, il devrait renvoyer un tableau d'export.",
	'scribunto-luastandalone-proc-error' => 'Erreur LUA: Impossible de créer le processus.',
	'scribunto-luastandalone-proc-error-msg' => 'Erreur LUA : Impossible de créer le processus : $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Erreur LUA : Impossible de créer le processus : proc_open n’est pas disponible. Vérifiez la directive de configuration PHP « disable_functions ».',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Erreur LUA : Impossible de créer le processus : Remarquez que la directive obsolère de configuration PHP « safe_mode » est activée.',
	'scribunto-luastandalone-decode-error' => 'Erreur LUA: Erreur interne: Impossible de décoder le message.',
	'scribunto-luastandalone-write-error' => "Erreur LUA: Erreur interne: Erreur d'écriture dans le pipe.",
	'scribunto-luastandalone-read-error' => 'Erreur LUA: Erreur interne: Erreur de lecture du pipe.',
	'scribunto-luastandalone-gone' => "Erreur LUA: Erreur interne: L'interpréteur est déjà terminé.",
	'scribunto-luastandalone-signal' => 'Erreur LUA: Erreur interne: L\'interpréteur s\'est terminé avec le signal "$2".',
	'scribunto-luastandalone-exited' => 'Erreur LUA: Erreur interne: L\'interpréteur s\'est terminé avec le statut "$2".',
);

/** Galician (galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'scribunto-desc' => 'Estrutura para incorporar linguaxes de script nas páxinas de MediaWiki',
	'scribunto-ignore-errors' => 'Permitir o gardado de código con erros',
	'scribunto-line' => 'na liña $1',
	'scribunto-module-line' => 'en $1 na liña $2',
	'scribunto-parser-error' => 'Erro de script',
	'scribunto-parser-dialog-title' => 'Erro de script',
	'scribunto-error-short' => 'Erro de script: $1',
	'scribunto-error-long' => 'Erros de script:

$1',
	'scribunto-doc-page-name' => 'Módulo:$1/uso',
	'scribunto-doc-page-does-not-exist' => "''A documentación deste módulo pódese crear en \"[[\$1]]\"''",
	'scribunto-doc-page-header' => "'''Esta é a páxina de documentación de \"[[\$1]]\"'''",
	'scribunto-console-intro' => '* As exportacións do módulo están dispoñibles como a variable "p", incluídas as modificacións sen gardar.
* Poña un "=" ao comezo da liña para avaliala como unha expresión; tamén pode usar print().
* Use mw.log() no código do módulo para enviar mensaxes a esta consola.',
	'scribunto-console-title' => 'Consola de depuración',
	'scribunto-console-too-large' => 'A sesión desta consola é grande de máis. Limpe o historial da consola ou reduza o tamaño do módulo.',
	'scribunto-console-current-src' => 'entrada da consola',
	'scribunto-console-clear' => 'Limpar',
	'scribunto-console-cleared' => 'Limpouse o estado da consola debido á actualización do módulo.',
	'scribunto-console-cleared-session-lost' => 'Limpouse o estado da consola debido á perda dos datos da sesión.',
	'scribunto-common-error-category' => 'Páxinas con erros de script',
	'scribunto-common-nosuchmodule' => 'Erro de script: Non existe ese módulo.',
	'scribunto-common-nofunction' => 'Erro de script: Cómpre especificar a función que se quere chamar.',
	'scribunto-common-nosuchfunction' => 'Erro de script: A función especificada non existe.',
	'scribunto-common-timeout' => 'O tempo reservado para executar os scripts rematou.',
	'scribunto-common-oom' => 'Superouse a cantidade de memoria permitida para executar os scripts.',
	'scribunto-common-backtrace' => 'Rastro inverso (backtrace):',
	'scribunto-lua-in-function' => 'na función "$1"',
	'scribunto-lua-in-main' => 'no bloque principal',
	'scribunto-lua-in-function-at' => 'na función en $1:$2',
	'scribunto-lua-error-location' => 'Erro de Lua $1: $2.',
	'scribunto-lua-error' => 'Erro de Lua: $2.',
	'scribunto-lua-noreturn' => 'Erro de script: O módulo non devolveu ningún valor; debería devolver unha táboa de exportación.',
	'scribunto-lua-notarrayreturn' => 'Erro de script: O módulo devolveu algo que non era unha táboa; debería devolver unha táboa de exportación.',
	'scribunto-luastandalone-proc-error' => 'Erro de Lua: Non se pode crear o proceso.',
	'scribunto-luastandalone-proc-error-msg' => 'Erro de Lua: Non se pode crear o proceso: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Erro de Lua: Non se pode crear o proceso: proc_open non está dispoñible. Comprobe a directiva de configuración "disable_functions" do PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Erro de Lua: Non se pode crear o proceso. Teña en conta que a directiva de configuración obsoleta "safe_mode" do PHP está activada.',
	'scribunto-luastandalone-decode-error' => 'Erro de Lua: Erro interno: Non se puido descodificar a mensaxe.',
	'scribunto-luastandalone-write-error' => 'Erro de Lua: Erro interno: Erro ao escribir na canalización (pipe).',
	'scribunto-luastandalone-read-error' => 'Erro de Lua: Erro interno: Erro ao ler desde a canalización (pipe).',
	'scribunto-luastandalone-gone' => 'Erro de Lua: Erro interno: O intérprete xa rematou.',
	'scribunto-luastandalone-signal' => 'Erro de Lua: Erro interno: O intérprete rematou co sinal "$2".',
	'scribunto-luastandalone-exited' => 'Erro de Lua: Erro interno: O intérprete rematou co estado $2.',
);

/** Hebrew (עברית)
 * @author Amire80
 * @author ערן
 */
$messages['he'] = array(
	'scribunto-desc' => 'מסגרת להטמעת שפות תסריט לדפים של מדיה־ויקי',
	'scribunto-ignore-errors' => 'לאפשר שמירת קוד עם שגיאות',
	'scribunto-line' => 'בשורה $1',
	'scribunto-module-line' => 'ביחידה $1 בשורה $2',
	'scribunto-parser-error' => 'שגיאה בתסריט',
	'scribunto-parser-dialog-title' => 'שגיאה בתסריט',
	'scribunto-error-short' => 'שגיאה בתסריט: $1',
	'scribunto-error-long' => 'שגיאות בתסריט:

$1',
	'scribunto-doc-page-name' => 'יחידה:$1/תיעוד',
	'scribunto-doc-page-does-not-exist' => 'ניתן למצוא תיעוד על היחידה הזאת בדף [[$1]]',
	'scribunto-doc-page-header' => 'זהו דף התיעוד עבור [[$1]]',
	'scribunto-console-intro' => '* השמות המיוצאים מיחידה זמינים במשתנה "p", כולל שינויים שלא נשמרו.
* כדי לחשב את השורה כביטוי יש להתחיל אותה בסימן "=" או להשתמש ב־print()‎.
* כדי לשלוח הודעות למסוף הזה, יש להשתמש ב־mw.log()‎.',
	'scribunto-console-title' => 'מסוף לבדיקת קוד',
	'scribunto-console-too-large' => 'השיחה במסוף גדולה מדי. נא לנקות את ההיסטוריה או להקטין את היחידה.',
	'scribunto-console-current-src' => 'קלט במסוף',
	'scribunto-console-clear' => 'ניקוי',
	'scribunto-console-cleared' => 'מצב המסוף נוקה כי היחידה עודכנה.',
	'scribunto-console-cleared-session-lost' => 'מצב המסוף נוקה, כי נתוני השיחה אבדו.',
	'scribunto-common-error-category' => 'דפים עם שגיאות בתסריט',
	'scribunto-common-nosuchmodule' => 'שגיאת תסריט: אין יחידה כזאת.',
	'scribunto-common-nofunction' => 'שגיאת תסריט: חובה לציין לאיזו פונקציה לקרוא.',
	'scribunto-common-nosuchfunction' => 'שגיאת תסריט: הפונקציה שציינת אינה קיימת.',
	'scribunto-common-timeout' => 'הזמן שהוקצה להרצת תסריטים פג.',
	'scribunto-common-oom' => 'הזיכרון שהוקצה להרצת תסריטים אזל.',
	'scribunto-common-backtrace' => 'מחסנית קריאות:',
	'scribunto-lua-in-function' => 'בפונקציה "$1"',
	'scribunto-lua-in-main' => 'בגוש העיקרי',
	'scribunto-lua-in-function-at' => 'בפונקציה $1 בשורה $2',
	'scribunto-lua-error-location' => 'שגיאת לואה $1: $2.',
	'scribunto-lua-error' => 'שגיאת לואה: $2.',
	'scribunto-lua-noreturn' => 'שגיאת תסריט: היחידה לא החזירה ערך. היא אמורה להחזיר טבלת שמות מיוצאים.',
	'scribunto-lua-notarrayreturn' => 'שגיאת תסריט: היחידה החזירה ערך שאינו טבלה. היא אמורה להחזיר טבלת שמות מיוצאים.',
	'scribunto-luastandalone-proc-error' => 'שגיאת לואה: לא ניתן ליצור תהליך.',
	'scribunto-luastandalone-proc-error-msg' => 'שגיאת לואה: לא ניתן ליצור תהליך: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'שגיאת לואה: לא ניתן ליצור תהליך: הפונקציה proc_open אינה זמינה. נא לבדוק את ההגדרה "disable_functions" של PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'שגיאת לואה: לא ניתן ליצור תהליך. יש לשים לב לכך שמופעלת הגדרת ה־PHP המיושנת "safe_mode".',
	'scribunto-luastandalone-decode-error' => 'שגיאת לואה: שגיאה פנימית: לא ניתן לפענח הודעה.',
	'scribunto-luastandalone-write-error' => 'שגיאת לואה: שגיאה פנימית: שגיאה בכתיבה לצינור.',
	'scribunto-luastandalone-read-error' => 'שגיאת לואה: שגיאה פנימית: שגיאה בקריאה מצינור.',
	'scribunto-luastandalone-gone' => 'שגיאת לואה: שגיאה פנימית: המפענח כבר יצא.',
	'scribunto-luastandalone-signal' => 'שגיאת לואה: שגיאה פנימית: המפענח גמר עם הסיגנל "$2".',
	'scribunto-luastandalone-exited' => 'שגיאת לואה: שגיאה פנימית: המפענח יצא עם המצב $2.',
);

/** Upper Sorbian (hornjoserbsce)
 * @author Michawiki
 * @author Tlustulimu
 */
$messages['hsb'] = array(
	'scribunto-desc' => 'Wobłuk za zasadźenje skriptowych rěčow do stronow MediaWiki',
	'scribunto-ignore-errors' => 'Składowanje koda ze zmylkami dowolić',
	'scribunto-line' => 'w lince $1',
	'scribunto-module-line' => 'w $1, w lince $2',
	'scribunto-parser-error' => 'Skriptowy zmylk',
	'scribunto-parser-dialog-title' => 'Skriptowy zmylk',
	'scribunto-error-short' => 'Skriptowy zmylk: $1',
	'scribunto-error-long' => 'Skriptowe zmylki:

$1',
	'scribunto-doc-page-name' => 'Modul:$1/dokumentacija',
	'scribunto-doc-page-does-not-exist' => "''Dokumentacija za tutón modul hodźi so na [[$1]] wutworić''",
	'scribunto-doc-page-header' => "'''To je dokumentaciska strona za [[$1]]'''",
	'scribunto-console-intro' => '* Modulowe eksporty steja jako wariabla "p" k dispoziciji, inkluziwnje njeskładowanych změnow.
* Staj "=" před linku, zo by ju jako wuraz wuhódnoćił, abo wužij print().
* Wužij mw.log() w modulowym kodźe, zo by powěsće na konsolu pósłał.',
	'scribunto-console-title' => 'Konsola za porjedźenje zmylkow',
	'scribunto-console-too-large' => 'Tute konsolowe posedźnje je přewulke. Prošu wuprózdń konsolowu historiju abo redukuj wulkosć modula.',
	'scribunto-console-current-src' => 'konsolowe zapodaće',
	'scribunto-console-clear' => 'Wuprózdnić',
	'scribunto-console-cleared' => 'Konsola je so wuprózdniła, dokelž modul je so zaktualizował.',
	'scribunto-console-cleared-session-lost' => 'Status konsole je so zhašał, dokelž posedźenske daty su so zhubili.',
	'scribunto-common-error-category' => 'Strony ze skriptowymi zmylkami',
	'scribunto-common-nosuchmodule' => 'Skriptowy zmylk: Tajki modul njeje.',
	'scribunto-common-nofunction' => 'Skriptowy zmylk: Dyrbiš funkciju podać, kotraž ma so wołać.',
	'scribunto-common-nosuchfunction' => 'Skriptowy zmylk: Funkcija, kotruž sy podał, njeeksistuje.',
	'scribunto-common-timeout' => 'Čas, kotryž je so za wuwjedźenje skriptow postajił, je spadnył.',
	'scribunto-common-oom' => 'Wulkosć dźěłoweho składa, kotraž je za wuwjedźenje skriptow dowolena, je překročena.',
	'scribunto-common-backtrace' => 'Wróćoslědowanje:',
	'scribunto-lua-in-function' => 'we funkciji "$1"',
	'scribunto-lua-in-main' => 'we hłownym segmenće',
	'scribunto-lua-in-function-at' => 'we funkciji při $1:$2',
	'scribunto-lua-error-location' => 'Lua-zmylk $1:  $2.',
	'scribunto-lua-error' => 'Lua-zmylk:  $2.',
	'scribunto-lua-noreturn' => 'Skriptowy zmylk: Modul njeje hódnotu wróćił, byrnjež měł eksportowu tabelu wróćić.',
	'scribunto-lua-notarrayreturn' => 'Skriptowy zmylk: Modul je něšto druhe hač tabelu wróćił, wón měł eksportowu tabelu wróćić.',
	'scribunto-luastandalone-proc-error' => 'Lua-zmylk: Proces njeda so wutworić.',
	'scribunto-luastandalone-decode-error' => 'Lua-zmylk: Nutřkowny zmylk: Zdźělenka njeda so dekodować.',
	'scribunto-luastandalone-write-error' => 'Lua-zmylk: Nutřkowny zmylk: Při pisanju je zmylk wustupił.',
	'scribunto-luastandalone-read-error' => 'Lua-zmylk: Nutřkowny zmylk: Při čitanju je zmylk wustupił.',
	'scribunto-luastandalone-gone' => 'Lua-zmylk: Nutřkowny zmylk: Interpreter je so hižo skónčił.',
	'scribunto-luastandalone-signal' => 'Lua-zmylk: Nutřkowny zmylk: Interpreter je so ze signalom "$2" skónčił.',
	'scribunto-luastandalone-exited' => 'Lua-zmylk: Nutřkowny zmylk: Interpreter je so ze statusom $2 skónčił.',
);

/** Hungarian (magyar)
 * @author TK-999
 * @author Tgr
 */
$messages['hu'] = array(
	'scribunto-desc' => 'Keretrendszer a parancsnyelvek MediaWiki-lapokba történő beágyazására',
	'scribunto-ignore-errors' => 'Engedje a kód elmentését akkor is, ha hibás',
	'scribunto-line' => 'a(z) $1. sorban',
	'scribunto-module-line' => 'a(z) $1 modulban a(z) $2. sorban',
	'scribunto-parser-error' => 'Parancsfájl-hiba',
	'scribunto-parser-dialog-title' => 'Parancsfájl-hiba',
	'scribunto-error-short' => 'Parancsfájl-hiba: $1',
	'scribunto-error-long' => 'Parancsfájl-hibák:

$1',
	'scribunto-doc-page-name' => 'doc', # Fuzzy
	'scribunto-doc-page-does-not-exist' => "''A modult a [[$1]] lapon tudod dokumentálni''",
	'scribunto-doc-page-header' => "'''Ez a [[$1]] dokumentációs lapja'''", # Fuzzy
	'scribunto-console-title' => 'Hibakereső konzol',
	'scribunto-console-current-src' => 'konzol bemenet',
	'scribunto-console-clear' => 'törlés',
	'scribunto-common-nosuchmodule' => 'Parancsfájl-hiba: nincs ilyen modul.',
	'scribunto-common-nofunction' => 'Parancsfájl-hiba: meg kell adnod a használandó függvényt.',
	'scribunto-common-nosuchfunction' => 'Parancsfájl-hiba: a megadott függvény nem létezik.',
	'scribunto-common-timeout' => 'A parancsfájlok futtatására lefoglalt idő lejárt.',
	'scribunto-common-oom' => 'A parancsfájlok futtatásához engedélyezett memória mennyisége túl lett lépve.',
	'scribunto-common-backtrace' => 'Backtrace:',
	'scribunto-lua-in-function' => 'a(z) "$1" függvényben',
	'scribunto-lua-error' => 'Lua-hiba:  $2.',
);

/** Interlingua (interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'scribunto-desc' => 'Structura pro incorporar linguages de script in paginas de MediaWiki',
	'scribunto-ignore-errors' => 'Permitter salveguardar codice con errores',
	'scribunto-line' => 'al linea $1',
	'scribunto-module-line' => 'in $1 al linea $2',
	'scribunto-parser-error' => 'Error de script',
	'scribunto-parser-dialog-title' => 'Error de script',
	'scribunto-error-short' => 'Error de script: $1',
	'scribunto-error-long' => 'Errores de script:

$1',
	'scribunto-console-intro' => '* Le exportationes del modulo es disponibile como le variabile "p", incluse le modificationes non salveguardate.
* Initia un linea con "=" pro evalutar lo como expression, o usa print().
* Usa mw.log() in le codice del modulo pro inviar messages a iste consola.',
	'scribunto-console-title' => 'Consola de debug',
	'scribunto-console-too-large' => 'Iste session de consola es troppo grande. Per favor rade le historia del consola o reduce le dimension del modulo.',
	'scribunto-console-current-src' => 'entrata del consola',
	'scribunto-console-clear' => 'Rader',
	'scribunto-console-cleared' => 'Le stato del consola ha essite radite perque le modulo ha essite actualisate.',
	'scribunto-common-nosuchmodule' => 'Error de script: modulo non trovate',
	'scribunto-common-nofunction' => 'Error de script: tu debe specificar un function a appellar.',
	'scribunto-common-nosuchfunction' => 'Error de script: le function specificate non existe.',
	'scribunto-common-timeout' => 'Le tempore allocate pro le execution de scripts ha expirate.',
	'scribunto-common-oom' => 'Le quantitate de memoria permittite pro le execution de scripts ha essite excedite.',
	'scribunto-common-backtrace' => 'Tracia a retro:',
	'scribunto-lua-in-function' => 'in function "$1"',
	'scribunto-lua-in-main' => 'in le parte principal',
	'scribunto-lua-in-function-at' => 'in le function a $1:$2',
	'scribunto-lua-error-location' => 'Error de Lua $1: $2',
	'scribunto-lua-error' => 'Error de Lua: $2',
	'scribunto-lua-noreturn' => 'Error de script: Le modulo non retornava un valor, illo deberea retornar un tabella de exportation.',
	'scribunto-lua-notarrayreturn' => 'Error de script: Le modulo retornava qualcosa altere que un tabella, illo deberea retornar un tabella de exportation.',
	'scribunto-luastandalone-proc-error' => 'Error de Lua: non pote crear processo',
	'scribunto-luastandalone-decode-error' => 'Error de Lua: error interne: impossibile decodificar le message',
	'scribunto-luastandalone-write-error' => 'Error de Lua: error interne: error de scriptura al tubo',
	'scribunto-luastandalone-read-error' => 'Error de Lua: error interne: error de lectura del tubo',
	'scribunto-luastandalone-gone' => 'Error de Lua: error interne: le interpretator ha jam exite',
	'scribunto-luastandalone-signal' => 'Error de Lua: error interne: le interpretator ha terminate con le signal "$2"',
	'scribunto-luastandalone-exited' => 'Error de Lua: error interne: le interpretator exiva con le stato $2',
);

/** Indonesian (Bahasa Indonesia)
 * @author Iwan Novirion
 */
$messages['id'] = array(
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Dokumentasi untuk modul ini dapat dibuat di [[$1]]''",
	'scribunto-doc-page-header' => "'''Ini adalah halaman dokumentasi untuk [[$1]]'''",
	'scribunto-console-clear' => 'Kosongkan',
	'scribunto-console-cleared' => 'Pernyataan konsol dibersihkan karena modul telah diperbarui.',
	'scribunto-console-cleared-session-lost' => 'Pernyataan konsol dibersihkan karena data sesi hilang.',
	'scribunto-common-error-category' => 'Halaman dengan galat skrip',
	'scribunto-common-nosuchmodule' => 'Galat skrip: tidak ada modul tersebut.',
	'scribunto-lua-backtrace-line' => '$1: $2',
);

/** Iloko (Ilokano)
 * @author Lam-ang
 */
$messages['ilo'] = array(
	'scribunto-desc' => 'Obra a batayan para iti panagikabil ti pagsasao a panagisurat kadagiti panid ti MediaWiki',
	'scribunto-ignore-errors' => 'Agpalubos nga agidulin ti kodigo nga adda dagiti biddutna',
	'scribunto-line' => 'iti linia $1',
	'scribunto-module-line' => 'iti $1 iti linia $2',
	'scribunto-parser-error' => 'Biddut nga eskritu',
	'scribunto-parser-dialog-title' => 'Biddut nga eskritu',
	'scribunto-error-short' => 'Biddut nga eskritu: $1',
	'scribunto-error-long' => 'Dagiti biddut nga eskritu:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Ti dokumentasion para iti daytoy a modulo ket mabalin a mapartuat idiay [[$1]]''",
	'scribunto-doc-page-header' => "'''Daytoy ket dokumentasion a panid para iti [[$1]]'''",
	'scribunto-console-intro' => '* Dagiti modulo nga eksport ket magun-od a kas ti nadumaduma a "p", a mairaman dagiti saan a naidulin a panagbalbaliw.
* Pasarunuan ti maysa a linia iti "=" tapno mapategan daytoy a kas maysa a panangisao, wenno agusar ti prenta().
* Usaren ti mw.log() iti kodigo ti modulo tapno makaipatulod kadagiti mensahe iti daytoy a konsola.',
	'scribunto-console-title' => 'Konsola a pagpasayaatan',
	'scribunto-console-too-large' => 'Dakkel unay daytoy a sesion ti konsola. Pangngaasi a dalusam ti pakasaritaan ti konsola wenno pabassiten ti kadakkel ti modulo.',
	'scribunto-console-current-src' => 'naipastrek ti konsola',
	'scribunto-console-clear' => 'Dalusan',
	'scribunto-console-cleared' => 'Ti kasasaad ti konsola ket nadalusan idi gaputa ti modulo ket napabaro idi.',
	'scribunto-console-cleared-session-lost' => 'Ti kasasaad ti konsola ket nadalusan idi gaputa ti sesion ti datos ket napukaw idi.',
	'scribunto-common-error-category' => 'Pampanid nga adda dagiti biddut ti eskritu',
	'scribunto-common-nosuchmodule' => 'Biddut nga eskritu: Awan ti kasta a modulo.',
	'scribunto-common-nofunction' => 'Biddut nga eskritu: Nasken a naganam ti maawagan a pamay-an.',
	'scribunto-common-nosuchfunction' => 'Biddut nga eskritu: Ti pamay-an nga innaganam ket awan.',
	'scribunto-common-timeout' => 'Ti oras a maipalubos para iti panagpataray kadagiti eskritu ket nagpason.',
	'scribunto-common-oom' => 'Ti kaadu ti lagip a mapalubosan para iti panagpataray kadagiti eskritu ket nalabsanen.',
	'scribunto-common-backtrace' => 'Sublian:',
	'scribunto-lua-in-function' => 'iti pamay-an "$1"',
	'scribunto-lua-in-main' => 'iti kangrunaan a pisi',
	'scribunto-lua-in-function-at' => 'iti pamay-an idiay $1:$2',
	'scribunto-lua-error-location' => '$1 a biddut a Lua: $2.',
	'scribunto-lua-error' => 'Biddut a Lua: $2.',
	'scribunto-lua-noreturn' => 'Biddut nga eskritu: Ti modulo ket saan a nangisubli ti maysa a pateg. Daytoy ket nasken koma nga agisubli ti eksport a tabla.',
	'scribunto-lua-notarrayreturn' => 'Biddut nga eskritu: Ti modulo ket nangisubli ti sabali a saana maysa a tabla. Daytoy ket nasken koma nga agisubli ti eksport a tabla.',
	'scribunto-luastandalone-proc-error' => 'Biddut a Lua: Saan a mapartuat ti pamuspusan.',
	'scribunto-luastandalone-decode-error' => 'Biddut a Lua: Akin-uneg a biddut: Saan a nakudiguan ti mensahe.',
	'scribunto-luastandalone-write-error' => 'Biddut a Lua: Akin-uneg a biddut: Biddut ti panagsurat iti pipa.',
	'scribunto-luastandalone-read-error' => 'Biddut a Lua: Akin-uneg a biddut: Biddut a panagbasa manipud ti pipa.',
	'scribunto-luastandalone-gone' => 'Biddut a Lua: Akin-uneg a biddut: Ti agipatpatarus ket nakaruaren.',
	'scribunto-luastandalone-signal' => 'Biddut a Lua: Akin-uneg a biddut: Ti agipatpatarus ket pinasardengna ti senial ti "$2".',
	'scribunto-luastandalone-exited' => 'Biddut a Lua: Akin-uneg a biddut: Ti agipatpatarus ket rimmuar nga adda ti kasasaad iti $2.',
);

/** Icelandic (íslenska)
 * @author Snævar
 */
$messages['is'] = array(
	'scribunto-ignore-errors' => 'Leyfa vistun kóða með villum',
	'scribunto-line' => 'í línu $1',
	'scribunto-module-line' => 'í $1, línu $2',
	'scribunto-parser-error' => 'Villa í skriftu',
	'scribunto-parser-dialog-title' => 'Villa í skriftu',
	'scribunto-error-short' => 'Villa í skriftu: $1',
	'scribunto-error-long' => 'Villur í skriftu: 

$1',
	'scribunto-doc-page-does-not-exist' => "''Hægt er að búa til leiðbeiningar fyrir þessa skriftu á [[$1]]''",
	'scribunto-doc-page-header' => "'''Þetta er leiðbeiningar síða fyrir [[$1]]'''",
	'scribunto-console-title' => 'Aflúsunar stjórnborð',
	'scribunto-console-too-large' => 'Þessi seta stjórnborðsins er of stór. Vinsamlegast hreinsaðu setu stjórnborðsins eða minnkaðu stærð skriftunnar.',
	'scribunto-console-clear' => 'Hreinsa',
	'scribunto-console-cleared' => 'Staða stjórnborðsins var hreinsuð því skriftan var uppfærð.',
	'scribunto-console-cleared-session-lost' => 'Staða stjórnborðsins var hreinsuð því setu gögn töpuðust.',
	'scribunto-common-error-category' => 'Síður með villum í skriftum',
	'scribunto-common-nosuchmodule' => 'Villa í skriftu: Einingin er ekki til.',
	'scribunto-common-nofunction' => 'Villa í skriftu: Þú þarft að kalla á aðgerð.',
	'scribunto-common-nosuchfunction' => 'Villa í skriftu: Aðgerðin sem þú tilgreindir er ekki til.',
	'scribunto-common-timeout' => 'Skriftan keyrir of lengi.',
	'scribunto-common-oom' => 'Skriftan notar of mikið minni.',
	'scribunto-common-backtrace' => 'Afturekja:',
	'scribunto-lua-in-function' => 'í aðgerðinni „$1"',
	'scribunto-lua-in-main' => 'í aðal hlutanum',
	'scribunto-lua-in-function-at' => 'í aðgerðinni $1:$2',
	'scribunto-lua-error-location' => 'Lua villa $1: $2.',
	'scribunto-lua-error' => 'Lua villa: $2',
	'scribunto-lua-noreturn' => 'Villa í skriftu: Skriftan skilaði ekki gildi. Hún á að skila útflutnings töflu.',
	'scribunto-lua-notarrayreturn' => 'Villa í skriftu: Skriftan skilaði einhverju öðru en töflu. Hún á að skila útflutnings töflu.',
	'scribunto-luastandalone-proc-error' => 'Lua villa: Mistókst að búa til ferli.',
	'scribunto-luastandalone-decode-error' => 'Lua villa: Innri villa: Ófær um að afkóta skilaboð.',
);

/** Italian (italiano)
 * @author Beta16
 * @author Codicorumus
 * @author F. Cosoleto
 * @author Raoli
 */
$messages['it'] = array(
	'scribunto-desc' => 'Framework per incorporare linguaggi di scripting in pagine MediaWiki',
	'scribunto-ignore-errors' => 'Consenti di salvare codice con errori',
	'scribunto-line' => 'alla linea $1',
	'scribunto-module-line' => 'in $1 alla linea $2',
	'scribunto-parser-error' => 'Errore script',
	'scribunto-parser-dialog-title' => 'Errore script',
	'scribunto-error-short' => 'Errore script: $1',
	'scribunto-error-long' => 'Errori script:

$1',
	'scribunto-doc-page-name' => 'Module:$1/man',
	'scribunto-doc-page-does-not-exist' => "''La documentazione per questo modulo può essere creata in [[$1]]''",
	'scribunto-doc-page-header' => "'''Questa è la pagina di documentazione per [[$1]]'''",
	'scribunto-console-intro' => '* Le esportazioni del modulo sono rappresentate dalla variabile "p", incluse le modifiche non salvate.
* Per valutare una linea come espressione, iniziala con "=" oppure usa print().
* Usa mw.log() nel codice del modulo per inviare messaggi a questa console.',
	'scribunto-console-title' => 'Console di debug',
	'scribunto-console-too-large' => 'Questa sessione della console è troppo grande, cancellare la cronologia della console o ridurre le dimensioni del modulo.',
	'scribunto-console-current-src' => 'input della console',
	'scribunto-console-clear' => 'Pulisci',
	'scribunto-console-cleared' => 'Lo stato della console è stato azzerato perché il modulo è stato aggiornato.',
	'scribunto-console-cleared-session-lost' => 'Lo stato della console è stato azzerato perché i dati della sessione sono andati persi.',
	'scribunto-common-error-category' => 'Pagine con errori di script',
	'scribunto-common-nosuchmodule' => 'Errore script: nessun modulo corrispondente trovato.',
	'scribunto-common-nofunction' => 'Errore script: devi specificare una funzione da chiamare.',
	'scribunto-common-nosuchfunction' => 'Errore script: la funzione specificata non esiste.',
	'scribunto-common-timeout' => "Il tempo assegnato per l'esecuzione dello script è scaduto.",
	'scribunto-common-oom' => "La quantità di memoria consentita per l'esecuzione dello script è stata superata.",
	'scribunto-common-backtrace' => 'Backtrace:',
	'scribunto-lua-in-function' => 'nella funzione "$1"',
	'scribunto-lua-in-main' => 'nel blocco principale',
	'scribunto-lua-in-function-at' => 'nella funzione a $1:$2',
	'scribunto-lua-error-location' => 'Errore Lua $1: $2.',
	'scribunto-lua-error' => 'Errore Lua: $2.',
	'scribunto-lua-noreturn' => 'Errore script: il modulo non ha restituito un valore, doveva restituire una tabella in esportazione.',
	'scribunto-lua-notarrayreturn' => 'Errore script: il modulo ha restituito qualcosa di diverso da una tabella, doveva restituire una tabella in esportazione.',
	'scribunto-luastandalone-proc-error' => 'Errore Lua: impossibile creare il processo.',
	'scribunto-luastandalone-proc-error-msg' => 'Errore Lua: impossibile creare il processo: $2.',
	'scribunto-luastandalone-proc-error-proc-open' => 'Errore Lua: impossibile creare il processo: proc_open non è disponibile. Controlla la configurazione della direttiva PHP "disable_functions".',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Errore Lua: impossibile creare il processo. Nota che la configurazione della direttiva deprecata "safe_mode" è attiva.',
	'scribunto-luastandalone-decode-error' => 'Errore Lua: errore interno - impossibile decodificare il messaggio.',
	'scribunto-luastandalone-write-error' => 'Errore Lua: errore interno - errore durante la scrittura nel canale di comunicazione',
	'scribunto-luastandalone-read-error' => 'Errore Lua: errore interno - errore durante la lettura nel canale di comunicazione',
	'scribunto-luastandalone-gone' => "Errore Lua: errore interno - l'interprete è già stato terminato.",
	'scribunto-luastandalone-signal' => 'Errore Lua: errore interno - l\'interprete è terminato con il segnale "$2".',
	'scribunto-luastandalone-exited' => "Errore Lua: errore interno - l'interprete è uscito con stato $2.",
);

/** Japanese (日本語)
 * @author Shirayuki
 * @author Whym
 */
$messages['ja'] = array(
	'scribunto-desc' => 'MediaWiki ページにスクリプト言語を埋め込むフレームワーク',
	'scribunto-ignore-errors' => 'エラーがあるコードを保存できるようにする',
	'scribunto-line' => '$1 行目',
	'scribunto-module-line' => '$1 内、$2 行目',
	'scribunto-parser-error' => 'スクリプトエラー',
	'scribunto-parser-dialog-title' => 'スクリプトエラー',
	'scribunto-error-short' => 'スクリプトエラー: $1',
	'scribunto-error-long' => 'スクリプトエラー:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''このモジュールについての説明文ページを [[$1]] に作成できます''",
	'scribunto-doc-page-header' => "'''このページは、[[[$1]]]の説明文を記述するページです。'''",
	'scribunto-console-intro' => '* モジュールのエクスポートは、保存していない変更も含めて、「p」という変数として利用できます。
* 式として評価するには、行頭に「=」をつけるか print() を使ってください。
* モジュールのコードからこのコンソールにメッセージを送信するには mw.log() を使ってください。',
	'scribunto-console-title' => 'デバッグコンソール',
	'scribunto-console-too-large' => 'このコンソールセッションは大きすぎます。コンソール履歴を消去するか、モジュールのサイズを減らしてください。',
	'scribunto-console-current-src' => 'コンソール入力',
	'scribunto-console-clear' => '消去',
	'scribunto-console-cleared' => 'モジュールが更新されたため、コンソールの状態を消去しました。',
	'scribunto-console-cleared-session-lost' => 'セッションデータが失われたため、コンソールの状態を消去しました。',
	'scribunto-common-error-category' => 'スクリプトエラーがあるページ',
	'scribunto-common-nosuchmodule' => 'スクリプトエラー: そのようなモジュールはありません。',
	'scribunto-common-nofunction' => 'スクリプトエラー: 呼び出す関数を指定してください。',
	'scribunto-common-nosuchfunction' => 'スクリプトエラー: 指定した関数は存在しません。',
	'scribunto-common-timeout' => 'スクリプトに割り当てた時間が終了しました。',
	'scribunto-common-oom' => 'スクリプトの実行で使用を許可されているメモリ量を超過しました。',
	'scribunto-common-backtrace' => 'バックトレース:',
	'scribunto-lua-in-function' => '関数「$1」内',
	'scribunto-lua-in-main' => 'メインチャンク内',
	'scribunto-lua-in-function-at' => '関数内、$1:$2',
	'scribunto-lua-error-location' => 'Lua エラー $1: $2',
	'scribunto-lua-error' => 'Lua エラー: $2',
	'scribunto-lua-noreturn' => 'スクリプトエラー: モジュールは値を返しませんでしたが、書き出しテーブルを返すことになっています。',
	'scribunto-lua-notarrayreturn' => 'スクリプトエラー: モジュールはテーブル以外の何かを返しましたが、書き出しテーブルを返すことになっています。',
	'scribunto-luastandalone-proc-error' => 'Lua エラー: プロセスを作成できません。',
	'scribunto-luastandalone-proc-error-msg' => 'Lua エラー: プロセスを作成できません: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Lua エラー: プロセスを作成できません: proc_open を利用できません。PHP の「disable_function」ディレクティブを確認してください。',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Lua エラー: プロセスを作成できません。廃止された PHP ディレクティブ「safe_mode」が有効になっていることにご注意ください。',
	'scribunto-luastandalone-decode-error' => 'Lua エラー: 内部エラー: メッセージを復号できません。',
	'scribunto-luastandalone-write-error' => 'Lua エラー: 内部エラー: パイプへの書き込みエラーです。',
	'scribunto-luastandalone-read-error' => 'Lua エラー: 内部エラー: パイプからの読み取りエラーです。',
	'scribunto-luastandalone-gone' => 'Lua エラー: 内部エラー: インタープリターは既に終了しています。',
	'scribunto-luastandalone-signal' => 'Lua エラー: 内部エラー: インタープリターはシグナル「$2」により終了しました。',
	'scribunto-luastandalone-exited' => 'Lua エラー: 内部エラー: インタープリターは終了コード $2 で終了しました。',
);

/** Javanese (Basa Jawa)
 * @author NoiX180
 */
$messages['jv'] = array(
	'scribunto-desc' => 'Rangka kerja kanggo nyisipaké basa skrip nèng kaca MediaWiki',
	'scribunto-ignore-errors' => 'Bènaké kasalahan kodhé', # Fuzzy
	'scribunto-line' => 'nèng larik $1',
	'scribunto-module-line' => 'nèng $1 larik $2',
	'scribunto-parser-error' => 'Kasalahan skrip',
	'scribunto-parser-dialog-title' => 'Kasalahan skrip',
	'scribunto-error-short' => 'Kasalahan skrip: $1',
	'scribunto-error-long' => 'Kasalahan skrip:

$1',
	'scribunto-console-title' => 'Debug konsol',
);

/** Georgian (ქართული)
 * @author David1010
 */
$messages['ka'] = array(
	'scribunto-parser-error' => 'სკრიპტის შეცდომა',
	'scribunto-parser-dialog-title' => 'სკრიპტის შეცდომა',
	'scribunto-error-short' => 'სკრიპტის შეცდომა: $1',
	'scribunto-error-long' => 'სკრიპტის შეცდომები:

$1',
	'scribunto-doc-page-name' => 'Module:$1/ინფო',
	'scribunto-doc-page-header' => "''ეს არის „[[$1]]“-ის დოკუმენტაციის გვერდი''",
	'scribunto-console-clear' => 'გასუფთავება',
);

/** Korean (한국어)
 * @author Klutzy
 * @author Kwj2772
 * @author 아라
 */
$messages['ko'] = array(
	'scribunto-desc' => '미디어위키 문서에 스크립트 언어를 넣기 위한 프레임워크',
	'scribunto-ignore-errors' => '오류 코드 저장 허용',
	'scribunto-line' => '$1번째 줄에서',
	'scribunto-module-line' => '$1 $2번째 줄에서',
	'scribunto-parser-error' => '스크립트 오류',
	'scribunto-parser-dialog-title' => '스크립트 오류',
	'scribunto-error-short' => '스크립트 오류: $1',
	'scribunto-error-long' => '스크립트 오류:

$1',
	'scribunto-doc-page-name' => 'Module:$1/설명문서',
	'scribunto-doc-page-does-not-exist' => "''이 모듈에 대한 설명문서는 [[$1]]에서 만들 수 있습니다''",
	'scribunto-doc-page-header' => "'''이 문서는 [[$1]]에 대한 설명문서입니다.'''",
	'scribunto-console-intro' => '* 모듈 내보내기는 저장하지 않은 수정을 포함하여 변수 "p"로 사용할 수 있습니다.
* 표현으로 평가하는 "="이 있는 줄을 앞에 넣거나 print()를 사용하세요.
* 이 콘솔에 메시지를 보낼 모듈 코드에 mw.log()를 사용하세요.',
	'scribunto-console-title' => '콘솔 디버그',
	'scribunto-console-too-large' => '이 콘솔 세션이 너무 큽니다. 콘솔 역사를 삭제하거나 모듈의 크기를 줄이세요.',
	'scribunto-console-current-src' => '콘솔 입력',
	'scribunto-console-clear' => '지우기',
	'scribunto-console-cleared' => '모듈을 업데이트했기 때문에 콘솔 상태가 지워졌습니다.',
	'scribunto-console-cleared-session-lost' => '세션 데이터를 잃었기 때문에 콘솔 상태가 지워졌습니다.',
	'scribunto-common-error-category' => '스크립트 오류가 있는 문서',
	'scribunto-common-nosuchmodule' => '스크립트 오류: 해당 모듈을 찾을 수 없습니다.',
	'scribunto-common-nofunction' => '스크립트 오류: 호출할 함수를 지정해야 합니다.',
	'scribunto-common-nosuchfunction' => '스크립트 오류: 지정한 함수가 존재하지 않습니다.',
	'scribunto-common-timeout' => '스크립트를 실행하는 데 할당한 시간이 만료되었습니다.',
	'scribunto-common-oom' => '스크립트를 실행하는 데 허용하는 메모리 크기를 초과했습니다.',
	'scribunto-common-backtrace' => '역추적:',
	'scribunto-lua-in-function' => '"$1" 함수에서',
	'scribunto-lua-in-main' => '메인 청크에서',
	'scribunto-lua-in-function-at' => '함수 $1, $2번째 줄에서',
	'scribunto-lua-error-location' => '$1 Lua 오류: $2.',
	'scribunto-lua-error' => 'Lua 오류: $2.',
	'scribunto-lua-noreturn' => '스크립트 오류: 모듈이 값을 반환하지 않았습니다. 내보내기 테이블을 반환해야 합니다.',
	'scribunto-lua-notarrayreturn' => '스크립트 오류: 모듈이 테이블이 아닌 다른 무언가를 반환했습니다. 내보내기 테이블을 반환해야 합니다.',
	'scribunto-luastandalone-proc-error' => 'Lua 오류: 프로세스를 생성할 수 없습니다.',
	'scribunto-luastandalone-proc-error-msg' => 'Lua 오류: 프로세스를 생성할 수 없습니다: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Lua 오류: 프로세스를 생성할 수 없습니다: proc_open은 사용할 수 없습니다. PHP의 "disable_functions" 설정을 확인하십시오.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Lua 오류: 프로세스를 생성할 수 없습니다. 참고로 PHP의 사용 폐기된 "safe_mode" 설정이 활성화되었습니다.',
	'scribunto-luastandalone-decode-error' => 'Lua 오류: 내부 오류: 메시지를 디코드할 수 없습니다.',
	'scribunto-luastandalone-write-error' => 'Lua 오류: 내부 오류: 파이프에 쓰는 도중 오류가 발생했습니다.',
	'scribunto-luastandalone-read-error' => 'Lua 오류: 내부 오류: 파이프에서 읽는 도중 오류가 발생했습니다.',
	'scribunto-luastandalone-gone' => 'Lua 오류: 내부 오류: 인터프리터가 이미 종료되었습니다.',
	'scribunto-luastandalone-signal' => 'Lua 오류: 내부 오류: 인터프리터가 "$2" 신호로 종료했습니다.',
	'scribunto-luastandalone-exited' => 'Lua 오류: 내부 오류: 인터프리터가 $2 상태로 종료했습니다.',
);

/** Kurdish (Latin script) (Kurdî (latînî)‎)
 * @author Ghybu
 * @author Gomada
 */
$messages['ku-latn'] = array(
	'scribunto-ignore-errors' => 'Destûra tomarkirina kodan a bi çewtiyan bide',
	'scribunto-line' => 'li ser rêza $1',
	'scribunto-module-line' => 'di $1 de, li ser rêza $2',
	'scribunto-parser-error' => 'Çewtiya nivîsê',
	'scribunto-parser-dialog-title' => 'Çewtiya nivîsê',
	'scribunto-error-short' => 'Çewtiya nivîsê: $1',
	'scribunto-error-long' => 'Çewtiyên nivîsê:

$1',
	'scribunto-doc-page-name' => 'Module:$1/belge',
	'scribunto-doc-page-header' => "'''Ev rûpela belgekirinê ya [[$1]] ye'''",
	'scribunto-console-clear' => 'Jê bibe',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'scribunto-line' => 'an der Linn $1',
	'scribunto-parser-error' => 'Script-Feeler',
	'scribunto-parser-dialog-title' => 'Script-Feeler',
	'scribunto-error-short' => 'Script-Feeler: $1',
	'scribunto-error-long' => 'Script-Feeler:

$1',
	'scribunto-doc-page-name' => 'Modul:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''D'Dokumentatioun fir dëse Modul kann op [[$1]] ugeluecht ginn''",
	'scribunto-doc-page-header' => "'''Dëst ass d'Dokumentationsäit fir [[$1]]'''",
	'scribunto-console-clear' => 'Eidel maachen',
	'scribunto-common-error-category' => 'Säite mat Scriptfeeler',
	'scribunto-lua-error-location' => 'Lua-Feeler $1: $2.',
	'scribunto-lua-error' => 'Lua Feeler: $2',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'scribunto-desc' => 'Рамка за вметнување на скриптни јазици во страница на МедијаВики',
	'scribunto-ignore-errors' => 'Дозволи зачувување на кодот и кога има грешки',
	'scribunto-line' => 'во редот $1',
	'scribunto-module-line' => 'во $1, ред $2',
	'scribunto-parser-error' => 'Грешка во скриптата',
	'scribunto-parser-dialog-title' => 'Грешка во скриптата',
	'scribunto-error-short' => 'Грешка во скриптата: $1',
	'scribunto-error-long' => 'Грешки во скриптата:

$1',
	'scribunto-doc-page-name' => 'Module:$1/док',
	'scribunto-doc-page-does-not-exist' => "''Документацијата за овој модул можете да ја создадете на [[$1]]''",
	'scribunto-doc-page-header' => "'''Ова е страницата за документација на [[$1]]'''",
	'scribunto-console-intro' => '* Извозот на модулот е достапен како променлива „p“, вклучувајќи ги незачуваните измени.
* Пред редот ставајте „=“ за да ја вреднувате како израз, или пак користете print().
* Во кодот на модулот користете mw.log() за испраќање на пораки до оваа конзола.',
	'scribunto-console-title' => 'Конзола за решавање на грешки',
	'scribunto-console-too-large' => 'Оваа сесија на конзолата е преголема. Исчистете ја нејзината историја или намалете ја големината на модулот.',
	'scribunto-console-current-src' => 'внос во конзолата',
	'scribunto-console-clear' => 'Исчисти',
	'scribunto-console-cleared' => 'Состојбата на конзолата е исчистена поради поднова на модулот.',
	'scribunto-console-cleared-session-lost' => 'Состојбата на конзолата е исчистена поради загуба на сесиските податоци.',
	'scribunto-common-error-category' => 'Страници со грешки во скриптата',
	'scribunto-common-nosuchmodule' => 'Грешка во скриптата: Нема таков модул',
	'scribunto-common-nofunction' => 'Грешка во скриптата: Мора да ја наведете функцијата што треба да се повика.',
	'scribunto-common-nosuchfunction' => 'Грешка во скриптата: Наведената функција не постои.',
	'scribunto-common-timeout' => 'Зададеното време за работа на скриптите истече.',
	'scribunto-common-oom' => 'Надминат е дозволениот складишен простор за работа на скрипти.',
	'scribunto-common-backtrace' => 'Проследување на текот:',
	'scribunto-lua-in-function' => 'во функцијата „$1“',
	'scribunto-lua-in-main' => 'во главниот дел',
	'scribunto-lua-in-function-at' => 'во функцијата, кај $1:$2',
	'scribunto-lua-error-location' => 'Грешка во Lua $1: $2',
	'scribunto-lua-error' => 'Грешка во Lua: $2',
	'scribunto-lua-noreturn' => 'Грешка во скриптата: Модулот не врати вредност. Треба да врати извозна табела.',
	'scribunto-lua-notarrayreturn' => 'Грешка во скриптата: Модулот не врати табела, туку нешто друго. Треба да врати извозна табела.',
	'scribunto-luastandalone-proc-error' => 'Грешка во Lua: не можам да ја создадам постапката',
	'scribunto-luastandalone-proc-error-msg' => 'Грешка во Lua: не можам да ја создадам постапката: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Гречка во Lua: Не можам да ја создадам постапката: proc_open е недостапен. Проверете ја наредбата „disable_functions“ во поставките за PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Гречка во Lua: Не можам да ја создадам постапката: Застарената наредба „safe_mode“ во поставките за PHP е вклучена.',
	'scribunto-luastandalone-decode-error' => 'Грешка во Lua: внатрешна грешка: не можам да ја декодирам пораката',
	'scribunto-luastandalone-write-error' => 'Грешка во Lua: внатрешна грешка: грешка при записот',
	'scribunto-luastandalone-read-error' => 'Грешка во Lua: внатрешна грешка: грешка при читањето',
	'scribunto-luastandalone-gone' => 'Грешка во Lua: внатрешна грешка: толкувачот веќе напушти',
	'scribunto-luastandalone-signal' => 'Грешка во Lua: внатрешна грешка: толкувачот прекина да работи со сигналот „$2“',
	'scribunto-luastandalone-exited' => 'Грешка во Lua: внатрешна грешка: толкувачот напушти со статусот $2',
);

/** Malayalam (മലയാളം)
 * @author Praveenp
 */
$messages['ml'] = array(
	'scribunto-desc' => 'മീഡിയവിക്കി താളുകളിൽ സ്ക്രിപ്റ്റിങ് ഭാഷകൾ ഉൾക്കൊള്ളിക്കാനുള്ള ചട്ടക്കൂട്',
	'scribunto-ignore-errors' => 'കോഡ് പിഴവുകളോടെ സേവ് ചെയ്യാൻ അനുവദിക്കുക',
	'scribunto-line' => '$1 വരിയിൽ',
	'scribunto-module-line' => '$1-ൽ $2 വരിയിൽ',
	'scribunto-parser-error' => 'സ്ക്രിപ്റ്റ് പിഴവ്',
	'scribunto-parser-dialog-title' => 'സ്ക്രിപ്റ്റ് പിഴവ്',
	'scribunto-error-short' => 'സ്ക്രിപ്റ്റ് പിഴവ്: $1',
	'scribunto-error-long' => 'സ്ക്രിപ്റ്റ് പിഴവുകൾ:

$1',
	'scribunto-doc-page-name' => 'ഘടകം: $1/വിവരണം',
	'scribunto-doc-page-does-not-exist' => "''ഈ ഘടകത്തിന്റെ വിവരണം [[$1]] എന്ന താളിൽ നിർമ്മിക്കാവുന്നതാണ്''",
	'scribunto-doc-page-header' => "'''ഇത് [[$1]] എന്നതിന്റെ വിവരണതാൾ ആണ്'''",
	'scribunto-console-clear' => 'ശൂന്യമാക്കുക',
	'scribunto-lua-error-location' => 'ലുവ പിഴവ് $1 : $2',
	'scribunto-lua-error' => 'ലുവ പിഴവ്: $2.',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'scribunto-desc' => 'Kerangka pembenaman bahasa skrip dalam halaman MediaWiki',
	'scribunto-ignore-errors' => 'Benarkan penyimpanan kod dengan ralat',
	'scribunto-line' => 'pada baris $1',
	'scribunto-module-line' => 'pada baris $2 di $1',
	'scribunto-parser-error' => 'Ralat skrip',
	'scribunto-parser-dialog-title' => 'Ralat skrip',
	'scribunto-error-short' => 'Ralat skrip: $1',
	'scribunto-error-long' => 'Ralat-ralat skrip:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Dokumentasi untuk modul ini boleh diwujudkan di [[$1]]''",
	'scribunto-doc-page-header' => "'''Ini ialah halaman pendokumenan untuk [[$1]]'''",
	'scribunto-console-intro' => '* Eksport modul terdapat dalam bentuk pembolehubah "p", termasuk pengubahsuaian yang belum disimpan.
* Dahului baris dengan tanda "=" untuk menilainya sebagai ungkapan, atau gunakan print().
* Gunakan mw.log() dalam kod modul untuk menghantar mesej ke konsol ini.',
	'scribunto-console-title' => 'Konsol penyahpepijatan',
	'scribunto-console-too-large' => 'Sesi konsol ini terlalu besar. Sila padamkan sejarah konsol atau kecilkan saiz modul.',
	'scribunto-console-current-src' => 'input konsol',
	'scribunto-console-clear' => 'Padamkan',
	'scribunto-console-cleared' => 'Keadaan konsol dipadamkan kerana modul dikemaskinikan.',
	'scribunto-console-cleared-session-lost' => 'Keadaan konsol dipadamkan kerana kehilangan data sesi.',
	'scribunto-common-error-category' => 'Halaman yang ada ralat skrip',
	'scribunto-common-nosuchmodule' => 'Ralat skrip: Modul ini tidak wujud.',
	'scribunto-common-nofunction' => 'Ralat skrip: Anda mesti menyatakan fungsi yang hendak diseru.',
	'scribunto-common-nosuchfunction' => 'Ralat skrip: Fungsi yang anda nyatakan itu tidak wujud.',
	'scribunto-common-timeout' => 'Masa yang diperuntukkan untuk menjalankan skrip sudah luput.',
	'scribunto-common-oom' => 'Had jumlah ingatan yang dibenarkan untuk menjalankan skrip sudah dilampaui.',
	'scribunto-common-backtrace' => 'Jejak balik:',
	'scribunto-lua-in-function' => 'dalam fungsi "$1"',
	'scribunto-lua-in-main' => 'dalam ketulan utama',
	'scribunto-lua-in-function-at' => 'dalam fungsi di $1:$2',
	'scribunto-lua-error-location' => 'Ralat Lua $1: $2.',
	'scribunto-lua-error' => 'Ralat Lua: $2.',
	'scribunto-lua-noreturn' => 'Ralat skrip: Modul tidak memulangkan nilai. Ia sepatutnya memulangkan jadual eksport.',
	'scribunto-lua-notarrayreturn' => 'Ralat skrip: Modul sepatutnya memulangkan jadual eksport, tetapi lain pula yang dipulangkannya.',
	'scribunto-luastandalone-proc-error' => 'Ralat Lua: Proses tidak boleh diwujudkan.',
	'scribunto-luastandalone-proc-error-msg' => 'Ralat Lua: Proses tidak boleh diwujudkan: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Ralat Lua: Proses tidak boleh diwujudkan: proc_open tidak disediakan. Semak arahan konfigurasi "disable_functions" PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Ralat Lua: Proses tidak boleh diwujudkan. Pastikan arahan konfigurasi "safe_mode" PHP yang lapuk itu dihidupkan.',
	'scribunto-luastandalone-decode-error' => 'Ralat Lua: Ralat dalaman: Pesanan tidak dapat dinyahkod.',
	'scribunto-luastandalone-write-error' => 'Ralat Lua: Ralat dalaman: Ralat ketika menulis pada paip.',
	'scribunto-luastandalone-read-error' => 'Ralat Lua: Ralat dalaman: Ralat ketika membaca dari paip.',
	'scribunto-luastandalone-gone' => 'Ralat Lua: Ralat dalaman: Pentafsir sudah keluar.',
	'scribunto-luastandalone-signal' => 'Ralat Lua: Ralat dalaman: Pentafsir sudah berhenti dengan isyarat "$2".',
	'scribunto-luastandalone-exited' => 'Ralat Lua: Ralat dalaman: Pentafsir sudah berhenti dengan status "$2".',
);

/** Norwegian Bokmål (norsk bokmål)
 * @author Danmichaelo
 */
$messages['nb'] = array(
	'scribunto-desc' => 'Rammeverk for å bygge inn scriptspråk i MediaWiki-sider',
	'scribunto-ignore-errors' => 'Tillatt lagring av kode med feil',
	'scribunto-line' => 'i linje $1',
	'scribunto-module-line' => 'i $1, linje $2',
	'scribunto-parser-error' => 'Skriptfeil',
	'scribunto-parser-dialog-title' => 'Skriptfeil',
	'scribunto-error-short' => 'Skriptfeil: $1',
	'scribunto-error-long' => 'Skriptfeil:

$1',
	'scribunto-doc-page-name' => 'Module:$1/dok',
	'scribunto-doc-page-does-not-exist' => "''Dokumentasjon for denne modulen kan opprettes på [[$1]]''",
	'scribunto-doc-page-header' => "'''Dette er dokumentasjonssiden for [[$1]]'''",
	'scribunto-console-intro' => '* Moduleksporteringer er tilgjengelig som variabelen «p», inkludert ulagrede endringer.
* Start en linje med «=» for å evaluere den som et uttrykk, eller bruk print().
* Bruk mw.log() i modulkode for å sende meldinger til denne konsollen.',
	'scribunto-console-title' => 'Feilsøkingskonsoll',
	'scribunto-console-too-large' => 'Denne konsollsesjonen er for stor. Vennligst tøm konsollhistorikken eller reduser størrelsen på modulen.',
	'scribunto-console-current-src' => 'konsollinndata',
	'scribunto-console-clear' => 'Tøm',
	'scribunto-console-cleared' => 'Konsolltilstanden ble tømt siden modulen ble oppdatert.',
	'scribunto-console-cleared-session-lost' => 'Konsolltilstanden ble tømt fordi sesjonsdata ble mistet.',
	'scribunto-common-error-category' => 'Sider med skriptfeil',
	'scribunto-common-nosuchmodule' => 'Skriptfeil: Ingen slik modul',
	'scribunto-common-nofunction' => 'Skriptfeil: Du må angi en funksjon som skal kalles.',
	'scribunto-common-nosuchfunction' => 'Skriptfeil: Funksjonen du anga eksisterer ikke.',
	'scribunto-common-backtrace' => 'Tilbakesporing:',
	'scribunto-lua-in-function' => 'i funksjon "$1"',
	'scribunto-lua-in-function-at' => 'i funksjonen ved $1:$2',
	'scribunto-lua-error-location' => 'Lua-feil $1: $2.',
	'scribunto-lua-error' => 'Lua-feil: $2.',
	'scribunto-lua-noreturn' => 'Skriptfeil: Modulen returnerte ingen verdi. Det forventes at den returnerer en eksporttabell.',
	'scribunto-lua-notarrayreturn' => 'Skriptfeil: Modulen returnerte noe annet enn en tabell. Det forventes at den returnerer en eksporttabell.',
	'scribunto-luastandalone-proc-error' => 'Lua-feil: Kan ikke opprette prosess.',
	'scribunto-luastandalone-decode-error' => 'Lua-feil: Intern feil: Kunne ikke dekode meldingen.',
	'scribunto-luastandalone-write-error' => 'Lua-feil: Intern feil: Feil ved skriving til rør.',
	'scribunto-luastandalone-read-error' => 'Lua-feil: Intern feil: Feil ved lesing fra rør.',
	'scribunto-luastandalone-gone' => 'Lua-feil: Intern feil: Fortolkeren har allerede avsluttet.',
	'scribunto-luastandalone-signal' => 'Lua-feil: Intern feil: Fortolkeren har avsluttet med signal «$2».',
	'scribunto-luastandalone-exited' => 'Lua-feil: Intern feil: Fortolkeren har avsluttet med status $2.',
);

/** Dutch (Nederlands)
 * @author Konovalov
 * @author SPQRobin
 * @author Saruman
 * @author Siebrand
 */
$messages['nl'] = array(
	'scribunto-desc' => "Framework voor het inbedden van scripttalen in pagina's",
	'scribunto-ignore-errors' => 'Opslaan van code met fouten toestaan',
	'scribunto-line' => 'op regel $1',
	'scribunto-module-line' => 'in $1 op regel $2',
	'scribunto-parser-error' => 'Scriptfout',
	'scribunto-parser-dialog-title' => 'Scriptfout',
	'scribunto-error-short' => 'Scriptfout: $1',
	'scribunto-error-long' => 'Scriptfouten:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Documentatie voor deze module kan aangemaakt worden op de volgende pagina: [[$1]]''",
	'scribunto-doc-page-header' => "'''Dit is de documentatiepagina voor [[$1]]'''",
	'scribunto-console-intro' => '* De moduleexports zijn beschikbaar als de variabele "p", inclusief nog niet opgeslagen wijzigingen;
* Begin een regel met "=" om deze als expressie te evalueren, of gebruik print();
* Gebruik mw.log() in modulecode om berichten aan deze console te zenden.',
	'scribunto-console-title' => 'Debugconsole',
	'scribunto-console-too-large' => 'Deze consolesessie is te groot. Wis de consolegeschiedenis of beperk de grootte van de module.',
	'scribunto-console-current-src' => 'consoleinvoer',
	'scribunto-console-clear' => 'Wissen',
	'scribunto-console-cleared' => 'De consolestatus is gewist omdat de module is bijgewerkt.',
	'scribunto-console-cleared-session-lost' => 'De consolestatus is gewist omdat de sessiegegevens verloren zijn gegaan.',
	'scribunto-common-error-category' => "Pagina's met scriptfouten",
	'scribunto-common-nosuchmodule' => 'Scriptfout: de module bestaat niet.',
	'scribunto-common-nofunction' => 'Scriptfout: u moet een aan te roepen functie opgeven.',
	'scribunto-common-nosuchfunction' => 'Scriptfout: de opgegeven functie bestaat niet.',
	'scribunto-common-timeout' => 'De maximale uitvoertijd voor scripts is verlopen.',
	'scribunto-common-oom' => 'De hoeveelheid geheugen die uitgevoerde scripts mogen gebruiken is overschreden.',
	'scribunto-common-backtrace' => 'Backtrace:',
	'scribunto-lua-in-function' => 'in functie "$1"',
	'scribunto-lua-in-main' => 'in het hoofdgedeelte',
	'scribunto-lua-in-function-at' => 'in de functie op $1:$2',
	'scribunto-lua-error-location' => 'Luafout $1: $2',
	'scribunto-lua-error' => 'Luafout: $2',
	'scribunto-lua-noreturn' => 'Scriptfout: de module heeft geen waarde teruggegeven. Deze hoort een exporttabel terug te geven.',
	'scribunto-lua-notarrayreturn' => 'Scriptfout: de module heeft iets anders dan een tabel teruggegeven. Deze hoort een exporttabel terug te geven.',
	'scribunto-luastandalone-proc-error' => 'Luafout: het was niet mogelijk een proces te creëren.',
	'scribunto-luastandalone-decode-error' => 'Luafout: interne fout: het was niet mogelijk het bericht te decoderen.',
	'scribunto-luastandalone-write-error' => 'Luafout: interne fout: fout tijdens het schrijven naar de pipe.',
	'scribunto-luastandalone-read-error' => 'Luafout: interne fout: fout tijdens het lezen van de pipe.',
	'scribunto-luastandalone-gone' => 'Luafout: interne fout: de verwerkingsmodule is al klaar',
	'scribunto-luastandalone-signal' => 'Luafout: interne fout: de verwerkingsmodule is gestopt met het signaal "$2".',
	'scribunto-luastandalone-exited' => 'Luafout: interne fout: de verwerkingsmodule is gestopt met de status $2.',
);

/** Norwegian Nynorsk (norsk nynorsk)
 * @author Njardarlogar
 */
$messages['nn'] = array(
	'scribunto-desc' => 'Rammeverk for å byggja inn skriptspråk i MediaWiki-sider',
	'scribunto-ignore-errors' => 'Tillat lagring av kode med feil',
	'scribunto-line' => 'på line $1',
	'scribunto-module-line' => 'i $1, line $2',
	'scribunto-parser-error' => 'Skriptfeil',
	'scribunto-parser-dialog-title' => 'Skriptfeil',
	'scribunto-error-short' => 'Skriptfeil: $1',
	'scribunto-error-long' => 'Skriptfeil:

$1',
	'scribunto-doc-page-name' => 'Module:$1/dok',
	'scribunto-doc-page-does-not-exist' => "''Dokumentasjon for modulen kan opprettast på [[$1]]''",
	'scribunto-doc-page-header' => "'''Dette er dokumentasjonssida for [[$1]]'''",
	'scribunto-console-intro' => '* Moduleksporteringar er tilgjengelege som variabelen «p», inkludert ulagra endringar.
* Byrja ei line med «=» for å evaluera henne som eit uttrykk, eller bruk print().
* Bruk mw.log() i modulkode for å senda meldingar til denne konsollen.',
	'scribunto-console-title' => 'Feilsøkingskonsoll',
	'scribunto-console-too-large' => 'Konsolløkta er for stor. Tøm konsollhistorikken eller minsk storleiken på modulen.',
	'scribunto-console-current-src' => 'konsollinndata',
	'scribunto-console-clear' => 'Tøm',
	'scribunto-console-cleared' => 'Konsollstoda vart tømd av di modulen vart oppdatert.',
	'scribunto-console-cleared-session-lost' => 'Konsollstoda vart tømd av di øktdataa vart tapte.',
	'scribunto-common-error-category' => 'Sider med skriptfeil',
	'scribunto-common-nosuchmodule' => 'Skriptfeil: Modulen finst ikkje.',
	'scribunto-common-nofunction' => 'Skriptfeil: du lyt oppgje ein funksjon som skal kallast.',
	'scribunto-common-nosuchfunction' => 'Skriptfeil: funksjonen du oppgav finst ikkje.',
	'scribunto-common-timeout' => 'Tida tildelt skriptkøyring har gått ut.',
	'scribunto-common-oom' => 'Mengda minne tildelt skriptkøyring er overstigen.',
	'scribunto-common-backtrace' => 'Attendesporing:',
	'scribunto-lua-in-function' => 'i funksjonen «$1»',
	'scribunto-lua-in-main' => 'i hovuddelen',
	'scribunto-lua-in-function-at' => 'i funksjonen ved $1:$2',
	'scribunto-lua-error-location' => 'Lua-feil $1: $2.',
	'scribunto-lua-error' => 'Lua-feil: $2.',
	'scribunto-lua-noreturn' => 'Skriptfeil: modulen returnerte ikkje ein verdi. Han er meint å returnera ein eksporteringstabell.',
	'scribunto-lua-notarrayreturn' => 'Skriptfeil: modulen returnerte noko anna enn ein tabell. Han er meint å returnera ein eksporteringstabell.',
	'scribunto-luastandalone-proc-error' => 'Lua-feil: kan ikkje oppretta prosess.',
	'scribunto-luastandalone-decode-error' => 'Lua-feil: Intern feil: kan ikkje avkoda melding.',
	'scribunto-luastandalone-write-error' => 'Lua-feil: Intern feil: feil under skriving til røyret.',
	'scribunto-luastandalone-read-error' => 'Lua-feil: Intern feil: feil under lesing av røyret.',
	'scribunto-luastandalone-gone' => 'Lua-feil: Intern feil: tolkaren har alt avslutta.',
	'scribunto-luastandalone-signal' => 'Lua-feil: Intern feil: tolkaren har avslutta med signalet «$2».',
	'scribunto-luastandalone-exited' => 'Lua-feil: Intern feil: tolkaren avslutta med stoda $2.',
);

/** Polish (polski)
 * @author Beau
 * @author Matma Rex
 */
$messages['pl'] = array(
	'scribunto-desc' => 'Framework pozwalający na osadzanie języków skryptowych na stronach MediaWiki',
	'scribunto-ignore-errors' => 'Pozwól na zapisywanie kodu z błędami',
	'scribunto-line' => 'w linii $1',
	'scribunto-module-line' => 'w module „$1”, w linii $2',
	'scribunto-parser-error' => 'Błąd skryptu',
	'scribunto-parser-dialog-title' => 'Błąd skryptu',
	'scribunto-error-short' => 'Błąd skryptu: $1',
	'scribunto-error-long' => 'Błędy skryptu:

$1',
	'scribunto-doc-page-name' => 'Module:$1/opis',
	'scribunto-doc-page-does-not-exist' => "''Dokumentacja dla tego modułu może zostać utworzona pod nazwą [[$1]]''",
	'scribunto-doc-page-header' => "'''To jest podstrona dokumentacji dla [[$1]]'''", # Fuzzy
	'scribunto-console-title' => 'Konsola debugowania',
	'scribunto-console-current-src' => 'wejście z konsoli',
	'scribunto-console-clear' => 'Wyczyść',
	'scribunto-common-error-category' => 'Strony z błędami skryptów',
	'scribunto-common-nosuchmodule' => 'Błąd skryptu: nie ma takiego modułu.',
	'scribunto-common-nofunction' => 'Błąd skryptu: nie została podana nazwa funkcji.',
	'scribunto-common-nosuchfunction' => 'Błąd skryptu: nie ma takiej funkcji.',
	'scribunto-common-backtrace' => 'Backtrace:',
	'scribunto-lua-in-function' => 'w funkcji „$1”',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 * @author පසිඳු කාවින්ද
 */
$messages['pms'] = array(
	'scribunto-desc' => "Quàder për l'antëgrassion dij langagi ëd copin an dle pàgine ëd MediaWiki",
	'scribunto-ignore-errors' => "Përmëtte ëd salvé ëd còdes con dj'eror",
	'scribunto-line' => 'a la linia $1',
	'scribunto-module-line' => 'an $1 a la linia $2',
	'scribunto-parser-error' => 'Eror ëd copion',
	'scribunto-parser-dialog-title' => 'Eror ëd copion',
	'scribunto-error-short' => 'Eror ëd copion: $1',
	'scribunto-error-long' => 'Eror ëd copion:

$1',
	'scribunto-console-intro' => "* J'esportassion ëd mòdul a son disponìbij com la variàbil «p», comprèise le modìfiche nen salvà.
* Ch'a ancamin-a na linia con «=» për valutela com n'espression, o ch'a deuvra print().
* Ch'a deuvra mw.log() ant ël còdes dël mòdul për mandé dij mëssagi a costa plancia.",
	'scribunto-console-title' => "Plancia d'eliminassion dij givo",
	'scribunto-console-too-large' => "Sta session ëd plancia a l'é tròp gròssa. Për piasì, ch'a scancela la stòria dla plancia o ch'a riduv la dimension dël mòdul.",
	'scribunto-console-current-src' => 'anseriment ëd la plancia',
	'scribunto-console-clear' => 'Scancela',
	'scribunto-console-cleared' => "Lë stat ëd la plancia a l'é stàit ëscancelà përché ël mòdul a l'é stàit agiornà.",
	'scribunto-common-error-category' => 'Pàgine con eror ëd copion',
	'scribunto-common-nosuchmodule' => 'Eror ëd copion: Gnun mòduj parèj.',
	'scribunto-common-nofunction' => 'Eror ëd copion. A dev specifiché na funsion da ciamé.',
	'scribunto-common-nosuchfunction' => "Eror ëd copion. La funsion ch'a l'ha specificà a esist nen.",
	'scribunto-common-timeout' => "Ël temp assignà për fé andé ij copion a l'é finì.",
	'scribunto-common-oom' => "La quantità ëd memòria assignà për fé marcé ij copion a l'é stàita sorpassà.",
	'scribunto-common-backtrace' => 'Marca andré:',
	'scribunto-lua-in-function' => 'ant la funsion "$1"',
	'scribunto-lua-in-main' => 'ant ël blòch prinsipal',
	'scribunto-lua-in-function-at' => 'ant la funsion a $1:$2',
	'scribunto-lua-error-location' => 'Eror Lua $1: $2.',
	'scribunto-lua-error' => 'Eror Lua: $2.',
	'scribunto-lua-noreturn' => "Eror ëd copion. Ël mòdul a l'ha dàit gnun valor. A dovrìa dé na tàula d'esportassion.",
	'scribunto-lua-notarrayreturn' => "Eror ëd copion: Ël mòdul a l'ha dàit quaicòs d'àutr nopà che na tàula. A dovrìa dé na tàula d'esportassion.",
	'scribunto-luastandalone-proc-error' => 'Eror Lua: As peul pa creesse un process.',
	'scribunto-luastandalone-decode-error' => 'Eror Lua: Eror intern: A peul pa decodifichesse ël mëssagi.',
	'scribunto-luastandalone-write-error' => 'Eror Lua: Eror intern: Eror scrivend ant ël canal.',
	'scribunto-luastandalone-read-error' => 'Eror Lua: Eror intern: Eror ëd letura dal canal.',
	'scribunto-luastandalone-gone' => "Eror Lua: Eror intern: L'antérpret a l'ha già finì.",
	'scribunto-luastandalone-signal' => "Eror Lua: Eror intern: L'antérpret a l'ha finì con ël signal «$2».",
	'scribunto-luastandalone-exited' => "Eror Lua: Eror intern: L'antérpret a l'ha finì con lë statù $2.",
);

/** Portuguese (português)
 * @author Helder.wiki
 * @author SandroHc
 */
$messages['pt'] = array(
	'scribunto-desc' => "''Framework'' para incluir linguagens de scripts em páginas do MediaWiki",
	'scribunto-ignore-errors' => 'Permitir que o código seja gravado com erros',
	'scribunto-line' => 'na linha $1',
	'scribunto-module-line' => 'em $1 na linha $2',
	'scribunto-parser-error' => 'Erro no script',
	'scribunto-parser-dialog-title' => 'Erro no script',
	'scribunto-error-short' => 'Erro no script: $1',
	'scribunto-error-long' => 'Erro no script:

$1',
	'scribunto-doc-page-name' => 'Módulo:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''A documentação para este módulo pode ser criada na página [[$1]]''",
	'scribunto-doc-page-header' => "'''Esta é a página de documentação de [[$1]]'''",
	'scribunto-console-clear' => 'Limpar',
	'scribunto-common-error-category' => 'Páginas com erros de scripts',
	'scribunto-common-backtrace' => 'Pilha de chamadas:',
	'scribunto-lua-in-function' => 'na função "$1"',
	'scribunto-lua-in-main' => 'na parte principal',
	'scribunto-lua-in-function-at' => 'na função em $1:$2',
	'scribunto-lua-error-location' => 'Erro em Lua $1: $2.',
	'scribunto-lua-error' => 'Erro em Lua: $2.',
	'scribunto-luastandalone-proc-error' => 'Erro em Lua: não é possível criar o processo.',
	'scribunto-luastandalone-decode-error' => 'Erro em Lua: erro interno: não é possível decodificar a mensagem.',
	'scribunto-luastandalone-write-error' => 'Erro em Lua: erro interno: erro ao escrever na canalização.',
	'scribunto-luastandalone-read-error' => 'Erro em Lua: erro interno: erro ao ler da canalização.',
	'scribunto-luastandalone-gone' => 'Erro em Lua: erro interno: o interpretador já foi encerrado.',
	'scribunto-luastandalone-signal' => 'Erro em Lua: erro interno: o interpretador terminou com o sinal "$2".',
	'scribunto-luastandalone-exited' => 'Erro em Lua: erro interno: o interpretador finalizou com o estado $2.',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Jaideraf
 */
$messages['pt-br'] = array(
	'scribunto-desc' => 'Estrutura para incorporar linguagens de script em páginas do MediaWiki',
	'scribunto-ignore-errors' => 'Permitir salvar o código com erros',
	'scribunto-line' => 'na linha $1',
	'scribunto-module-line' => 'em $1 na linha $2',
	'scribunto-parser-error' => 'Erro de script',
	'scribunto-parser-dialog-title' => 'Erro de script',
	'scribunto-error-short' => 'Erro de script: $1',
	'scribunto-error-long' => 'Erros de script:

$1',
	'scribunto-doc-page-name' => 'doc', # Fuzzy
	'scribunto-doc-page-does-not-exist' => "''A documentação para este módulo pode ser criada em [[$1]]''",
	'scribunto-doc-page-header' => "'''Esta é a subpágina de documentação para [[$1]]'''", # Fuzzy
	'scribunto-console-intro' => '* As exportações do módulo estão disponíveis por meio da variável "p", incluindo modificações não salvas.
* Preceda uma linha com "=" para avaliá-la como uma expressão ou utilize print().
* Utilize mw.log() no código do módulo para enviar mensagens para este interpretador de comandos.',
	'scribunto-console-title' => 'Interpretador de depuração de erros',
	'scribunto-console-too-large' => 'Esta sessão do interpretador está muito grande. Por favor, limpe o histórico do interpretador ou reduza o tamanho do módulo.',
	'scribunto-console-current-src' => 'entrada do interpretador',
	'scribunto-console-clear' => 'Limpar',
	'scribunto-console-cleared' => 'O estado do interpretador foi esvaziado porque o módulo foi atualizado.',
	'scribunto-console-cleared-session-lost' => 'O estado do interpretador foi esvaziado porque os dados de sessão foram perdidos.',
	'scribunto-common-error-category' => 'Páginas com erros de script',
	'scribunto-common-nosuchmodule' => 'Erro de script: módulo não encontrado',
	'scribunto-common-nofunction' => 'Erro de script: você deve especificar uma função para chamar.',
	'scribunto-common-nosuchfunction' => 'Erro de script: a função especificada não existe.',
	'scribunto-common-timeout' => 'O tempo alocado para a execução de scripts expirou.',
	'scribunto-common-oom' => 'A quantidade de memória permitida para a execução de scripts foi excedida.',
	'scribunto-common-backtrace' => 'Backtrace:',
	'scribunto-lua-in-function' => 'na função "$1"',
	'scribunto-lua-in-main' => 'na parte principal',
	'scribunto-lua-in-function-at' => 'na função em $1:$2',
	'scribunto-lua-error-location' => 'Erro em Lua $1: $2',
	'scribunto-lua-error' => 'Erro em lua: $2',
	'scribunto-lua-noreturn' => 'Erro de script: o módulo não retornou um valor, ele deveria retornar uma tabela de exportação.',
	'scribunto-lua-notarrayreturn' => 'Erro de script: o módulo retornou algo diferente de uma tabela, ele deveria retornar uma tabela de exportação.',
	'scribunto-luastandalone-proc-error' => 'Erro em Lua: impossível criar o processo',
	'scribunto-luastandalone-decode-error' => 'Erro em Lua: erro interno: não foi possível decodificar a mensagem',
	'scribunto-luastandalone-write-error' => 'Erro em Lua: erro interno: erro ao gravar pipe',
	'scribunto-luastandalone-read-error' => 'Erro em Lua: erro interno: erro ao ler do pipe',
	'scribunto-luastandalone-gone' => 'Erro em Lua: erro interno: o interpretador já foi encerrado.',
	'scribunto-luastandalone-signal' => 'Erro em Lua: erro interno: o interpretador foi finalizado com o sinal "$2"',
	'scribunto-luastandalone-exited' => 'Erro em Lua: erro interno: o interpretador saiu com status $2',
);

/** Romanian (română)
 * @author Minisarm
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'scribunto-ignore-errors' => 'Permite salvarea codului cu erori',
	'scribunto-line' => 'la linia $1',
	'scribunto-module-line' => 'în $1 la linia $2',
	'scribunto-parser-error' => 'Eroare în script',
	'scribunto-parser-dialog-title' => 'Eroare în script',
	'scribunto-error-short' => 'Eroare în script: $1',
	'scribunto-error-long' => 'Erori în script:

$1',
	'scribunto-console-title' => 'Consolă de depanare',
	'scribunto-common-nosuchmodule' => 'Eroare în script: Niciun astfel de modul.',
	'scribunto-common-nofunction' => 'Eroare în script: Trebuie să specificați o funcție pentru apelare.',
	'scribunto-common-nosuchfunction' => 'Eroare în script: Funcția specificată nu există.',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'scribunto-ignore-errors' => "Permette 'a reggistrazione d'u codece cu l'errore",
	'scribunto-line' => "a 'a linèe $1",
	'scribunto-module-line' => "jndr'à $1 a 'a linèe $2",
	'scribunto-parser-error' => "Errore d'u script",
	'scribunto-parser-dialog-title' => "Errore d'u script",
	'scribunto-error-short' => "Errore d'u script: $1",
	'scribunto-error-long' => "Errore d'u script:

$1",
	'scribunto-console-clear' => 'Pulizze',
	'scribunto-lua-in-function' => 'jndr\'à funzione "$1"',
	'scribunto-lua-error-location' => 'Errore Lua: $1: $2.',
	'scribunto-lua-error' => 'Errore Lua: $2.',
);

/** Russian (русский)
 * @author Base
 * @author Ignatus
 * @author Kalan
 */
$messages['ru'] = array(
	'scribunto-desc' => 'Средство для включения скриптовых языков на страницах MediaWiki',
	'scribunto-ignore-errors' => 'Разрешить сохранение кода с ошибками',
	'scribunto-line' => 'на строке $1',
	'scribunto-module-line' => 'в $1 на строке $2',
	'scribunto-parser-error' => 'Ошибка скрипта',
	'scribunto-parser-dialog-title' => 'Ошибка скрипта',
	'scribunto-error-short' => 'Ошибка скрипта: $1',
	'scribunto-error-long' => 'Ошибки скрипта:

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''Для документации этого модуля может быть создана страница [[$1]]''",
	'scribunto-doc-page-header' => "''Это страница документации [[$1]]''",
	'scribunto-console-intro' => '* То, что экспортирует данный модуль, доступно как переменная «p», включая несохранённые изменения.
* Предваряйте строку знаком «=», чтобы вычислить её как выражение, или используйте print().
* Используйте mw.log() в коде модуля для вывода сообщений в эту консоль.',
	'scribunto-console-title' => 'Консоль отладки',
	'scribunto-console-too-large' => 'Этот сеанс консоли слишком большой. Очистите историю консоли или уменьшите размер модуля.',
	'scribunto-console-current-src' => 'ввод консоли',
	'scribunto-console-clear' => 'Очистить',
	'scribunto-console-cleared' => 'Консоль очищена, потому что модуль был обновлён.',
	'scribunto-console-cleared-session-lost' => 'Состояние консоли очищено, поскольку потеряны данные сессии.',
	'scribunto-common-error-category' => 'Страницы с ошибками скриптов',
	'scribunto-common-nosuchmodule' => 'Ошибка скрипта: Такого модуля нет.',
	'scribunto-common-nofunction' => 'Ошибка скрипта: Вы должны указать вызываемую функцию.',
	'scribunto-common-nosuchfunction' => 'Ошибка скрипта: Указанной вами функции не существует.',
	'scribunto-common-timeout' => 'Время, выделенное для выполнения скриптов, истекло.',
	'scribunto-common-oom' => 'Количество памяти, выделенное для выполнения скриптов, превышено.',
	'scribunto-common-backtrace' => 'Трассировка вызовов:',
	'scribunto-lua-in-function' => 'в функции «$1»',
	'scribunto-lua-in-main' => 'в основной части кода',
	'scribunto-lua-in-function-at' => 'в функции $1:$2',
	'scribunto-lua-error-location' => 'Ошибка Lua $1: $2.',
	'scribunto-lua-error' => 'Ошибка Lua: $2.',
	'scribunto-lua-noreturn' => 'Ошибка скрипта: Модуль не вернул значение. Ожидается, что модуль вернёт таблицу экспорта.',
	'scribunto-lua-notarrayreturn' => 'Ошибка скрипта: Модуль вернул значение, не являющееся таблицей экспорта. Ожидается, что модуль вернёт таблицу экспорта.',
	'scribunto-luastandalone-proc-error' => 'Ошибка Lua: Не удалось создать процесс.',
	'scribunto-luastandalone-decode-error' => 'Ошибка Lua: Внутренняя ошибка: Не удаётся декодировать сообщение.',
	'scribunto-luastandalone-write-error' => 'Ошибка Lua: Внутренняя ошибка: Ошибка записи в конвейер.',
	'scribunto-luastandalone-read-error' => 'Ошибка Lua: Внутренняя ошибка: Ошибка чтения из конвейера.',
	'scribunto-luastandalone-gone' => 'Ошибка Lua: Внутренняя ошибка: Интерпретатор уже завершил работу.',
	'scribunto-luastandalone-signal' => 'Ошибка Lua: Внутренняя ошибка: Интерпретатор был остановлен сигналом «$2».',
	'scribunto-luastandalone-exited' => 'Ошибка Lua: Внутренняя ошибка: Интерпретатор завершил работу со статусом $2.',
);

/** Sinhala (සිංහල)
 * @author පසිඳු කාවින්ද
 */
$messages['si'] = array(
	'scribunto-line' => '$1 පේලියේදී',
	'scribunto-parser-error' => 'අක්ෂර දෝෂය',
	'scribunto-parser-dialog-title' => 'අක්ෂර දෝෂය',
	'scribunto-error-short' => 'අක්ෂර දෝෂය: $1',
	'scribunto-error-long' => 'අක්ෂර දෝෂ:

$1',
	'scribunto-console-title' => 'නිදොස්කිරීම් කොන්සෝලය',
	'scribunto-console-current-src' => 'කොන්සෝල ආදානය',
	'scribunto-console-clear' => 'හිස් කරන්න',
	'scribunto-common-error-category' => 'අක්ෂර දෝෂ සහිත පිටු',
	'scribunto-common-nosuchmodule' => 'අක්ෂර දෝෂය: සැබෑ මොඩියුලක් නොමැත.',
	'scribunto-common-backtrace' => 'ආපසුවිතගමන:',
	'scribunto-lua-in-function' => '"$1" ක්‍රියාවෙහි',
	'scribunto-lua-in-main' => 'ප්‍රධාන කුට්ටියේ',
	'scribunto-lua-in-function-at' => '$1:$2 හිදී කාර්යය තුල',
	'scribunto-lua-error-location' => 'Lua දෝෂය $1: $2.',
	'scribunto-lua-error' => 'Lua දෝෂය: $2.',
	'scribunto-luastandalone-proc-error' => 'Lua දෝෂය: ක්‍රියාවලිය තැනිය නොහැක.',
	'scribunto-luastandalone-decode-error' => 'Lua දෝෂය: අභ්‍යන්තර දෝෂය: පණිවුඩය විකේතනය කල නොහැක.',
	'scribunto-luastandalone-write-error' => 'Lua දෝෂය: අභ්‍යන්තර දෝෂය: pipe වෙත ලිවීමේ දෝෂය.',
	'scribunto-luastandalone-read-error' => 'Lua දෝෂය: අභ්‍යන්තර දෝෂය: pipe වෙතින් කියවීමේ දෝෂය.',
	'scribunto-luastandalone-gone' => 'Lua දෝෂය: අභ්‍යන්තර දෝෂය: ව්‍යාඛ්‍යා කර්තෘ දැනටමත් පවතී.',
	'scribunto-luastandalone-signal' => 'Lua දෝෂය: අභ්‍යන්තර දෝෂය: ව්‍යාඛ්‍යා කර්තෘ "$2" සංඥාව සමඟ අවසාන වුණි.',
	'scribunto-luastandalone-exited' => 'Lua දෝෂය: අභ්‍යන්තර දෝෂය: ව්‍යාඛ්‍යා කර්තෘ "$2" තත්වය සමඟ ඉවත් වුණි.',
);

/** Slovak (slovenčina)
 * @author KuboF
 */
$messages['sk'] = array(
	'scribunto-desc' => 'Umožňuje vkladať do stránok MediaWiki skriptovacie jazyky',
	'scribunto-ignore-errors' => 'Povoliť uloženie kódu s chybami',
	'scribunto-line' => 'na riadku $1',
	'scribunto-parser-error' => 'Chyba skriptu',
	'scribunto-parser-dialog-title' => 'Chyba skriptu',
	'scribunto-error-short' => 'Chyba skriptu: $1',
	'scribunto-error-long' => 'Chyby skriptu:

$1',
	'scribunto-doc-page-name' => 'Dokumentácia', # Fuzzy
	'scribunto-doc-page-does-not-exist' => "''Dokumentácia pre tento modul môže byť vytvorená na [[$1]]''",
	'scribunto-doc-page-header' => "'''Toto je podstránka dokumentácie pre [[$1]]'''", # Fuzzy
);

/** Slovenian (slovenščina)
 * @author Eleassar
 */
$messages['sl'] = array(
	'scribunto-parser-error' => 'Skriptna napaka',
	'scribunto-error-short' => 'Skriptna napaka: $1',
	'scribunto-common-nosuchmodule' => 'Skriptna napaka: tak modul ne obstaja.',
	'scribunto-common-nofunction' => 'Skriptna napaka: določiti morate funkcijo za klic.',
	'scribunto-common-nosuchfunction' => 'Skriptna napaka: funkcija, ki ste jo določili, ne obstaja.',
	'scribunto-lua-noreturn' => 'Skriptna napaka: modul ni vrnil vrednosti. Vrniti bi moral izvozno tabelo.',
	'scribunto-lua-notarrayreturn' => 'Skriptna napaka: modul je vrnil nekaj drugega kot tabelo. Vrniti bi moral izvozno tabelo.',
);

/** Swedish (svenska)
 * @author GameOn
 * @author Hangsna
 * @author Lokal Profil
 */
$messages['sv'] = array(
	'scribunto-doc-page-name' => 'Module:$1/dok',
	'scribunto-doc-page-does-not-exist' => "''Dokumentationen för denna modul kan skapas på [[$1]]''",
	'scribunto-common-error-category' => 'Sidor med skriptfel',
);

/** Thai (ไทย)
 * @author Nullzero
 */
$messages['th'] = array(
	'scribunto-line' => 'บรรทัดที่ $1',
	'scribunto-parser-error' => 'สคริปต์ผิดพลาด',
	'scribunto-parser-dialog-title' => 'สคริปต์ผิดพลาด',
	'scribunto-error-short' => 'สคริปต์ผิดพลาด: $1',
	'scribunto-error-long' => 'สคริปต์ผิดพลาด:

$1',
	'scribunto-doc-page-does-not-exist' => "''เอกสารการใช้งานของมอดูลนี้อาจสร้างขึ้นที่ [[$1]]''",
	'scribunto-console-clear' => 'ล้าง',
	'scribunto-common-error-category' => 'หน้าที่มีสคริปต์ผิดพลาด',
	'scribunto-lua-error-location' => 'สคริปต์ลูอาผิดพลาด $1: $2',
	'scribunto-lua-error' => 'สคริปต์ลูอาผิดพลาด: $2',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'scribunto-desc' => 'Baskagan para sa pagbabaon ng mga wikang pampagpapanitik papaloob sa mga pahina ng MediaWiki',
	'scribunto-ignore-errors' => 'Pahintulutan ang pagsagip ng mga kodigong may mga kamalian',
	'scribunto-line' => 'sa guhit na $1',
	'scribunto-module-line' => 'sa loob ng $1 na nasa guhit na $2',
	'scribunto-parser-error' => 'Kamalian sa panitik',
	'scribunto-parser-dialog-title' => 'Kamalian sa panitik',
	'scribunto-error-short' => 'Kamalian sa panitik: $1',
	'scribunto-error-long' => 'Mga kamalian sa panitik:

$1',
	'scribunto-console-intro' => '* Ang mga luwas ng modulo ay makukuha bilang ang nagpapabagu-bagong "p", kasama na ang hindi pa nasasagip na mga pagbabago.
* Magpauna ng "=" sa isang guhit upang mahatulan ito bilang isang pagpapahayag, o gamitin ang paglimbag ().
* Gamitin ang mw.log() na nasa kodigo ng modulo upang makapagpadala ng mga mensahe sa kahang pantaban na ito.',
	'scribunto-console-title' => 'Kahang pantaban ng sira',
	'scribunto-console-too-large' => 'Ang inilaang panahon sa kahang pantaban ay napakalaki. Paki hawiin ang kasaysayan ng kahang pantaban o bawasan ang sukat ng modyul.',
	'scribunto-console-current-src' => 'pagpapasok sa kahang pantaban',
	'scribunto-console-clear' => 'Hawiin',
	'scribunto-console-cleared' => 'Hinawi ang katayuan ng kahang pantaban dahil isinapanahon ang modyul.',
	'scribunto-common-nosuchmodule' => 'Kamalian sa panitik: Walang ganyang modulo',
	'scribunto-common-nofunction' => 'Kamalian sa panitik: Dapat kang magtukoy ng isang tungkuling tatawagin.',
	'scribunto-common-nosuchfunction' => 'Kamalian sa panitik: Ang tinukoy mong tungkulin ay hindi umiiral.',
	'scribunto-common-timeout' => 'Ang panahong inilaan para sa pagpapatakbo ng mga panitik ay lipas na.',
	'scribunto-common-oom' => 'Ang dami ng pinahintulutang alaala para sa pagpapatakbo ng mga panitik ay nalampasan na.',
	'scribunto-common-backtrace' => 'Paurong na pagbabakas:',
	'scribunto-lua-in-function' => 'sa loob ng tungkuling "$1"',
	'scribunto-lua-in-main' => 'sa loob ng pangunahing tipak',
	'scribunto-lua-in-function-at' => 'sa loob ng tungkuling nasa $1:$2',
	'scribunto-lua-backtrace-line' => '$1: $2',
	'scribunto-lua-error-location' => 'Kamalian ng lua na $1: $2',
	'scribunto-lua-error' => 'Kamalian ng lua: $2',
	'scribunto-lua-noreturn' => 'Kamalian sa panitik: Ang modyul ay hindi nagbalik ng isang halaga, dapat itong magbalik ng isang talahanayan ng pag-aangkat.',
	'scribunto-lua-notarrayreturn' => 'Kamalian sa panitik: Ang modulo ay nagbalik ng isang bagay na bukod sa isang talahanayan, dapat itong magbalik ng isang talahanayan ng pag-aangkat.',
	'scribunto-luastandalone-proc-error' => 'Kamalian ng lua: hindi malikha ang proseso',
	'scribunto-luastandalone-decode-error' => 'Kamalian ng lua: panloob na kamalian: hindi nagawang alamin ang kodigo ng mensahe',
	'scribunto-luastandalone-write-error' => 'Kamalian ng lua: panloob na kamalian: kamalian sa pagsusulat sa tubo',
	'scribunto-luastandalone-read-error' => 'Kamalian sa lua: kamaliang panloob: kamalian sa pagbabasa mula sa tubo',
	'scribunto-luastandalone-gone' => 'Kamalian sa lua: panloob na kamalian: lumabas na ang tagapagpaunawa',
	'scribunto-luastandalone-signal' => 'Kamalian sa lua: panloob na kamalian: huminto ang tagapagpaliwanag na mayroong senyas na "$2"',
	'scribunto-luastandalone-exited' => 'Kamalian sa lua: panloob na kamalian: ang tagapagpaunawa ay lumabas na mayroong katayuang $2',
);

/** Turkish (Türkçe)
 * @author Sadrettin
 */
$messages['tr'] = array(
	'scribunto-parser-error' => 'Betik hatası',
	'scribunto-parser-dialog-title' => 'Betik hatası',
	'scribunto-error-short' => 'Betik hatası: $1',
	'scribunto-error-long' => 'Betik hataları:
$1',
	'scribunto-doc-page-name' => 'Modül:$1/belge',
	'scribunto-doc-page-does-not-exist' => 'Bu modül için bir belgeleme oluşturabilirsiniz: [[$1]]',
	'scribunto-doc-page-header' => 'Bu belgeleme sayfası için [[$1]]',
	'scribunto-console-clear' => 'Temizle',
);

/** Uyghur (Arabic script) (ئۇيغۇرچە)
 * @author Sahran
 */
$messages['ug-arab'] = array(
	'scribunto-console-clear' => 'تازىلا',
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 * @author Base
 * @author DixonD
 * @author Steve.rusyn
 * @author SteveR
 */
$messages['uk'] = array(
	'scribunto-desc' => 'Фреймворк для включення скриптових мов на сторінки MediaWiki',
	'scribunto-ignore-errors' => 'Дозволити збереження коду з помилками',
	'scribunto-line' => 'у рядку $1',
	'scribunto-module-line' => 'у $1 у рядку $2',
	'scribunto-parser-error' => 'Помилка скрипту',
	'scribunto-parser-dialog-title' => 'Помилка скрипту',
	'scribunto-error-short' => 'Помилка скрипту: $1',
	'scribunto-error-long' => 'Помилки скрипту:

$1',
	'scribunto-doc-page-name' => 'Module:$1/документація',
	'scribunto-doc-page-does-not-exist' => "''Документацію для цього модуля можна створити у [[$1]]''",
	'scribunto-doc-page-header' => "'''Це сторінка документації для [[$1]]'''",
	'scribunto-console-intro' => '* Експорти модуля доступні як змінна "p", у тому числі і незбережені зміни.
* Починайте рядок з "=", щоб обчислити його як вираз, або використовуйте print().
* Використовуйте mw.log() у коді модуля, що відправити повідомлення в цю консоль.',
	'scribunto-console-title' => 'Консоль налагодження',
	'scribunto-console-too-large' => 'Цей сеанс консолі занадто великий. Очистіть історію консолі або зменшіть розмір модуля.',
	'scribunto-console-current-src' => 'консольному вводі',
	'scribunto-console-clear' => 'Очистити',
	'scribunto-console-cleared' => 'Консоль очищена, тому що модуль був оновлений.',
	'scribunto-console-cleared-session-lost' => 'Стан консолі очищено, оскільки втрачені дані сесії.',
	'scribunto-common-error-category' => 'Сторінки з помилками скриптів',
	'scribunto-common-nosuchmodule' => 'Помилка скрипту: Такого модуля немає.',
	'scribunto-common-nofunction' => 'Помилка скрипту: Ви повинні вказати функцію для виклику.',
	'scribunto-common-nosuchfunction' => 'Помилка скрипту: Вказаної вами функції не існує.',
	'scribunto-common-timeout' => 'Закінчився час, виділений для виконання скриптів.',
	'scribunto-common-oom' => "Перевищено розмір пам'яті, дозволений для виконання скриптів.",
	'scribunto-common-backtrace' => 'Зворотне трасування:',
	'scribunto-lua-in-function' => 'у функції «$1»',
	'scribunto-lua-in-main' => 'в головній частині коду',
	'scribunto-lua-in-function-at' => 'у функції в $1:$2',
	'scribunto-lua-error-location' => 'Помилка Lua $1: $2.',
	'scribunto-lua-error' => 'Помилка Lua: $2.',
	'scribunto-lua-noreturn' => 'Помилка скрипту: Модуль не повернув значення. Він повинен повернутися таблицю експорту.',
	'scribunto-lua-notarrayreturn' => 'Помилка скрипту: Модуль повернув значення, що не є таблицею. Він повинен повернутися таблицю експорту.',
	'scribunto-luastandalone-proc-error' => 'Помилка Lua: Неможливо створити процес.',
	'scribunto-luastandalone-proc-error-msg' => 'Помилка Lua: Неможливо створити процес $2.',
	'scribunto-luastandalone-proc-error-proc-open' => 'Помилка Lua: не вдалося створити процес - proc_open недоступна. Перевірте директиви конфігурації РНР "disable_functions".',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Lua помилка: не вдалося створити процес. Зверніть увагу, що увімкнено застарілу директиву конфігурації РНР  "safe_mode".',
	'scribunto-luastandalone-decode-error' => 'Помилка Lua: Внутрішня помилка: Не вдається декодувати повідомлення.',
	'scribunto-luastandalone-write-error' => 'Помилка Lua: Внутрішня помилка: Помилка запису в конвеєр.',
	'scribunto-luastandalone-read-error' => 'Помилка Lua: Внутрішня помилка: Помилка читання з конвеєра.',
	'scribunto-luastandalone-gone' => 'Помилка Lua: Внутрішня помилка: Інтерпретатор вже завершив роботу.',
	'scribunto-luastandalone-signal' => 'Помилка Lua: Внутрішня помилка: Інтерпретатор було зупинено з сигналом «$2».',
	'scribunto-luastandalone-exited' => 'Помилка Lua: Внутрішня помилка: Інтерпретатор завершив роботу зі статусом $2.',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 */
$messages['vi'] = array(
	'scribunto-desc' => 'Khuôn khổ đế nhúng ngôn ngữ kịch bản vào các trang MediaWiki',
	'scribunto-ignore-errors' => 'Cho phép lưu mã nguồn có lỗi',
	'scribunto-line' => 'tại dòng $1',
	'scribunto-module-line' => 'trong $1 tại dòng $2',
	'scribunto-parser-error' => 'Lỗi kịch bản',
	'scribunto-parser-dialog-title' => 'Lỗi kịch bản',
	'scribunto-error-short' => 'Lỗi kịch bản: $1',
	'scribunto-error-long' => 'Lỗi kịch bản:

$1',
	'scribunto-doc-page-name' => 'Module:$1/tài liệu',
	'scribunto-doc-page-does-not-exist' => "''Có thể viết tài liệu về mô đun này tại [[$1]].''",
	'scribunto-doc-page-header' => "'''Đây là trang dành cho tài liệu về [[$1]].'''",
	'scribunto-console-intro' => '* Các giá trị được xuất khẩu từ mô đun, bao gồm các thay đổi chưa lưu, có sẵn trong biến “p”.
* Đưa “=” vào đầu dòng để tính toán nó như một biểu thức, hoặc sử dụng print().
* Sử dụng mw.log() trong mã mô đun để đưa thông điệp vào bảng điều khiển này.',
	'scribunto-console-title' => 'Bảng điều khiển gỡ lỗi',
	'scribunto-console-too-large' => 'Phiên bảng điều khiển này đã dài quá. Xin vui lòng tẩy trống lịch sử bảng điều khiển hoặc giảm cỡ mô đun.',
	'scribunto-console-current-src' => 'đầu vào bảng điều khiển',
	'scribunto-console-clear' => 'Tẩy trống',
	'scribunto-console-cleared' => 'Trạng thái bảng điều khiển được tẩy trống vì mô đun đã được cập nhật.',
	'scribunto-console-cleared-session-lost' => 'Trạng thái bảng điều khiển được tẩy trống do mất dữ liệu phiên làm việc.',
	'scribunto-common-error-category' => 'Trang có lỗi kịch bản',
	'scribunto-common-nosuchmodule' => 'Lỗi kịch bản: Không tìm thấy mô đun.',
	'scribunto-common-nofunction' => 'Lỗi kịch bản: Bạn cần phải định rõ hàm để gọi.',
	'scribunto-common-nosuchfunction' => 'Lỗi kịch bản: Hàm được định rõ không tồn tại.',
	'scribunto-common-timeout' => 'Đã hết thời gian dành để chạy kịch bản.',
	'scribunto-common-oom' => 'Đã vượt quá lượng bộ nhớ dành để chạy kịch bản.',
	'scribunto-common-backtrace' => 'Danh sách ngăn xếp:',
	'scribunto-lua-in-function' => 'trong hàm “$1”',
	'scribunto-lua-in-main' => 'trong đoạn chính',
	'scribunto-lua-in-function-at' => 'trong hàm tại $1:$2',
	'scribunto-lua-backtrace-line' => '$1: $2',
	'scribunto-lua-error-location' => 'Lỗi Lua $1: $2.',
	'scribunto-lua-error' => 'Lỗi Lua: $2.',
	'scribunto-lua-noreturn' => 'Lỗi kịch bản: Mô đun không cho ra một giá trị. Nó cần phải cho ra một bảng xuất khẩu.',
	'scribunto-lua-notarrayreturn' => 'Lỗi kịch bản: Mô đun cho ra gì đó không phải là bảng. Nó cần phải cho ra một bảng xuất khẩu.',
	'scribunto-luastandalone-proc-error' => 'Lỗi Lua: Không thể tạo ra quá trình.',
	'scribunto-luastandalone-proc-error-msg' => 'Lỗi Lua: Không thể tạo quá trình: $2',
	'scribunto-luastandalone-proc-error-proc-open' => 'Lỗi Lua: Không thể tạo quá trình: proc_open không có sẵn. Hãy kiểm tra chỉ thị cấu hình “disable_functions” của PHP.',
	'scribunto-luastandalone-proc-error-safe-mode' => 'Lỗi Lua: Không thể tạo quá trình. Lưu ý rằng chỉ thị cấu hình phản đối “safe_mode” của PHP đang được bật.',
	'scribunto-luastandalone-decode-error' => 'Lỗi Lua: Lỗi nội bộ: Không thể giải mã thông điệp.',
	'scribunto-luastandalone-write-error' => 'Lỗi Lua: Lỗi nội bộ: Lỗi khi ghi vào đường ống.',
	'scribunto-luastandalone-read-error' => 'Lỗi Lua: Lỗi nội bộ: Lỗi khi đọc từ đường ống.',
	'scribunto-luastandalone-gone' => 'Lỗi Lua: Lỗi nội bộ: Bộ phân tích đã thoát.',
	'scribunto-luastandalone-signal' => 'Lỗi Lua: Lỗi nội bộ: Bộ phân tích đã kết thúc với tín hiệu “$2”.',
	'scribunto-luastandalone-exited' => 'Lỗi Lua: Lỗi nội bộ: Bộ phân tích đã thoát với trạng thái $2.',
);

/** Yiddish (ייִדיש)
 * @author පසිඳු කාවින්ද
 */
$messages['yi'] = array(
	'scribunto-console-clear' => 'רייניקן',
);

/** Simplified Chinese (中文（简体）‎)
 * @author Liangent
 * @author Yfdyh000
 */
$messages['zh-hans'] = array(
	'scribunto-desc' => '嵌入脚本语言到MediaWiki页面中的框架',
	'scribunto-ignore-errors' => '允许保存有错误的代码',
	'scribunto-line' => '在第$1行',
	'scribunto-module-line' => '在$1中的第$2行',
	'scribunto-parser-error' => '脚本错误',
	'scribunto-parser-dialog-title' => '脚本错误',
	'scribunto-error-short' => '脚本错误：$1',
	'scribunto-error-long' => '脚本错误：

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-doc-page-does-not-exist' => "''此模块的文档可以在[[$1]]创建''",
	'scribunto-doc-page-header' => "'''这是[[$1]]的文档页面'''",
	'scribunto-console-intro' => '* 此模块的导出表存于变量“p”中，包括没有保存的变更。
* 在一行的前面加上“=”可以将其作为表达式来计算，或使用print()。
* 在模块代码中使用mw.log()来向控制台发送消息。',
	'scribunto-console-title' => '调试控制台',
	'scribunto-console-too-large' => '此控制台会话太大。请清除控制台历史记录或减少模块的大小。',
	'scribunto-console-current-src' => '控制台输入',
	'scribunto-console-clear' => '清除',
	'scribunto-console-cleared' => '控制台状态已清除，因为模块已更新。',
	'scribunto-console-cleared-session-lost' => '控制台状态已清除，因为会话数据已丢失。',
	'scribunto-common-error-category' => '有脚本错误的页面',
	'scribunto-common-nosuchmodule' => '脚本错误：没有这个模块。',
	'scribunto-common-nofunction' => '脚本错误：您必须指定要调用的函数。',
	'scribunto-common-nosuchfunction' => '脚本错误：您指定的函数不存在。',
	'scribunto-common-timeout' => '为运行的脚本分配的时间已耗尽。',
	'scribunto-common-oom' => '运行的脚本超出允许的内存用量。',
	'scribunto-common-backtrace' => '回溯：',
	'scribunto-lua-in-function' => '在函数“$1”中',
	'scribunto-lua-in-main' => '在主块中',
	'scribunto-lua-in-function-at' => '在函数 $1:$2 中',
	'scribunto-lua-error-location' => 'Lua错误 $1：$2',
	'scribunto-lua-error' => 'Lua错误：$2。',
	'scribunto-lua-noreturn' => '脚本错误：该模块未返回一个值，它应该返回一个导出表。',
	'scribunto-lua-notarrayreturn' => '脚本错误：该模块返回的不是一个表。它应该返回一个导出表。',
	'scribunto-luastandalone-proc-error' => 'Lua错误：无法创建进程。',
	'scribunto-luastandalone-decode-error' => 'Lua错误：内部错误：无法解码消息。',
	'scribunto-luastandalone-write-error' => 'Lua错误：内部错误：写入管道时出错。',
	'scribunto-luastandalone-read-error' => 'Lua错误：内部错误：从管道读取时出错。',
	'scribunto-luastandalone-gone' => 'Lua错误：内部错误：解释器已退出。',
	'scribunto-luastandalone-signal' => 'Lua错误：内部错误：解释器因收到信号“$2”而终止。',
	'scribunto-luastandalone-exited' => 'Lua错误：内部错误：解释器已退出，状态为 $2。',
);

/** Traditional Chinese (中文（繁體）‎)
 * @author Yfdyh000
 */
$messages['zh-hant'] = array(
	'scribunto-desc' => '用於在MediaWiki頁面中嵌入腳本語言的框架',
	'scribunto-line' => '在第$1行',
	'scribunto-module-line' => '在$1中的第$2行',
	'scribunto-parser-error' => '腳本錯誤',
	'scribunto-parser-dialog-title' => '腳本錯誤',
	'scribunto-error-short' => '腳本錯誤：$1',
	'scribunto-error-long' => '腳本錯誤：

$1',
	'scribunto-doc-page-name' => 'Module:$1/doc',
	'scribunto-console-intro' => '* 此模塊的導出表存於變量“p”中，包括沒有保存的變更。
* 在一行的前面加上“=”可以將其作為表達式來計算，或使用print()。
* 在模塊代碼中使用mw.log()來向控制台發送消息。',
	'scribunto-console-title' => '調試控制台',
	'scribunto-console-too-large' => '此控制台會話太大。請清除控制台歷史記錄或減少模塊的大小。',
	'scribunto-console-current-src' => '控制台輸入',
	'scribunto-console-clear' => '清除',
	'scribunto-console-cleared' => '控制台狀態已清除，因為模塊已更新。',
	'scribunto-common-nosuchmodule' => '腳本錯誤：沒有這個模塊。',
	'scribunto-common-nofunction' => '腳本錯誤：您必須指定要調用的函數。',
	'scribunto-common-nosuchfunction' => '腳本錯誤：您指定的函數不存在。',
	'scribunto-common-timeout' => '為正在運行的腳本分配的時間已用完。',
	'scribunto-common-oom' => '正在運行的腳本允許的內存用量已超出。',
	'scribunto-common-backtrace' => '回溯：',
	'scribunto-lua-in-function' => '在函數“$1”中',
	'scribunto-lua-in-main' => '在主要塊中',
	'scribunto-lua-in-function-at' => '在函數 $1:$2 中',
	'scribunto-lua-error-location' => 'Lua錯誤 $1：$2',
	'scribunto-lua-error' => 'Lua錯誤：$2。',
	'scribunto-lua-noreturn' => '腳本錯誤：該模塊未返回一個值，它應該返回導出表。',
	'scribunto-lua-notarrayreturn' => '腳本錯誤：該模塊返回的不是表，它應該返回導出表。',
	'scribunto-luastandalone-proc-error' => 'Lua錯誤：無法創建進程。',
	'scribunto-luastandalone-decode-error' => 'Lua錯誤：內部錯誤：無法解碼消息。',
	'scribunto-luastandalone-write-error' => 'Lua錯誤：內部錯誤：寫入管道時出錯。',
	'scribunto-luastandalone-read-error' => 'Lua錯誤：內部錯誤：從管道讀取時出錯。',
	'scribunto-luastandalone-gone' => 'Lua錯誤：內部錯誤：解釋器已退出。',
	'scribunto-luastandalone-signal' => 'Lua錯誤：內部錯誤：解釋器因收到信號“$2”而終止。',
	'scribunto-luastandalone-exited' => 'Lua錯誤：內部錯誤：解釋器已退出，狀態碼為$2。',
);
