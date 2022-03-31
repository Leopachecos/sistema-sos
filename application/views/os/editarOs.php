<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/trumbowyg/ui/trumbowyg.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/trumbowyg.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/langs/pt_br.js"></script>

<style>
    .ui-datepicker {
        z-index: 99999 !important;
    }

    .trumbowyg-box {
        margin-top: 0;
        margin-bottom: 0;
    }

    textarea {
        resize: vertical;
    }
</style>
<?php include_once("conexao.php"); ?>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-diagnoses"></i>
                </span>
                <h5>Ordem de Serviço</h5>
                <div class="buttons">
                    <?php if ($result->finalizado == 0) { ?>
                        <a href="#modal-faturar" id="btn-faturar" role="button" data-toggle="modal" class="btn btn-mini btn-danger">
                            <i class="fas fa-cash-register"></i> Faturar</a>
                    <?php
                    } ?>

                    <?php if ($editavel) {
                        echo '<a title="Editar OS" class="btn btn-mini btn-info" href="' . base_url() . 'index.php/os/editar/' . $result->idOs . '"><i class="fas fa-edit"></i> Editar</a>';
                    } ?>
                    <a title="Visualizar OS" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/visualizar/<?php echo $result->idOs; ?>"><i class="fas fa-eye"></i> Visualizar OS</a>
                    <a target="_blank" title="Imprimir OS" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/imprimir/<?php echo $result->idOs; ?>"><i class="fas fa-print"></i> Imprimir A4</a>
                    <a target="_blank" title="Imprimir OS" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/imprimirTermica/<?php echo $result->idOs; ?>"><i class="fas fa-print"></i> Imprimir Não Fiscal</a>
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eOs')) {
                        $this->load->model('os_model');
                        $zapnumber = preg_replace("/[^0-9]/", "", $result->celular_cliente);
                        $troca = [$result->nomeCliente, $result->idOs, $result->status, 'R$ ' . number_format($totalProdutos + $totalServico, 2, ',', '.'), strip_tags($result->descricaoProduto), ($emitente ? $emitente[0]->nome : ''), ($emitente ? $emitente[0]->telefone : ''), strip_tags($result->observacoes), strip_tags($result->defeito), strip_tags($result->laudoTecnico), date('d/m/Y', strtotime($result->dataFinal)), date('d/m/Y', strtotime($result->dataInicial)), $result->garantia . ' dias'];
                        $texto_de_notificacao = $this->os_model->criarTextoWhats($texto_de_notificacao, $troca);
                        if (!empty($zapnumber)) {
                            echo '<a title="Enviar Por WhatsApp" class="btn btn-mini btn-success" id="enviarWhatsApp" target="_blank" href="https://web.whatsapp.com/send?phone=55' . $zapnumber . '&text=' . $texto_de_notificacao . '" ' . ($zapnumber == '' ? 'disabled' : '') . '><i class="fab fa-whatsapp"></i> WhatsApp</a>';
                        }
                    } ?>

                    <a title="Enviar por E-mail" class="btn btn-mini btn-warning" href="<?php echo site_url() ?>/os/enviar_email/<?php echo $result->idOs; ?>"><i class="fas fa-envelope"></i> Enviar por E-mail</a>
                    <?php if ($result->garantias_id) { ?> <a target="_blank" title="Imprimir Termo de Garantia" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/garantias/imprimir/<?php echo $result->garantias_id; ?>"><i class="fas fa-text-width"></i> Imprimir Termo de Garantia</a> <?php } ?>
                </div>
            </div>
            <div class="widget-content nopadding tab-content">
                <div class="span12" id="divProdutosServicos" style=" margin-left: 0">
                    <ul class="nav nav-tabs">
                        <li class="active" id="tabDetalhes"><a href="#tab1" data-toggle="tab">Detalhes da OS</a></li>
                        <li id="tabProdutos"><a href="#tab2" data-toggle="tab">Produtos</a></li>
                        <li id="tabServicos"><a href="#tab3" data-toggle="tab">Serviços</a></li>
                        <li id="tabAnexos"><a href="#tab4" data-toggle="tab">Anexos</a></li>
                        <li id="tabAnotacoes"><a href="#tab5" data-toggle="tab">Anotações</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="span12" id="divCadastrarOs">
                                <form action="<?php echo current_url(); ?>" method="post" id="formOs">
                                    <?php echo form_hidden('idOs', $result->idOs) ?>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <h3>N° OS:
                                            <?php echo $result->idOs; ?>
                                        </h3>
                                        <div class="span6" style="margin-left: 0">
                                            <label for="cliente">Cliente<span class="required">*</span></label>
                                            <input id="cliente" class="span12" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                                            <input id="clientes_id" class="span12" type="hidden" name="clientes_id" value="<?php echo $result->clientes_id ?>" />
                                            <input id="valorTotal" type="hidden" name="valorTotal" value="" />
                                        </div>
                                        <div class="span6">
                                            <label for="tecnico">Técnico / Responsável<span class="required">*</span></label>
                                            <input id="tecnico" class="span12" type="text" name="tecnico" value="<?php echo $result->nome ?>" />
                                            <input id="usuarios_id" class="span12" type="hidden" name="usuarios_id" value="<?php echo $result->usuarios_id ?>" />
                                        </div>
                                    </div>
                                    <div class="span12">
                                        <div class="span5">
                                            <label for="atendente">Atendente<span class="required">*</span></label>
                                            <select class="span12" name="atendente" id="atendente" value="">
                                                <option value="<?php echo $result->atendente ?>"><?php echo $result->atendente ?> 
                                                </option>
                                                 <?php
                                            $result_tec_post = "SELECT * FROM usuarios ORDER BY nome";
                                            $resultado_tec_post = mysqli_query ($conn, $result_tec_post);
                                            while($row_tec_post = mysqli_fetch_assoc ($resultado_tec_post)) {
                                               echo '<option value="'.$row_tec_post['nome'].'">'.$row_tec_post['nome'].'</option>';
                            					} 
                                            
                                        ?>
                                                
                                            </select>
                                        </div>

                                        <div class="span3">
                                            <label for="status">Status<span class="required">*</span></label>
                                            <select class="span12" name="status" id="status" value="">
                                                <option <?php if ($result->status == 'Aberto') {
                        echo 'selected';
                    } ?> value="Aberto">Aberto
                                                </option>
                                                <option <?php if ($result->status == 'Orçamento') {
                        echo 'selected';
                    } ?> value="Orçamento">Orçamento
                                                </option>
                                                <option <?php if ($result->status == 'Em Atendimento') {
                        echo 'selected';
                    } ?> value="Em Atendimento">Em Atendimento
                                                </option>
                                                <option <?php if ($result->status == 'Aguardo Cliente') {
                        echo 'selected';
                    } ?> value="Aguardo Cliente">Aguardo Cliente
                                                </option>
                                                <option <?php if ($result->status == 'Aguardando Peças') {
                        echo 'selected';
                    } ?> value="Aguardando Peças">Aguardando Peças
                                                </option>
                                                <option <?php if ($result->status == 'Aguardando Retirar') {
                        echo 'selected';
                    } ?> value="Aguardando Retirar">Aguardando Retirar
                                                </option>
                                                <option <?php if ($result->status == 'Finalizado') {
                        echo 'selected';
                    } ?> value="Finalizado">Finalizado
                                                </option>
                                            </select>
                                        </div>
                                         <div class="span3">
                                            <label for="cidades">Cidade</label>
                                            <select class="span12" name="cidades" id="cidades" value="">
                                               <option <?php if ($result->cidades == 'Aceguá') {
                        echo 'selected';
                    } ?> value="Aceguá">Aceguá
                                                </option>
                                                <option <?php if ($result->cidades == 'Água Santa') {
                        echo 'selected';
                    } ?> value="Água Santa">Água Santa
                                                </option>
                                                <option <?php if ($result->cidades == 'Agudo') {
                        echo 'selected';
                    } ?> value="Agudo">Agudo        
                                                </option>
                                                <option <?php if ($result->cidades == 'Ajuricaba') {
                        echo 'selected';
                    } ?> value="Ajuricaba">Ajuricaba        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alecrim') {
                        echo 'selected';
                    } ?> value="Alecrim">Alecrim        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alegrete') {
                        echo 'selected';
                    } ?> value="Alegrete">Alegrete        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alegria') {
                        echo 'selected';
                    } ?> value="Alegria">Alegria        
                                                </option>
                                                <option <?php if ($result->cidades == 'Almirante Tamandaré do Sul') {
                        echo 'selected';
                    } ?> value="Almirante Tamandaré do Sul">Almirante Tamandaré do Sul        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alpestre') {
                        echo 'selected';
                    } ?> value="Alpestre">Alpestre        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alto Alegre') {
                        echo 'selected';
                    } ?> value="Alto Alegre">Alto Alegre        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alto Feliz') {
                        echo 'selected';
                    } ?> value="Alto Feliz">Alto Feliz        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alvorada') {
                        echo 'selected';
                    } ?> value="Alvorada">Alvorada        
                                                </option>
                                                <option <?php if ($result->cidades == 'Amaral Ferrador') {
                        echo 'selected';
                    } ?> value="Amaral Ferrador">Amaral Ferrador        
                                                </option>
                                                <option <?php if ($result->cidades == 'Amestista do Sul') {
                        echo 'selected';
                    } ?> value="Amestista do Sul">Amestista do Sul        
                                                </option>
                                                <option <?php if ($result->cidades == 'Andre da Rocha') {
                        echo 'selected';
                    } ?> value="Andre da Rocha">Andre da Rocha        
                                                </option>
                                                <option <?php if ($result->cidades == 'Alegria') {
                        echo 'selected';
                    } ?> value="Alegria">Alegria        
                                                </option>
                                                <option <?php if ($result->cidades == 'Anta Gorda') {
                        echo 'selected';
                    } ?> value="Anta Gorda">Anta Gorda        
                                                </option>
                                                <option <?php if ($result->cidades == 'Antonio Prado') {
                        echo 'selected';
                    } ?> value="Antonio Prado">Antonio Prado        
                                                </option>
                                                <option <?php if ($result->cidades == 'Arambaré') {
                        echo 'selected';
                    } ?> value="Arambaré">Arambaré        
                                                </option>
                                                
                                                <option <?php if ($result->cidades == 'Ararica') {
                        echo 'selected';
                    } ?> value="Ararica">Ararica        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Aratiba') {
                        echo 'selected';
                    } ?> value="Aratiba">Aratiba        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Arroio do Meio') {
                        echo 'selected';
                    } ?> value="Arroio do Meio">Arroio do Meio        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Arroio do Sal') {
                        echo 'selected';
                    } ?> value="Arroio do Sal">Arroio do Sal        
                                                </option>
                                                
                                                <option <?php if ($result->cidades == 'Arroio do Padre') {
                        echo 'selected';
                    } ?> value="Arroio do Padre">Arroio do Padre        
                                                </option>
                                                <option <?php if ($result->cidades == 'Arroio dos Ratos') {
                        echo 'selected';
                    } ?> value="Arroio dos Ratos">Arroio dos Ratos        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Arroio do Tigre') {
                        echo 'selected';
                    } ?> value="Arroio do Tigre">Arroio do Tigre        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Arroio Grande') {
                        echo 'selected';
                    } ?> value="Arroio Grande">Arroio Grande        
                                                </option>
                                                <option <?php if ($result->cidades == 'Arvorezinha') {
                        echo 'selected';
                    } ?> value="Arvorezinha">Arvorezinha        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Augusto Pestana') {
                        echo 'selected';
                    } ?> value="Augusto Pestana">Augusto Pestana        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Áurea') {
                        echo 'selected';
                    } ?> value="Áurea">Áurea        
                                                </option> <option <?php if ($result->cidades == 'Bagé') {
                        echo 'selected';
                    } ?> value="Bagé">Bagé        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Balneario Pinhal') {
                        echo 'selected';
                    } ?> value="Balneario Pinhal">Balneario Pinhal        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Bagé') {
                        echo 'selected';
                    } ?> value="Bagé">Bagé        
                                                </option> <option <?php if ($result->cidades == 'Barão') {
                        echo 'selected';
                    } ?> value="Barão">Barão        
                                                </option> <option <?php if ($result->cidades == 'Barão de Cotegipe') {
                        echo 'selected';
                    } ?> value="Barão de Cotegipe">Barão de Cotegipe        
                                                </option> <option <?php if ($result->cidades == 'Barão do Triunfo') {
                        echo 'selected';
                    } ?> value="Barão do Triunfo">Barão do Triunfo        
                                                </option> <option <?php if ($result->cidades == 'Barracão') {
                        echo 'selected';
                    } ?> value="Barracão">Barracão        
                                                </option> <option <?php if ($result->cidades == 'Barra do Guarita') {
                        echo 'selected';
                    } ?> value="Barra do Guarita">Barra do Guarita        
                                                </option> <option <?php if ($result->cidades == 'Barra do Quara') {
                        echo 'selected';
                    } ?> value="Barra do Quara">Barra do Quara        
                                                </option> 
                                                <option <?php if ($result->cidades == 'Barra do Ribeiro') {
                        echo 'selected';
                    } ?> value="Barra do Ribeiro">Barra do Ribeiro        
                                                </option> 
                                                <option <?php if ($result->cidades == 'Barra do Rio Azul') {
                        echo 'selected';
                    } ?> value="Barra do Rio Azul">Barra do Rio Azul        
                                                </option> 
                                                <option <?php if ($result->cidades == 'Barra Funda') {
                        echo 'selected';
                    } ?> value="Barra Funda">Barra Funda        
                                                </option>
                                                <option <?php if ($result->cidades == 'Barra Cassal') {
                        echo 'selected';
                    } ?> value="Barra Cassal">Barra Cassal        
                                                </option>
                                                <option <?php if ($result->cidades == 'Benjamin Constant do Sul') {
                        echo 'selected';
                    } ?> value="Benjamin Constant do Sul">Benjamin Constant do Sul        
                                                </option>
                                                <option <?php if ($result->cidades == 'Bento Gonçalves') {
                        echo 'selected';
                    } ?> value="Bento Gonçalves">Bento Gonçalves        
                                                </option>
                                                <option <?php if ($result->cidades == 'Barra Cassal') {
                        echo 'selected';
                    } ?> value="Barra Cassal">Barra Cassal        
                                                </option>
                                                <option <?php if ($result->cidades == 'Boa Vista das Misões') {
                        echo 'selected';
                    } ?> value="Boa Vista das Misões">Boa Vista das Misões        
                                                </option>
                                                <option <?php if ($result->cidades == 'Boa Vista do Buricá') {
                        echo 'selected';
                    } ?> value="Boa Vista do Buricá">Boa Vista do Buricá        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Boa Vista do Cadeado') {
                        echo 'selected';
                    } ?> value="Boa Vista do Cadeado">Boa Vista do Cadeado        
                                                </option> <option <?php if ($result->cidades == 'Boa Vista do Incra') {
                        echo 'selected';
                    } ?> value="Boa Vista do Incra">Boa Vista do Incra        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Boa Vista do Sul') {
                        echo 'selected';
                    } ?> value="Boa Vista do Sul">Boa Vista do Sul        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Bom Jesus') {
                        echo 'selected';
                    } ?> value="Bom Jesus">Bom Jesus        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Bom Princípio') {
                        echo 'selected';
                    } ?> value="Bom Princípio">Bom Princípio        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Bom Progresso') {
                        echo 'selected';
                    } ?> value="Bom Progresso">Bom Progresso        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Bom Retiro do Sul') {
                        echo 'selected';
                    } ?> value="Bom Retiro do Sul">Bom Retiro do Sul        
                                                </option>
                                                 <option <?php if ($result->cidades == 'Boqueirão do Leão') {
                        echo 'selected';
                    } ?> value="Boqueirão do Leão">Boqueirão do Leão        
                                                </option>
                                                
                                                <option <?php if ($result->cidades == 'Bossoroca') {
                        echo 'selected';
                    } ?> value="Bossoroca">Bossoroca
                                                </option>
                                                  <option <?php if ($result->cidades == 'Bozano') {
                        echo 'selected';
                    } ?> value="Bozano">Bozano
                                                </option>
                                                  <option <?php if ($result->cidades == 'Braga') {
                        echo 'selected';
                    } ?> value="Braga">Braga
                                                </option>
                                                  <option <?php if ($result->cidades == 'Brochier') {
                        echo 'selected';
                    } ?> value="Brochier">Brochier
                                                </option>
                                                  <option <?php if ($result->cidades == 'Butiá') {
                        echo 'selected';
                    } ?> value="Butiá">Butiá
                                                </option>
                                                  <option <?php if ($result->cidades == 'Caçapava do Sul<') {
                        echo 'selected';
                    } ?> value="Caçapava do Sul<">Caçapava do Sul<
                                                </option>
                                                  <option <?php if ($result->cidades == 'Cacequi') {
                        echo 'selected';
                    } ?> value="Cacequi">Cacequi
                                                </option>
                                                  <option <?php if ($result->cidades == 'Cachoeira do Sul') {
                        echo 'selected';
                    } ?> value="Cachoeira do Sul">Cachoeira do Sul
                                                </option>
                                                  <option <?php if ($result->cidades == 'Cachoeirinha') {
                        echo 'selected';
                    } ?> value="Cachoeirinha">Cachoeirinha
                                                </option>
                                                  <option <?php if ($result->cidades == 'Cacique Doble') {
                        echo 'selected';
                    } ?> value="Cacique Doble">Cacique Doble
                                                </option>
                                                  <option <?php if ($result->cidades == 'Caibaté') {
                        echo 'selected';
                    } ?> value="Caibaté">Caibaté
                                                </option>
                                                  <option <?php if ($result->cidades == 'Caicara') {
                        echo 'selected';
                    } ?> value="Caicara">Caicara
                                                </option>
                                                  <option <?php if ($result->cidades == 'Camargo') {
                        echo 'selected';
                    } ?> value="Camargo">Camargo
                                                </option>
                                                  <option <?php if ($result->cidades == 'Camaquã') {
                        echo 'selected';
                    } ?> value="Camaquã">Camaquã
                                                </option>
                                                  <option <?php if ($result->cidades == 'Cambará do Sul') {
                        echo 'selected';
                    } ?> value="Cambará do Sul">Cambará do Sul
                                                </option>  
                                                <option <?php if ($result->cidades == 'Campestre da Serra') {
                        echo 'selected';
                    } ?> value="Campestre da Serra">Campestre da Serra
                                                </option>
                                                  <option <?php if ($result->cidades == 'Campina das Missões') {
                        echo 'selected';
                    } ?> value="Campina das Missões">Campina das Missões
                                                </option>
                                                
                                                <option <?php if ($result->cidades == 'Capina do Sul') {
                        echo 'selected';
                    } ?> value="Capina do Sul">Capina do Sul
                                                </option>
                                                  <option <?php if ($result->cidades == 'Campo Bom') {
                        echo 'selected';
                    } ?> value="Campo Bom">Campo Bom
                                                </option>
                                                  <option <?php if ($result->cidades == 'Campo Novo') {
                        echo 'selected';
                    } ?> value="Campo Novo">Campo Novo
                                                </option>
                                                  <option <?php if ($result->cidades == 'Camargo') {
                        echo 'selected';
                    } ?> value="Camargo">Camargo
                                                </option>  <option <?php if ($result->cidades == 'Campos Borges') {
                        echo 'selected';
                    } ?> value="Campos Borges">Campos Borges
                                                </option>
                                                  <option <?php if ($result->cidades == 'Candelaria') {
                        echo 'selected';
                    } ?> value="Candelaria">Candelaria
                                                </option>
                                                  <option <?php if ($result->cidades == 'Candido Godói') {
                        echo 'selected';
                    } ?> value="Candido Godói">Candido Godói
                                                </option>
                                                  <option <?php if ($result->cidades == 'Candiota') {
                        echo 'selected';
                    } ?> value="Candiota">Candiota
                                                </option>
                                                  <option <?php if ($result->cidades == 'Canela') {
                        echo 'selected';
                    } ?> value="Canela">Canela
                                                </option>
                                                  <option <?php if ($result->cidades == 'Canguçu') {
                        echo 'selected';
                    } ?> value="Canguçu">Canguçu
                                                </option> 
                                                <option <?php if ($result->cidades == 'Canoas') {
                        echo 'selected';
                        
                    } ?> value="Canoas">Canoas
                                                </option>
                                                <option <?php if ($result->cidades == 'Canudos do Vale') {
                        echo 'selected';
                    } ?> value="Canudos do Vale">Canudos do Vale
                                                </option>  
                                                <option <?php if ($result->cidades == 'Capão Bonito do Sul') {
                        echo 'selected';
                    } ?> value="Capão Bonito do Sul">Capão Bonito do Sul
                                                </option> 
                                                <option <?php if ($result->cidades == 'Capão da Canoa') {
                        echo 'selected';
                    } ?> value="Capão da Canoa">Capão da Canoa
                                                </option>  
                                                <option <?php if ($result->cidades == 'Capão do Cipo') {
                        echo 'selected';
                    } ?> value="Capão do Cipo">Capão do Cipo
                                                </option>
                                                  <option <?php if ($result->cidades == 'Capão do Leão') {
                        echo 'selected';
                    } ?> value="Capão do Leão">Capão do Leão
                                                </option>
                                                  <option <?php if ($result->cidades == 'Capivari do Sul') {
                        echo 'selected';
                    } ?> value="Capivari do Sul">Capivari do Sul
                                                </option>
                                                  <option <?php if ($result->cidades == 'Capela de Santana') {
                        echo 'selected';
                    } ?> value="Capela de Santana">Capela de Santana
                                                </option>
                                                  <option <?php if ($result->cidades == 'Capitão') {
                        echo 'selected';
                    } ?> value="Capitão">Capitão
                                                </option>
                                                  <option <?php if ($result->cidades == 'Carazinho') {
                        echo 'selected';
                    } ?> value="Carazinho">Carazinho
                                                </option>
                                                  <option <?php if ($result->cidades == 'Caraá') {
                        echo 'selected';
                    } ?> value="Caraá">Caraá
                                                </option>  
                                                <option <?php if ($result->cidades == 'Carlos Barbosa') {
                        echo 'selected';
                    } ?> value="Carlos Barbosa">Carlos Barbosa
                                                </option>
                                                <option <?php if ($result->cidades == 'Carlos Gomes') {
                        echo 'selected';
                    } ?> value="Carlos Gomes">Carlos Gomes
                                                </option>
                                                <option <?php if ($result->cidades == 'Casca') {
                        echo 'selected';
                    } ?> value="Casca">Casca
                                                </option>
                                                <option <?php if ($result->cidades == 'Caseiros') {
                        echo 'selected';
                    } ?> value="Caseiros">Caseiros
                                                </option>
                                                <option <?php if ($result->cidades == 'Catuípe') {
                        echo 'selected';
                    } ?> value="Catuípe">Catuípe
                                                </option>
                                                <option <?php if ($result->cidades == 'Caxias do Sul') {
                        echo 'selected';
                    } ?> value="Caxias do Sul">Caxias do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Centenário') {
                        echo 'selected';
                    } ?> value="Centenário">Centenário
                                                </option>
                                                <option <?php if ($result->cidades == 'Cerrito') {
                        echo 'selected';
                    } ?> value="Cerrito">Cerrito
                                                </option>
                                                <option <?php if ($result->cidades == 'Cerro Branco') {
                        echo 'selected';
                    } ?> value="Cerro Branco">Cerro Branco
                                                </option>
                                                <option <?php if ($result->cidades == 'Cerro Grande') {
                        echo 'selected';
                    } ?> value="Cerro Grande">Cerro Grande
                                                </option>
                                                <option <?php if ($result->cidades == 'Cerro Grande do Sul') {
                        echo 'selected';
                    } ?> value="Cerro Grande do Sul">Cerro Grande do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Cerro Largo') {
                        echo 'selected';
                    } ?> value="Cerro Largo">Cerro Largo
                                                </option>
                                                <option <?php if ($result->cidades == 'Chapada') {
                        echo 'selected';
                    } ?> value="Chapada">Chapada
                                                </option>
                                                <option <?php if ($result->cidades == 'Charqueadas') {
                        echo 'selected';
                    } ?> value="Charqueadas">Charqueadas
                                                </option>
                                                <option <?php if ($result->cidades == 'Charrua') {
                        echo 'selected';
                    } ?> value="Charrua">Charrua
                                                </option>
                                                <option <?php if ($result->cidades == 'Chiapetta') {
                        echo 'selected';
                    } ?> value="Chiapetta">Chiapetta
                                                </option>
                                                <option <?php if ($result->cidades == 'Chuí') {
                        echo 'selected';
                    } ?> value="Chuí">Chuí
                                                </option>
                                                <option <?php if ($result->cidades == 'Chuvísca') {
                        echo 'selected';
                    } ?> value="Chuvísca">Chuvísca
                                                </option>
                                                <option <?php if ($result->cidades == 'Cidreira') {
                        echo 'selected';
                    } ?> value="Cidreira">Cidreira
                                                </option>
                                                <option <?php if ($result->cidades == 'Ciríaco') {
                        echo 'selected';
                    } ?> value="Ciríaco">Ciríaco
                                                </option>
                                                <option <?php if ($result->cidades == 'Colinas') {
                        echo 'selected';
                    } ?> value="Colinas">Colinas
                                                </option>
                                                <option <?php if ($result->cidades == 'Colorado') {
                        echo 'selected';
                    } ?> value="Colorado">Colorado
                                                </option>
                                                <option <?php if ($result->cidades == 'Condor') {
                        echo 'selected';
                    } ?> value="Condor">Condor
                                                </option>
                                                <option <?php if ($result->cidades == 'Constantina') {
                        echo 'selected';
                    } ?> value="Constantina">Constantina
                                                </option>
                                                <option <?php if ($result->cidades == 'Coqueiros Baixo') {
                        echo 'selected';
                    } ?> value="Coqueiros Baixo">Coqueiros Baixo
                                                </option>
                                                <option <?php if ($result->cidades == 'Coqueiros do Sul') {
                        echo 'selected';
                    } ?> value="Coqueiros do Sul">Coqueiros do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Coronel Barros') {
                        echo 'selected';
                    } ?> value="Coronel Barros">Coronel Barros
                                                </option>
                                                <option <?php if ($result->cidades == 'Coronel Bicaco') {
                        echo 'selected';
                    } ?> value="Coronel Bicaco">Coronel Bicaco
                                                </option>
                                                <option <?php if ($result->cidades == 'Coronel Pilar') {
                        echo 'selected';
                    } ?> value="Coronel Pilar">Coronel Pilar
                                                </option>
                                                <option <?php if ($result->cidades == 'Cotipora') {
                        echo 'selected';
                    } ?> value="Cotipora">Cotipora
                                                </option>
                                                <option <?php if ($result->cidades == 'Coxilha') {
                        echo 'selected';
                    } ?> value="Coxilha">Coxilha
                                                </option>
                                                <option <?php if ($result->cidades == 'Crissiumal') {
                        echo 'selected';
                    } ?> value="Crissiumal">Crissiumal
                                                </option>
                                                <option <?php if ($result->cidades == 'Cristal') {
                        echo 'selected';
                    } ?> value="Cristal">Cristal
                                                </option>
                                                <option <?php if ($result->cidades == 'Cristal do Sul') {
                        echo 'selected';
                    } ?> value="Cristal do Sul">Cristal do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Cruz Alta') {
                        echo 'selected';
                    } ?> value="Cruz Alta">Cruz Alta
                                                </option>
                                                <option <?php if ($result->cidades == 'Cruzaltense') {
                        echo 'selected';
                    } ?> value="Cruzaltense">Cruzaltense
                                                </option>
                                                <option <?php if ($result->cidades == 'Cruzeiro do Sul') {
                        echo 'selected';
                    } ?> value="Cruzeiro do Sul">Cruzeiro do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'David Canabarro') {
                        echo 'selected';
                    } ?> value="David Canabarro">David Canabarro
                                                </option>
                                                <option <?php if ($result->cidades == 'Derrubadas') {
                        echo 'selected';
                    } ?> value="Derrubadas">Derrubadas
                                                </option>
                                                <option <?php if ($result->cidades == 'Dezesseis de Novembro') {
                        echo 'selected';
                    } ?> value="Dezesseis de Novembro">Dezesseis de Novembro
                                                </option>
                                                <option <?php if ($result->cidades == 'Dilermando de Aguiar') {
                        echo 'selected';
                    } ?> value="Dilermando de Aguiar">Dilermando de Aguiar
                                                </option>
                                                <option <?php if ($result->cidades == 'Dois Irmãos') {
                        echo 'selected';
                    } ?> value="Dois Irmãos">Dois Irmãos
                                                </option>
                                                <option <?php if ($result->cidades == 'Dois Irmãos das Missões') {
                        echo 'selected';
                    } ?> value="Dois Irmãos das Missões">Dois Irmãos das Missões
                                                </option>
                                                <option <?php if ($result->cidades == 'Dois Lajeados') {
                        echo 'selected';
                    } ?> value="Dois Lajeados">Dois Lajeados
                                                </option>
                                                <option <?php if ($result->cidades == 'Dois Felicianos') {
                        echo 'selected';
                    } ?> value="Dois Felicianos">Dois Felicianos
                                                </option>
                                                <option <?php if ($result->cidades == 'Dom Pedro de Alcantara') {
                        echo 'selected';
                    } ?> value="Dom Pedro de Alcantara">Dom Pedro de Alcantara
                                                </option>
                                                <option <?php if ($result->cidades == 'Dom Pedrito') {
                        echo 'selected';
                    } ?> value="Dom Pedrito">Dom Pedrito
                                                </option>
                                                <option <?php if ($result->cidades == 'Dona Francisca') {
                        echo 'selected';
                    } ?> value="Dona Francisca">Dona Francisca
                                                </option>
                                                <option <?php if ($result->cidades == 'Doutor Maurício Cardoso') {
                        echo 'selected';
                    } ?> value="Doutor Maurício Cardoso">Doutor Maurício Cardoso
                                                </option>
                                                <option <?php if ($result->cidades == 'Eldorado do Sul') {
                        echo 'selected';
                    } ?> value="Eldorado do Sul">Eldorado do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Encantado') {
                        echo 'selected';
                    } ?> value="Encantado">Encantado
                                                </option>
                                                <option <?php if ($result->cidades == 'Encruilhada do Sul') {
                        echo 'selected';
                    } ?> value="Encruilhada do Sul">Encruilhada do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Engenho Velho') {
                        echo 'selected';
                    } ?> value="Engenho Velho">Engenho Velho
                                                </option>
                                                <option <?php if ($result->cidades == 'Entre Rios do Sul') {
                        echo 'selected';
                    } ?> value="Entre Rios do Sul">Entre Rios do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Erebango') {
                        echo 'selected';
                    } ?> value="Erebango">Erebango
                                                </option>
                                                <option <?php if ($result->cidades == 'Erechim') {
                        echo 'selected';
                    } ?> value="Erechim">Erechim
                                                </option>
                                                <option <?php if ($result->cidades == 'Ernestina') {
                        echo 'selected';
                    } ?> value="Ernestina">Ernestina
                                                </option>
                                                <option <?php if ($result->cidades == 'Herval') {
                        echo 'selected';
                    } ?> value="Herval">Herval
                                                </option>
                                                <option <?php if ($result->cidades == 'Erval Grande') {
                        echo 'selected';
                    } ?> value="Erval Grande">Erval Grande
                                                </option>
                                                <option <?php if ($result->cidades == 'Erval Seco') {
                        echo 'selected';
                    } ?> value="Erval Seco">Erval Seco
                                                </option>
                                                <option <?php if ($result->cidades == 'Esmeralda') {
                        echo 'selected';
                    } ?> value="Esmeralda">Esmeralda
                                                </option>
                                                <option <?php if ($result->cidades == 'Esperança do Sul') {
                        echo 'selected';
                    } ?> value="Esperança do Sul">Esperança do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Espumoso') {
                        echo 'selected';
                    } ?> value="Espumoso">Espumoso
                                                </option>
                                                <option <?php if ($result->cidades == 'Estação') {
                        echo 'selected';
                    } ?> value="Estação">Estação
                                                </option><option <?php if ($result->cidades == 'Estância Velha') {
                        echo 'selected';
                    } ?> value="Estância Velha">Estância Velha
                                                </option>
                                                <option <?php if ($result->cidades == 'Esteio') {
                        echo 'selected';
                    } ?> value="Esteio">Esteio
                                                </option>
                                                <option <?php if ($result->cidades == 'Estrela') {
                        echo 'selected';
                    } ?> value="Estrela">Estrela
                                                </option>
                                                <option <?php if ($result->cidades == 'Estrela Velha') {
                        echo 'selected';
                    } ?> value="Estrela Velha">Estrela Velha
                                                </option>
                                                <option <?php if ($result->cidades == 'Eugênio de Castro') {
                        echo 'selected';
                    } ?> value="Eugênio de Castro">Eugênio de Castro
                                                </option>
                                                <option <?php if ($result->cidades == 'Fagundes Varela') {
                        echo 'selected';
                    } ?> value="Fagundes Varela">Fagundes Varela
                                                </option>
                                                <option <?php if ($result->cidades == 'Farroupilha') {
                        echo 'selected';
                    } ?> value="Farroupilha">Farroupilha
                                                </option><option <?php if ($result->cidades == 'Faxinal do Soturno') {
                        echo 'selected';
                    } ?> value="Faxinal do Soturno">Faxinal do Soturno
                                                </option>
                                                <option <?php if ($result->cidades == 'Faxinalzinho') {
                        echo 'selected';
                    } ?> value="Faxinalzinho">Faxinalzinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Fazenda Vilanova') {
                        echo 'selected';
                    } ?> value="CristFazenda Vilanovaal">Fazenda Vilanova
                                                </option>
                                                <option <?php if ($result->cidades == 'Feliz') {
                        echo 'selected';
                    } ?> value="Feliz">Feliz
                                                </option>
                                                <option <?php if ($result->cidades == 'Flores da Cunha') {
                        echo 'selected';
                    } ?> value="Flores da Cunha">Flores da Cunha
                                                </option>
                                                <option <?php if ($result->cidades == 'Floriano Peixoto') {
                        echo 'selected';
                    } ?> value="Floriano Peixoto">Floriano Peixoto
                                                </option>
                                                <option <?php if ($result->cidades == 'Fontoura Xavier') {
                        echo 'selected';
                    } ?> value="Fontoura Xavier">Fontoura Xavier
                                                </option>
                                                <option <?php if ($result->cidades == 'Formigueiro') {
                        echo 'selected';
                    } ?> value="Formigueiro">Formigueiro
                                                </option>
                                                <option <?php if ($result->cidades == 'Forquetinha') {
                        echo 'selected';
                    } ?> value="Forquetinha">Forquetinha
                                                </option>
                                                <option <?php if ($result->cidades == 'Fortaleza dos Valos') {
                        echo 'selected';
                    } ?> value="Fortaleza dos Valos">Fortaleza dos Valos
                                                </option>
                                                <option <?php if ($result->cidades == 'Frederico Westphalen') {
                        echo 'selected';
                    } ?> value="Frederico Westphalen">Frederico Westphalen
                                                </option>
                                                <option <?php if ($result->cidades == 'Garibaldi') {
                        echo 'selected';
                    } ?> value="Garibaldi">Garibaldi
                                                </option>
                                                <option <?php if ($result->cidades == 'Garruchos') {
                        echo 'selected';
                    } ?> value="Garruchos">Garruchos
                                                </option>
                                                <option <?php if ($result->cidades == 'Gaurama') {
                        echo 'selected';
                    } ?> value="Gaurama">Gaurama
                                                </option><option <?php if ($result->cidades == 'General Camara') {
                        echo 'selected';
                    } ?> value="General Camara">General Camara
                                                </option><option <?php if ($result->cidades == 'Gentil') {
                        echo 'selected';
                    } ?> value="Gentil">Gentil
                                                </option><option <?php if ($result->cidades == 'Getúlio Vargas') {
                        echo 'selected';
                    } ?> value="Getúlio Vargas">Getúlio Vargas
                                                </option>
                                                <option <?php if ($result->cidades == 'Giruá') {
                        echo 'selected';
                    } ?> value="Giruá">Giruá
                                                </option>
                                                <option <?php if ($result->cidades == 'Glorinha') {
                        echo 'selected';
                    } ?> value="Glorinha">Glorinha
                                                </option>
                                                <option <?php if ($result->cidades == 'Gramado') {
                        echo 'selected';
                    } ?> value="Gramado">Gramado
                                                </option>
                                                <option <?php if ($result->cidades == 'Gramado dos Loureiros') {
                        echo 'selected';
                    } ?> value="Gramado dos Loureiros">Gramado dos Loureiros
                                                </option>
                                                <option <?php if ($result->cidades == 'Gramado Xavier') {
                        echo 'selected';
                    } ?> value="Gramado Xavier">Gramado Xavier
                                                </option>
                                                <option <?php if ($result->cidades == 'Gravataí') {
                        echo 'selected';
                    } ?> value="Gravataí">Gravataí
                                                </option>
                                                <option <?php if ($result->cidades == 'Guabiju') {
                        echo 'selected';
                    } ?> value="Guabiju">Guabiju
                                                </option>
                                                <option <?php if ($result->cidades == 'Guaíba') {
                        echo 'selected';
                    } ?> value="Guaíba">Guaíba
                                                </option>
                                                <option <?php if ($result->cidades == 'Guaporé') {
                        echo 'selected';
                    } ?> value="Guaporé">Guaporé
                                                </option>
                                                <option <?php if ($result->cidades == 'Guarani das Missões') {
                        echo 'selected';
                    } ?> value="Guarani das Missões">Guarani das Missões
                                                </option>
                                                <option <?php if ($result->cidades == 'Harmonia') {
                        echo 'selected';
                    } ?> value="Harmonia">Harmonia
                                                </option>
                                                <option <?php if ($result->cidades == 'Herveiras') {
                        echo 'selected';
                    } ?> value="Herveiras">Herveiras
                                                </option>
                                                <option <?php if ($result->cidades == 'Horizontina') {
                        echo 'selected';
                    } ?> value="Horizontina">Horizontina
                                                </option>
                                                <option <?php if ($result->cidades == 'Hulha Negra') {
                        echo 'selected';
                    } ?> value="Hulha Negra">Hulha Negra
                                                </option>
                                                <option <?php if ($result->cidades == 'Humaitá') {
                        echo 'selected';
                    } ?> value="Humaitá">Humaitá
                                                </option>
                                                <option <?php if ($result->cidades == 'Ibarama') {
                        echo 'selected';
                    } ?> value="Ibarama">Ibarama
                                                </option>
                                                <option <?php if ($result->cidades == 'Ibiacá') {
                        echo 'selected';
                    } ?> value="Ibiacá">Ibiacá
                                                </option>
                                                <option <?php if ($result->cidades == 'Ibiraiaras') {
                        echo 'selected';
                    } ?> value="Ibiraiaras">Ibiraiaras
                                                </option>
                                                <option <?php if ($result->cidades == 'Ibirapuita') {
                        echo 'selected';
                    } ?> value="Ibirapuita">Ibirapuita
                                                </option>
                                                <option <?php if ($result->cidades == 'Ibirubá') {
                        echo 'selected';
                    } ?> value="Ibirubá">Ibirubá
                                                </option>
                                                <option <?php if ($result->cidades == 'Igrejinha') {
                        echo 'selected';
                    } ?> value="Igrejinha">Igrejinha
                                                </option>
                                                <option <?php if ($result->cidades == 'Ijuí') {
                        echo 'selected';
                    } ?> value="Ijuí">Ijuí
                                                </option>
                                                <option <?php if ($result->cidades == 'Ilópolis') {
                        echo 'selected';
                    } ?> value="Ilópolis">Ilópolis
                                                </option>
                                                <option <?php if ($result->cidades == 'Imbé') {
                        echo 'selected';
                    } ?> value="Imbé">Imbé
                                                </option>
                                                <option <?php if ($result->cidades == 'Imigrante') {
                        echo 'selected';
                    } ?> value="Imigrante">Imigrante
                                                </option>
                                                <option <?php if ($result->cidades == 'Independência') {
                        echo 'selected';
                    } ?> value="Independência">Independência
                                                </option>
                                                <option <?php if ($result->cidades == 'Inhacorá') {
                        echo 'selected';
                    } ?> value="Inhacorá">Inhacorá
                                                </option>
                                                <option <?php if ($result->cidades == 'Ipê') {
                        echo 'selected';
                    } ?> value="Ipê">Ipê
                                                </option>
                                                <option <?php if ($result->cidades == 'Ipiranga do Sul') {
                        echo 'selected';
                    } ?> value="Ipiranga do Sul">Ipiranga do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Ira') {
                        echo 'selected';
                    } ?> value="Ira">Ira
                                                </option>
                                                <option <?php if ($result->cidades == 'Itaara') {
                        echo 'selected';
                    } ?> value="Itaara">Itaara
                                                </option>
                                                <option <?php if ($result->cidades == 'Itacurubi') {
                        echo 'selected';
                    } ?> value="Itacurubi">Itacurubi
                                                </option>
                                                <option <?php if ($result->cidades == 'Itapuca') {
                        echo 'selected';
                    } ?> value="Itapuca">Itapuca
                                                </option>
                                                <option <?php if ($result->cidades == 'Itaqui') {
                        echo 'selected';
                    } ?> value="Itaqui">Itaqui
                                                </option>
                                                <option <?php if ($result->cidades == 'Itati') {
                        echo 'selected';
                    } ?> value="Itati">Itati
                                                </option>
                                                <option <?php if ($result->cidades == 'Itaiba do Sul') {
                        echo 'selected';
                    } ?> value="Itaiba do Sul">Itaiba do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Ivorá') {
                        echo 'selected';
                    } ?> value="Ivorá">Ivorá
                                                </option>
                                                <option <?php if ($result->cidades == 'Ivoti') {
                        echo 'selected';
                    } ?> value="Ivoti">Ivoti
                                                </option>
                                                <option <?php if ($result->cidades == 'Jaboticaba') {
                        echo 'selected';
                    } ?> value="Jaboticaba">Jaboticaba
                                                </option>
                                                <option <?php if ($result->cidades == 'Jacuizinho') {
                        echo 'selected';
                    } ?> value="Jacuizinho">Jacuizinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Jacutinga') {
                        echo 'selected';
                    } ?> value="Jacutinga">Jacutinga
                                                </option>
                                                <option <?php if ($result->cidades == 'Jaguarão') {
                        echo 'selected';
                    } ?> value="Jaguarão">Jaguarão
                                                </option>
                                                <option <?php if ($result->cidades == 'Jaguari') {
                        echo 'selected';
                    } ?> value="Jaguari">Jaguari
                                                </option>
                                                <option <?php if ($result->cidades == 'Jaquirana') {
                        echo 'selected';
                    } ?> value="Jaquirana">Jaquirana
                                                </option>
                                                <option <?php if ($result->cidades == 'Jari') {
                        echo 'selected';
                    } ?> value="Jari">Jari
                                                </option>
                                                <option <?php if ($result->cidades == 'Joia') {
                        echo 'selected';
                    } ?> value="Joia">Joia
                                                </option>
                                                <option <?php if ($result->cidades == 'Júlio de Castilhos') {
                        echo 'selected';
                    } ?> value="Júlio de Castilhos">Júlio de Castilhos
                                                </option>
                                                <option <?php if ($result->cidades == 'Lagoa Bonita do Sul') {
                        echo 'selected';
                    } ?> value="Lagoa Bonita do Sul">Lagoa Bonita do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Lagoão') {
                        echo 'selected';
                    } ?> value="Lagoão">Lagoão
                                                </option>
                                                <option <?php if ($result->cidades == 'Lagoa dos Três Cantos') {
                        echo 'selected';
                    } ?> value="Lagoa dos Três Cantos">Lagoa dos Três Cantos
                                                </option>
                                                <option <?php if ($result->cidades == 'Lagoa Vermelha') {
                        echo 'selected';
                    } ?> value="Lagoa Vermelha">Lagoa Vermelha
                                                </option>
                                                <option <?php if ($result->cidades == 'Lajeado') {
                        echo 'selected';
                    } ?> value="Lajeado">Lajeado
                                                </option>
                                                <option <?php if ($result->cidades == 'Lajeado do Bugre') {
                        echo 'selected';
                    } ?> value="Lajeado do Bugre">Lajeado do Bugre
                                                </option>
                                                <option <?php if ($result->cidades == 'Lavras do Sul') {
                        echo 'selected';
                    } ?> value="Lavras do Sul">Lavras do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Liberato Salzano') {
                        echo 'selected';
                    } ?> value="Liberato Salzano">Liberato Salzano
                                                </option>
                                                <option <?php if ($result->cidades == 'Lindolfo Collor') {
                        echo 'selected';
                    } ?> value="Lindolfo Collor">Lindolfo Collor
                                                </option>
                                                <option <?php if ($result->cidades == 'Linha Nova') {
                        echo 'selected';
                    } ?> value="Linha Nova">Linha Nova
                                                </option>
                                                <option <?php if ($result->cidades == 'Machadinho') {
                        echo 'selected';
                    } ?> value="Machadinho">Machadinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Macambará') {
                        echo 'selected';
                    } ?> value="Macambará">Macambará
                                                </option>
                                                <option <?php if ($result->cidades == 'Mampituba') {
                        echo 'selected';
                    } ?> value="Mampituba">Mampituba
                                                </option>
                                                <option <?php if ($result->cidades == 'Manoel Viana') {
                        echo 'selected';
                    } ?> value="Manoel Viana">Manoel Viana
                                                </option>
                                                <option <?php if ($result->cidades == 'Maquiné') {
                        echo 'selected';
                    } ?> value="Maquiné">Maquiné
                                                </option>
                                                <option <?php if ($result->cidades == 'Maratá') {
                        echo 'selected';
                    } ?> value="Maratá">Maratá
                                                </option>
                                                <option <?php if ($result->cidades == 'Marau') {
                        echo 'selected';
                    } ?> value="Marau">Marau
                                                </option>
                                                <option <?php if ($result->cidades == 'Marcelino Ramos') {
                        echo 'selected';
                    } ?> value="Marcelino Ramos">Marcelino Ramos
                                                </option>
                                                <option <?php if ($result->cidades == 'Mariana Pimentel') {
                        echo 'selected';
                    } ?> value="Mariana Pimentel">Mariana Pimentel
                                                </option>
                                                <option <?php if ($result->cidades == 'Mariana Moro') {
                        echo 'selected';
                    } ?> value="Mariana Moro">Mariana Moro
                                                </option>
                                                <option <?php if ($result->cidades == 'Marques de Souza') {
                        echo 'selected';
                    } ?> value="Marques de Souza">Marques de Souza
                                                </option>
                                                <option <?php if ($result->cidades == 'Mata') {
                        echo 'selected';
                    } ?> value="Mata">Mata
                                                </option>
                                                <option <?php if ($result->cidades == 'Mato Castelhano') {
                        echo 'selected';
                    } ?> value="Mato Castelhano">Mato Castelhano
                                                </option>
                                                <option <?php if ($result->cidades == 'Mato Leitão') {
                        echo 'selected';
                    } ?> value="Mato Leitão">Mato Leitão
                                                </option>
                                                <option <?php if ($result->cidades == 'Mato Queimado') {
                        echo 'selected';
                    } ?> value="Mato Queimado">Mato Queimado
                                                </option>
                                                <option <?php if ($result->cidades == 'Maximiliano de Almeida') {
                        echo 'selected';
                    } ?> value="Maximiliano de Almeida">Maximiliano de Almeida
                                                </option>
                                                <option <?php if ($result->cidades == 'Minas do Leão') {
                        echo 'selected';
                    } ?> value="Minas do Leão">Minas do Leão
                                                </option>
                                                <option <?php if ($result->cidades == 'Miraguá') {
                        echo 'selected';
                    } ?> value="Miraguá">Miraguá
                                                </option>
                                                <option <?php if ($result->cidades == 'Mountari') {
                        echo 'selected';
                    } ?> value="Mountari">Mountari
                                                </option>
                                                <option <?php if ($result->cidades == 'Monte Alegre dos Campos') {
                        echo 'selected';
                    } ?> value="Monte Alegre dos Campos">Monte Alegre dos Campos
                                                </option>
                                                <option <?php if ($result->cidades == 'Monte Belo do Sul') {
                        echo 'selected';
                    } ?> value="Monte Belo do Sul">Monte Belo do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Montenegro') {
                        echo 'selected';
                    } ?> value="Montenegro">Montenegro
                                                </option>
                                                <option <?php if ($result->cidades == 'Mormaco') {
                        echo 'selected';
                    } ?> value="Mormaco">Mormaco
                                                </option>
                                                <option <?php if ($result->cidades == 'Morrinhos do Sul') {
                        echo 'selected';
                    } ?> value="Morrinhos do Sul">Morrinhos do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Morro Redondo') {
                        echo 'selected';
                    } ?> value="Morro Redondo">Morro Redondo
                                                </option>
                                                <option <?php if ($result->cidades == 'Morro Reuter') {
                        echo 'selected';
                    } ?> value="Morro Reuter">Morro Reuter
                                                </option>
                                                <option <?php if ($result->cidades == 'Mostardas') {
                        echo 'selected';
                    } ?> value="Mostardas">Mostardas
                                                </option><option <?php if ($result->cidades == 'Mucum') {
                        echo 'selected';
                    } ?> value="Mucum">Mucum
                                                </option>
                                                <option <?php if ($result->cidades == 'Muitos Capitões') {
                        echo 'selected';
                    } ?> value="Muitos Capitões">Muitos Capitões
                                                </option>
                                                
                                                <option <?php if ($result->cidades == 'Muliterno') {
                        echo 'selected';
                    } ?> value="Muliterno">Muliterno
                                                </option>
                                                <option <?php if ($result->cidades == 'Não-Me-Toque') {
                        echo 'selected';
                    } ?> value="Não-Me-Toque">Não-Me-Toque
                                                </option>
                                                
                                                <option <?php if ($result->cidades == 'Nicolau Vergueiro') {
                        echo 'selected';
                    } ?> value="Nicolau Vergueiro">Nicolau Vergueiro
                                                </option>
                                                <option <?php if ($result->cidades == 'Nonoai') {
                        echo 'selected';
                    } ?> value="Nonoai">Nonoai
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Alvorada') {
                        echo 'selected';
                    } ?> value="Nova Alvorada">Nova Alvorada
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Aracá') {
                        echo 'selected';
                    } ?> value="Nova Aracá">Nova Aracá
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Bassano') {
                        echo 'selected';
                    } ?> value="Nova Bassano">Nova Bassano
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Boa Vista') {
                        echo 'selected';
                    } ?> value="Nova Boa Vista">Nova Boa Vista
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Bréscia') {
                        echo 'selected';
                    } ?> value="Nova Bréscia">Nova Bréscia
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Candelária') {
                        echo 'selected';
                    } ?> value="Nova Candelária">Nova Candelária
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Esperança do Sul') {
                        echo 'selected';
                    } ?> value="Nova Esperança do Sul">Nova Esperança do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Hartz') {
                        echo 'selected';
                    } ?> value="Nova Hartz">Nova Hartz
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Paduá') {
                        echo 'selected';
                    } ?> value="Nova Paduá">Nova Paduá
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Palma') {
                        echo 'selected';
                    } ?> value="Nova Palma">Nova Palma
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Petrópolis') {
                        echo 'selected';
                    } ?> value="Nova Petrópolis">Nova Petrópolis
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Prata') {
                        echo 'selected';
                    } ?> value="Nova Prata">Nova Prata
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Ramada') {
                        echo 'selected';
                    } ?> value="Nova Ramada">Nova Ramada
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Roma do Sul') {
                        echo 'selected';
                    } ?> value="Nova Roma do Sul">Nova Roma do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Nova Santa Rita') {
                        echo 'selected';
                    } ?> value="Nova Santa Rita">Nova Santa Rita
                                                </option>
                                                <option <?php if ($result->cidades == 'Novo Cabrais') {
                        echo 'selected';
                    } ?> value="Novo Cabrais">Novo Cabrais
                                                </option>
                                                <option <?php if ($result->cidades == 'Novo Hamburgo') {
                        echo 'selected';
                    } ?> value="Novo Hamburgo">Novo Hamburgo
                                                </option>
                                                <option <?php if ($result->cidades == 'Novo Machado') {
                        echo 'selected';
                    } ?> value="Novo Machado">Novo Machado
                                                </option>
                                                <option <?php if ($result->cidades == 'Novo Tiradentes') {
                        echo 'selected';
                    } ?> value="Novo Tiradentes">Novo Tiradentes
                                                </option>
                                                <option <?php if ($result->cidades == 'Novo Xingu') {
                        echo 'selected';
                    } ?> value="Novo Xingu">Novo Xingu
                                                </option>
                                                <option <?php if ($result->cidades == 'Novo Barreiro') {
                        echo 'selected';
                    } ?> value="Novo Barreiro">Novo Barreiro
                                                </option>
                                                <option <?php if ($result->cidades == 'Osório') {
                        echo 'selected';
                    } ?> value="Osório">Osório
                                                </option>
                                                <option <?php if ($result->cidades == 'Paim Filho') {
                        echo 'selected';
                    } ?> value="Paim Filho">Paim Filho
                                                </option>
                                                <option <?php if ($result->cidades == 'Palmares do Sul') {
                        echo 'selected';
                    } ?> value="Palmares do Sul">Palmares do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Palmeira das Missões') {
                        echo 'selected';
                    } ?> value="Palmeira das Missões">Palmeira das Missões
                                                </option>
                                                <option <?php if ($result->cidades == 'Palmitinho') {
                        echo 'selected';
                    } ?> value="Palmitinho">Palmitinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Panambi') {
                        echo 'selected';
                    } ?> value="Panambi">Panambi
                                                </option>
                                                <option <?php if ($result->cidades == 'Pântano Grande') {
                        echo 'selected';
                    } ?> value="Pântano Grande">Pântano Grande
                                                </option>
                                                <option <?php if ($result->cidades == 'Paraí') {
                        echo 'selected';
                    } ?> value="Paraí">Paraí
                                                </option>
                                                <option <?php if ($result->cidades == 'Paraíso do Sul') {
                        echo 'selected';
                    } ?> value="Paraíso do Sul">Paraíso do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Pareci Novo') {
                        echo 'selected';
                    } ?> value="Pareci Novo">Pareci Novo
                                                </option>
                                                <option <?php if ($result->cidades == 'Parobé') {
                        echo 'selected';
                    } ?> value="Parobé">Parobé
                                                </option>
                                                <option <?php if ($result->cidades == 'Passa Sete') {
                        echo 'selected';
                    } ?> value="Passa Sete">Passa Sete
                                                </option>
                                                <option <?php if ($result->cidades == 'Passo do Sobrado') {
                        echo 'selected';
                    } ?> value="Passo do Sobrado">Passo do Sobrado
                                                </option>
                                                <option <?php if ($result->cidades == 'Passo Fundo') {
                        echo 'selected';
                    } ?> value="Passo Fundo">Passo Fundo
                                                </option>
                                                <option <?php if ($result->cidades == 'Paulo Bento') {
                        echo 'selected';
                    } ?> value="Paulo Bento">Paulo Bento
                                                </option>
                                                <option <?php if ($result->cidades == 'Paverama') {
                        echo 'selected';
                    } ?> value="Paverama">Paverama
                                                </option>
                                                <option <?php if ($result->cidades == 'Pedras Altas') {
                        echo 'selected';
                    } ?> value="Pedras Altas">Pedras Altas
                                                </option>
                                                <option <?php if ($result->cidades == 'Pedro Osório') {
                        echo 'selected';
                    } ?> value="Pedro Osório">Pedro Osório
                                                </option>
                                                <option <?php if ($result->cidades == 'Pejucara') {
                        echo 'selected';
                    } ?> value="Pejucara">Pejucara
                                                </option>
                                                <option <?php if ($result->cidades == 'Pelotas') {
                        echo 'selected';
                    } ?> value="Pelotas">Pelotas
                                                </option>
                                                <option <?php if ($result->cidades == 'Picada Café') {
                        echo 'selected';
                    } ?> value="Picada Café">Picada Café
                                                </option>
                                                <option <?php if ($result->cidades == 'Pinhal') {
                        echo 'selected';
                    } ?> value="Pinhal">Pinhal
                                                </option>
                                                <option <?php if ($result->cidades == 'Pinhal da Serra') {
                        echo 'selected';
                    } ?> value="Pinhal da Serra">Pinhal da Serra
                                                </option>
                                                <option <?php if ($result->cidades == 'Pinhal Grande') {
                        echo 'selected';
                    } ?> value="Pinhal Grande">Pinhal Grande
                                                </option>
                                                <option <?php if ($result->cidades == 'Pinheirinho do Vale') {
                        echo 'selected';
                    } ?> value="Pinheirinho do Vale">Pinheirinho do Vale
                                                </option>
                                                <option <?php if ($result->cidades == 'Pinheiro Machado') {
                        echo 'selected';
                    } ?> value="Pinheiro Machado">Pinheiro Machado
                                                </option>
                                                <option <?php if ($result->cidades == 'Pirapo') {
                        echo 'selected';
                    } ?> value="Pirapo">Pirapo
                                                </option>
                                                <option <?php if ($result->cidades == 'Piratini') {
                        echo 'selected';
                    } ?> value="Piratini">Piratini
                                                </option>
                                                <option <?php if ($result->cidades == 'Planalto') {
                        echo 'selected';
                    } ?> value="Planalto">Planalto
                                                </option>
                                                <option <?php if ($result->cidades == 'Poço das Antas') {
                        echo 'selected';
                    } ?> value="Poço das Antas">Poço das Antas
                                                </option>
                                                <option <?php if ($result->cidades == 'Pontão') {
                        echo 'selected';
                    } ?> value="Pontão">Pontão
                                                </option>
                                                <option <?php if ($result->cidades == 'Ponte Preta') {
                        echo 'selected';
                    } ?> value="Ponte Preta">Ponte Preta
                                                </option>
                                                <option <?php if ($result->cidades == 'Portão') {
                        echo 'selected';
                    } ?> value="Portão">Portão
                                                </option>
                                                <option <?php if ($result->cidades == 'Porto Alegre') {
                        echo 'selected';
                    } ?> value="Porto Alegre">Porto Alegre
                                                </option>
                                                <option <?php if ($result->cidades == 'Porto Lucena') {
                        echo 'selected';
                    } ?> value="Porto Lucena">Porto Lucena
                                                </option>
                                                <option <?php if ($result->cidades == 'Porto Mauá') {
                        echo 'selected';
                    } ?> value="Porto Mauá">Porto Mauá
                                                </option>
                                                <option <?php if ($result->cidades == 'Porto Vera Cruz') {
                        echo 'selected';
                    } ?> value="Porto Vera Cruz">Porto Vera Cruz
                                                </option>
                                                <option <?php if ($result->cidades == 'Porto Xavier') {
                        echo 'selected';
                    } ?> value="Porto Xavier">Porto Xavier
                                                </option>
                                                <option <?php if ($result->cidades == 'Pouso Novo') {
                        echo 'selected';
                    } ?> value="Pouso Novo">Pouso Novo
                                                </option>
                                                <option <?php if ($result->cidades == 'Presidente Lucena') {
                        echo 'selected';
                    } ?> value="Presidente Lucena">Presidente Lucena
                                                </option>
                                                <option <?php if ($result->cidades == 'Progresso') {
                        echo 'selected';
                    } ?> value="Progresso">Progresso
                                                </option>
                                                <option <?php if ($result->cidades == 'Protásio Alves') {
                        echo 'selected';
                    } ?> value="Protásio Alves">Protásio Alves
                                                </option>
                                                <option <?php if ($result->cidades == 'Putinga') {
                        echo 'selected';
                    } ?> value="Putinga">Putinga
                                                </option>
                                                <option <?php if ($result->cidades == 'Quara') {
                        echo 'selected';
                    } ?> value="Quara">Quara
                                                </option>
                                                <option <?php if ($result->cidades == 'Quatro Irmãos') {
                        echo 'selected';
                    } ?> value="Quatro Irmãos">Quatro Irmãos
                                                </option>
                                                <option <?php if ($result->cidades == 'Quevedos') {
                        echo 'selected';
                    } ?> value="Quevedos">Quevedos
                                                </option>
                                                <option <?php if ($result->cidades == 'Quinze de Novembro') {
                        echo 'selected';
                    } ?> value="Quinze de Novembro">Quinze de Novembro
                                                </option>
                                                <option <?php if ($result->cidades == 'Redentora') {
                        echo 'selected';
                    } ?> value="Redentora">Redentora
                                                </option>
                                                <option <?php if ($result->cidades == 'Relvado') {
                        echo 'selected';
                    } ?> value="Relvado">Relvado
                                                </option>
                                                <option <?php if ($result->cidades == 'Restinga Seca') {
                        echo 'selected';
                    } ?> value="Restinga Seca">Restinga Seca
                                                </option>
                                                <option <?php if ($result->cidades == 'Rio dos Índios') {
                        echo 'selected';
                    } ?> value="Rio dos Índios">Rio dos Índios
                                                </option>
                                                <option <?php if ($result->cidades == 'Rio Grande') {
                        echo 'selected';
                    } ?> value="Rio Grande">Rio Grande
                                                </option>
                                                <option <?php if ($result->cidades == 'Rio Pardo') {
                        echo 'selected';
                    } ?> value="Rio Pardo">Rio Pardo
                                                </option>
                                                <option <?php if ($result->cidades == 'Riozinho') {
                        echo 'selected';
                    } ?> value="Riozinho">Riozinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Roca Sales') {
                        echo 'selected';
                    } ?> value="Roca Sales">Roca Sales
                                                </option>
                                                <option <?php if ($result->cidades == 'Rodeio Bonito') {
                        echo 'selected';
                    } ?> value="Rodeio Bonito">Rodeio Bonito
                                                </option>
                                                <option <?php if ($result->cidades == 'Rolador') {
                        echo 'selected';
                    } ?> value="Rolador">Rolador
                                                </option>
                                                <option <?php if ($result->cidades == 'Rolante') {
                        echo 'selected';
                    } ?> value="Rolante">Rolante
                                                </option>
                                                <option <?php if ($result->cidades == 'Ronda Alta') {
                        echo 'selected';
                    } ?> value="Ronda Alta">Ronda Alta
                                                </option>
                                                <option <?php if ($result->cidades == 'Rondinha') {
                        echo 'selected';
                    } ?> value="Rondinha">Rondinha
                                                </option>
                                                <option <?php if ($result->cidades == 'Roque Gonzales') {
                        echo 'selected';
                    } ?> value="Roque Gonzales">Roque Gonzales
                                                </option>
                                                <option <?php if ($result->cidades == 'Rosário do Sul') {
                        echo 'selected';
                    } ?> value="Rosário do Sul">Rosário do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Sagrada Família') {
                        echo 'selected';
                    } ?> value="Sagrada Família">Sagrada Família
                                                </option>
                                                <option <?php if ($result->cidades == 'Saldanha Marinho') {
                        echo 'selected';
                    } ?> value="Saldanha Marinho">Saldanha Marinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Salto do Jacu') {
                        echo 'selected';
                    } ?> value="Salto do Jacu">Salto do Jacu
                                                </option>
                                                <option <?php if ($result->cidades == 'Salvador das Missões') {
                        echo 'selected';
                    } ?> value="Salvador das Missões">Salvador das Missões
                                                </option>
                                                <option <?php if ($result->cidades == 'Salvador do Sul') {
                        echo 'selected';
                    } ?> value="Salvador do Sul">Salvador do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Sanaduva') {
                        echo 'selected';
                    } ?> value="Sanaduva">Sanaduva
                                                </option>
                                                <option <?php if ($result->cidades == 'Santa Bárbara do Sul') {
                        echo 'selected';
                    } ?> value="Santa Bárbara do Sul">Santa Bárbara do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Santa Cecília do Sul') {
                        echo 'selected';
                    } ?> value="Santa Cecília do Sul">Santa Cecília do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Santa Clara do Sul') {
                        echo 'selected';
                    } ?> value="Santa Clara do Sul">Santa Clara do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Santa Maria') {
                        echo 'selected';
                    } ?> value="Santa Maria">Santa Maria
                                                </option>
                                                <option <?php if ($result->cidades == 'Santa Maria do Herval') {
                        echo 'selected';
                    } ?> value="Santa Maria do Herval">Santa Maria do Herval
                                                </option>
                                                <option <?php if ($result->cidades == 'Santa Margarida do Sul') {
                        echo 'selected';
                    } ?> value="Santa Margarida do Sul">Santa Margarida do Sul
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santana da Boa Vista') {
                        echo 'selected';
                    } ?> value="Santana da Boa Vista">Santana da Boa Vista
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santana do Livramento') {
                        echo 'selected';
                    } ?> value="Santana do Livramento">Santana do Livramento
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santa Rosa') {
                        echo 'selected';
                    } ?> value="Santa Rosa">Santa Rosa
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santa Tereza') {
                        echo 'selected';
                    } ?> value="Santa Tereza">Santa Tereza
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santa Vitória do Palmar') {
                        echo 'selected';
                    } ?> value="Santa Vitória do Palmar">Santa Vitória do Palmar
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santiago') {
                        echo 'selected';
                    } ?> value="Santiago">Santiago
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Angelo') {
                        echo 'selected';
                    } ?> value="Santo Angelo">Santo Angelo
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Antônio do Palma') {
                        echo 'selected';
                    } ?> value="Santo Antônio do Palma">Santo Antônio do Palma
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Antônio da Patrulha') {
                        echo 'selected';
                    } ?> value="Santo Antônio da Patrulha">Santo Antônio da Patrulha
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Antônio das Missões') {
                        echo 'selected';
                    } ?> value="Santo Antônio das Missões">Santo Antônio das Missões
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Antônio do Planalto') {
                        echo 'selected';
                    } ?> value="Santo Antônio do Planalto">Santo Antônio do Planalto
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Augusto') {
                        echo 'selected';
                    } ?> value="Santo Augusto">Santo Augusto
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Cristo') {
                        echo 'selected';
                    } ?> value="Santo Cristo">Santo Cristo
                                                </option>
                                                 <option <?php if ($result->cidades == 'Santo Expedito do Sul') {
                        echo 'selected';
                    } ?> value="Santo Expedito do Sul">Santo Expedito do Sul
                                                </option>
                                                 <option <?php if ($result->cidades == 'São Borja') {
                        echo 'selected';
                    } ?> value="São Borja">São Borja
                                                </option>
                                                 <option <?php if ($result->cidades == 'São Domingos do Sul') {
                        echo 'selected';
                    } ?> value="São Domingos do Sul">São Domingos do Sul
                                                </option>
                                                 <option <?php if ($result->cidades == 'São Francisco de Assis') {
                        echo 'selected';
                    } ?> value="São Francisco de Assis">São Francisco de Assis
                                                </option>
                                                 <option <?php if ($result->cidades == 'São Francisco de Paula') {
                        echo 'selected';
                    } ?> value="São Francisco de Paula">São Francisco de Paula
                                                </option> 
                                                <option <?php if ($result->cidades == 'São Gabriel') {
                        echo 'selected';
                    } ?> value="São Gabriel">São Gabriel
                                                </option>
                                                 <option <?php if ($result->cidades == 'São Jerônimo') {
                        echo 'selected';
                    } ?> value="São Jerônimo">São Jerônimo
                                                </option>
                                                 <option <?php if ($result->cidades == 'São João da Urtiga') {
                        echo 'selected';
                    } ?> value="São João da Urtiga">São João da Urtiga
                                                </option>
                                                 <option <?php if ($result->cidades == 'São João do Polêsine') {
                        echo 'selected';
                    } ?> value="São João do Polêsine">São João do Polêsine
                                                </option>
                                                 <option <?php if ($result->cidades == 'São Jorge') {
                        echo 'selected';
                    } ?> value="São Jorge">São Jorge
                                                </option> 
                                                <option <?php if ($result->cidades == 'São José das Missões') {
                        echo 'selected';
                    } ?> value="São José das Missões">São José das Missões
                                                </option> 
                                                <option <?php if ($result->cidades == 'São José do Hortêncio') {
                        echo 'selected';
                    } ?> value="São José do Hortêncio">São José do Hortêncio
                                                </option>
                                                <option <?php if ($result->cidades == 'São José do Inhacorá') {
                        echo 'selected';
                    } ?> value="São José do Inhacorá">São José do Inhacorá
                                                </option>
                                                <option <?php if ($result->cidades == 'São José do Norte') {
                        echo 'selected';
                    } ?> value="São José do Norte">São José do Norte
                                                </option>
                                                <option <?php if ($result->cidades == 'São José do Ouro') {
                        echo 'selected';
                    } ?> value="São José do Ouro">São José do Ouro
                                                </option>
                                                <option <?php if ($result->cidades == 'São José do Sul') {
                        echo 'selected';
                    } ?> value="São José do Sul">São José do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'São José dos Ausentes') {
                        echo 'selected';
                    } ?> value="São José dos Ausentes">São José dos Ausentes
                                                </option>
                                                <option <?php if ($result->cidades == 'São Leopoldo') {
                        echo 'selected';
                    } ?> value="São Leopoldo">São Leopoldo
                                                </option>
                                                <option <?php if ($result->cidades == 'São Lourenço do Sul') {
                        echo 'selected';
                    } ?> value="São Lourenço do Sul">São Lourenço do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'São Luiz Gonzaga') {
                        echo 'selected';
                    } ?> value="São Luiz Gonzaga">São Luiz Gonzaga
                                                </option>
                                                <option <?php if ($result->cidades == 'São Marcos') {
                        echo 'selected';
                    } ?> value="São Marcos">São Marcos
                                                </option>
                                                <option <?php if ($result->cidades == 'São Martinho') {
                        echo 'selected';
                    } ?> value="São Martinho">São Martinho
                                                </option>
                                                <option <?php if ($result->cidades == 'São Martinho da Serra') {
                        echo 'selected';
                    } ?> value="São Martinho da Serra">São Martinho da Serra
                                                </option>
                                                <option <?php if ($result->cidades == 'São Miguel das Missões') {
                        echo 'selected';
                    } ?> value="São Miguel das Missões">São Miguel das Missões
                                                </option>
                                                <option <?php if ($result->cidades == 'São Nicolau') {
                        echo 'selected';
                    } ?> value="São Nicolau">São Nicolau
                                                </option>
                                                <option <?php if ($result->cidades == 'São Paulo das Missôes') {
                        echo 'selected';
                    } ?> value="São Paulo das Missôes">São Paulo das Missôes
                                                </option>
                                                <option <?php if ($result->cidades == 'São Pedro da Serra') {
                        echo 'selected';
                    } ?> value="São Pedro da Serra">São Pedro da Serra
                                                </option>
                                                <option <?php if ($result->cidades == 'São Pedro das Missões') {
                        echo 'selected';
                    } ?> value="São Pedro das Missões">São Pedro das Missões
                                                </option>
                                                <option <?php if ($result->cidades == 'São Pedro do Butiá') {
                        echo 'selected';
                    } ?> value="São Pedro do Butiá">São Pedro do Butiá
                                                </option>
                                                <option <?php if ($result->cidades == 'São Pedro do Sul') {
                        echo 'selected';
                    } ?> value="São Pedro do Sul">São Pedro do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'São Sebastião do Caí') {
                        echo 'selected';
                    } ?> value="São Sebastião do Caí">São Sebastião do Caí
                                                </option>
                                                <option <?php if ($result->cidades == 'São Sepé') {
                        echo 'selected';
                    } ?> value="São Sepé">São Sepé
                                                </option>
                                                <option <?php if ($result->cidades == 'São Valentim') {
                        echo 'selected';
                    } ?> value="São Valentim">São Valentim
                                                </option>
                                                <option <?php if ($result->cidades == 'São Valentim do Sul') {
                        echo 'selected';
                    } ?> value="São Valentim do Sul">São Valentim do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'São Valério do Sul') {
                        echo 'selected';
                    } ?> value="São Valério do Sul">São Valério do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'São Valério do Sul') {
                        echo 'selected';
                    } ?> value="São Valério do Sul">São Valério do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'São Vendelino') {
                        echo 'selected';
                    } ?> value="São Vendelino">São Vendelino
                                                </option>
                                                <option <?php if ($result->cidades == 'São Vicente do Sul') {
                        echo 'selected';
                    } ?> value="São Vicente do Sul">São Vicente do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Sapiranga') {
                        echo 'selected';
                    } ?> value="Sapiranga">Sapiranga
                                                </option>
                                                <option <?php if ($result->cidades == 'Sapucaia do Sul') {
                        echo 'selected';
                    } ?> value="Sapucaia do Sul">Sapucaia do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Sarandi') {
                        echo 'selected';
                    } ?> value="Sarandi">Sarandi
                                                </option>
                                                <option <?php if ($result->cidades == 'Seberi') {
                        echo 'selected';
                    } ?> value="Seberi">Seberi
                                                </option>
                                                <option <?php if ($result->cidades == 'Sede Nova') {
                        echo 'selected';
                    } ?> value="Sede Nova">Sede Nova
                                                </option>
                                                <option <?php if ($result->cidades == 'Segredo') {
                        echo 'selected';
                    } ?> value="Segredo">Segredo
                                                </option>
                                                <option <?php if ($result->cidades == 'Selbach') {
                        echo 'selected';
                    } ?> value="Selbach">Selbach
                                                </option>
                                                <option <?php if ($result->cidades == 'Senador Salgado Filho') {
                        echo 'selected';
                    } ?> value="Senador Salgado Filho">Senador Salgado Filho
                                                </option>
                                                <option <?php if ($result->cidades == 'Sentinela do Sul') {
                        echo 'selected';
                    } ?> value="Sentinela do Sul">Sentinela do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Serafina Corrêa') {
                        echo 'selected';
                    } ?> value="Serafina Corrêa">Serafina Corrêa
                                                </option>
                                                <option <?php if ($result->cidades == 'Sério') {
                        echo 'selected';
                    } ?> value="Sério">Sério
                                                </option>
                                                <option <?php if ($result->cidades == 'Sertão') {
                        echo 'selected';
                    } ?> value="Sertão">Sertão
                                                </option>
                                                <option <?php if ($result->cidades == 'Sertão Santana') {
                        echo 'selected';
                    } ?> value="Sertão Santana">Sertão Santana
                                                </option>
                                                <option <?php if ($result->cidades == 'Sete de Setembro') {
                        echo 'selected';
                    } ?> value="Sete de Setembro">Sete de Setembro
                                                </option>
                                                <option <?php if ($result->cidades == 'Severiano de Almeida') {
                        echo 'selected';
                    } ?> value="Severiano de Almeida">Severiano de Almeida
                                                </option>
                                                <option <?php if ($result->cidades == 'Silveira Martins') {
                        echo 'selected';
                    } ?> value="Silveira Martins">Silveira Martins
                                                </option>
                                                <option <?php if ($result->cidades == 'Sinimbu') {
                        echo 'selected';
                    } ?> value="Sinimbu">Sinimbu
                                                </option>
                                                <option <?php if ($result->cidades == 'Sobradinho') {
                        echo 'selected';
                    } ?> value="Sobradinho">Sobradinho
                                                </option>
                                                <option <?php if ($result->cidades == 'Soledade') {
                        echo 'selected';
                    } ?> value="Soledade">Soledade
                                                </option>
                                                <option <?php if ($result->cidades == 'Taba') {
                        echo 'selected';
                    } ?> value="Taba">Taba
                                                </option>
                                                <option <?php if ($result->cidades == 'Tapejara') {
                        echo 'selected';
                    } ?> value="Tapejara">Tapejara
                                                </option>
                                                <option <?php if ($result->cidades == 'Tapera') {
                        echo 'selected';
                    } ?> value="Tapera">Tapera
                                                </option>
                                                <option <?php if ($result->cidades == 'Tapes') {
                        echo 'selected';
                    } ?> value="Tapes">Tapes
                                                </option>
                                                <option <?php if ($result->cidades == 'Taquara') {
                        echo 'selected';
                    } ?> value="Taquara">Taquara
                                                </option>
                                                <option <?php if ($result->cidades == 'Taquari') {
                        echo 'selected';
                    } ?> value="Taquari">Taquari
                                                </option>
                                                <option <?php if ($result->cidades == 'Taquarucu do Sul') {
                        echo 'selected';
                    } ?> value="Taquarucu do Sul">Taquarucu do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Tavares') {
                        echo 'selected';
                    } ?> value="Tavares">Tavares
                                                </option>
                                                <option <?php if ($result->cidades == 'Tenente Portela') {
                        echo 'selected';
                    } ?> value="Tenente Portela">Tenente Portela
                                                </option>
                                                <option <?php if ($result->cidades == 'Terra de Areia') {
                        echo 'selected';
                    } ?> value="Terra de Areia">Terra de Areia
                                                </option>
                                                <option <?php if ($result->cidades == 'Teutonia') {
                        echo 'selected';
                    } ?> value="Teutonia">Teutonia
                                                </option>
                                                <option <?php if ($result->cidades == 'Tio Hugo') {
                        echo 'selected';
                    } ?> value="Tio Hugo">Tio Hugo
                                                </option>
                                                <option <?php if ($result->cidades == 'Tiradentes do Sul') {
                        echo 'selected';
                    } ?> value="Tiradentes do Sul">Tiradentes do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Toropi') {
                        echo 'selected';
                    } ?> value="Toropi">Toropi
                                                </option>
                                                <option <?php if ($result->cidades == 'Torres') {
                        echo 'selected';
                    } ?> value="Torres">Torres
                                                </option>
                                                <option <?php if ($result->cidades == 'Tramandaí') {
                        echo 'selected';
                    } ?> value="Tramandaí">Tramandaí
                                                </option>
                                                <option <?php if ($result->cidades == 'Travesseiro') {
                        echo 'selected';
                    } ?> value="Travesseiro">Travesseiro
                                                </option>
                                                <option <?php if ($result->cidades == 'Três Arroios') {
                        echo 'selected';
                    } ?> value="Três Arroios">Três Arroios
                                                </option>
                                                <option <?php if ($result->cidades == 'Três Cachoeiras') {
                        echo 'selected';
                    } ?> value="Três Cachoeiras">Três Cachoeiras
                                                </option>
                                                <option <?php if ($result->cidades == 'Três Coroas') {
                        echo 'selected';
                    } ?> value="Três Coroas">Três Coroas
                                                </option>
                                                <option <?php if ($result->cidades == 'Três de Maio') {
                        echo 'selected';
                    } ?> value="Três de Maio">Três de Maio
                                                </option>
                                                <option <?php if ($result->cidades == 'Três Forquilhas') {
                        echo 'selected';
                    } ?> value="Três Forquilhas">Três Forquilhas
                                                </option>
                                                <option <?php if ($result->cidades == 'Três Palmeiras') {
                        echo 'selected';
                    } ?> value="Três Palmeiras">Três Palmeiras
                                                </option>
                                                <option <?php if ($result->cidades == 'Três Passos') {
                        echo 'selected';
                    } ?> value="Três Passos">Três Passos
                                                </option>
                                                <option <?php if ($result->cidades == 'Trindade do Sul') {
                        echo 'selected';
                    } ?> value="Trindade do Sul">Trindade do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Triunfo') {
                        echo 'selected';
                    } ?> value="Triunfo">Triunfo
                                                </option>
                                                <option <?php if ($result->cidades == 'Tucunduva') {
                        echo 'selected';
                    } ?> value="Tucunduva">Tucunduva
                                                </option>
                                                <option <?php if ($result->cidades == 'Tunas') {
                        echo 'selected';
                    } ?> value="Tunas">Tunas
                                                </option>
                                                <option <?php if ($result->cidades == 'Tupanci do Sul') {
                        echo 'selected';
                    } ?> value="Tupanci do Sul">Tupanci do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Tupancireta') {
                        echo 'selected';
                    } ?> value="Tupancireta">Tupancireta
                                                </option>
                                                <option <?php if ($result->cidades == 'Tupandi') {
                        echo 'selected';
                    } ?> value="Tupandi">Tupandi
                                                </option>
                                                <option <?php if ($result->cidades == 'Tuparendi') {
                        echo 'selected';
                    } ?> value="Tuparendi">Tuparendi
                                                </option>
                                                <option <?php if ($result->cidades == 'Turucu') {
                        echo 'selected';
                    } ?> value="Turucu">Turucu
                                                </option>
                                                <option <?php if ($result->cidades == 'Ubiterama') {
                        echo 'selected';
                    } ?> value="Ubiterama">Ubiterama
                                                </option>
                                                <option <?php if ($result->cidades == 'União da Serra') {
                        echo 'selected';
                    } ?> value="União da Serra">União da Serra
                                                </option>
                                                <option <?php if ($result->cidades == 'Unistalda') {
                        echo 'selected';
                    } ?> value="Unistalda">Unistalda
                                                </option>
                                                <option <?php if ($result->cidades == 'Uruguaiana') {
                        echo 'selected';
                    } ?> value="Uruguaiana">Uruguaiana
                                                </option>
                                                <option <?php if ($result->cidades == 'Vacaria') {
                        echo 'selected';
                    } ?> value="Vacaria">Vacaria
                                                </option>
                                                <option <?php if ($result->cidades == 'Vale Verde') {
                        echo 'selected';
                    } ?> value="Vale Verde">Vale Verde
                                                </option>
                                                <option <?php if ($result->cidades == 'Vale do Sol') {
                        echo 'selected';
                    } ?> value="Vale do Sol">Vale do Sol
                                                </option>
                                                <option <?php if ($result->cidades == 'Vale Real') {
                        echo 'selected';
                    } ?> value="Vale Real">Vale Real
                                                </option>
                                                <option <?php if ($result->cidades == 'Vanini') {
                        echo 'selected';
                    } ?> value="Vanini">Vanini
                                                </option>
                                                <option <?php if ($result->cidades == 'Vanâncio Aires') {
                        echo 'selected';
                    } ?> value="Vanâncio Aires">Vanâncio Aires
                                                </option>
                                                <option <?php if ($result->cidades == 'Vera Cruz') {
                        echo 'selected';
                    } ?> value="Vera Cruz">Vera Cruz
                                                </option>
                                                <option <?php if ($result->cidades == 'Veranopolis') {
                        echo 'selected';
                    } ?> value="Veranopolis">Veranopolis
                                                </option>
                                                <option <?php if ($result->cidades == 'Vespasiano Corrêa') {
                        echo 'selected';
                    } ?> value="Vespasiano Corrêa">Vespasiano Corrêa
                                                </option>
                                                <option <?php if ($result->cidades == 'Viadutos') {
                        echo 'selected';
                    } ?> value="Viadutos">Viadutos
                                                </option>
                                                <option <?php if ($result->cidades == 'Viamão') {
                        echo 'selected';
                    } ?> value="Viamão">Viamão
                                                </option>
                                                <option <?php if ($result->cidades == 'Vicente Dutra') {
                        echo 'selected';
                    } ?> value="Vicente Dutra">Vicente Dutra
                                                </option>
                                                <option <?php if ($result->cidades == 'Victor Graeff') {
                        echo 'selected';
                    } ?> value="Victor Graeff">Victor Graeff
                                                </option>
                                                <option <?php if ($result->cidades == 'Vila Flores') {
                        echo 'selected';
                    } ?> value="Vila Flores">Vila Flores
                                                </option>
                                                <option <?php if ($result->cidades == 'Vila Langaro') {
                        echo 'selected';
                    } ?> value="Vila Langaro">Vila Langaro
                                                </option>
                                                <option <?php if ($result->cidades == 'Vila Maria') {
                        echo 'selected';
                    } ?> value="Vila Maria">Vila Maria
                                                </option>
                                                <option <?php if ($result->cidades == 'Vila Nova do Sul') {
                        echo 'selected';
                    } ?> value="Vila Nova do Sul">Vila Nova do Sul
                                                </option>
                                                <option <?php if ($result->cidades == 'Vista Algre') {
                        echo 'selected';
                    } ?> value="Vista Algre">Vista Algre
                                                </option>
                                                <option <?php if ($result->cidades == 'Vista Alegre do Prata') {
                        echo 'selected';
                    } ?> value="Vista Alegre do Prata">Vista Alegre do Prata
                                                </option>
                                                <option <?php if ($result->cidades == 'Vista Gaúcha') {
                        echo 'selected';
                    } ?> value="Vista Gaúcha">Vista Gaúcha
                                                </option>
                                                <option <?php if ($result->cidades == 'Vitória das Missôes') {
                        echo 'selected';
                    } ?> value="Vitória das Missôes">Vitória das Missôes
                                                </option>
                                                <option <?php if ($result->cidades == 'Westfalia') {
                        echo 'selected';
                    } ?> value="Westfalia">Westfalia
                                                </option>
                                                <option <?php if ($result->cidades == 'Xangri-lá') {
                        echo 'selected';
                    } ?> value="Xangri-lá">Xangri-lá
                                                </option>
                                               </select>
                                    </div>
                                    </div>
                                      <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span3">
                                            <label for="dataInicial">Data Inicial<span class="required">*</span></label>
                                            <input id="dataInicial" autocomplete="off" class="span12 datepicker" type="text" name="dataInicial" value="<?php echo date('d/m/Y', strtotime($result->dataInicial)); ?>" />
                                        </div>
                                        <div class="span3">
                                            <label for="dataFinal">Data Final<span class="required">*</span></label>
                                            <input id="dataFinal" autocomplete="off" class="span12 datepicker" type="text" name="dataFinal" value="<?php echo date('d/m/Y', strtotime($result->dataFinal)); ?>" />
                                        </div>
                                        <div class="span3">
                                            <label for="garantia">Garantia (dias)</label>
                                            <input id="garantia" type="number" min="0" max="9999" class="span12" name="garantia" value="<?php echo $result->garantia ?>" />
                                            <?php echo form_error('garantia'); ?>
                                        </div>
                                         <div class="span3">
                                            <label for="garantiaTipo">Garantia<span></span></label>
                                            <select class="span12" name="garantiaTipo" id="garantiaTipo" value="">
                                                <option <?php if ($result->garantiaTipo == 'Nenhum') {
                        echo 'selected';
                    } ?> value="Nenhum">        
                                                </option>
                                                <option <?php if ($result->garantiaTipo == 'Serviço') {
                        echo 'selected';
                    } ?> value="Serviço">Serviço
                                                </option>
                                                <option <?php if ($result->garantiaTipo == 'Fábrica') {
                        echo 'selected';
                    } ?> value="Fábrica">Fábrica
                                                </option>
                                                <option <?php if ($result->garantiaTipo == 'Seguradora') {
                        echo 'selected';
                    } ?> value="Seguradora">Seguradora
                                                </option>
                                            </select>
                                    </div>
                                    </div>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                       <div class="span3">
                                        <label for="descricaoProduto">
                                            <h4>Descrição</h4>
                                        </label>
                                        <textarea class="span12" name="descricaoProduto" id="descricaoProduto" rows="1"><?php echo $result->descricaoProduto ?></textarea>
                                       </div>
                                    
                                     <div class="span3">
                                         <label for="marcaProduto">
                                            <h4>Marca</h4>
                                        </label>
                                        <textarea class="span12" name="marcaProduto" id="marcaProduto" rows="1"><?php echo $result->marcaProduto ?></textarea>
                                    </div>
                                    
                                    <div class="span3">
                                         <label for="modeloProduto">
                                            <h4>Modelo</h4>
                                        </label>
                                        <textarea class="span12" name="modeloProduto" id="modeloProduto" rows="1"><?php echo $result->modeloProduto ?></textarea>
                                    </div>
                                    
                                     <div class="span3">
                                         <label for="serieProduto">
                                            <h4>Número de Série</h4>
                                        </label>
                                        <textarea class="span12" name="serieProduto" id="serieProduto" rows="1"><?php echo $result->serieProduto ?></textarea>
                                    </div>
                                    </div>
                                    
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                     <div class="span3">
                                         <label for="codfabProduto">
                                            <h4>Cod. Fab.</h4>
                                        </label>
                                        <textarea class="span12" name="codfabProduto" id="codfabProduto" rows="1"><?php echo $result->codfabProduto ?></textarea>
                                    </div>
                                    <div class="span3">
                                         <label for="corProduto">
                                            <h4>Cor</h4>
                                        </label>
                                        <textarea class="span12" name="corProduto" id="corProduto" rows="1"><?php echo $result->corProduto ?></textarea>
                                    </div> 
                                    </div>
                                    
                                    
                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="defeito">
                                            <h4>Defeito</h4>
                                        </label>
                                        <textarea class="span12" name="defeito" id="defeito" cols="30" rows="5"><?php echo $result->defeito ?></textarea>
                                    </div>
                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="observacoes">
                                            <h4>Observações</h4>
                                        </label>
                                        <textarea class="span12" name="observacoes" id="observacoes" cols="30" rows="5"><?php echo $result->observacoes ?></textarea>
                                    </div>
                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="laudoTecnico">
                                            <h4>Laudo Técnico</h4>
                                        </label>
                                        <textarea class="span12" name="laudoTecnico" id="laudoTecnico" cols="30" rows="5"><?php echo $result->laudoTecnico ?></textarea>
                                    </div>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span6 offset3" style="text-align: center">
                                            <?php if ($result->finalizado == 0) { ?>
                                                <a href="#modal-faturar" id="btn-faturar" role="button" data-toggle="modal" class="btn btn-danger"><i class="fas fa-cash-register"></i> Faturar</a>
                                            <?php
                                            } ?>
                                            <button class="btn btn-primary" id="btnContinuar"><i class="fas fa-sync-alt"></i> Atualizar
                                            </button>
                                            <?php if ($result->garantias_id) { ?> <a target="_blank" title="Imprimir Termo de Garantia" class="btn btn-inverse" href="<?php echo site_url() ?>/garantias/imprimir/<?php echo $result->garantias_id; ?>"><i class="fas fa-text-width"></i> Imprimir Termo de
                                                    Garantia</a> <?php } ?>
                                            <a href="<?php echo base_url() ?>index.php/os" class="btn"><i class="fas fa-backward"></i> Voltar</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--Produtos-->
                        <div class="tab-pane" id="tab2">
                            <div class="span12 well" style="padding: 1%; margin-left: 0">
                                <form id="formProdutos" action="<?php echo base_url() ?>index.php/os/adicionarProduto" method="post">
                                    <div class="span6">
                                        <input type="hidden" name="idProduto" id="idProduto" />
                                        <input type="hidden" name="idOsProduto" id="idOsProduto" value="<?php echo $result->idOs; ?>" />
                                        <input type="hidden" name="estoque" id="estoque" value="" />
                                        <label for="">Produto</label>
                                        <input type="text" class="span12" name="produto" id="produto" placeholder="Digite o nome do produto" />
                                    </div>
                                    <div class="span2">
                                        <label for="">Preço</label>
                                        <input type="text" placeholder="Preço" id="preco" name="preco" class="span12 money" data-affixes-stay="true" data-thousands="" data-decimal="." />
                                    </div>
                                    <div class="span2">
                                        <label for="">Quantidade</label>
                                        <input type="text" placeholder="Quantidade" id="quantidade" name="quantidade" class="span12" />
                                    </div>
                                    <div class="span2">
                                        <label for="">&nbsp;</label>
                                        <button class="btn btn-success span12" id="btnAdicionarProduto"><i class="fas fa-plus"></i> Adicionar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="widget-box" id="divProdutos">
                                <div class="widget_content nopadding">
                                    <table width="100%" class="table table-bordered" id="tblProdutos">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th width="8%">Quantidade</th>
                                                <th width="10%">Preço unit.</th>
                                                <th width="6%">Ações</th>
                                                <th width="10%">Sub-total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            foreach ($produtos as $p) {
                                                $total = $total + $p->subTotal;
                                                echo '<tr>';
                                                echo '<td>' . $p->descricao . '</td>';
                                                echo '<td><div align="center">' . $p->quantidade . '</td>';
                                                echo '<td><div align="center">R$: ' . ($p->preco ?: $p->precoVenda)  . '</td>';
                                                echo '<td><div align="center"><a href="" idAcao="' . $p->idProdutos_os . '" prodAcao="' . $p->idProdutos . '" quantAcao="' . $p->quantidade . '" title="Excluir Produto" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>';
                                                echo '<td><div align="center">R$: ' . number_format($p->subTotal, 2, ',', '.') . '</td>';
                                                echo '</tr>';
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align: right"><strong>Total:</strong></td>
                                                <td>
                                                    <div align="center"><strong>R$ <?php echo number_format($total, 2, ',', '.'); ?><input type="hidden" id="total-venda" value="<?php echo number_format($total, 2); ?>"></strong></div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!--Serviços-->
                        <div class="tab-pane" id="tab3">
                            <div class="span12 well" style="padding: 1%; margin-left: 0">
                                <form id="formServicos" action="<?php echo base_url() ?>index.php/os/adicionarServico" method="post">
                                    <div class="span6">
                                        <input type="hidden" name="idServico" id="idServico" />
                                        <input type="hidden" name="idOsServico" id="idOsServico" value="<?php echo $result->idOs; ?>" />
                                        <label for="">Serviço</label>
                                        <input type="text" class="span12" name="servico" id="servico" placeholder="Digite o nome do serviço" />
                                    </div>
                                    <div class="span2">
                                        <label for="">Preço</label>
                                        <input type="text" placeholder="Preço" id="preco_servico" name="preco" class="span12 money" data-affixes-stay="true" data-thousands="" data-decimal="." />
                                    </div>
                                    <div class="span2">
                                        <label for="">Quantidade</label>
                                        <input type="text" placeholder="Quantidade" id="quantidade_servico" name="quantidade" class="span12" />
                                    </div>
                                    <div class="span2">
                                        <label for="">&nbsp;</label>
                                        <button class="btn btn-success span12"><i class="fas fa-plus"></i> Adicionar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="widget-box" id="divServicos">
                                <div class="widget_content nopadding">
                                    <table width="100%" class="table table-bordered" id="tblServicos">
                                        <thead>
                                            <tr>
                                                <th>Serviço</th>
                                                <th width="8%">Quantidade</th>
                                                <th width="10%">Preço</th>
                                                <th width="6%">Ações</th>
                                                <th width="10%">Sub-totals</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totals = 0;
                                            foreach ($servicos as $s) {
                                                $preco = $s->preco ?: $s->precoVenda;
                                                $subtotals = $preco * ($s->quantidade ?: 1);
                                                $totals = $totals + $subtotals;
                                                echo '<tr>';
                                                echo '<td>' . $s->nome . '</td>';
                                                echo '<td><div align="center">' . ($s->quantidade ?: 1) . '</div></td>';
                                                echo '<td><div align="center">R$ ' . $preco  . '</div></td>';
                                                echo '<td><div align="center"><span idAcao="' . $s->idServicos_os . '" title="Excluir Serviço" class="btn btn-danger servico"><i class="fas fa-trash-alt"></i></span></div></td>';
                                                echo '<td><div align="center">R$: ' . number_format($subtotals, 2, ',', '.') . '</div></td>';
                                                echo '</tr>';
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align: right"><strong>Total:</strong></td>
                                                <td>
                                                    <div align="center"><strong>R$ <?php echo number_format($totals, 2, ',', '.'); ?><input type="hidden" id="total-servico" value="<?php echo number_format($totals, 2); ?>"></strong></div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!--Anexos-->
                        <div class="tab-pane" id="tab4">
                            <div class="span12" style="padding: 1%; margin-left: 0">
                                <div class="span12 well" style="padding: 1%; margin-left: 0" id="form-anexos">
                                    <form id="formAnexos" enctype="multipart/form-data" action="javascript:;" accept-charset="utf-8" s method="post">
                                        <div class="span10">
                                            <input type="hidden" name="idOsServico" id="idOsServico" value="<?php echo $result->idOs; ?>" />
                                            <label for="">Anexo</label>
                                            <input type="file" class="span12" name="userfile[]" multiple="multiple" size="20" />
                                        </div>
                                        <div class="span2">
                                            <label for="">.</label>
                                            <button class="btn btn-success span12"><i class="fas fa-paperclip"></i>
                                                Anexar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="span12 pull-left" id="divAnexos" style="margin-left: 0">
                                    <?php
                                    foreach ($anexos as $a) {
                                        if ($a->thumb == null) {
                                            $thumb = base_url() . 'assets/img/icon-file.png';
                                            $link = base_url() . 'assets/img/icon-file.png';
                                        } else {
                                            $thumb = $a->url . '/thumbs/' . $a->thumb;
                                            $link = $a->url . '/' . $a->anexo;
                                        }
                                        echo '<div class="span3" style="min-height: 150px; margin-left: 0">
                                                    <a style="min-height: 150px;" href="#modal-anexo" imagem="' . $a->idAnexos . '" link="' . $link . '" role="button" class="btn anexo span12" data-toggle="modal">
                                                        <img src="' . $thumb . '" alt="">
                                                    </a>
                                                    <span>' . $a->anexo . '</span>
                                                </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!--Anotações-->
                        <div class="tab-pane" id="tab5">
                            <div class="span12" style="padding: 1%; margin-left: 0">

                                <div class="span12" id="divAnotacoes" style="margin-left: 0">

                                    <a href="#modal-anotacao" id="btn-anotacao" role="button" data-toggle="modal" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar anotação</a>
                                    <hr>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Anotação</th>
                                                <th>Data/Hora</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($anotacoes as $a) {
                                                echo '<tr>';
                                                echo '<td>' . $a->anotacao . '</td>';
                                                echo '<td>' . date('d/m/Y H:i:s', strtotime($a->data_hora)) . '</td>';
                                                echo '<td><span idAcao="' . $a->idAnotacoes . '" title="Excluir Anotação" class="btn btn-danger anotacao"><i class="fas fa-trash-alt"></i></span></td>';
                                                echo '</tr>';
                                            }
                                            if (!$anotacoes) {
                                                echo '<tr><td colspan="2">Nenhuma anotação cadastrada</td></tr>';
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- Fim tab anotações -->
                    </div>
                </div>
                &nbsp
            </div>
        </div>
    </div>
</div>

<!-- Modal visualizar anexo -->
<div id="modal-anexo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Visualizar Anexo</h3>
    </div>
    <div class="modal-body">
        <div class="span12" id="div-visualizar-anexo" style="text-align: center">
            <div class='progress progress-info progress-striped active'>
                <div class='bar' style='width: 100%'></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <a href="" id-imagem="" class="btn btn-inverse" id="download">Download</a>
        <a href="" link="" class="btn btn-danger" id="excluir-anexo">Excluir Anexo</a>
    </div>
</div>

<!-- Modal cadastro anotações -->
<div id="modal-anotacao" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="#" method="POST" id="formAnotacao">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Adicionar Anotação</h3>
        </div>
        <div class="modal-body">
            <div class="span12" id="divFormAnotacoes" style="margin-left: 0"></div>
            <div class="span12" style="margin-left: 0">
                <label for="anotacao">Anotação</label>
                <textarea class="span12" name="anotacao" id="anotacao" cols="30" rows="3"></textarea>
                <input type="hidden" name="os_id" value="<?php echo $result->idOs; ?>">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true" id="btn-close-anotacao">Fechar</button>
            <button class="btn btn-primary">Adicionar</button>
        </div>
    </form>
</div>

<!-- Modal Faturar-->
<div id="modal-faturar" class="modal hide fade widget_box_vizualizar4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formFaturar" action="<?php echo current_url() ?>" method="post">
        <div class="modal_header_anexos">
            <button type="button" class="close" style="color:#f00" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Faturar OS</h3>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
            <div class="span12" style="margin-left: 0">
                <label for="descricao">Descrição</label>
                <input class="span12" id="descricao" type="text" name="descricao" value="Fatura de OS Nº: <?php echo $result->idOs; ?> " />
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span12" style="margin-left: 0">
                    <label for="cliente">Cliente*</label>
                    <input class="span12" id="cliente" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                    <input type="hidden" name="clientes_id" id="clientes_id" value="<?php echo $result->clientes_id ?>">
                    <input type="hidden" name="os_id" id="os_id" value="<?php echo $result->idOs; ?>">
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span5" style="margin-left: 0">
                    <label for="valor">Valor*</label>
                    <input type="hidden" id="tipo" name="tipo" value="receita" />
                    <input class="span12 money" id="valor" type="text" data-affixes-stay="true" data-thousands="" data-decimal="." name="valor" value="<?php echo number_format($totals + $total, 2, '.', ''); ?>" />
                    <strong><span style="color: red" id="resultado"></span></strong>
                </div>
                <div class="span4">
                    <label>Desconto</label>
                    <input style="width: 4em;" id="num2" type="text" placeholder="%" onblur="calcular()" maxlength="3" size="2" />
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="vencimento">Data Entrada*</label>
                    <input class="span12 datepicker" autocomplete="on" id="vencimento" type="text" name="vencimento" />
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="recebido">Recebido?</label>
                    &nbsp &nbsp &nbsp &nbsp <input id="recebido" type="checkbox" name="recebido" value="1" />
                </div>
                <div id="divRecebimento" class="span8" style=" display: none">
                    <div class="span6">
                        <label for="recebimento">Data Recebimento</label>
                        <input class="span12 datepicker" autocomplete="on" id="recebimento" type="text" name="recebimento" />
                    </div>
                    <div class="span6">
                        <label for="formaPgto">Forma Pgto</label>
                        <select name="formaPgto" id="formaPgto" class="span12">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Débito">Débito</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Pix">Pix</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true" id="btn-cancelar-faturar">Cancelar</button>
            <button class="btn btn-primary">Faturar</button>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>

<script type="text/javascript">
    function calcular() {
        var desconto = Number(document.getElementById("valor").value);
        var num2 = Number(document.getElementById("num2").value);
        var elemResult = document.getElementById("resultado");

        if (elemResult.textContent === undefined) {
            elemResult.textContent = "Preço com Desconto: R$ " + String(desconto - num2 * desconto / 100) + ".	";
        } else { // IE
            elemResult.innerText = "(Preço com Desconto: R$ " + String(desconto - num2 * desconto / 100) + ")";
        }
    }

    $(document).ready(function() {

        $(".money").maskMoney();

        $('#recebido').click(function(event) {
            var flag = $(this).is(':checked');
            if (flag == true) {
                $('#divRecebimento').show();
            } else {
                $('#divRecebimento').hide();
            }
        });

        $("#formFaturar").validate({
            rules: {
                descricao: {
                    required: true
                },
                cliente: {
                    required: true
                },
                valor: {
                    required: true
                },
                vencimento: {
                    required: true
                }

            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                cliente: {
                    required: 'Campo Requerido.'
                },
                valor: {
                    required: 'Campo Requerido.'
                },
                vencimento: {
                    required: 'Campo Requerido.'
                }
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();
                var qtdProdutos = $('#tblProdutos >tbody >tr').length;
                var qtdServicos = $('#tblServicos >tbody >tr').length;
                var qtdTotalProdutosServicos = qtdProdutos + qtdServicos;

                $('#btn-cancelar-faturar').trigger('click');

                if (qtdTotalProdutosServicos <= 0) {
                    Swal.fire({
                        type: "error",
                        title: "Atenção",
                        text: "Não é possível faturar uma OS sem serviços e/ou produtos"
                    });
                } else if (qtdTotalProdutosServicos > 0) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/os/faturar",
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            if (data.result == true) {
                                window.location.reload(true);
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    text: "Ocorreu um erro ao tentar faturar OS."
                                });
                                $('#progress-fatura').hide();
                            }
                        }
                    });

                    return false;
                }
            }
        });

        $("#formwhatsapp").validate({
            rules: {
                descricao: {
                    required: true
                },
                cliente: {
                    required: true
                },
                valor: {
                    required: true
                },
                vencimento: {
                    required: true
                }

            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                cliente: {
                    required: 'Campo Requerido.'
                },
                valor: {
                    required: 'Campo Requerido.'
                },
                vencimento: {
                    required: 'Campo Requerido.'
                }
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();
                $('#btn-cancelar-faturar').trigger('click');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/faturar",
                    data: dados,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {

                            window.location.reload(true);
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar faturar OS."
                            });
                            $('#progress-fatura').hide();
                        }
                    }
                });

                return false;
            }
        });

        $("#produto").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteProduto",
            minLength: 2,
            select: function(event, ui) {
                $("#codDeBarra").val(ui.item.codbar);
                $("#idProduto").val(ui.item.id);
                $("#estoque").val(ui.item.estoque);
                $("#preco").val(ui.item.preco);
                $("#quantidade").focus();
            }
        });

        $("#servico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteServico",
            minLength: 2,
            select: function(event, ui) {
                $("#idServico").val(ui.item.id);
                $("#preco_servico").val(ui.item.preco);
                $("#quantidade_servico").focus();
            }
        });

        $("#cliente").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
            minLength: 2,
            select: function(event, ui) {
                $("#clientes_id").val(ui.item.id);
            }
        });

        $("#tecnico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteUsuario",
            minLength: 2,
            select: function(event, ui) {
                $("#usuarios_id").val(ui.item.id);
            }
        });
        
        $("#termoGarantia").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteTermoGarantia",
            minLength: 1,
            select: function(event, ui) {
                if (ui.item.id) {
                    $("#garantias_id").val(ui.item.id);
                }
            }
        });

        $('#termoGarantia').on('change', function() {
            if (!$(this).val() && $("#garantias_id").val()) {
                $("#garantias_id").val('');
                Swal.fire({
                    type: "success",
                    title: "Sucesso",
                    text: "Termo de garantia removido"
                });
            }
        });

        $("#formOs").validate({
            rules: {
                cliente: {
                    required: true
                },
                tecnico: {
                    required: true
                },
                dataInicial: {
                    required: true
                }
            },
            messages: {
                cliente: {
                    required: 'Campo Requerido.'
                },
                tecnico: {
                    required: 'Campo Requerido.'
                },
                dataInicial: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });

        $("#formProdutos").validate({
            rules: {
                quantidade: {
                    required: true
                }
            },
            messages: {
                quantidade: {
                    required: 'Insira a quantidade'
                }
            },
            submitHandler: function(form) {
                var quantidade = parseInt($("#quantidade").val());
                var estoque = parseInt($("#estoque").val());

                <?php if (!$configuration['control_estoque']) {
                                                echo 'estoque = 1000000';
                                            }; ?>

                if (estoque < quantidade) {
                    Swal.fire({
                        type: "error",
                        title: "Atenção",
                        text: "Você não possui estoque suficiente."
                    });
                } else {
                    var dados = $(form).serialize();
                    $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/os/adicionarProduto",
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            if (data.result == true) {
                                $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                                $("#quantidade").val('');
                                $("#preco").val('');
                                $("#produto").val('').focus();
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    text: "Ocorreu um erro ao tentar adicionar produto."
                                });
                            }
                        }
                    });
                    return false;
                }
            }
        });

        $("#formServicos").validate({
            rules: {
                servico: {
                    required: true
                },
                preco: {
                    required: true
                },
                quantidade: {
                    required: true
                },
            },
            messages: {
                servico: {
                    required: 'Insira um serviço'
                },
                preco: {
                    required: 'Insira o preço'
                },
                quantidade: {
                    required: 'Insira a quantidade'
                },
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();

                $("#divServicos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/adicionarServico",
                    data: dados,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divServicos").load("<?php echo current_url(); ?> #divServicos");
                            $("#quantidade_servico").val('');
                            $("#preco_servico").val('');
                            $("#servico").val('').focus();
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar adicionar serviço."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $("#formAnotacao").validate({
            rules: {
                anotacao: {
                    required: true
                }
            },
            messages: {
                anotacao: {
                    required: 'Insira a anotação'
                }
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();
                $("#divFormAnotacoes").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/adicionarAnotacao",
                    data: dados,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divAnotacoes").load("<?php echo current_url(); ?> #divAnotacoes");
                            $("#anotacao").val('');
                            $('#btn-close-anotacao').trigger('click');
                            $("#divFormAnotacoes").html('');
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar adicionar anotação."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $("#formAnexos").validate({
            submitHandler: function(form) {
                //var dados = $( form ).serialize();
                var dados = new FormData(form);
                $("#form-anexos").hide('1000');
                $("#divAnexos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/anexar",
                    data: dados,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divAnexos").load("<?php echo current_url(); ?> #divAnexos");
                            $("#userfile").val('');

                        } else {
                            $("#divAnexos").html('<div class="alert fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Atenção!</strong> ' + data.mensagem + '</div>');
                        }
                    },
                    error: function() {
                        $("#divAnexos").html('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Atenção!</strong> Ocorreu um erro. Verifique se você anexou o(s) arquivo(s).</div>');
                    }
                });
                $("#form-anexos").show('1000');
                return false;
            }
        });

        $(document).on('click', 'a', function(event) {
            var idProduto = $(this).attr('idAcao');
            var quantidade = $(this).attr('quantAcao');
            var produto = $(this).attr('prodAcao');
            var idOS = "<?php echo $result->idOs ?>"
            if ((idProduto % 1) == 0) {
                $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/excluirProduto",
                    data: "idProduto=" + idProduto + "&quantidade=" + quantidade + "&produto=" + produto + "&idOs=" + idOS,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");

                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir produto."
                            });
                        }
                    }
                });
                return false;
            }

        });

        $(document).on('click', '.servico', function(event) {
            var idServico = $(this).attr('idAcao');
            var idOS = "<?php echo $result->idOs ?>"
            if ((idServico % 1) == 0) {
                $("#divServicos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/excluirServico",
                    data: "idServico=" + idServico + "&idOs=" + idOS,
                    data: "idServico=" + idServico,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divServicos").load("<?php echo current_url(); ?> #divServicos");

                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir serviço."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $(document).on('click', '.anexo', function(event) {
            event.preventDefault();
            var link = $(this).attr('link');
            var id = $(this).attr('imagem');
            var url = '<?php echo base_url(); ?>index.php/os/excluirAnexo/';
            $("#div-visualizar-anexo").html('<img src="' + link + '" alt="">');
            $("#excluir-anexo").attr('link', url + id);

            $("#download").attr('href', "<?php echo base_url(); ?>index.php/os/downloadanexo/" + id);

        });

        $(document).on('click', '#excluir-anexo', function(event) {
            event.preventDefault();
            var link = $(this).attr('link');
            var idOS = "<?php echo $result->idOs ?>"
            $('#modal-anexo').modal('hide');
            $("#divAnexos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");

            $.ajax({
                type: "POST",
                url: link,
                dataType: 'json',
                data: "idOs=" + idOS,
                success: function(data) {
                    if (data.result == true) {
                        $("#divAnexos").load("<?php echo current_url(); ?> #divAnexos");
                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Atenção",
                            text: data.mensagem
                        });
                    }
                }
            });
        });

        $(document).on('click', '.anotacao', function(event) {
            var idAnotacao = $(this).attr('idAcao');
            var idOS = "<?php echo $result->idOs ?>"
            if ((idAnotacao % 1) == 0) {
                $("#divAnotacoes").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/excluirAnotacao",
                    data: "idAnotacao=" + idAnotacao + "&idOs=" + idOS,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divAnotacoes").load("<?php echo current_url(); ?> #divAnotacoes");

                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir Anotação."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });

        $('.editor').trumbowyg({
            lang: 'pt_br'
        });
    });
</script>
