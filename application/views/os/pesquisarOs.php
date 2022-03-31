<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/table-custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<div class="span12" style="margin-left: 0">

    <?php include_once("conexao.php"); ?>

      <div class="span3">
            <input type="text" name="pesquisa" id="pesquisa" class="span12" value="">
        </div>
      <div class="span3">
            <button class="span12 btn"><i class="fas fa-search"></i> Pesquisar</button>
        </div>  

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-diagnoses"></i>
        </span>
        <h5>Ordens de Serviço</h5>
    </div>
    <div class="widget-content nopadding tab-content">
        <div class="table-responsive">
            <table class="table table-bordered ">
                <thead>
                    <tr style="background-color: #2D335B">
                        <th>N° OS</th>
                        <th>Cliente</th>
                        <th>Responsável</th>
                        <th>Data Inicial</th>
                        <th>Data Final</th>
                        <th>Venc. Garantia</th>
                        <th>Valor Total</th>
                        <th>Valor Total (Faturado)</th>
                        <th>Status</th>
                        <th>Cidade</th>
                        <th>Equipamento</th>
                        <th>Garantia</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <?php
                  $sql="SELECT * FROM imoveis WHERE finalidade LIKE '%" . $busca . "%' OR tipo LIKE '%" . $busca . "%' OR cidade LIKE '%" . $busca . "%' order by valor LIMIT $inicio, $npp";
$res=@mysql_query($sql, $conexao) or die("Erro no MySQL:<br/>" . mysql_errno());

//exibe resultados encontrados no Banco de Dados
    while(list($codigo, $tipo, $area, $finalidade, $complemento, $endereço, $bairro, $cidade, $valor, $telefone, $informações, $foto, $lat, $lon)=mysql_fetch_array($res))
{
    echo "
apenas teste de resultados
$tipo - $area<br>
$finalidade<br>
$complemento<br>
$endereço<br>
$bairro<br>
$cidade<br>
$valor<br>
$telefone<br>
$informações";

}

mysql_free_result($res);

?>

            </table>
        </div>
    </div>
