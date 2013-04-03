<?php
/**
 * Copyright (c) 2011
 *      Reever Pesquisa e Desenvolvimento (ReeverP&D).  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *      This product includes software developed by the University of
 *      California, Berkeley and its contributors.
 * 4. Neither the name of the University nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * 
 * @author Iuri Andreazza {iuri@reeverpd.com.br, iuri.andreazza@gmail.com}
 * @since 08/2012
 * @version 0.0.1-alpha
 * 
 */

require_once('reever/security/SecurityConfig.php');
require_once('reever/route/Routes.php');

define('use_route', 1);
define('class_route', 'app_routes');

$cSec = new SecurityConfig();
$cSec->setLoginRoute('Login');
$cSec->RequireAuth(false);


$app_routes = Reever_Routes::getInstance();

//$app_routes->setFolders('Controllers', 'Views');

$app_routes->UseHTAccess(true);

//Rules
$app_routes->addRule("{slug}", "([a-zA-Z-_]+)");
$app_routes->addRule("{empresa}", "([a-zA-Z-_]+)");
$app_routes->addRule("{profissional}", "([a-zA-Z-_]+)");
$app_routes->addRule("{id}", "([0-9]+)");
$app_routes->addRule("{pagina}", "([0-9]+)");
$app_routes->addRule("{titulo}", "([a-zA-Z-0-9-_]+)");
$app_routes->addRule("{localizar}", "(.*)");

//General Rules
$app_routes->addRule("{controller}", "([a-zA-Z-_]+)");
$app_routes->addRule("{view}", "([a-zA-Z-_]+)");

//Search Folders
$app_routes->AddSearchFolder("~/");
$app_routes->AddSearchFolder("~/Restrito/");

//Geral
$app_routes->addRouteGeneralRule("Geral", "~/{controller}/{view}", "{controller}/{view}", null, $cSec, true);


//#########################
//### Publico
//############
//Home
$app_routes->addRoute("HomeClean", "~/", "Home", null, null, true);
$app_routes->addRoute("Home", "~/Home", "Home", null, null, true);

//AJAX Routes
$app_routes->addRoute("ContatoSend", "~/Contact/Send", "Home/SendContact", null, null, true);

//Projetos Publicos
$app_routes->addRoute("ProjetosPublicos", "~/Public/Projects", "Projetos", null, null, true);

//Usuarios Publicos
$app_routes->addRoute("UsuariosPublicos", "~/Public/Enterpreurs", "Perfis/Usuarios", null, null, true);

$app_routes->addRoute("EntrarLogin", "~/Entrar", "Perfis/Entrar", null, null, true);



//Perfil Publico das Empresas e Usuarios
//Empresa
$app_routes->addRoute("epp", "~/e/{slug}", "Empresa/Profile", null, null, true); //Perfil

//usuario
$app_routes->addRoute("upp", "~/u/{slug}", "Usuario/Profile", null, null, true);


//Projeto
$app_routes->addRoute("upp", "~/p/{slug}/Profile"	, "Projeto/Profile"	, null, null, true); //Perfil do projeto
$app_routes->addRoute("upp", "~/p/{slug}/Calendar"	, "Projeto/Calendar", null, null, true);
$app_routes->addRoute("upp", "~/p/{slug}/Teams"		, "Projeto/Teams"	, null, null, true);
$app_routes->addRoute("upp", "~/p/{slug}/Files"		, "Projeto/Files"	, null, null, true);





Reever_Routes::GetRoute("Home");