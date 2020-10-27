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

	    $id_vacinado  = "";
	    $data         = "";
	    $dias_uso     = "";
	    $id_vacina    = "";
	    $id_lote      = "";
	    
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
				$data      = mysqli_real_escape_string($bd, $_POST["data"]);
				$dias_uso  = mysqli_real_escape_string($bd, $_POST["dias_uso"]);
				$id_vacina = mysqli_real_escape_string($bd, $_POST["id_vacina"]);
				$id_lote   = mysqli_real_escape_string($bd, $_POST["id_lote"]);
			 }
			 
			 if ( strtoupper($acao) == "SALVAR" || 
			      strtoupper($acao) == "EXCLUIR" || 
			      strtoupper($acao) == "BUSCAR") { 
			    
			    /*
	               ==> Atribur a chave primária
	            */
			    $id_vacinado = $_POST["id_vacinado"];
			 }
			 
			 if (strtoupper($acao) == "INCLUIR") {
				
				/*
	               ==> Montar um comando SQL do tipo INSERT declarando todas as colunas e valores 
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
			    $sql = "insert into vacinado  
			                 (data, dias_uso, id_vacina, id_lote)
			            values 
			                 ('$data', '$dias_uso', $id_vacina, $id_lote)";
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_vacinado' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao inserir os dados: </h3> <h3>".mysqli_error($bd)."</h3> <h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	/*
	                    ==> O comando mysqli_insert_id pega o último valor gerado pelo BD
	                */
			    	$id_vacinado = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Montar um comando SQL do tipo UPDATE para fazer a atualização dos dados que podem ser alterados (não é obrigatório usar todos)
	                  (observe a necessidade de aspas em campos do tipo caracter e do tipo data.
	            */
				 
				 $sql = " update vacinado  
				          set
				            data      = '$data',
				            dias_uso  = '$dias_uso',
				            id_vacina = $id_vacina,
				            id_lote   = $id_lote 
				            
				          where
				            id_vacinado = $id_vacinado";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					 
				    /*
	                    ==> Se sua tabela tiver um campo UNIQUE (ex. login, CPF, nº de matrícula, etc., configure
	                        a mensagem abaixo (a mensagem vai aparecer quando houver uma duplicação de valor não autorizada)
	                */					 
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Prezado usuário o valor escolhido '$id_vacinado' já está sendo utilizado. Escolha outro!</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao alterar os dados: </h3> <h3>".mysqli_error($bd)."</h3>".$sql. "<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				 
				 /*
	               ==> Ajustar o comando para fazer o delete
	             */
				  
				 $sql = "delete from vacinado where id_vacinado = $id_vacinado";
				 
				 mysqli_query($bd, $sql);
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 /*
	               ==> Ajustar o comando para buscar um único registro a partir da chave primária
	             */
				 
				 $sql = "select * from vacinado where id_vacinado = $id_vacinado";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					
					/*
	                   ==> Atribur os dados recebidos do Banco de Dados às variáveis (inclusive a chave primária)
	                */
	 
					$id_vacinado = $dados["id_vacinado"];
					$data        = $dados["data"];
					$dias_uso    = $dados["dias_uso"];
					$id_vacina   = $dados["id_vacina"];
					$id_lote     = $dados["id_lote"];
				 }
			 }
		}
		
		/*
	        ==> Ajustar o comando para listar todos os dados existentes
	    */		
		$sql_listar = "select vacinado.*, vacina.nome, lote.num_lote
		               from vacinado, vacina, lote
		               where vacinado.id_vacina = vacina.id_vacina and
		                     vacinado.id_lote = lote.id_lote
		               $sqlExtra	
		               order by vacina.nome";

		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
		     /*
	           ==> Definir quais serão as informações que devem ser listadas na tabela 
	               (observe que as duas últimas colunas são os botões de alterar e excluir, se não for possível excluir, retire o botão)
	         */				
			
			$tabela = "<table border=1>";
			$tabela = $tabela."<tr><th>Data</th><th>Dias de Uso</th><th>Vacina</th><th>Lote</th><th>Alterar</th><th>Excluir</th></tr>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				
			    /*
	                ==> Definir nomes de variáveis que irão receber os dados do BD e listá-los na tabela
	                    Sugestão ... use a letra "v" na frente (estes nomes de variáveis devem ser distintos das variáveis declaradas anteriormente)
	            */	
				
				$vIdVacinado  = $dados["id_vacinado"];
				$vData        = $dados["data"];
				$vDiasUso     = $dados["dias_uso"];
				$vIdVacina    = $dados["id_vacina"];
			    $vNomeVacina  = $dados["nome"];
				$vLote        = $dados["id_lote"];
				$vNumLote     = $dados["num_lote"];
				
				//imagem do botão ALTERAR (ele vai buscar os dados a partir da chave primária e posicioná-los na tela)
				$alterar = "<center><form method='post'>
				               <input type='hidden' name='id_vacinado' value='$vIdVacinado'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.gif' value='submit'>
				            </form></center> ";
				
				//imagem do botão EXCLUIR (ele vai excluir os dados a partir da chave primária)            
				$excluir = "<center><form method='post'>
				               <input type='hidden' name='id_vacinado' value='$vIdVacinado'>
				               <input type='hidden' name='acao' value='EXCLUIR'>
				               <input type='image' src='./img/excluir.gif' value='submit'>
				            </form></center>";
				            
			    /*
	                ==> Fique atento à ordem dos valores (respeite a mesma ordem dos títulos das colunas)
	            */
				$tabela = $tabela."<tr><td>$vData</td><td>$vDiasUso</td><td>$vNomeVacina</td><td>$vNumLote</td><td>$alterar</td><td>$excluir</td></td>";
				            
			}
			
			$tabela = $tabela."</table>";
			
		} else {
			$tabela = "não há dados para listar";
		}

        /*
	       ==> Utilize essas três variáveis se você quer construir um menu suspenso (comboBox) para escolha de valores
	    */

		$sql_vacina = "select id_vacina, nome from vacina order by nome";
		$vacinaOpcoes = montaSelectBD($bd, $sql_vacina, $id_vacina, false);
	    
	    $sql_lote = "select id_lote, num_lote from lote order by num_lote";
		$LoteOpcoes = montaSelectBD($bd, $sql_lote, $id_lote, false);

	    mysqli_close($bd);
	?>
	
	<h2>Cadastro de Suínos Medicados</h2>
	
	<?php echo $mensagem; ?>
	
	<form method="post">
      <fieldset>
	    <legend>Dados para o cadastro:</legend>
	        
	    <label for="data" class="campo">Data: </label>
	    <input type="date" id="data" name="data" value="<?php echo $data; ?>" <?php echo $podeAlterar; ?> > <br>

	    <label for="dias_uso" class="campo">Dias de Uso: </label>
	    <input type="number" id="dias_uso" name="dias_uso" value="<?php echo $dias_uso; ?>" <?php echo $podeAlterar; ?> > <br>
	  
	    <label for="id_vacina" class="campo">Vacina: </label>
	    <select id="id_vacina" name="id_vacina">
	    	<?php echo $vacinaOpcoes; ?>
	    </select> <br>

	    <label for="id_lote" class="campo">Lote: </label>
		<select id="id_lote" name="id_lote" <?php echo $podeAlterar; ?> >
	    	<?php echo $LoteOpcoes; ?>
	    </select><br>	    

	    
	    <!-- Chave Primária ... MUITO IMPORTANTE -->    
	    <input type="hidden" name="id_vacinado" value="<?php echo $id_vacinado; ?>">
	        
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