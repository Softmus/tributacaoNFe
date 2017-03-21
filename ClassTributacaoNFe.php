<?php
class Tributacao{
	
	//Base de Cálculo
	public $vBC;
	public $qBCProd;
	public $vAliqProd;
	
	//ICMS - Imposto sobre Circulação de Mercadorias e Serviços
	
	public $cstICMS;
	public $modBC;
	public $pRedBC;
	public $pICMS;
	public $vICMS;
	public $vICMSDeson;
	public $motDesICMS;
	public $modBCST;
	public $pMVAST;
	public $pRedBCST;
	public $vBCST;
	public $pICMSST;
	public $vICMSST;
	public $pDif;
	public $vICMSDif;
	public $vICMSOp;
	public $vBCSTRet;
	public $vICMSSTRet;
	
	public $pCredSN;
	public $vCredICMSSN;
	
	
	//IPI - Imposto sobre Produto Industrializado
	
	public $cstIPI;        // (Código da Situação Tributária)
	public $clEnq;
	public $cnpjProd;
	public $cSelo;
	public $qSelo;
	public $cEnq;
	public $pIPI;
	public $qUnid;
	public $vUnid;
	public $vIPI;          // = $vBC * ( $pIPI / 100 )
	
	
	//PIS - Programa de Integração Social
	
	public $cstPIS;               // (Código da Situação Tributária)
	public $pPIS;
	public $vPIS;                 // = $vBC * ( $pPIS / 100 )
	
	
	
	
	//COFINS - Contribuição para o Financiamento da Seguridade Social
	public $cstCOFINS; 
    public $pCOFINS;
	public $vCOFINS;
	
	
	
	public function CalculaTributos(){
     
		
		
	// +----------------------------------------------------------------------+
    // |                         CALCULA O PIS                                |
    // +----------------------------------------------------------------------+
		
		
		// PIS 01 - Operação Tributável (base de cálculo = valor da operação alíquota normal)
		if ($this->cstPIS == '01'){	
		    
		    $this->vPIS = $this->vBC * ( $this->pPIS / 100 );
		}
		
		// PIS 02 - Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada));
		if ($this->cstPIS == '02'){
		
			$this->vPIS = $this->vBC * ( $this->pPIS / 100 );
		}
		
		// PIS 03 - Operação Tributável (base de cálculo = qtd vendida x alíquota por unidade de produto);
		if ($this->cstPIS == '03'){
				
		    $this->vPIS = $this->qBCProd * $this->vAliqProd ;
		}
	
		// PIS04-OperaçãoTributável(tributaçãomonofásica(alíquotazero));
		// PIS06-OperaçãoTributável(alíquotazero);
		// PIS07-OperaçãoIsentadaContribuição;
		// PIS08-OperaçãoSemIncidênciadaContribuição;
		// PIS09-OperaçãocomSuspensãodaContribuição;
		
		// array com a Situação Tributária do COFINS
		$_cstPIS = array('04','06','07','08','09');
		
		if ( in_array( $this->cstPIS, $_cstPIS ) ){ 
			
		    $this->vPIS = 0.00;
		}
		
		// PIS 99 - Outras operações
		if ($this->cstPIS == '03'){
			
 	        // Cálculo percentual
                $this->vPIS = $this->vBC * ( $this->pPIS / 100 );
	        
		// Cálculo em valor
                // $this->vPIS = $this->qBCProd * $this->vAliqProd ;
	    }
		
		
    // +----------------------------------------------------------------------+
    // |                         CALCULA O COFINS                             |
    // +----------------------------------------------------------------------+
		
		// PIS 01 - Operação Tributável (base de cálculo = valor da operação alíquota normal)
		if ($this->cstCOFINS == '01'){
		   
		    $this->vCOFINS = $this->vBC * ( $this->pCOFINS / 100 );
		}
		
		// PIS 02 - Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada));
		if ($this->cstCOFINS == '02'){
			
		    $this->vCOFINS = $this->vBC * ( $this->pCOFINS / 100 );
		}
		
		// PIS 03 - Operação Tributável (base de cálculo = qtd vendida x alíquota por unidade de produto);
		if ($this->cstCOFINS == '03'){
		
		    $this->vCOFINS = $this->qBCProd * $this->vAliqProd ;
		}
		
		// COFINS04-OperaçãoTributável(tributaçãomonofásica(alíquotazero));
		// COFINS06-OperaçãoTributável(alíquotazero);
		// COFINS07-OperaçãoIsentadaContribuição;
		// COFINS08-OperaçãoSemIncidênciadaContribuição;
		// COFINS09-OperaçãocomSuspensãodaContribuição;
					
		// array com a Situação Tributária do COFINS
		   $_cstCOFINS = array('04','06','07','08','09');

		if ( in_array( $this->cstCOFINS, $_cstCOFINS ) ){ 
		
		    $this->vCOFINS = 0.00;
		}
		
		// PIS 99 - Outras operações	
		if ($this->cstCOFINS == '03'){
			
		// Cálculo percentual
		$this->vCOFINS = $this->vBC * ( $this->pCOFINS / 100 );
			
		// Cálculo em valor
                // $this->vPIS = $this->qBCProd * $this->vAliqProd ;
	    }
		
		
    // +----------------------------------------------------------------------+
    // |                            CALCULA O IPI                             |
    // +----------------------------------------------------------------------+
		
		//	00 Entrada com Recuperação de Crédito
		//	01 Entrada Tributável com Alíquota Zero
		//	02 Entrada Isenta
		//	03 Entrada Não-Tributada
		//	04 Entrada Imune
		//	05 Entrada com Suspensão
		//	49 Outras Entradas
		//	50 Saída Tributada
		//	51 Saída Tributável com Alíquota Zero
		//	52 Saída Isenta
		//	53 Saída Não-Tributada
		//	54 Saída Imune
		//	55 Saída com Suspensão
		//	99 Outras Saídas
		
		// array com a Situação Tributária do IPI
		$_cstIPI = array('00','01','02','03','04','05','49','50','51','52','53','54','55','99');

		if ( in_array( $this->cstIPI, $_cstIPI ) ){ 
			
			$this->vIPI = $this->vBC  * ( $this->pIPI / 100 );
				
		}
		
		
	// +---------------------------------------------------------------------------------------------------+
        // |                          CALCULA O ICMS - REGIME NORMAL 
        // |
        // |   ATENÇÃO [ Regra Para CST 000 ] : Quando destinatário for um consumidor final e o NCM do produto 
        // |   tiver aliquota de IPI o valor do IPI deve ser somado a Base  $this->vBC + $vIPI                   
        // +---------------------------------------------------------------------------------------------------+

		
		   // CST 000 - Tributada integralmente
		   if ($this->cstICMS == '000'){ 
			   
               		$this->vBC = $this->vBC + $vIPI; 

			$this->vICMS = $this->vBC  * ( $this->pICMS / 100 );
		   
		   }
		
		   // CST 010 - Tributada com cobrança do ICMS ST
		   if ($this->cstICMS == '010'){ 
		   
		       $this->vBC = $this->vBC + $vIPI;
		       $this->vICMS = $this->vBC * ( $this->pICMS / 100 );
			   
		       // ST
		       $this->vBCST = $this->vBC + ( $this->vBC * $this->pMVAST / 100);
	       	       $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
		       $this->vICMSST = ( $this->vBCST - $this->vBC ) * $this->pICMSST / 100;			   
			   
		   }
		
		   // CST 020 - Com reduçao de base de 22 calculo
		   if ($this->cstICMS == '020'){
		   
		       $this->vICMS = ( $this->vBC - ( $this->vBC *  $this->pRedBCST / 100) )  * $this->pICMS / 100 ;
		   
		   }
		
		   // CST 030 - Isenta ou não tributada e com cobrança do ICMS ST
		   if ($this->cstICMS == '030'){
		   
		      	$this->vICMS = $this->vBC * ( $this->pICMS / 100 );
			
    			// ST
			$this->vBCST = $this->vBC + ( $this->vBC * $this->pMVAST / 100);
			$this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
			$this->vICMSST =  $this->vBCST  * $this->pICMSST / 100;	
		   
		   }
		
		   // ST 040 - Isenta, com isençao cond
		   if ($this->cstICMS == '040'){ }
		 
		   // CST 041 - Nao tributada
		   if ($this->cstICMS == '041'){ }
		
		   // CST 050 - Suspensao
		   if ($this->cstICMS == '050'){ }
		
		
		   // CST 051 - Diferimento, legislaçao pertinente da UF
		   if ($this->cstICMS == '051'){ 
		   
		       $this->vICMS = ( $this->vBC - ( $this->vBC *  $this->pRedBCST / 100) )  * $this->pICMS / 100 ;
		   
		   }
    
		   // CST 060 - ICMS cobrado anteriormente por ST
		   if ($this->cstICMS == '060'){ }
  
		   // CST 070 - Com redução de base de cálculo e cobrança de ICMS por ST
		   if ($this->cstICMS == '070'){ 
		   
		       	   $this->vICMS = $this->vBC * ( $this->pICMS / 100 );
			   
			   $this->vBC = $this->vBC + $vIPI; // somar IPI somente no ST

			   $this->vBCST = $this->vBC + ( $this->vBC * $this->pMVAST / 100);
			   $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
				   
			   $this->vICMSST = ( $this->vBCST - $this->vBC ) * $this->pICMSST / 100;
		   
		   
		   }
  
		   // CST 090 - Outras
		   if ($this->cstICMS == '090'){ 
			   
		           $this->vICMS = $this->vBC * ( $this->pICMS / 100 );

		           $this->vBC = $this->vBC + $vIPI; // somar IPI somente no ST
			   
			   $this->vBCST = $this->vBC + ( $this->vBC * $this->pMVAST / 100);
			   $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
				   
			   $this->vICMSST = ( $this->vBCST - $this->vBC ) * $this->pICMSST / 100;	
		   
		   }
		
	// +----------------------------------------------------------------------+
        // |             CALCULA O ICMS - REGIME SIMPLES NACIONAL                 |
        // +----------------------------------------------------------------------+
		
		   // CSOSN 101 - Tributada com permissao de credito
		   if ($this->cstICMS == '101'){
		   
		       $this->vCredICMSSN = $this->vBC  * ( $this->pCredSN / 100 );
			   
		   }
		
		   // CSOSN 102 - Tributada sem permissao de credito
		   if ($this->cstICMS == '102'){
			   
			   
		   
		   }
		
		   // SOSN 103 - Isençao do ICMS para faixa de receita bruta
		   if ($this->cstICMS == '103'){ }
		
		
		   // CSOSN 201 - Tributada com permissao de credito e com cobrança do ICMS ST
		   if ($this->cstICMS == '201'){ 
		   
		           $this->vCredICMSSN = $this->vBC  * ( $this->pCredSN / 100 );
		   
			   $this->vICMS = $this->vBC - ( $this->vBC * $this->pRedBC / 100 );
			   
			   $this->vBCST = $this->vICMS + ( $this->vICMS * $this->pMVAST / 100);
			   $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
				   
			  
			   $this->vICMSST = ( $this->vBCST - $this->vICMS ) * $this->pICMSST / 100;
		   
		   }
		
		   // CSOSN 202 - Tributada sem permissao de credito e com cobrança do ICMS ST
		   if ($this->cstICMS == '202'){ 
		   
		           $this->vICMS = $this->vBC - ( $this->vBC * $this->pRedBC / 100 );
			   
			   $this->vBCST = $this->vICMS + ( $this->vICMS * $this->pMVAST / 100);
			   $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
				   
			   $this->vICMSST = ( $this->vBCST - $this->vICMS ) * $this->pICMSST / 100;
		   
		   }
		
		   // CSOSN 203 - Isençao do ICMS para faixa de receita bruta e com cobrança de ICMS ST
		   if ($this->cstICMS == '203'){ 
		   
		           $this->vICMS = $this->vBC * ( $this->pICMS / 100 );
			   
			   $this->vBCST = $this->vBC + ( $this->vBC * $this->pMVAST / 100);
			   $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
				   
			   $this->vICMSST = ( $this->vBCST - $this->vICMS ) * $this->pICMSST / 100;
		   
		   }
		
		   // CSOSN 300 - Imune
		   if ($this->cstICMS == '300'){ }
		
		
		   // CSOSN 400 - Nao tributada
		   if ($this->cstICMS == '400'){ }
		
		   // CSOSN 500 - ICMS cobrado anteriormente por ST ou por antecipaçao
		   if ($this->cstICMS == '500'){ }  
		
		
		   // CSOSN 900 - Outras
		   if ($this->cstICMS == '900'){ 
		   
			   $this->vCredICMSSN = $this->vBC  * ( $this->pCredSN / 100 );
		   
			   $this->vICMS = $this->vBC - ( $this->vBC * $this->pRedBC / 100 );
			   
			   $this->vBCST = $this->vICMS + ( $this->vICMS * $this->pMVAST / 100);
			   $this->vBCST = $this->vBCST - ( $this->vBCST *  $this->pRedBCST / 100);
				   
			  
			   $this->vICMSST = ( $this->vBCST - $this->vICMS ) * $this->pICMSST / 100;
		   
		   }
		
		
		
		
	} // CalculaTributos 
	
}// Tributacao
?>
