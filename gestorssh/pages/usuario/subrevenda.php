<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
    exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}
?>
<div class="row" id="table-hover-row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title font-medium-2"><i class="fad fa-user-tie text-success font-large-1"></i> SUB-Revendedores</h1>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <p>Seus revendedores listado abaixo.</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="MeuServidor" data-search="minhaPesquisa-lista">
                        <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>NOME</th>
                            <th>LOGIN</th>
                            <th>SENHA</th>
                            <th>CONTAS SSH</th>
							<th>VALIDADE</th>
                            <th>SERVIDORES</th>
                            <th>DONO</th>
                            <th>OPÇÕES</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php




                        $SQLUsuario = "SELECT * FROM usuario   where  tipo = 'revenda' and subrevenda='sim' and id_mestre='".$usuario['id_usuario']."' ORDER BY ativo ";
                        $SQLUsuario = $conn->prepare($SQLUsuario);
                        $SQLUsuario->execute();


                        // output data of each row
                        if (($SQLUsuario->rowCount()) > 0) {

                            while($row = $SQLUsuario->fetch())


                            {
                                $class = "class='btn btn-danger'";
                                $status="";
                                $color = "";
                                $contas = 0;
                                $servidores = 0;
                                if($row['ativo']== 1){
                                    $status="Ativo";
                                    $class = "class='btn-sm btn-primary'";
                                }else{
                                    $status="Desativado";
                                    $color = "bgcolor='#FF6347'";
                                }


                                $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '".$row['id_usuario']."'";
                                $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                $SQLContasSSH->execute();
                                $contas += $SQLContasSSH->rowCount();

                                $SQLServidores = "select * from acesso_servidor WHERE id_usuario = '".$row['id_usuario']."'";
                                $SQLServidores = $conn->prepare($SQLServidores);
                                $SQLServidores->execute();
                                $servidores += $SQLServidores->rowCount();

                                $total_acesso_ssh = 0;
                                $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='".$row['id_usuario']."' ";
                                $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                $SQLAcessoSSH->execute();
                                $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                $total_acesso_ssh += $SQLAcessoSSH['quantidade'];


                                $SQLUserSub = "select * from usuario WHERE id_mestre = '".$row['id_usuario']."'";
                                $SQLUserSub = $conn->prepare($SQLUserSub);
                                $SQLUserSub->execute();

                                if (($SQLUserSub->rowCount()) > 0) {

                                    while($rowS = $SQLUserSub->fetch()) {
                                        $SQLContasSSH = "select * from usuario_ssh WHERE id_usuario = '".$rowS['id_usuario']."'";
                                        $SQLContasSSH = $conn->prepare($SQLContasSSH);
                                        $SQLContasSSH->execute();
                                        $contas += $SQLContasSSH->rowCount();

                                        $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_usuario='".$rowS['id_usuario']."' ";
                                        $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                                        $SQLAcessoSSH->execute();
                                        $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                                        $total_acesso_ssh += $SQLAcessoSSH['quantidade'];


                                    }
                                }







                                ?>
                                <div class="modal fade" id="squarespaceModal<?php echo $row['id_usuario'];?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="lineModalLabel"><i class="fa fa-pencil-square-o"></i> Notificar Usuário</h3>
                                            </div>
                                            <div class="modal-body">

                                                <!-- content goes here -->
                                                <form action="pages/usuario/notifica_sub.php" method="post">
                                                    <input name="idsubrev" type="hidden" value="<?php echo $row['id_usuario'];?>">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Usuário</label>
                                                        <input type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $row['nome'];?>" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Tipo de Alerta</label>
                                                        <select size="1" name="tipo" class="form-control">
                                                            <option value="1" selected=selected>SUBrevenda</option>
                                                            <option value="2">Outros</option>
                                                        </select>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Mensagem</label>
                                                        <textarea class="form-control" name="msg" rows=5 cols=20 wrap="off" placeholder="Digite..." required></textarea>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Cancelar</button>
                                                    </div>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-default btn-hover-green">Confirmar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <tr  <?php echo $color; ?> >
                                    <td><?php echo $status;?></td>
                                    <td><?php echo $row['nome'];?></td>

                                    <td><?php echo $row['login'];?></td>
                                    <td><?php echo $row['senha'];?></td>


                                    <td><?php echo $contas;?></td>
									<td><?php echo $contas;?></td>
                                    <td><?php echo $total_acesso_ssh;?></td>
                                    <td><?php echo $servidores;?></td>


                                    <td>

                                        <a href="home.php?page=usuario/perfil&id_usuario=<?php echo $row['id_usuario'];?>" <?php echo $class;?>><i class="fad fa-eye"></i></a>
                                        <a data-toggle="modal" href="#squarespaceModal<?php echo $row['id_usuario'];?>"  class="btn-sm btn-warning"><i class="fad fa-flag"></i></a>
                                    </td>
                                </tr>

                            <?php }
                        }

                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
