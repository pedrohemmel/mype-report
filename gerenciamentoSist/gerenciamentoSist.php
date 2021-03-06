<?php
session_start();

require '../config.php';
require '../dao/UsuarioAdministradorDaoMysql.php';
require '../dao/UsuariosDaoMysql.php';
require '../dao/EmpresasDaoMysql.php';

if(!$_SESSION['loggedAdm']) {
    header('Location:../index.php');
    exit;
} else {
    $logged = 'ativo';
}

//Instanciando classes
$UsuariosDao = new UsuariosDaoMysql($pdo);
$UsuarioAdministradorDao = new UsuarioAdministradorDaoMysql($pdo);
$EmpresasDao = new EmpresasDaoMysql($pdo);

//chamando as funções necessárias para o código
$usuariosAdministrador = $UsuariosDao->findAllAdm();
$usuarios = $UsuariosDao->findAllUsu(); 
$usuarioAdministrador = $UsuarioAdministradorDao->findAll();

$msgCadEmp = filter_input(INPUT_GET, 'msgCadEmp');

//MENSAGENS

$classeNone = 'displayNone';

if(!empty($msgCadEmp)) {
    if(password_verify($_SESSION['msgCadEmp'], $msgCadEmp)) {
        $classeNone = 'displayBlkGreen';
        $mensagem = $_SESSION['msgCadEmp'];
    }
}



//chaves que vão decidir quais sections de registro de usuarios, configurações ou alteração de dados vão ser abertos
$_SESSION['registrosSistema'] = 'registrosSistema';
    $_SESSION['gruposAcesso'] = 'gruposAcesso';
        $_SESSION['usuariosGruposAcesso'] = 'usuariosGruposAcesso';
        $_SESSION['fechaUsuariosGruposAcesso'] = 'fechaUsuariosGruposAcesso';
        $_SESSION['administradorGruposAcesso'] = 'administradorGruposAcesso';
        $_SESSION['fechaAdministradorGruposAcesso'] = 'fechaAdministradorGruposAcesso';
    $_SESSION['relatorios'] = 'relatorios';
    $_SESSION['empresas'] = 'empresas';
    $_SESSION['usuarios'] = 'usuarios';
$_SESSION['configSistema'] = 'configSistema';

//chaves criptografadas
$_SESSION['registrosSistemaCrypt'] = password_hash($_SESSION['registrosSistema'], PASSWORD_DEFAULT);
    $_SESSION['gruposAcessoCrypt'] = password_hash($_SESSION['gruposAcesso'], PASSWORD_DEFAULT);
        $_SESSION['usuariosGruposAcessoCrypt'] = password_hash($_SESSION['usuariosGruposAcesso'], PASSWORD_DEFAULT);
        $_SESSION['fechaUsuariosGruposAcessoCrypt'] = password_hash($_SESSION['fechaUsuariosGruposAcesso'], PASSWORD_DEFAULT);
        $_SESSION['administradorGruposAcessoCrypt'] = password_hash($_SESSION['administradorGruposAcesso'], PASSWORD_DEFAULT);
        $_SESSION['fechaAdministradorGruposAcessoCrypt'] = password_hash($_SESSION['fechaAdministradorGruposAcesso'], PASSWORD_DEFAULT);
    $_SESSION['relatoriosCrypt'] = password_hash($_SESSION['relatorios'], PASSWORD_DEFAULT);
    $_SESSION['empresasCrypt'] = password_hash($_SESSION['empresas'], PASSWORD_DEFAULT);
    $_SESSION['usuariosCrypt'] = password_hash($_SESSION['usuarios'], PASSWORD_DEFAULT);
$_SESSION['configSistemaCrypt'] = password_hash($_SESSION['configSistema'], PASSWORD_DEFAULT);

$chaveDispSections = filter_input(INPUT_GET, 'chaveDispSections');

//variáveis das sections pre-definidas como display none
$classeRegistrosSistema = 'displayNoneNav';
$classeGruposAcesso = 'displayNone';
    $classeUsuariosGruposAcesso = 'displayNone';   
    $classeAdministradorGruposAcesso = 'displayNone';
    $linkAdministrador = 'displayBlock';
    $linkUsuarios   = 'displayBlock';
    $blockEmpresas = 'displayNone';
$classeRelatorios = 'displayNone';
$classeEmpresas = 'displayNone';
$classeUsuarios = 'displayNone';

//setando cores de background do botao para cada seção

$bckgrGrupoAcesso = 'color-white';
$bckgrRelatorios = 'color-white';
$bckgrEmpresas = 'color-white';
$bckgrUsuarios = 'color-white';

//variáveis das opcoes dentro das sections de registro do sistema pre-definidas como display none

if(!empty($chaveDispSections)) {
    
    if(password_verify($_SESSION['displayRegistrosSistema'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeGruposAcesso = 'displayBlock';
        $bckgrGrupoAcesso = 'corFundoSecao';
    } else if(password_verify($_SESSION['displayGruposAcesso'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeGruposAcesso = 'displayBlock';
        $bckgrGrupoAcesso = 'corFundoSecao';
    } else if(password_verify($_SESSION['displayRelatorios'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeRelatorios = 'displayBlock';
        $bckgrRelatorios = 'corFundoSecao';
    } else if(password_verify($_SESSION['displayEmpresas'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeEmpresas = 'displayBlock';
        $bckgrEmpresas = 'corFundoSecao';
    } else if(password_verify($_SESSION['displayUsuarios'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeUsuarios = 'displayBlock';
        $bckgrUsuarios = 'corFundoSecao';
    }


    /* OPCOES DENTRO DAS SECTIONS DE REGISTRO DO SISTEMA */

    
    else if(password_verify($_SESSION['displayUsuariosGruposAcesso'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeGruposAcesso = 'displayBlock';
        $classeUsuariosGruposAcesso = 'displayBlock';
        $linkUsuarios   = 'displayNone';
    } else if(password_verify($_SESSION['displayFechaUsuariosGruposAcesso'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeGruposAcesso = 'displayBlock';
        $classeUsuariosGruposAcesso = 'displaNone';
        $linkUsuarios = 'displayBlock';
    } else if(password_verify($_SESSION['displayAdministradorGruposAcesso'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeGruposAcesso = 'displayBlock';
        $classeAdministradorGruposAcesso = 'displayBlock';
        $linkAdministrador = 'displayNone';
    } else if(password_verify($_SESSION['displayFechaAdministradorGruposAcesso'], $chaveDispSections)) {
        $classeRegistrosSistema = 'displayBlockNav';
        $classeGruposAcesso = 'displayBlock';
        $classeAdministradorGruposAcesso = 'displayNone';
        $linkAdministrador = 'displayBlock';
    }
    

    else {
        $_SESSION['erroMsgGerenSist'] = 'tempo limite';
        $_SESSION['erroMsgGerenSistCrypt'] = password_hash($_SESSION['erroMsgGerenSist'], PASSWORD_DEFAULT);

        header('Location:../index.php?erroMsgGerenSist='.$_SESSION['erroMsgGerenSistCrypt']);
        exit;

    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento do sistema</title>
    <!--BOOTSTRAP-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <!--STYLE-->
    <link rel="stylesheet" href="../style/base.css"/>

</head>
<body>
    <main class="container-fluid">
        <!--Icones e logo-->
        <div class="row maxWidth">
            <div class="col-1 background-secondary-color maxVhHeight overflow-auto vertical-space-around ">

                <a href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['registrosSistemaCrypt']?>" class="bi bi-people linkIcon color-black"></a>
                
                <a href="" class="bi bi-gear linkIcon color-black"></a>
                
                <a href="" class="bi bi-person linkIcon color-black"></a>
            </div>
            <div class="col-11 registrosSist positionRelative">
                <!--Cadastros do sistema-->
                <section class="registrosSistema <?=$classeRegistrosSistema?>">
                    <nav class="displayFlex flex-direction-row">
                    <div class="navBase background-primary-color displayFlex flex-direction-row justify-content-center">
                            <a class="<?=$bckgrGrupoAcesso?> text-decoration-none linkAcessoRegistros" href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['gruposAcessoCrypt']?>">Grupos de acesso</a>
                            <a class="<?=$bckgrRelatorios?> text-decoration-none linkAcessoRegistros"  href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['relatoriosCrypt']?>">Relatorios</a>
                            <a class="<?=$bckgrEmpresas?> text-decoration-none linkAcessoRegistros"  href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['empresasCrypt']?>">Empresas</a>
                        </div>
                        <span>
                            <i onclick="abreFechaNav()" class="bi bi-list linkIcon iconAbreFecha"></i>
                        </span>

                    </nav>
                    <p class="<?=$classeNone?>"><?=$mensagem?></p>
                    <main class="conteudoSist">
                        
                        <!--GRUPOS DE ACESSO-->
                        <div class="gruposAcesso <?=$classeGruposAcesso?>">
                            <div class="tituloPaginas">
                                <h2>Usuários do sistema</h2>
                            </div>
                            <div class="tamanhoConteudo margin-top-bottom-2-em addEmpresa displayFlex flex-direction-row align-items-center">
                                <a href="../cadastrar/cadastrarUsuario.php" style="margin-right: 10px;" class="bi bi-plus-lg iconAdd color-black background-secondary-color border-radius-10-px"></a>
                                <p style="margin: 0;">Adicionar um novo usuário</p>
                            </div>
                            <table class="table-primary tamanhoConteudo border-radius-10-px">
                                <thead class="background-primary-color">
                                    <tr>
                                    <th scope="col" class="padding-10-px color-white">Grupos de acesso</th>
                                    <th scope="col" class="padding-10-px color-white">Ações</th>
                            
                                    </tr>
                                </thead>
                                <tbody class="background-secondary-color">
                                    <tr class="<?=$linkAdministrador?>">
                                        <td class="padding-10-px"><a  class="color-black text-decoration-none" href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['administradorGruposAcessoCrypt']?>">Administrador</a></td>
                                        <td class="padding-10-px"></td>
                                    </tr>

                                    <tr class="<?=$classeAdministradorGruposAcesso?>">
                                        <td class="padding-10-px"><a  class="color-black text-decoration-none" href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['fechaAdministradorGruposAcessoCrypt']?>">Administrador</a></td>
                                        <td class="padding-10-px"></td>
                                    </tr>

                                    <?php foreach($usuariosAdministrador as $getUsuariosAdministrador):?>
                                        <tr class="<?=$classeAdministradorGruposAcesso?>">
                                            <td class="padding-10-px"><a  class="color-black text-decoration-none" i style="padding-left: 50px" href=""><?=$getUsuariosAdministrador->getUsernameUsu();?> | <?=$getUsuariosAdministrador->getEmailUsu();?></a></td>
                                            <td class="padding-10-px"><a  class="color-black" i style="padding-left: 50px" href="">Ver mais</a></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php foreach($usuarioAdministrador as $getUsuarioAdministrador):?>
                                        <tr class="<?=$classeAdministradorGruposAcesso?>">
                                            <td class="padding-10-px"><a  class="color-black text-decoration-none" style="padding-left: 50px" href=""><?=$getUsuarioAdministrador->getUsernameAdm();?> | <?=$getUsuarioAdministrador->getEmailAdm();?> <span style="color: #00f; font-weight: 600;">MASTER</span></a></td>
                                            <td class="padding-10-px"><a  class="color-black" i style="padding-left: 50px" href="">Ver mais</a></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr class="<?=$linkUsuarios?>">
                                        <td class="padding-10-px"><a class="color-black text-decoration-none" href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['usuariosGruposAcessoCrypt']?>">Usuário</a></td>
                                        <td class="padding-10-px"></td>
                                    </tr>

                                    <tr class="<?=$classeUsuariosGruposAcesso?>">
                                        <td class="padding-10-px"><a class="color-black text-decoration-none" href="../verificar/registroEditUsuarios_action.php?chave=<?=$_SESSION['fechaUsuariosGruposAcessoCrypt']?>">Usuário</a></td>
                                        <td class="padding-10-px"></td>
                                    </tr>

                                    <?php foreach($usuarios as $getUsuarios):?>
                                    <tr class="<?=$classeUsuariosGruposAcesso?>">
                                        <td class="padding-10-px"><a class="color-black text-decoration-none" style="padding-left: 50px" href=""><?=$getUsuarios->getUsernameUsu();?> | <?=$getUsuarios->getEmailUsu();?></a></td>
                                        <td class="padding-10-px"><a  class="color-black" i style="padding-left: 50px" href="">Ver mais</a></td>
                                    </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>

                        </div>

                        <!--RELATORIOS-->
                        <div class="<?=$classeRelatorios?>">
                            <div class="tituloPaginas">
                                <h2>Selecione a empresa</h2>
                            </div>
                            <section class="tamanhoConteudo displayFlex flex-direction-row justify-content-center">
                                <?php   
                                    if($EmpresasDao->verifyRow()):
                                        $bloboEmpresas = 'displayBlock';
                                        $empresas = $EmpresasDao->findAll();
                                        foreach($empresas as $getEmpresas):
                                ?>
                                <a href="../verMais/verMaisRelatorios.php?id_emp=<?=$getEmpresas->getIdEmp()?>" style="margin: 20px; width:200px"class="hover-shadow color-black text-decoration-none displayFlex flex-direction-column align-items-center justify-content-center background-secondary-color padding-10-px border-radius-10-px">
                                    
                                    <img width="80%" src="<?=$getEmpresas->getLogoEmp();?>" class="border-radius-10-px" alt="logo"/>
                                    <h6><?=$getEmpresas->getNomeFantasiaEmp();?></h6>
                                    
                                </a>
                                <?php       
                                        endforeach;
                                    else: 
                                ?>
                                    <span>Não há empresas cadastradas até o momento.</span>
                                <?php
                                    endif;
                                ?>
                            </section>
                        </div>
                         
                        <!--EMPRESAS-->
                        <div class="sectionEmpresa <?=$classeEmpresas?>">
                            <div class="tituloPaginas">
                                <h2>Empresas</h2>
                            </div>
                            <div class="tamanhoConteudo addEmpresa displayFlex flex-direction-row align-items-center">
                                <a href="../cadastrar/cadastrarEmpresa.php" style="margin-right: 10px;" class="bi bi-plus-lg iconAdd color-black background-secondary-color border-radius-10-px"></a>
                                <p style="margin: 0;">Adicionar uma nova empresa</p>
                            </div>
                            <section class="tamanhoConteudo displayFlex flex-direction-row justify-content-center">
                                <?php   
                                    if($EmpresasDao->verifyRow()):
                                        $bloboEmpresas = 'displayBlock';
                                        $empresas = $EmpresasDao->findAll();
                                        foreach($empresas as $getEmpresas):
                                ?>
                                <div style="margin: 20px; width:200px"class="displayFlex flex-direction-column align-items-center justify-content-center background-secondary-color padding-10-px border-radius-10-px">
                                    
                                    <img width="80%" src="<?=$getEmpresas->getLogoEmp();?>" class="border-radius-10-px" alt="logo"/>
                                    <h6><?=$getEmpresas->getNomeFantasiaEmp();?></h6>
                                    <div class="displayFlex flex-direction-row align-items-center justify-content-center">
                                        <a href="../verMais/verMaisEmp_action.php?id=<?=$getEmpresas->getIdEmp()?>">Ver mais</a>  
                                        
                                    </div>
                                    
                                </div>
                                <?php       
                                        endforeach;
                                    else: 
                                ?>
                                    <span>Não há empresas cadastradas até o momento.</span>
                                <?php
                                    endif;
                                ?>
                            </section>
                        <div>
                    </main>
                </section>
            </div>
        </div>
    </main>

    <script src="../js/gerenciamentoSist.js"></script>
</body>
</html>