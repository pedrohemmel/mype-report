<?php

session_start();

if(!$_SESSION['loggedAdm']) {
    header('Location:../index.php');
    exit;
} 

require '../config.php';
require '../dao/EmpresasDaoMysql.php';
require '../dao/RelatoriosDaoMysql.php';
require '../dao/IndicadoresDaoMysql.php';
require '../dao/UsuariosDaoMysql.php';
require '../dao/DepartamentosDaoMysql.php';

$IndicadoresDao = new IndicadoresDaoMysql($pdo);
$EmpresasDao = new EmpresasDaoMysql($pdo);
$RelatoriosDao = new RelatoriosDaoMysql($pdo);
$UsuariosDao = new UsuariosDaoMysql($pdo);
$DepartamentosDao = new DepartamentosDaoMysql($pdo);

$_SESSION['id_rel'] = filter_input(INPUT_GET, 'id_rel');

if($RelatoriosDao->verifyRowById($_SESSION['id_rel'])) {
    $relatorios = $RelatoriosDao->findById($_SESSION['id_rel']);
    foreach($relatorios as $getRelatorios) {
        $id_rel = $getRelatorios->getIdRel();
        $id_emp = $getRelatorios->getIdEmp();
        $nome_rel = $getRelatorios->getNomeRel();
        $link_rel = $getRelatorios->getLinkRel();  
        $situacao_rel = $getRelatorios->getSituacaoRel();
    }
    
} else {
    $_SESSION['erroRel'] = 'Os dados inseridos estão incompletos';
    $_SESSION['erroRelCrypt'] = password_hash($_SESSION['erroCadRel'], PASSWORD_DEFAULT);

    header('Location:../verMais/verMaisRelatorios.php?erroRel='.$_SESSION['erroRelCrypt'].'&id_emp='.$_SESSION['id_emp']);
    exit;
}



    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indicador</title>
    <!--BOOTSTRAP-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <!--CSS-->
    <link rel="stylesheet" href="../style/base.css"/>
</head>
<body>

    <div class="">
        <div class="tituloPaginas">
            <h2>Indicador</h2>
            
        </div>
        <div class="row tamanhoConteudo">
            <div class="col-12">
                <div class="row tamanhoConteudo">
                    <div class="col-md-6 col-12 background-primary-color padding-10-px color-white" >Id</div>
                    <div class="col-md-6 col-12 padding-10-px background-secondary-color"><?=$id_rel?></div>
                    <div class="col-md-6 col-12 background-primary-color padding-10-px color-white">Nome</div>
                    <div class="col-md-6 col-12 padding-10-px background-secondary-color"><?=$nome_rel?></div>
                    <div class="col-md-6 col-12 background-primary-color padding-10-px color-white">Link</div>
                    <div class="col-md-6 col-12 padding-10-px background-secondary-color"><?=$link_rel?></div>
                    <div class="col-md-6 col-12 background-primary-color padding-10-px color-white">Situação</div>
                    <div class="col-md-6 col-12 padding-10-px background-secondary-color"><?=$situacao_rel?></div>
                    <div class="col-12 background-primary-color padding-10-px" ><a href="../editar/editarRelatorio.php?id_rel=<?=$_SESSION['id_rel'];?>" class="color-white text-align-center">Alterar Dados</a></div>

                </div>

                
                <h6 class="margin-top-bottom-2-em">Escolha o usuário que deseja conceder acesso ao relatório e clique em "Vincular", ou escolha um usuário já vinculado no relatório que deseja retirar o acesso e clique em "Desvincular". </h6>
                
                    
              
                
            </div>
            <div class="col-md-6 col-12">
                <table>
                    <thead class="background-primary-color">
                        <tr>
                            <th scope="col" class="padding-10-px color-white">Id</th>
                            <th scope="col" class="padding-10-px color-white">Usuario</th>
                            <th scope="col" class="padding-10-px color-white">Departamento</th>
                            <th scope="col" class="padding-10-px color-white">Ações</th>
                        </tr>
                    </thead>
                    <?php   
                        if($UsuariosDao->verifyRowByEmpId($id_emp)):
                            $usuarios = $UsuariosDao->findUsuByEmpId($id_emp);
                            foreach($usuarios as $getUsuarios):
                    ?>
                        <tbody class="background-secondary-color">
                            <tr>
                                <td class="padding-10-px"><?=$getUsuarios->getIdUsu();?></td>
                                <td class="padding-10-px"><?=$getUsuarios->getUsernameUsu();?></td>
                                <?php
                                    $departamentos = $DepartamentosDao->findById($getUsuarios->getIdDpto());
                                    foreach($departamentos as $getDepartamentos):
                                        if($IndicadoresDao->verifyRowByUsuId($getUsuarios->getIdUsu())):
                                ?>
                                <td class="padding-10-px"><?=$getDepartamentos->getNomeDpto();?></td>
                                <td class="background-primary-color padding-10-px color-white">Já Vinculado</td>
                                <?php
                                        else:
                                ?>
                                <td class="padding-10-px"><?=$getDepartamentos->getNomeDpto();?></td>
                                <td class="background-primary-color padding-10-px"><a class="color-white" href="../cadastrar/cadastrarIndicador.php?id_usu=<?=$getUsuarios->getIdUsu();?>&id_rel=<?=$id_rel?>">Vincular</a></td>
                            </tr>
                                    
                        </tbody>
                    <?php       
                                        endif;
                                    endforeach;
                                    endforeach;
                        else: 
                    ?>
                        
                        <span>Não há usuários nessa empresa no momento.</span>
                    <?php
                        endif;
                    ?>
                    
                </table>
            </div>
            <div class="col-md-6 col-12">
            <table>
                    <thead class="background-primary-color">
                        <tr>
                            <th scope="col" class="padding-10-px color-white">Id</th>
                            <th scope="col" class="padding-10-px color-white">Usuario</th>
                            <th scope="col" class="padding-10-px color-white">Departamento</th>
                            <th scope="col" class="padding-10-px color-white">Ações</th>
                        </tr>
                    </thead>
                    <?php   
                        if($IndicadoresDao->verifyRowByRelId($id_rel)):
                            $indicadores = $IndicadoresDao->findByRelId($id_rel);
                            foreach($indicadores as $getIndicadores):
                    ?>
                        <tbody class="background-secondary-color">
                            <tr>
                                <td class="padding-10-px"><?=$getIndicadores->getIdInd();?></td>
                                <?php
                                    $usuarios = $UsuariosDao->findById($getIndicadores->getIdUsu());
                                    foreach($usuarios as $getUsuarios):
                                ?>
                                <td class="padding-10-px"><?=$getUsuarios->getUsernameUsu();?></td>
                                <?php
                                    $departamentos = $DepartamentosDao->findById($getUsuarios->getIdDpto());
                                    foreach($departamentos as $getDepartamentos):
                                ?>
                                <td class="padding-10-px"><?=$getDepartamentos->getNomeDpto();?></td>
                                <td class="background-primary-color padding-10-px"><a class="color-white" href="../apagar/apagarIndicador.php?id_ind=<?=$getIndicadores->getIdInd();?>&id_rel=<?=$getRelatorios->getIdRel();?>">Desvincular</a></td>
                            </tr>
                                    
                        </tbody>
                    <?php       
                                    endforeach;
                                    endforeach;
                            endforeach;
                        else: 
                    ?>
                        
                        <span>Não há usuários nessa empresa no momento.</span>
                    <?php
                        endif;
                    ?>
                    
                
            </div>
        </div>
        
    <div>
</body>
</html>

