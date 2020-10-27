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

	    $id_morte  = "";
	    $peso      = "";
	    $data      = "";
	    $sexo      = "";
	    $id_doenca = "";
	    $id_lote   = "";
	    
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
				$peso      = mysqli_real_escape_string($bd, $_POST["peso"]);
				$data      = mysqli_real_escape_string($bd, $_POST["data"]);
				$sexo      = mysqli_real_escape_string($bd, $_POST["sexo"]);
				$id_doenca = mysqli_real_escape_string($bd, $_POST["id_doenca"]);
				$id_lote   = mysqli_real_escape_string($bd, $_POST["id_lote"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_morte = $_POST["id_morte"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into morte  
			                 (peso, data, sexo, id_doenca, id_lote)
			            values 
			                 ($peso,'$data', '$sexo', $id_doenca, $id_lote)";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_morte' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_morte = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update morte  
				          set
				            peso      = $peso,
				            data      = '$data',
				            sexo      = '$sexo',
				            id_doenca = $id_doenca,
				            id_lote   = $id_lote 
				            
				          where
				            id_morte = $id_morte";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_morte' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from morte where id_morte = $id_morte";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from morte where id_morte = $id_morte";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
	 
					$id_morte  = $dados["id_morte"];
					$peso      = $dados["peso"];
					$data      = $dados["data"];
					$sexo      = $dados["sexo"];
					$id_doenca = $dados["id_doenca"];
					$id_lote   = $dados["id_lote"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		$sql_listar = "select morte.*, doenca.nome_doenca, lote.num_lote
		               from morte, doenca, lote
		               where morte.id_doenca = doenca.id_doenca and
		                     morte.id_lote = lote.id_lote
		               $sqlExtra	
		               order by doenca.nome_doenca ";

		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Peso</th><th>Data</th><th>Sexo</th><th>Doença</th><th>Lote</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdMorte  = $dados["id_morte"];
				$vPeso     = $dados["peso"];
				$vData     = $dados["data"];
				$vSexo     = $dados["sexo"];
				$vIdDoenca   = $dados["id_doenca"];
			    $vNomeDoenca = $dados["nome_doenca"];
				$vLote     = $dados["id_lote"];
				$vNumLote     = $dados["num_lote"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_morte' value='$vIdMorte'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_morte' value='$vIdMorte'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vPeso</td><td>$vData</td><td>$vSexo</td><td>$vNomeDoenca</td><td>$vNumLote</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */

		$sexoVal    = array("F","M");
		$sexoDescr  = array("Feminino","Masculino");
		$sexoOpcoes = montaSelect($sexoVal, $sexoDescr, $sexo, false);

		$sql_doenca = "select id_doenca, nome_doenca from doenca order by nome_doenca";
		$doencaOpcoes = montaSelectBD($bd, $sql_doenca, $id_doenca, false);
	    
	    $sql_lote = "select id_lote, num_lote from lote order by num_lote";
		$LoteOpcoes = montaSelectBD($bd, $sql_lote, $id_lote, false);

	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de MORTES</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="peso" class="campo">Peso: </label>
	    <input type="decimal" id="peso" name="peso" size="50" value="<?php echo $peso; ?>" <?php echo $podeAlterar; ?> > <br>
	        
	    <label for="data" class="campo">Data: </label>
	    <input type="date" id="data" name="data" value="<?php echo $data; ?>" <?php echo $podeAlterar; ?> > <br>

	    <label for="sexo" class="campo">Sexo: </label>
	    <select id="sexo" name="sexo" <?php echo $podeAlterar;?>> 
	    	<?php echo $sexoOpcoes;?>
	    </select><br>
	  
	    <label for="id_doenca" class="campo">Doença: </label>
	    <select id="id_doenca" name="id_doenca">
	    	<?php echo $doencaOpcoes; ?>
	    </select> <br>

	    <label for="id_lote" class="campo">Lote: </label>
		<select id="id_lote" name="id_lote" <?php echo $podeAlterar; ?> >
	    	<?php echo $LoteOpcoes; ?>
	    </select><br>	    

	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_morte" value="<?php echo $id_morte; ?>">
	        
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