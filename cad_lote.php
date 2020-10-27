<?php include_once("validar_sessao.php"); ?>

<!DOCTYPE html>

<html>

<head>
	<title>TCC</title>
	<meta charset="utf-8" />
	<link rel='stylesheet' href="./css/menu.css">	
	<link rel='stylesheet' href="./css/formularios.css">	
	
</head>

<body>
	
	<?php 
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php"); 
	    
	    $mensagem = "";
	    $tabela = "";
	   
	    /*
	     ==> Declarar uma variável para cada campo da tabela
	    */
	    $id_lote       = "";
	    $num_lote      = "";
	    $id_fornecedor = "";
	    $data_chegada  = ""; 
	    $quant_chegada = "";
	    $peso_chegada  = "";
	    $data_saida    = "0";
	    $quant_saida   = "0";
	    $peso_saida    = "0";
	    $situacao      = "";

	    $podeAlterar = "";
	    $sqlExtra = "";
	    

	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = $_POST["acao"];
			 
			 if ( strtoupper($acao) == "INCLUIR" || 
			      strtoupper($acao) == "SALVAR" ) {
	            /*
	               ==> Atribur os dados recebidos por POST às variáveis (exceto a chave primária)
	            */
	            $num_lote      = mysqli_real_escape_string($bd, $_POST["num_lote"]);					  
				$id_fornecedor = mysqli_real_escape_string($bd, $_POST["id_fornecedor"]);
				$data_chegada  = mysqli_real_escape_string($bd, $_POST["data_chegada"]);
				$quant_chegada = mysqli_real_escape_string($bd, $_POST["quant_chegada"]);
				$peso_chegada  = mysqli_real_escape_string($bd, $_POST["peso_chegada"]);
				$data_saida    = mysqli_real_escape_string($bd, $_POST["data_saida"]);
				$quant_saida   = mysqli_real_escape_string($bd, $_POST["quant_saida"]);
				$peso_saida    = mysqli_real_escape_string($bd, $_POST["peso_saida"]);
				$situacao      = mysqli_real_escape_string($bd, $_POST["situacao"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_lote = $_POST["id_lote"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into lote  
			                 (num_lote, id_fornecedor, data_chegada, quant_chegada, peso_chegada, data_saida, quant_saida, peso_saida, situacao)
			            values 
			                 ($num_lote, $id_fornecedor, '$data_chegada', $quant_chegada, $peso_chegada, '$data_saida', $quant_saida, $peso_saida, '$situacao')";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$num_lote' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_lote = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update lote  
				          set
				            num_lote      =  $num_lote,
				            id_fornecedor =  $id_fornecedor,
				            data_chegada  = '$data_chegada',
				            quant_chegada =  $quant_chegada,
				            peso_chegada  =  $peso_chegada,
				            data_saida    = '$data_saida',
				            quant_saida   =  $quant_saida,
				            peso_saida    =  $peso_saida,
				            situacao      = '$situacao'
				            
				          where
				            id_lote = $id_lote";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$num_lote' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from lote where id_lote = $id_lote";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from lote  where id_lote = $id_lote";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
			

					$id_lote       = $dados["id_lote"];
					$num_lote      = $dados["num_lote"];					
					$id_fornecedor = $dados["id_fornecedor"];
					$data_chegada  = $dados["data_chegada"];
					$quant_chegada = $dados["quant_chegada"];
					$peso_chegada  = $dados["peso_chegada"];
					$data_saida    = $dados["data_saida"];
					$quant_saida   = $dados["quant_saida"];
					$peso_saida    = $dados["peso_saida"];
					$situacao      = $dados["situacao"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		
		$sql_listar = "select lote.*, fornecedor.nome 
		               from lote, fornecedor
		               where lote.id_fornecedor = fornecedor.id_fornecedor
		               $sqlExtra
		               order by fornecedor.nome ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Número do lote</th><th>Fornecedor</th><th>Data de chegada</th><th>Quantidade Recebida</th><th>Peso de Chegada</th><th>Data de saída</th><th>Quantidade de saída</th><th>Peso de saída</th><th>Situação</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdLote         = $dados["id_lote"];
				$vNumLote        = $dados["num_lote"];
				$vIdFornecedor   = $dados["id_fornecedor"];
				$vNomeFornecedor = $dados["nome"];
				$vDataChegada    = $dados["data_chegada"];
				$vQuantChegada   = $dados["quant_chegada"];
				$vPesoChegada    = $dados["peso_chegada"];
				$vDataSaida      = $dados["data_saida"];
				$vQuantSaida     = $dados["quant_saida"];
				$vPesoSaida      = $dados["peso_saida"];
				$vSituacao       = $dados["situacao"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_lote' value='$vIdLote'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_lote' value='$vIdLote'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vNumLote</td><td>$vNomeFornecedor</td><td>$vDataChegada</td><td>$vQuantChegada</td><td>$vPesoChegada</td><td>$vDataSaida</td><td>$vQuantSaida</td><td>$vPesoSaida</td><td>$vSituacao</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}




        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */

		$situacaoVal    = array("A","E");
		$situacaoDescr  = array("Ativo", "Encerrado");
		$situacaoOpcoes = montaSelect($situacaoVal, $situacaoDescr, $situacao, false); 

		$sql_fornecedor = "select id_fornecedor, nome from fornecedor order by nome";
		$fornecedorOpcoes = montaSelectBD($bd, $sql_fornecedor, $id_fornecedor, false); 

	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de LOTES</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="num_lote" class="campo">Número do Lote: </label>
	    <input type="number" id="num_lote" name="num_lote" value="<?php echo $num_lote; ?>" <?php echo $podeAlterar; ?> > <br>

	    <label for="id_fornecedor" class="campo">Fornecedor: </label>
	    <select id="id_fornecedor" name="id_fornecedor">
	    <?php echo $fornecedorOpcoes; ?>
	    </select> <br>
	       
	    <label for="data_chegada" class="campo">Data de chegada: </label>
	    <input type="date" id="data_chegada" name="data_chegada" value="<?php echo $data_chegada; ?>" <?php echo $podeAlterar; ?> > <br>
	      
	    <label for="quant_chegada" class="campo">Quantidade de suínos recebidos: </label>
	    <input type="number" id="quant_chegada" name="quant_chegada" step="0.01" value="<?php echo $quant_chegada; ?>" <?php echo $podeAlterar; ?> > <br>
	    
	    <label for="peso_chegada" class="campo">Peso médio por suíno (chegada): </label>
	    <input type="decimal" id="peso_chegada" name="peso_chegada" step="1" value="<?php echo $peso_chegada; ?>" <?php echo $podeAlterar; ?> > <br>

	    <label for="data_saida" class="campo">Data de saída: </label>
	    <input type="date" id="data_saida" name="data_saida" value="<?php echo $data_saida; ?>" <?php echo $podeAlterar; ?> > <br>
	      
	    <label for="quant_saida" class="campo">Quantidade de suínos entregues: </label>
	    <input type="number" id="quant_saida" name="quant_saida" step="0.01" value="<?php echo $quant_saida; ?>" <?php echo $podeAlterar; ?> > <br>
	    
	    <label for="peso_saida" class="campo">Peso médio por suíno: (saída)</label>
	    <input type="decimal" id="peso_saida" name="peso_saida" step="1" value="<?php echo $peso_saida; ?>" <?php echo $podeAlterar; ?> > <br>


	    <label for="situacao" class="campo">Situação: </label>
	    <select id="situacao" name="situacao" <?php echo $podeAlterar; ?> >
	      <?php echo $situacaoOpcoes; ?>
	    </select><br>
	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_lote" value="<?php echo $id_lote; ?>">
	        
	  </fieldset>
	  
      <input type='submit' value='Novo' <?php echo $podeAlterar; ?> > 
	  <input type="submit" tabindex="1" name="acao" value="<?php echo $descr_acao; ?>" <?php echo $podeAlterar; ?> >
	</form>
	
	<br>
	
	<fieldset>
	   <legend>Dados Cadastrados</legend>
	   
	   <?php
	      echo $tabela;
	   ?>
	   
	        
	</fieldset>	
	
</body>

</html>