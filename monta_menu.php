<ul>
  <li><a href="#">Principal</a></li>
  
  <?php
     if ( $_SESSION["tipo"] == "A" )
        echo "<li><a href='cad_usuario.php'>Usuários</a></li>";
     else
        echo "<li><a href='cad_usuario.php'>Meus Dados</a></li>";
        
  ?>
  
  <li><a href="cad_lote.php">Cadastro de Lotes</a></li>
  <li><a href="cad_racao.php">Cadastro de Ração</a></li>
  <li><a href="cad_fornecedor.php">Cadastro de Fornecedores</a></li>
  <li><a href="cad_doenca.php">Cadastro de Doenças</a></li>
  <li><a href="cad_vacina.php">Cadastro de Medicamentos</a></li>
  <li><a href="cad_vacinado.php">Cadastro de Suínos Medicados</a></li>
  <li><a href="cad_recebimento_racao.php">Recebimento de ração</a></li>
  <li><a href="cad_morte.php">Cadastro de mortes</a></li>
  <?php 
     if ( $_SESSION["tipo"] == "S" ) 
        echo 
      "
		  <li class='dropdown'>
			<a href='#' class='dropbtn'>Cadastros Básicos</a>'
			<div class='dropdown-content'>
			  <a href='cad_fornecedor.php'>Cadastro de Fornecedores</a>
        <a href='cad_produto.php'>Produto</a>
        <a href='cad_itens_pedido.php'>Itens (pedido)</a>
			</div>
		  </li>"
  ?>
   s
  <li><a href="sair.php">Sair</a></li>
  
</ul> 
