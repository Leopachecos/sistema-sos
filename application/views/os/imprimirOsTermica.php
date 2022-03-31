<?php $totalServico = 0;
$totalProdutos = 0; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Nota NF</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-style.css" />
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <style>
        .table {

            width: 80mm;
            margin: 2px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">

                <div class="invoice-content">
                    <div class="invoice-head" style="margin-bottom: 0">

                        <table class="table table-condensed">
                            <tbody>
                                <?php if ($emitente == null) { ?>

                                    <tr>
                                        <td colspan="5" class="alert">Você precisa configurar os dados do emitente. >>><a href="<?php echo base_url(); ?>index.php/mapos/emitente">Configurar</a>
                                            <<<</td> </tr> <?php } else { ?> <tr>

                                        <td colspan="5" style="text-align: center"> <span style="font-size: 20px; ">
                                                S.O.S Centro Eletrônico</span> </br>
                                            <span style="font-size: 15px; ">CNPJ: <?php echo $emitente[0]->cnpj; ?> </br>
                                                <?php echo $emitente[0]->rua . ', ' . $emitente[0]->numero . ' ' . $emitente[0]->bairro . ' -  ' . $emitente[0]->cidade . ' - ' . $emitente[0]->uf; ?> </span> </br> <span style= font-size:15px>Fone: <?php echo $emitente[0]->telefone; ?></span></td>
                                    </tr>
                                    <tr style= font-size:px>
                                        
                                        <td style="width: 100%; font-size: 10px;text-align:center">
                                            ==================================================</br>
                                            <b style="font-size:15px">ORDEM DE SERVIÇO NRO. </b> 
                                            <b style="font-size:15px"><?php echo $result->idOs ?></b></br>
                                            ==================================================</br>
                                            <span style="padding-left: 5%;font-size:15px">
                                                <b style="font-size:15px">Emissão:</b> <?php echo date('d/m/Y h:i') ?></span>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                         <table class="table table-condensend">
                            <tbody>
                                <tr style= font-size:10px>
                                    <td style="width: 100%;text-align:center">
                                        <ul>
                                            <li>
                                                <span>
                                    ==================================================
                                            <b style="font-size:15px">DADOS DO CLIENTE</b>
                                    ==================================================
                                                </span>
                                            </li>
                                        </ul>
                                    </td>        
                                </tr>
                            </tbody>
                        </table>
    
                        <table class="table table-condensend">
                            <tbody>
                                <tr style= font-size:15px>
                                    <td >
                                        <ul>
                                            <li>
                                                <span>
                                            
                                                    <b>Nome: </b>
                                                    <span><?php echo $result->nomeCliente ?></span></br>
                                                    <b>CPF:</b>
                                                    <span><?php echo $result->documento ?></span>
                                        
                                                    <span style="padding-left: 4%;"></br><b>Celular:</b> <?php echo $result->celular_cliente ?></span></br>
                                                    <b>Endereço:</b>
                                                     <span><?php echo $result->rua ?>, <?php echo $result->numero ?>, <?php echo $result->bairro ?></span></br>
                                                     <span>
                                                         <b>Cidade: </b>
                                                    <?php echo $result->cidades ?> </br></span>
                                                <span style="font-size:10px">
                                                    ==================================================
                                                    </span>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div style="margin-top: 0; padding-top: 0">
                        <table class="table table-condensed">
                            <tbody style=font-size:19px>
                                <tr>
                                    <td style="font-size:15px">
                                        <b>Status da O.S.: </b>
                                        <?php echo $result->status ?>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <table class="table table-condensed">
                            <tbody>

                                <?php if ($result->dataInicial != null) { ?>
                                    <tr>

                                        <td style="font-size:15px">
                                            <b>Inicial: </b>
                                            <?php echo date('d/m/Y', strtotime($result->dataInicial)); ?>
                                        </td>

                                       <!-- <td style="font-size:15px">
                                            <?php if ($result->garantia != null) { ?>
                                                <b>Garantia: </b>
                                                <?php echo $result->garantia . ' dias'; ?><?php } ?>
                                        </td> --->
                                    </tr>
                            </tbody>
                            <table class="table table-condensend">
                            <tbody>
                                <tr style= font-size:10px>
                                    <td style="width: 100%;text-align:center">
                                        <ul>
                                            <li>
                                                <span>
                                    ==================================================
                                            <b style="font-size:15px">DADOS DO  EQUIPAMENTO</b>
                                    ==================================================
                                                </span>
                                                 <tr>
                                 <td style="font-size:15px">
                                    <span>
                                        <p>ABANDONO: Equipamentos não retirados em até 90 (noventa)dias serão considerados abandonados e poderão ser vendidos ou descartados no lixo tecnológico, a critério da empresa.</span>
                                        </p>                         
                                 </td>
                                </tr>
                                 <tr style= font-size:10px>
                                    <td style="width: 100%;text-align:center">
                                        <ul>
                                            <li>
                                                <span>
                                    ==================================================
                                                </span>
                                                 <tr>
                                            </li>
                                        </ul>
                                    </td>        
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <tbody>
                                        <?php if ($result->descricaoProduto != null) { ?>
                                    <tr style="font-size:15px">

                                        
                                        <td colspan="5">
                                            <b>Descrição: </b>
                                            <?php echo htmlspecialchars_decode($result->descricaoProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                    
                                <?php if ($result->marcaProduto != null) { ?>
                                    <tr style="font-size:15px">
                                        <td colspan="5">
                                            <b>Marca: </b>
                                            <?php echo htmlspecialchars_decode($result->marcaProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                 <?php if ($result->modeloProduto != null) { ?>
                                     <tr style="font-size:15px">
                                        <td colspan="5">
                                            <b>Modelo: </b>
                                            <?php echo htmlspecialchars_decode($result->modeloProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>    
                                    
                                
                                <?php if ($result->serieProduto != null) { ?>
                                    <tr style="font-size:15px">
                                        <td colspan="5">
                                            <b>Número de Série: </b>
                                            <?php echo htmlspecialchars_decode($result->serieProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                
                                 <?php if ($result->codfabProduto != null) { ?>
                                    <tr style="font-size:15px">
                                        <td colspan="5">
                                            <b>Cod. Fab.: </b>
                                            <?php echo htmlspecialchars_decode($result->codfabProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                
                                 <?php if ($result->corProduto != null) { ?>
                                    <tr style="font-size:15px">
                                        <td colspan="5">
                                            <b>Cor: </b>
                                            <?php echo htmlspecialchars_decode($result->corProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                
                                <table class="table table-condensend">
                            <tbody>
                                <tr style= font-size:10px>
                                    <td style="width: 100%;text-align:center">
                                        <ul>
                                            <li>
                                                <span>
                                    ==================================================
                                            <b style="font-size:15px">DADOS DA ORDEM DE SERVIÇO</b>
                                    ==================================================
                                                </span>
                                            </li>
                                        </ul>
                                    </td>        
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <tbody>
                                <ul>
                                    <li>
                            <tr>
                               
                            </tr>

                                <?php if ($result->defeito != null) { ?>
                                    <tr style= font-size:15px>
                                        <td  colspan="5">
                                            <b>DEFEITO APRESENTADO: </b>
                                            <?php echo htmlspecialchars_decode($result->defeito) ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if ($result->observacoes != null) { ?>
                                    <tr style= font-size:15px>
                                        <td  colspan="5">
                                            <b>OBSERVAÇÕES: </b>
                                            <?php echo htmlspecialchars_decode($result->observacoes) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            
                          <!--  <?php if ($result->status != 'Aberto') { ?>
                                <?php if ($result->laudoTecnico != null) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <b>Laudo Técnico: </b>
                                            <?php echo htmlspecialchars_decode($result->laudoTecnico) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?> -->
                                <tr style= font-size:15px>
                                    <td colspan="5">
                                    <?php if ($result->garantiaTipo != null) { ?>
                                    <b>TIPO DE GARANTIA: </b>
                                    <?php echo htmlspecialchars_decode($result->garantiaTipo) ?><?php } ?>
                                    </td>
                                    <?php } ?>
                                </tr>    
                                <tr style= font-size:15px>
                                    <td>
                                        <p>Os Serviços prestados possuem garantia de 90 (noventa) dias a contar da data de entrega.
                                        </p>
                                    </td>
                                </tr>
                            
                                    </li>
                                </ul>
                            </tbody>
                        </table>


                        <?php if ($produtos != null) { ?>
                            <br />
                            <table style='font-size: 15px;' class="table table-bordered table-condensed" id="tblProdutos">
                                <thead>
                                    <tr>
                                        <th>Qtd.</th>
                                        <th>Produto</th>
                                        <th>Preço unit.</th>
                                        <th>Sub-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($produtos as $p) {
                                        $totalProdutos = $totalProdutos + $p->subTotal;
                                        echo '<tr>';
                                        echo '<td>' . $p->quantidade . '</td>';
                                        echo '<td>' . $p->descricao . '</td>';
                                        echo '<td>R$ ' . $p->preco ?: $p->precoVenda . '</td>';

                                        echo '<td>R$ ' . number_format($p->subTotal, 2, ',', '.') . '</td>';
                                        echo '</tr>';
                                    } ?>

                                    <tr>

                                        <td colspan="3" style="text-align: right"><strong>Total:</strong></td>
                                        <td><strong>R$ <?php echo number_format($totalProdutos, 2, ',', '.'); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>

                        <?php if ($servicos != null) { ?>
                            <table style='font-size: 15px;' class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Qtd.</th>
                                        <th>Serviço</th>
                                        <th>Preço unit.</th>
                                        <th>Sub-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    setlocale(LC_MONETARY, 'en_US');
                                    foreach ($servicos as $s) {
                                        $preco = $s->preco ?: $s->precoVenda;
                                        $subtotal = $preco * ($s->quantidade ?: 1);
                                        $totalServico = $totalServico + $subtotal;
                                        echo '<tr>';
                                        echo '<td>' . ($s->quantidade ?: 1) . '</td>';
                                        echo '<td>' . $s->nome . '</td>';
                                        echo '<td>R$ ' . $preco . '</td>';
                                        echo '<td>R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                                        echo '</tr>';
                                    } ?>

                                    <tr>
                                        <td colspan="3" style="text-align: right"><strong>Total:</strong></td>
                                        <td><strong>R$ <?php echo number_format($totalServico, 2, ',', '.'); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>

                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr>
                                    <td colspan="5"> <?php
                                                        if ($totalProdutos != 0 || $totalServico != 0) {
                                                            echo "<h4 style='text-align: right; font-size: 13px;'>Valor Total: R$" . number_format($totalProdutos + $totalServico, 2, ',', '.') . "</h4>";
                                                        }

                                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                    <td colspan="5">
                                        <p class="text-center">______________________________________</p>
                                        <p class="text-center">Assinatura do Cliente</p><br />
                                        <hr>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/matrix.js"></script>

    <script>
        window.print(); 
    </script>

</body>

</html>
